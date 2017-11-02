<?php   
namespace Ydzy\ApiBundle\Controller;
use Symfony\Component\HttpFoundation\Request;
use Symfony\Component\HttpFoundation\Response;
use Symfony\Bundle\FrameworkBundle\Controller\Controller;
use Symfony\Component\HttpFoundation\JsonResponse;


class MachineController extends Controller
{
    public function indexAction(Request $request)
    {
		$json = array(
			array('statusid'=>0,
			'status'=>'未审核'),
			array('statusid'=>1,
			'status'=>'有效数据'),
			array('statusid'=>2,
			'status'=>'未通过审核')
		);
        return new JsonResponse($json);
    }


    //------------------------------------------------
	// 获得录入信息列表
	//------------------------------------------------
    public function retrieveByUidAction(Request $request)
	{
	    $category_id = $request->request->get('category_id',0);
		$brand = $request->request->get('brand',0);
		$machine_name = $request->request->get('machine','');
		$mark = $request->request->get('mark',-1);
		$uid = $request->request->get('uid',0); 
		$flag = $request->request->get('flag',-4);
		$page = $request->request->get('page',1);
		$rows = $request->request->get('rows',10);
		$category_id==0?$category = " a.category_id!=0 ":$category = " a.category_id =$category_id ";
		$brand==0?$brand_sql = " and a.brand_id!=0 ":$brand_sql = " and a.brand_id =$brand ";
		$machine_name==''?$name = " a.status=1 ":$name = " a.machine_name like '%$machine_name%' ";
		$mark==-1?$mark_sql = " ":$mark_sql = " and a.mark=$mark ";
		$uid==0?$uid_sql = " and a.user_id=0 ":$uid_sql = " and a.user_id=$uid ";
		$flag==-4?$flag_sql = " ":$flag_sql = " and a.flag=$flag ";
		$this->get('my_datebase')->connection();
		$sql = "select a.*,b.category_name,c.brand_name,d.area from Pre_machine as a left join Category as b on a.category_id = b.id left join Brand as c on a.brand_id=c.id left join Area as d on a.city_id = d.id where".$category.$brand_sql." and ".$name.$mark_sql.$uid_sql.$flag_sql." order by a.updated_date desc";
		$result = mysql_query($sql);
		$num   = mysql_num_rows($result);
		if (!$num){
			$json_result=array();
		}
		while($row = mysql_fetch_array($result)){
			$json_result[]=array(
		  		  'machine_id' => $row['id'],
				  'flag' => $row['flag'],
				  'category_name' => $row['category_name'],
		          'machine_name' => $row['machine_name'],
				  'model' => $row['brand_model'],
				  'brand' => $row['brand_name'],
				  'mark' => $row['mark'],
				  'area' => $row['area'],
				  'workhours' => $row['workhours'],
				  'rent_by_num' => $row['rent_by_num'],
				  'sale_price' => $row['sale_price'],
				  'rent_by_month' => $row['rent_by_month'],
				  'recommend' => $row['recommend'],
				  'creation_date' => $row['creation_date'],
				  'updated_date' => $row['updated_date'],
				  'status' =>$row['status'],
				  'linker' =>$row['linker'],
				  'linker_tel'=>$row['linker_tel']
		  		  );
			}
		$json_r = array();
		$page_rows = array_slice($json_result, ($page-1)*$rows, $rows, false);
		$json_r = array(
					'total' => count($json_result),
					'rows' => $page_rows
							);
		
		$response = new Response(json_encode($json_r));
		$response->headers->set('content-type','application/json');
		return $response;
	}
	//获取总共的数据量
	public function retrieveSpiderMachineNumAction(Request $request)
	{
		$json = $this->get('json_parser')->parse($request);
		$user = $json->get('user',-1);
		$keyword = $json->get('keyword','');
		if(strlen($user)>1&&$user!=-1){
			$user = substr($user,1);
		}
		if($user==-1){$user_sql = "";}
		else{$user_sql = " and user = $user";}
		if($keyword!=""){
		$keyword_sql = " and linker like '%$keyword%' ";
		}else{
		$keyword_sql="";
		}
		$this->get('spider_datebase')->connection();
		$sql = "select count(*) from Machine where status = 1".$keyword_sql.$user_sql;
		$result = mysql_query($sql);
		$num = mysql_result($result,0);
		return new JsonResponse($num);	
	}
      //------------------------------------------------
	 // 获得录入信息列表superadmin
	//------------------------------------------------
    public function retrieveByAdminAction(Request $request)
	{
		$page = $request->request->get('page',1);
		$rows = $request->request->get('rows',100);
		$this->get('spider_datebase')->connection();
		$sql = "select a.*,b.area as province,c.area as city from Machine as a left join Area as b on a.province_id = b.id left join Area as c on c.id=a.city_id order by a.id desc";
		$result = mysql_query($sql);
		$num   = mysql_num_rows($result);
		if (!$num){
			$json_result=array();
		}
		while($row = mysql_fetch_array($result)){
			$area = $row['province'].$row['city'];
			if($row['sale_price']==0){
				$sale_price = "面议";
				}else{
					$sale_price = $row['sale_price']."万";
					}
			$sql1 = "select b.url from Machine_pic as a left join images as b on a.image_id = b.id where machine_id=$row[id]";
			//echo "<br>";
			$result1 = mysql_query($sql1);
			$pic=array();
			while($row1 = mysql_fetch_array($result1)){
				$pic[] = array(
				'url'=>$row1['url']
					);
				}
			$json_result[]=array(
		  		  'machine_id' => $row['id'],
		          'machine_name' => $row['machine_name'],
				  'linker' => $row['linker'],
				  'linker_tel' => $row['linker_tel'],
				  'workhours' => $row['workhours'],
				  'sale_price' => $sale_price,
				  'area'=>$area,
				  'pic'=>$pic,
				  'statusid'=>$row['status'],
				  'creation_date' => $row['creation_date']
				);
			}
		$json_r = array();
		$page_rows = array_slice($json_result, ($page-1)*$rows, $rows, false);
		$json_r = array(
					'total' => count($json_result),
					'rows' => $page_rows
							);
		
		$response = new Response(json_encode($json_result));
		$response->headers->set('content-type','application/json');
		return $response;
	}
	
	
	 //------------------------------------------------
	 // 管理员更改录取信息
	//------------------------------------------------
    public function updateAdminAction(Request $request)
	{
		$json = $this->get('json_parser')->parse($request);
		$machine_id = $json->get('machine_id',0);
		$status = $json->get('status',1);
		$linker = $json->get('linker','');
		$linker_tel = $json->get('linker_tel','');
		$machine_name = $json->get('machine_name','');
		$this->get('spider_datebase')->connection();
		//$sql = "update Machine set status=$status,linker='$linker',linker_tel='$linker_tel',machine_name='$machine_name' where id = $machine_id";
		$machine_name1 = mysql_result(mysql_query("select machine_name from Machine where id=$machine_id"),0);
		$sql = "update Machine set status=$status where id = $machine_id";
		if(mysql_query($sql))
		{
			mysql_close();
			$flagtyep = 1;
			$this->get('my_datebase')->connection();
			$current_time = date('Y-m-d H:i:s',time());
			$sql1 = "UPDATE Machine SET status=0,updated_date = '$current_time'  WHERE machine_name = '$machine_name1' LIMIT 1";
			if(mysql_query($sql1))
			{
				$flagtyep = 2;
			}else
			{
				$flagtyep = 3;
			}
			if($flagtyep == 1 || $flagtyep == 2)
			{
				return new Response('', 200);mysql_close();
			}elseif ($flagtyep == 3)
			{
				return new Response('', 500);mysql_close();				
			}
		}else
		{
			return new JsonResponse('更新失败',500);mysql_close();
		}
		
	}


    //------------------------------------------------
	// 获得所有机器列表
	//------------------------------------------------
    public function retrieveAllAction(Request $request)
	{
	    $category_id = $request->request->get('category_id',0);
		$machine_name = $request->request->get('machine','');
		$mark = $request->request->get('mark',-1);
		$recommend = $request->request->get('recommend',-1);
		$page = $request->request->get('page',1);
		$rows = $request->request->get('rows',10);
		$category_id==0?$category = " a.category_id!=0 ":$category = " a.category_id =$category_id ";
		$machine_name==''?$name = " a.status=1 ":$name = " a.machine_name like '%$machine_name%' ";
		$mark==-1?$mark_sql = " ":$mark_sql = " and a.mark=$mark ";
		$recommend==-1?$recommend_sql = " ":$recommend_sql = " and a.recommend=$recommend ";
		$this->get('my_datebase')->connection();
		$sql = "select a.*,b.category_name,c.brand_name,d.area from Machine as a left join Category as b on a.category_id = b.id left join Brand as c on a.brand_id=c.id left join Area as d on a.city_id = d.id where".$category." and ".$name.$mark_sql.$recommend_sql." order by id desc ";
		$result = mysql_query($sql);
		$num   = mysql_num_rows($result);
		if (!$num){
			$json_result=array();
		}
		while($row = mysql_fetch_array($result)){
			$json_result[]=array(
		  		  'machine_id' => $row['id'],
				  'category_name' => $row['category_name'],
		          'machine_name' => $row['machine_name'],
				  'model' => $row['brand_model'],
				  'brand' => $row['brand_name'],
				  'mark' => $row['mark'],
				  'area' => $row['area'],
				  'workhours' => $row['workhours'],
				  'rent_by_num' => $row['rent_by_num'],
				  'sale_price' => $row['sale_price'],
				  'rent_by_month' => $row['rent_by_month'],
				  'recommend' => $row['recommend'],
				  'creation_date' => $row['creation_date'],
				  'updated_date' => $row['updated_date'],
				  'status' =>$row['status']
		  		  );
			}
		$json_r = array();
		$page_rows = array_slice($json_result, ($page-1)*$rows, $rows, false);
		$json_r = array(
					'total' => count($json_result),
					'rows' => $page_rows
							);
		
		$response = new Response(json_encode($json_r));
		$response->headers->set('content-type','application/json');
		return $response;
	}
	//------------------------------------------------
	// 根据条件筛选机器列表
	//------------------------------------------------
    public function retrieveByFilterAction(Request $request)
    {
    	
		//$json = json_decode($request->getContent(),true);
		$json = $this->get('json_parser')->parse($request);
		$machine_id = $json->get('machine_id',0);
		$uid = $json->get('uid',-1);
		$category_id = $json->get('category_id',0);
		$brand_id = $json->get('brand_id',0);
		$mark = $json->get('mark',-1);
		$recommend = $json->get('recommend',-1);
		$province = $json->get('province',-1);
		$city = $json->get('city',-1);
		$mianyi = $json->get('mianyi',-1);
		$keyword = trim($json->get('keyword',''));
		$keyword_array = (explode(" ",$keyword));
		//echo 
		$keynum = count($keyword_array);
		/*$logger = $this->get('logger');
    	$logger->info('keyword:'.$keyword);*/
		$tonn_min = $json->get('tonn_min',0);
		$tonn_max = $json->get('tonn_max',100);
		$workhours_min = $json->get('workhours_min',0);
		$workhours_max = $json->get('workhours_max',99999);
		$lat = $json->get('lat',0);
		$lng = $json->get('lng',0);
		$distance = $json->get('distance',0);
		$factory_year_mins = $json->get('factory_year_min',0);
		$factory_year_maxs = $json->get('factory_year_max',100);
		$sale_price_min = $json->get('sale_price_min',0);
		$sale_price_max = $json->get('sale_price_max',99999);
		$rent_by_num = $json->get('rent_by_num',-1);
		$rent_by_month = $json->get('rent_by_month',-1);
		$distance_sort = $json->get('distance_sort',-1);
		$factory_year_sort = $json->get('factory_year_sort',-1);
		$workhours_sort = $json->get('workhours_sort',-1);
		$sale_price_sort = $json->get('sale_price_sort',-1);
		$updated_date_sort = $json->get('updated_date_sort',-1);
		$start = $json->get('start',0);
		$num = $json->get('num',20);
		$ago_check = $json->get('ago_check','');
		$current_year = date("Y",time());
		$factory_year_min = ($current_year - $factory_year_mins)."12";
		$factory_year_max = ($current_year - $factory_year_maxs)*100;
		
		$this->get('my_datebase')->connection();
		//自动获取uid
		$user = $this->getUser();
	    if($uid==-1||$uid==1||$uid==2){
			$uid_sql="";
			}else if($uid!=0){
				  $uid_sql = " a.user_id =$uid and  ";
				    }else if($uid==0&&$user){
						$user_id = $user->getId();
						$uid_sql=" a.user_id =$user_id and ";
				}else{
					 return new Response('',403);
					 }
		$category_id==0?$category=" a.category_id!='' and ":($category_id==3?$category=" a.category_id not in (1,2) and ":($category_id==4?$category=" a.category_id = 4 and ":($category_id==5?$category=" a.category_id = 5 and ":$category = " a.category_id =$category_id and ")));
		//判断uid，如果0，自动获取，如果为空，不进行uid判断，其他进行筛选
		//$uid==-1?$uid_sql = "":($uid==0?$uid_sql=" a.user_id =$user and ": $uid_sql = " a.user_id =$uid and ");
		//标识
		$mark_sort="";
		if($mark==-1){$mark = " a.mark!=-2 and a.mark!=-3 and";}
		//个人中心
		else if($mark==0&&$uid==0){$mark=" (a.mark =0 or a.mark = 2 or a.mark = -2) and ";$mark_sort="";}
		else if($mark==0&&$uid!=0){$mark=" (a.mark =0 or a.mark = 2) and ";$mark_sort=" a.mark ASC,";}
		else if($mark==1&&$uid==0){$mark=" (a.mark =1 or a.mark = 3 or a.mark = -3) and ";$mark_sort="";}
		else if($mark==1&&$uid!=0){$mark=" (a.mark =1 or a.mark = 3) and ";$mark_sort=" a.mark ASC,";}
		else{$mark = " a.mark =$mark and ";}
		//$mark==-1?$mark = "":$mark = " a.mark =$mark and ";
		//推荐
		$recommend==-1?$recommend = "":$recommend = " a.recommend =$recommend and ";
		$province==-1?$province = "":$province = " a.province_id =$province and ";
		$city==-1?$city = "":$city = " a.city_id =$city and ";
		//有机器的id
		$machine = ($machine_id==0?$machine = "":$machine = " a.id =$machine_id and ");
		//面议状态的筛选，默认是不显示
		if($mianyi==-1){
			$mianyi_sql = " a.sale_price!=0 and ";
			}else{
				$mianyi_sql = "";
				}
		//品牌筛选
		$brand = ($brand_id==0?"":" a.brand_id ='$brand_id' and "); 
		//关键词筛选
		if($keyword==''){$filter = " a.status = 1 ";}
		else if($keynum==1){$filter = " (a.machine_name like '%".$keyword."%' or a.brand_model like '%".$keyword."%' or b.brand_name like '%".$keyword."%' or e.nickname like '%".$keyword."%' or e.phone like '%".$keyword."%' or a.linker like '%".$keyword."%' or a.linker_tel like '%".$keyword."%') and a.status = 1 ";}
		else{
			$filter = "";
			for($i=0;$i<$keynum;$i++){
				$keyword_array[$i];
				$filter = $filter." (a.machine_name like '%".$keyword_array[$i]."%' or a.brand_model like '%".$keyword_array[$i]."%' or b.brand_name like '%".$keyword_array[$i]."%' or e.nickname like '%".$keyword_array[$i]."%' or e.phone like '%".$keyword_array[$i]."%' or a.linker like '%".$keyword_array[$i]."%' or a.linker_tel like '%".$keyword_array[$i]."%') and ";
				}
			$filter = substr($filter,0,-4)." and a.status = 1 ";
			}
		
		/*$keynum = count($keyword_array);
 		$filter =$keyword==''?" a.status = 1 ":" (a.machine_name like '%$keyword%' or a.brand_model like '%$keyword%' or b.brand_name like '%$keyword%' or e.phone like '%$keyword%' or a.linker_tel like '%$keyword%') and a.status = 1 ";*/
		
		
		$tonn=($tonn_min == 0&&$tonn_max == 100)?"":" and a.tonn>=$tonn_min and a.tonn<=$tonn_max ";
		$workhours=($workhours_min == 0&&$workhours_max ==99999)?"":" and a.workhours>=$workhours_min and a.workhours<=$workhours_max ";
		//距离筛选，先获取经纬度，然后判断保存的经纬度
		if($distance!=0&&$lng!=0&&$lat!=0){
		//添加经纬度的sql，查找id
			//$location_str = implode(",",$location);
			$location_sql = " and ((abs(a.lat-$lat)+abs(a.lng-$lng))*110)<=$distance ";
		
		
		/*$sql = "SELECT id,lat,lng FROM Machine";
		$result = mysql_query($sql);
		$location = array();
		while($row = mysql_fetch_array($result)){
			$range = $this->get('location')->GetDistance($lat,$lng,$row['lat'],$row['lng'],1);
			if($range<=$distance*1000){
				array_push($location,$row['id']);
				}
			}*/
			
		}
		else{$location_sql="";}
		//$distance_sql = " and distance>=$distance_min and distance<=$distance_max ";

		//出厂年限的筛选
		$factory_year = ($factory_year_mins==0&&$factory_year_maxs==100)?"":" and a.factory_year<= $factory_year_min and a.factory_year>=$factory_year_max ";
		
		$sale_price = ($sale_price_min==0&&$sale_price_max==99999)?"":" and a.sale_price>=$sale_price_min and a.sale_price<=$sale_price_max ";
		
		//排序部分
		$rent_by_num==-1?$rent_by_num = "":($rent_by_num==0?$rent_by_num = " order by ".$mark_sort."a.rent_by_num desc ":$rent_by_num = " order by ".$mark_sort." a.rent_by_num asc ");
		
		$rent_by_month==-1?$rent_by_month = "":($rent_by_month == 0?$rent_by_month = " order by ".$mark_sort." a.rent_by_month desc ":$rent_by_month = " order by ".$mark_sort."a.rent_by_month asc ");
		//距离排序
		$distance_sort==-1?$distance_sort = "":($distance_sort == 0?$distance_sort = " order by ".$mark_sort."(abs(a.lat-$lat)+abs(a.lng-$lng))*110 desc ":$distance_sort = " order by ".$mark_sort."(abs(a.lat-$lat
		)+abs(a.lng-$lng))*110 asc ");
		//出厂年限排序，直接查找数据库排序
		$factory_year_sort==-1?$factory_year_sort = "":($factory_year_sort == 0?$factory_year_sort = " order by ".$mark_sort."a.factory_year desc ":$factory_year_sort = " order by ".$mark_sort."a.factory_year asc ");
		
		$workhours_sort==-1?$workhours_sort = "":($workhours_sort == 0?$workhours_sort = " order by ".$mark_sort."a.workhours desc ":$workhours_sort = " order by ".$mark_sort."a.workhours asc ");
		
		$sale_price_sort==-1?$sale_price_sort = "":($sale_price_sort == 0?$sale_price_sort = " order by ".$mark_sort."a.sale_price desc ":$sale_price_sort = " order by ".$mark_sort."a.sale_price asc ");
		
		if($rent_by_num==""&&$updated_date_sort==-1&&$rent_by_month==""&&$distance_sort==""&&$factory_year_sort==""&&$workhours_sort==""&&$sale_price_sort==""){$updated_sort = " order by ".$mark_sort."a.id desc ";}
		else if($updated_date_sort == 0){$updated_sort = " order by ".$mark_sort."a.id desc ";}
		else if($updated_date_sort == 1){$updated_sort = " order by ".$mark_sort."a.id asc ";}
		else {$updated_sort = "";}
		//echo $updated_sort;
		//分页
		//$limit = (($start==-1&&$num==-1)||($start==-1))?"":" limit $start,$num ";
		$limit  = " limit $start,$num";
		$sql =$machine.$uid_sql.$category.$mianyi_sql.$brand.$mark.$recommend.$province.$city.$filter.$tonn.$workhours.$location_sql.$factory_year.$sale_price.$rent_by_num.$rent_by_month.$distance_sort.$factory_year_sort.$workhours_sort.$sale_price_sort.$updated_sort.$limit;

		if($ago_check == 1)
		{
			$sql = "SELECT a.*,f.category_name,b.brand_name,(abs(a.lat-$lat)+abs(a.lng-$lng))*110 as distance,c.area as province, d.area as city, e.nickname,e.phone FROM Machine as a left join Brand as b on a.brand_id=b.id left join Area as c on c.id= a.province_id left join Area as d on d.id=a.city_id left join fos_user as e on e.id = a.user_id left join Category as f on a.category_id=f.id where a.ago_check=1 order by a.id asc limit $start,$num";
		}else
		{
			$sql = "SELECT a.*,f.category_name,b.brand_name,(abs(a.lat-$lat)+abs(a.lng-$lng))*110 as distance,c.area as province, d.area as city, e.nickname,e.phone FROM Machine as a left join Brand as b on a.brand_id=b.id left join Area as c on c.id= a.province_id left join Area as d on d.id=a.city_id left join fos_user as e on e.id = a.user_id left join Category as f on a.category_id=f.id where ".$sql;
		}

 		//return new Response($sql);
		$result = mysql_query($sql);
		$num   = mysql_num_rows($result);
		
		if (!$num){
			return new Response('', 400);
		}
		
		// 性能优化代码，主要时间占用函数
//		$runtime_start = microtime(true);
//		
//		$strids = '';
//		while($row = mysql_fetch_array($result))
//		{
//			$strids = $strids . $row['id'] . ',';
//		}
//		if(strlen($strids) > 0)
//		{
//			$strids = substr($strids, 0, -1);
//		}
//		$sql = 'select b.thumbnail from Machine_pic as a left join images as b on a.image_id=b.id where a.id in ('.$strids.')';
//		return new JsonResponse($sql);

		$strids = '';
		while($row = mysql_fetch_array($result,MYSQL_ASSOC))
		  {
			 $strids = $strids . $row['id'] . ','; 
			 if($row['province']==null){$row['province']="";}
			 if($row['city']==null){$row['city']="";}
			 
			 $factory_year_full = substr($row['factory_year'],0,4)."年".substr($row['factory_year'],4)."月"; 
			 $factory_year = substr($row['factory_year'],0,4);
			 
		/*$pic=array();
		if ($machine){
		$pic_result = mysql_query("select b.thumbnail from Machine_pic as a left join images as b on a.image_id=b.id where a.machine_id='$machine_id'");
		 }else{
		$pre_pic = mysql_query("select pre_id from Machine where id=$row[id]");
		$pre_id = mysql_result($pre_pic,0);
		if($pre_id!=0){
		$pic_result = mysql_query("select b.thumbnail,a.position from Machine_pic as a left join images as b on a.image_id=b.id where a.pre_machine_id='$pre_id'");	
			}else{
		$pic_result = mysql_query("select b.thumbnail,a.position from Machine_pic as a left join images as b on a.image_id=b.id where a.machine_id='$row[id]'");
			}
		}
		   $pic_str = "";
		   $pic_first = "";
		  while($pic_row = mysql_fetch_array($pic_result)){
			  $pic[] =array(
			  'pic' => $pic_row['thumbnail'],
			  'position'=>$pic_row['position']
			  );
			  $pic_str = $pic_str.",".$pic_row['thumbnail'];
			 }
			 $pic_str = substr($pic_str,1);
			 $num_pic = mysql_num_rows($pic_result);
			 
			 if(!$num_pic){
				 $pic_first = "";
				 }else{
					 foreach($pic as $key=>$ss){
						 //echo $key;
						 //echo $ss['position'];
						// echo "</br>";
						 if($ss['position']==1){$pic_first = $ss['pic'];break;}
						 else{$pic_first = mysql_result($pic_result,0);break;}
						 }
					 
					 }
			if(substr($pic_first,0,1)=='/'){
				$pic_first_2 = "http://wanjiwang.cn".$pic_first;
				}else{
					$pic_first_2 = "http://img.wanjiwang.cn/imgs/".substr($pic_first,6);
					//$pic_first_2 = "http://wanji.oss-cn-hangzhou.aliyuncs.com".substr($pic_first,6);
					}	*/
					
		  $json_result[]=array(
		  		  'machine_id' => $row['id'],
				  'nickname'=> $row['nickname'],
				  'phone' => $row['phone'],
				  'linker'=>$row['linker'],
				  'linker_tel'=>$row['linker_tel'],
				  'province' => $row['province'],
				  'city' => $row['city'],
				  'category'=>$row['category_name'],
		  		  'category_id' => $row['category_id'],
		          'uid' => $row['user_id'],
				  'factory_year' => $factory_year,
				  'factory_year_full'=> $factory_year_full,
				  'machine_name' => $row['machine_name'],
				  'recommend'=>$row['recommend'],
				  //'pic_all' => $pic_str,
				  //'pic'=>$pic_first_2,
				  'mark' => $row['mark'],
				  'lat' => $row['lat'],
				  'lng' => $row['lng'],
				  'tonn' => $row['tonn'],
				  'model' => $row['brand_model'],
				  'brand' => $row['brand_name'],
				  'workhours' => $row['workhours'],
				  'rent_by_num' => $row['rent_by_num'],
				  'sale_price' => $row['sale_price'],
				  'rent_by_month' => $row['rent_by_month'],
				  'collection'=>$row['collection'],
				  'trueprice'=>$row['trueprice'],
				  'saleway'=>$row['saleway'],
				  'creation_date' => $row['creation_date'],
				  'updated_date' => $row['updated_date'],
				  'status' =>$row['status']
		  		  );
				  
			$json_key[$row['id']]=array(
		  		  'machine_id' => $row['id'],
				  'nickname'=> $row['nickname'],
				  'phone' => $row['phone'],
				  'linker'=>$row['linker'],
				  'linker_tel'=>$row['linker_tel'],
				  'province' => $row['province'],
				  'city' => $row['city'],
				  'category'=>$row['category_name'],
		  		  'category_id' => $row['category_id'],
		          'uid' => $row['user_id'],
				  'factory_year' => $factory_year,
				  'factory_year_full'=> $factory_year_full,
				  'machine_name' => $row['machine_name'],
				  'recommend'=>$row['recommend'],
				  'mark' => $row['mark'],
				  'lat' => $row['lat'],
				  'lng' => $row['lng'],
				  'tonn' => $row['tonn'],
				  'model' => $row['brand_model'],
				  'brand' => $row['brand_name'],
				  'workhours' => $row['workhours'],
				  'rent_by_num' => $row['rent_by_num'],
				  'sale_price' => $row['sale_price'],
				  'rent_by_month' => $row['rent_by_month'],
				  'collection'=>$row['collection'],
				  'trueprice'=>$row['trueprice'],
				  'saleway'=>$row['saleway'],
				  'creation_date' => $row['creation_date'],
				  'updated_date' => $row['updated_date'],
				  'status' =>$row['status'],
				  'pic_all'=>'',
				  'pic'=>''
		  		  );
				  
				  
		  }
		 //处理图片
		 if(strlen($strids) > 0)
		{
			$strids = substr($strids, 0, -1);
		}
		$pic=array();
		if ($machine){ //如果填写了machine_id
		$pic_result = mysql_query("select a.machine_id,b.thumbnail,a.position from Machine_pic as a left join images as b on a.image_id=b.id where a.machine_id='$machine_id'");
		 }else{
		$pic_result = mysql_query("select a.machine_id,b.thumbnail,a.position from Machine_pic as a left join images as b on a.image_id=b.id where a.machine_id in (".$strids.")");
		}
		$pic_str = "";
		$pic_first = "";
		while($pic_row = mysql_fetch_array($pic_result)){

			$pic[] =array(
			  'machine_id'=>$pic_row['machine_id'],
			  'pic' => $pic_row['thumbnail'],
			  'position'=>$pic_row['position']
			  );
			  
		}
		//echo "<pre>";
		//print_r($json_result);
		$pic_str = '';
		//array('machine_id'=>xx, "value"=xx);
		$array_postion = array();
		foreach ($pic as $key => $value) {
			if($json_key[$value['machine_id']]['pic_all']!=''){
				$json_key[$value['machine_id']]['pic_all'].=",".$value['pic'];
			}else{
				$json_key[$value['machine_id']]['pic_all']=$value['pic'];
				}
			if($value['position']==1){
				$json_key[$value['machine_id']]['pic']=$value['pic'];
			}else{
				if (array_key_exists($value['machine_id'],$array_postion)) {
					continue;	
				}else{
					$json_key[$value['machine_id']]['pic']=$pic[$key]['pic'];
					$array_postion[$value['machine_id']] = $value["machine_id"];
				}
			}
			if(substr($json_key[$value['machine_id']]['pic'],0,1)=='/'){
				$json_key[$value['machine_id']]['pic'] = "http://wanjiwang.cn".$json_key[$value['machine_id']]['pic'];
				}else{
					$json_key[$value['machine_id']]['pic'] = "http://img.wanjiwang.cn/imgs/".substr($json_key[$value['machine_id']]['pic'],6);
					}
		}
		
		
		//print_r(array_values($json_key));
		//return new Response('',500);
		return new JsonResponse(array_values($json_key));
		
		//return new Response($strids);
		// 性能测试代码，测试此段代码执行时间
		//  $runtime_stop = microtime(true);
		//return new JsonResponse("test time:".round($runtime_stop-$runtime_start,6));
		
		
    }
	
	//------------------------------------------------
	// 机器详情
	//------------------------------------------------
    public function infoAction(Request $request)
    {

		$json = $this->get('json_parser')->parse($request);
		$machine_id = $json->get('machine_id',0);
		
		$this->get('my_datebase')->connection();
		
		$sql = "SELECT a.*,b.brand_name,c.area as province ,d.area as city,f.category_name,e.username,e.nickname,e.roles FROM Machine as a left join Brand as b on a.brand_id = b.id left join Area as c on a.province_id=c.id left join Area as d on a.city_id=d.id left join Category as f on a.category_id = f.id left join fos_user as e on a.user_id = e.id where a.id = $machine_id and a.status=1";
		$result = mysql_query($sql);
		$num   = mysql_num_rows($result);
		if (!$num){
			return new Response('', 400);
		}
		while($row = mysql_fetch_array($result)){
		
		 //$factory_year = substr($row['factory_year'],0,4)."年".substr($row['factory_year'],4)."月"; 	
			
		 $pic=array();
		 $pic2=array();
		
		$pre_pic = mysql_query("select pre_id from Machine where id=$machine_id");
		$pre_id = mysql_result($pre_pic,0);
		
		if($pre_id!=0){
			$pic_result = mysql_query("select b.id,b.thumbnail,a.position from Machine_pic as a left join images as b on a.image_id=b.id where a.pre_machine_id='$pre_id'");	
			}else{
			$pic_result = mysql_query("select b.id,b.thumbnail,a.position from Machine_pic as a left join images as b on a.image_id=b.id where a.machine_id='$machine_id'");
			}
		  while($pic_row = mysql_fetch_array($pic_result)){
			  if($pic_row['position']==0){
				$pic2[] =array(
			  'pic_id'=>$pic_row['id'],
			  'url' => $pic_row['thumbnail'] 
			  );  
				  }else{
			  $pic[] =array(
			  'pic_id'=>$pic_row['id'],
			  'url' => $pic_row['thumbnail'],
			  'position'=>$pic_row['position'] 
			  );
			  }
			 } 
			 if($row['province']==null){$row['province']="";}
			 if($row['city']==null){$row['city']="";}
			   $json_result=array(
		          'machine_name' => $row['machine_name'],
				  'uid'=>$row['user_id'],
				  'roles'=>substr($row['roles'],4),
				  'phone'=> $row['username'],
				  'nickname'=>$row['nickname'],
				  'image' => $pic,
				  'image2' => $pic2,
				  'linker'=>$row['linker'],
				  'linker_tel'=>$row['linker_tel'],
				  'lat'=>$row['lat'],
				  'lng'=>$row['lng'],
				  'category_id' => $row['category_id'],
				  'category'=>$row['category_name'],
				  'description' =>$row['description'],
				  'brand_model' => $row['brand_model'],
				  'province' => $row['province'],
				  'provinice_id' => $row['province_id'],
				  'city_id'=>$row['city_id'],
				  'city' => $row['city'],
				  'recommend' => $row['recommend'],
				  'tonn' => $row['tonn'],
				  'mark' => $row['mark'],
				  'brand_name' => $row['brand_name'],
				  'brand_id' => $row['brand_id'],
				  'workhours' => $row['workhours'],
				  'rent_by_num' => $row['rent_by_num'],
				  'rent_by_month' => $row['rent_by_month'],
				  'sale_price'=> $row['sale_price'],
				  'factory_year'=>$row['factory_year'],
				  'address'=>$row['address'],
				  'updated_date'=>$row['updated_date'],
				  'trueprice'=>$row['trueprice']
		  		  );
		}
		
		$response = new Response(json_encode($json_result));
			
		return $response;
		
    }

	//------------------------------------------------
	// 信息详情
	//------------------------------------------------
    public function preinfoAction(Request $request)
    {

		$json = $this->get('json_parser')->parse($request);
		$machine_id = $json->get('machine_id',0);
		
		$this->get('my_datebase')->connection();
		
		$sql = "SELECT a.machine_name,a.lat,a.lng,a.linker,a.linker_tel,a.flag,a.category_id,a.province_id,a.city_id,a.brand_id,a.tonn,a.mark,a.flag,a.brand_model,a.workhours,a.rent_by_num,a.rent_by_month,b.brand_name,a.sale_price,a.description,c.area as province ,d.area as city FROM Pre_machine as a left join Brand as b on a.brand_id = b.id left join Area as c on a.province_id=c.id left join Area as d on a.city_id=d.id where a.id = $machine_id";
		$result = mysql_query($sql);
		$num   = mysql_num_rows($result);
		if (!$num){
			return new Response('', 400);
		}
		while($row = mysql_fetch_array($result)){
			//获得图片
			$pic=array();
			$pic_sql = "select b.thumbnail,a.position,b.id from Machine_pic as a left join images as b on a.image_id=b.id where a.pre_machine_id='$machine_id'";
			$pic_result = mysql_query($pic_sql);
			 while($pic_row = mysql_fetch_array($pic_result)){
			  $pic[] =array(
			  'pic_id'=>$pic_row['id'],
			  'url' => $pic_row['thumbnail'],
			  'position'=>$pic_row['position'] 
			  );
			 } 
			
			   $json_result=array(
		          'machine_name' => $row['machine_name'],
				  'category_id' => $row['category_id'],
				  'description' =>$row['description'],
				  'model' => $row['brand_model'],
				  'image' =>$pic,
				  'province' => $row['province'],
				  'province_id' =>$row['province_id'],
				  'city_id' =>$row['city_id'],
				  'city' => $row['city'],
				  'tonn' => $row['tonn'],
				  'linker' => $row['linker'],
				  'linker_tel' => $row['linker_tel'],
				  'mark' => $row['mark'],
				  'flag' => $row['flag'],
				  'lat' => $row['lat'],
				  'lng' => $row['lng'],
				  'brand_name' => $row['brand_name'],
				  'brand_id' => $row['brand_id'],
				  'workhours' => $row['workhours'],
				  'rent_by_num' => $row['rent_by_num'],
				  'rent_by_month' => $row['rent_by_month'],
				  'sale_price'=> $row['sale_price']
		  		  );
		}
		
		$response = new Response(json_encode($json_result));
			
		return $response;
		
    }


	
	//------------------------------------------------
	// 推荐机器
	//------------------------------------------------
    public function recommendAction(Request $request)
    {
        
		$json = $this->get('json_parser')->parse($request);
		$machine_id = $json->get('machine_id',0);
		//$status = $json->get('recommend_status',0);
		
		$this->get('my_datebase')->connection();
		$current_time = date('Y-m-d H:i:s',time());
		$sql = "UPDATE  `Machine` SET  `recommend` =  abs(`recommend`-1),
`updated_date` ='$current_time'  WHERE  `Machine`.`id` =$machine_id LIMIT 1";
		if(mysql_query($sql)){
			return new Response('', 200);
			}else{
				
			return new Response('', 500);
			
			}
		
		
    }
	
	
		
  //------------------------------------------------
	// 删除机器
	//------------------------------------------------
    public function delAction(Request $request)
    {
        
		$json = $this->get('json_parser')->parse($request);
		$machine_id = $json->get('machine_id',0);
		
		$this->get('my_datebase')->connection();
		$current_time = date('Y-m-d H:i:s',time());
		$sql = "UPDATE  `Machine` SET  `status` =  '0',
`updated_date` ='$current_time'  WHERE  `Machine`.`id` =$machine_id LIMIT 1";
		if(mysql_query($sql)){
			return new Response('', 200);
			}else{return new Response('', 500);}
		
    }
    //------------------------------------------------
	// 删除信息
	//------------------------------------------------
    public function predelAction(Request $request)
    {
        
		$json = $this->get('json_parser')->parse($request);
		$machine_id = $json->get('machine_id',0);
		
		$this->get('my_datebase')->connection();
		$current_time = date('Y-m-d H:i:s',time());
		$sql = "UPDATE  `Pre_machine` SET  `status` =  '0',
`updated_date` ='$current_time'  WHERE  `Pre_machine`.`id` =$machine_id LIMIT 1";
		if(mysql_query($sql)){
			return new Response('', 200);
			}else{return new Response('', 500);}
		
    }
	
	
	  //------------------------------------------------
	 //create
	//--------------------------------------------------
    public function createAction(Request $request)
    {
        
		$json = $this->get('json_parser')->parse($request);
		$mark = $json->get('mark',0);
		$category = $json->get('category',0);
		$machine_name = $json->get('machine_name',"");
		$image_url = $json->get('image_url',"");
		$image_url2 = $json->get('image_url2',"");
		$sale_price = $json->get('sale_price',0);
		$rent_by_num = $json->get('rent_by_num',0);
		$rent_by_month = $json->get('rent_by_month',0);
		$brand_id = $json->get('brand_id',0);
		$brand_model = $json->get('brand_model',"");
		$factory_year = $json->get('factory_year',0);
		$workhours = $json->get('workhours',0);
		$tonn = $json->get('tonn',0);
		$province_id = $json->get('province_id',0);
		$city_id = $json->get('city_id',0);
		$description = $json->get('description',"");
		$lat = $json->get('lat',0);
		$lng = $json->get('lng',0);
		$address = $json->get('address',"");
		$linker = $json->get('linker',"");
		$linker_tel = $json->get('linker_tel',"");
		
		$this->get('my_datebase')->connection();
		$current_time = date('Y-m-d H:i:s',time());
		$user = $this->getUser();
		
		if(!$user){
			return new Response('',403);
			}
		$uid = $user->getId();
		$nickname = $user->getNickname();
		$count = $this->getUser()->getCount() + 1;
		$sql = "insert into Machine set 
		user_id=$uid,
		machine_name='$machine_name',
		category_id='$category',
		factory_year='$factory_year',
		province_id='$province_id',
		city_id='$city_id',
		brand_id='$brand_id',
		brand_model='$brand_model',
		description='$description',
		address='$address',
		lat='$lat',
		lng='$lng', 
		linker='$linker',
		linker_tel='$linker_tel',
		workhours='$workhours',
		rent_by_month='$rent_by_month',
		rent_by_num='$rent_by_num',
		tonn='$tonn',
		sale_price='$sale_price',
		status=1,
		mark='$mark',
		creation_date='$current_time',
		updated_date='$current_time'
		";
		
		if(mysql_query($sql)){
			$id = mysql_insert_id();
			mysql_query("update fos_user set count = $count where id = $uid");
			mysql_query("insert into User_log set uid=$uid,nickname = '$nickname',content='发布了机械$machine_name',creation_date='$current_time',updated_date = '$current_time',status=1");
			
			if($image_url!=""){
			//$image_url_array = explode(',',$image_url);
		
			$image_url_array = json_decode($image_url);
			foreach($image_url_array as $key=>$rs){
				//echo $key."=>".$rs;
				$sql_img="insert into Machine_pic set image_id= $rs,position=$key,machine_id= $id,creation_date='$current_time',updated_date = '$current_time',status=1";
				//$logger = $this->get('logger');
    			//$logger->error('sqlimg1'.$sql_img);
				mysql_query($sql_img);
				//echo "<br>";
				}
			}
			
			if($image_url2!=""){
			$image_url_array = explode(',',$image_url2);
		
			//$image_url_array = json_decode($image_url);
			foreach($image_url_array as $key=>$rs){
				//echo $key."=>".$rs;
				$sql_img="insert into Machine_pic set image_id= $rs,position=0,machine_id= $id,creation_date='$current_time',updated_date = '$current_time',status=1";
				//$logger = $this->get('logger');
    			//$logger->error('sqlimg2'.$sql_img);
				mysql_query($sql_img);
				//echo "<br>";
				}
			}
			
			return new Response('', 200);
			
			}else{return new Response('', 500);}
		
		
		
		
    }
	
	
	
      //------------------------------------------------
	 //info create
	//--------------------------------------------------
    public function precreateAction(Request $request)
    {
        
		$json = $this->get('json_parser')->parse($request);
		$mark = $json->get('mark',0);
		$user_id = $json->get('user_id',0);
		$category = $json->get('category',0);
		$machine_name = $json->get('machine_name',"");
		$image_url = $json->get('image_url',"");
		$sale_price = $json->get('sale_price',0);
		$rent_by_num = $json->get('rent_by_num',0);
		$rent_by_month = $json->get('rent_by_month',0);
		$brand_id = $json->get('brand_id',0);
		$brand_model = $json->get('brand_model',"");
		$factory_year = $json->get('factory_year',0);
		$workhours = $json->get('workhours',0);
		$tonn = $json->get('tonn',0);
		$linker = $json->get('linker',"");
		$linker_tel = $json->get('linker_tel',"");
		$province_id = $json->get('province_id',0);
		$city_id = $json->get('city_id',0);
		$description = $json->get('description',"");
		$lat = $json->get('lat',0);
		$lng = $json->get('lng',0);
		$address = $json->get('address',"");
		
		$this->get('my_datebase')->connection();
		$current_time = date('Y-m-d H:i:s',time());
		
		$user = $this->getUser();
		if(!$user){
			return new Response('',403);
			}
		echo $count = $user->getCount() + 1;
		echo $uid = $user->getId();
		$sql="select id from Pre_machine where linker_tel='$linker_tel'";
		$result = mysql_query($sql);
		$num = mysql_num_rows($result);
		if($num){
			$flag = -1;
			}else{
				$flag = 0;
				}
		
		$sql = "insert into Pre_machine set 
		user_id=$uid,
		machine_name='$machine_name',
		category_id='$category',
		province_id='$province_id',
		city_id='$city_id',
		brand_id='$brand_id',
		brand_model='$brand_model',
		description='$description',
		address='$address',
		lat='$lat',
		lng='$lng', 
		linker='$linker',
		linker_tel='$linker_tel',
		workhours='$workhours',
		rent_by_month='$rent_by_month',
		rent_by_num='$rent_by_num',
		factory_year='$factory_year',
		tonn='$tonn',
		sale_price='$sale_price',
		status=1,
		mark='$mark',
		flag=$flag,
		creation_date='$current_time',
		updated_date='$current_time'
		";
		
		
		if(mysql_query($sql)){
			mysql_query("update fos_user set count = $count where id = $uid");
			
			if($image_url!=""){
			$image_url_array = explode(',',$image_url);
			//$image_url_array = json_decode($image_url);
			$sql = "select id from Pre_machine order by id desc limit 1";
			$id = mysql_result(mysql_query($sql),0);
			foreach($image_url_array as $key=>$rs){
				//echo $key."=>".$rs;
				$key = $key+1;
				$sql_img="insert into Machine_pic set image_id= $rs,position=$key,pre_machine_id= $id,creation_date='$current_time',updated_date = '$current_time'";
				mysql_query($sql_img);
				//echo "<br>";
				}
			}			
			return new Response('', 200);
			
			}else{return new Response('', 500);}
		
		
		
		
    }

	
	  //------------------------------------------------
	 //update
	//--------------------------------------------------
    public function updateAction(Request $request)
    {
        
		$json = $this->get('json_parser')->parse($request);
		$machine_id = $json->get('machine_id',0);
		$mark = $json->get('mark',-1);
		$category = $json->get('category',-1);
		$machine_name = $json->get('machine_name',"");
		$image_url = $json->get('image_url',"");
		$image_url2 = $json->get('image_url2',"");
		$sale_price = $json->get('sale_price',-1);
		$rent_by_num = $json->get('rent_by_num',-1);
		$rent_by_month = $json->get('rent_by_month',-1);
		$brand_id = $json->get('brand_id',-1);
		$brand_model = $json->get('brand_model',"");
		$factory_year = $json->get('factory_year',-1);
		$workhours = $json->get('workhours',-1);
		$tonn = $json->get('tonn',-1);
		$province_id = $json->get('province_id',-1);
		$city_id = $json->get('city_id',-1);
		$description = $json->get('description',"");
		$lat = $json->get('lat',-1);
		$lng = $json->get('lng',-1);
		$linker = $json->get('linker',"");
		$linker_tel = $json->get('linker_tel',"");
		$address = $json->get('address',"");
		$trueprice = $json->get('trueprice',-1);
		$saleway = $json->get('saleway',-1);
		$this->get('my_datebase')->connection();
		$current_time = date('Y-m-d H:i:s',time());
		$user = $this->getUser();
		
		if(!$user){
			return new Response('',403);
			}
		$uid = $user->getId();
		$nickname = $user->getNickname();
		
		
		$mark_sql = ($mark==-1?"":" mark=$mark,");
		$category_sql = ($category==-1?"":" category_id=$category,");
		$machine_name_sql = ($machine_name==""?"":" machine_name='$machine_name',");
		$sale_price_sql = ($sale_price==-1?"":" sale_price=$sale_price,");
		$rent_by_num_sql = ($rent_by_num==-1?"":" rent_by_num=$rent_by_num,");
		$rent_by_month_sql = ($rent_by_month==-1?"":" rent_by_month=$rent_by_month,");
		$brand_id_sql = ($brand_id==-1?"":" brand_id=$brand_id,");
		$brand_model_sql = ($brand_model==""?"":" brand_model='$brand_model',");
		$factory_year_sql = ($factory_year==-1?"":" factory_year='$factory_year',");
		$workhours_sql = ($workhours==-1?"":" workhours=$workhours,");
		$tonn_sql = ($tonn==-1?"":" tonn=$tonn,");
		$province_id_sql = ($province_id==-1?"":" province_id=$province_id,");
		$city_id_sql = ($city_id==-1?"":" city_id=$city_id,");
		$description_sql = ($description==""?"":" description='$description',");
		$lat_sql = ($lat==-1?"":" lat='$lat',");
		$lng_sql = ($lng==-1?"":" lng='$lng',");
		$linker_sql = ($linker==""?"":" linker='$linker',");
		$linker_tel_sql = ($linker_tel==""?"":" linker_tel='$linker_tel',");		
		$address_sql = ($address==""?"":" address='$address',");
		$trueprice_sql = ($trueprice==-1?"":" trueprice='$trueprice',");
		$saleway_sql = ($saleway==-1?"":" saleway='$saleway',");
		//$collection_sql = ($collection==-1?"":"  ");
		
		$sql = "update Machine set ".$mark_sql.$category_sql.$machine_name_sql.$sale_price_sql.$rent_by_month_sql.$rent_by_num_sql.$brand_id_sql.$brand_model_sql.$factory_year_sql.$workhours_sql.$tonn_sql.$province_id_sql.$city_id_sql.$description_sql.$lat_sql.$lng_sql.$linker_sql.$linker_tel_sql.$address_sql.$trueprice_sql.$saleway_sql."
		updated_date='$current_time' 
		where id=$machine_id
		";
		
		//return new Response($sql);
		if(mysql_query($sql)){
			$now_machine_name = mysql_result(mysql_query("select machine_name from Machine where id=$machine_id"),0);
			mysql_query("insert into User_log set uid=$uid,content = '更新了机械$now_machine_name',nickname = '$nickname',creation_date='$current_time',updated_date = '$current_time',status=1");

		//将图片关系表关于machine_id的删除然后在重新插入
		if($image_url!=""){
		$sql = "delete from Machine_pic where machine_id = $machine_id and position!=0";
		mysql_query($sql);
		//$image_url_array = explode(',',$image_url);
		$image_url_array = json_decode($image_url);
		foreach($image_url_array as $key=>$rs){
				$sql_img="insert into Machine_pic set image_id= $rs,position=$key, machine_id= $machine_id,creation_date='$current_time',updated_date = '$current_time'";
				mysql_query($sql_img);
				}
		}
		
		if($image_url2!=""){
		$sql = "delete from Machine_pic where machine_id = $machine_id and position=0";
		mysql_query($sql);
		$image_url_array = explode(',',$image_url2);
		//$image_url_array = json_decode($image_url);
		foreach($image_url_array as $key=>$rs){
				$sql_img="insert into Machine_pic set image_id= $rs,position=0, machine_id= $machine_id,creation_date='$current_time',updated_date = '$current_time'";
				mysql_query($sql_img);
				}
		}
			
			return new Response('', 200);
			}else{return new Response('', 500);}
	}
	
	  //------------------------------------------------
	 //collection
	//--------------------------------------------------
	
	public function collectionAction(Request $request){
		$json = $this->get('json_parser')->parse($request);
		$machine_id = $json->get('machine_id',-1);
		$collection = $json->get('collection',-1);
		if($collection==0){$collection_sql=" set collection = collection-1 ";}
		else if($collection==1){$collection_sql=" set `collection` = `collection`+1 ";}
		else {$collection_sql="";}
		$this->get('my_datebase')->connection();
		$sql = "update Machine ".$collection_sql." where id=$machine_id ";
		if(mysql_query($sql)){
			return new Response('', 200);
			}else{
				return new Response('', 500);
				}
		}
	
		
	  //------------------------------------------------
	 //preupdate
	//--------------------------------------------------
    public function preupdateAction(Request $request)
    {
        
		$json = $this->get('json_parser')->parse($request);
		$machine_id = $json->get('machine_id',0);
		$mark = $json->get('mark',-1);
		$linker = $json->get('linker',"");
		$linker_tel = $json->get('linker_tel',"");
		$flag = $json->get('flag',-9);
		$signer = $json->get('signer',-1);
		$user_id = $json->get('user_id',-1);
		$category = $json->get('category',-1);
		$machine_name = $json->get('machine_name',"");
		$image_url = $json->get('image_url',"");
		$sale_price = $json->get('sale_price',-1);
		$rent_by_num = $json->get('rent_by_num',-1);
		$rent_by_month = $json->get('rent_by_month',-1);
		$brand_id = $json->get('brand_id',-1);
		$brand_model = $json->get('brand_model',"");
		$factory_year = $json->get('factory_year',-1);
		$workhours = $json->get('workhours',-1);
		$tonn = $json->get('tonn',-1);
		$province_id = $json->get('province_id',-1);
		$city_id = $json->get('city_id',-1);
		$description = $json->get('description',"");
		$lat = $json->get('lat',-1);
		$lng = $json->get('lng',-1);
		$address = $json->get('address',"");
		$trueprice = $json->get('trueprice',-1);
		$saleway = $json->get('saleway',-1);
		
		$this->get('my_datebase')->connection();
		$current_time = date('Y-m-d H:i:s',time());
		
		$mark_sql = ($mark==-1?"":" mark=$mark,");
		$signer_sql = ($signer==-1?"":" signer=$signer,");
		$linker_sql = ($linker==""?"":" linker=$linker,");
		$linker_tel_sql = ($linker_tel==""?"":" linker_tel=$linker_tel,");
		$flag_sql = ($flag==-9?"":" flag=$flag,");
		$user_id_sql = ($user_id==-1?"":" user_id=$user_id,");
		$category_sql = ($category==-1?"":" category_id=$category,");
		$machine_name_sql = ($machine_name==""?"":" machine_name='$machine_name',");
		$sale_price_sql = ($sale_price==-1?"":" sale_price=$sale_price,");
		$rent_by_num_sql = ($rent_by_num==-1?"":" rent_by_num=$rent_by_num,");
		$rent_by_month_sql = ($rent_by_month==-1?"":" rent_by_month=$rent_by_month,");
		$brand_id_sql = ($brand_id==-1?"":" brand_id=$brand_id,");
		$brand_model_sql = ($brand_model==""?"":" brand_model='$brand_model',");
		$factory_year_sql = ($factory_year==-1?"":" factory_year='$factory_year',");
		$workhours_sql = ($workhours==-1?"":" workhours=$workhours,");
		$tonn_sql = ($tonn==-1?"":" tonn=$tonn,");
		$province_id_sql = ($province_id==-1?"":" province_id=$province_id,");
		$city_id_sql = ($city_id==-1?"":" city_id=$city_id,");
		$description_sql = ($description==""?"":" description='$description',");
		$lat_sql = ($lat==-1?"":" lat='$lat',");
		$lng_sql = ($lng==-1?"":" lng='$lng',");
		$address_sql = ($address==""?"":" address='$address',");
		$trueprice_sql = ($trueprice==-1?"":" trueprice='$trueprice',");
		$saleway_sql = ($saleway==-1?"":" saleway='$saleway',");
		
		
		$sql = "update Pre_machine set ".$mark_sql.$signer_sql.$linker_sql.$linker_tel_sql.$flag_sql.$user_id_sql.$category_sql.$machine_name_sql.$sale_price_sql.$rent_by_month_sql.$rent_by_num_sql.$brand_id_sql.$brand_model_sql.$factory_year_sql.$workhours_sql.$tonn_sql.$province_id_sql.$city_id_sql.$description_sql.$lat_sql.$lng_sql.$address_sql.$trueprice_sql.$sale_price_sql."
		updated_date='$current_time' 
		where id=$machine_id
		";
		if(mysql_query($sql)){
		
		//将图片关系表关于machine_id的删除然后在重新插入
		if($image_url!=""){
		$sql = "delete from Machine_pic where pre_machine_id = $machine_id";
		mysql_query($sql);
		$image_url_array = explode(',',$image_url);
		//$image_url_array = json_decode($image_url);
		foreach($image_url_array as $key=>$rs){
				$sql_img="insert into Machine_pic set image_id= $rs,position=$key,pre_machine_id= $machine_id,creation_date='$current_time',updated_date = '$current_time'";
				mysql_query($sql_img);
				}
		}
		
			return new Response('', 200);
		
			
			}else{return new Response('', 500);}
	}
	 //------------------------------------------------
	 //changestatus
	//--------------------------------------------------
	public function changeStatusAction(Request $request){
		$json = $this->get('json_parser')->parse($request);
		$user = $json->get('user',-1);
		$index = $json->get('index',0);
		if(strlen($user)>1&&$user!=-1){
			$user = substr($user,1);
		}
		$this->get('spider_datebase')->connection();
		if($user==-1){
			  $sqldel = "update Machine set `status`=2 where status=1";
			}else{
				$sqldel = "update Machine set `status`=2 where status=1 and user = ".$user;
				}
		mysql_query($sqldel);
		return new JsonResponse('', 200);
		}
	  //------------------------------------------------
	 //changestatusbypage
	//--------------------------------------------------
	public function changeStatusByPageAction(Request $request){
		$json = $this->get('json_parser')->parse($request);
		$user = $json->get('user',-1);
		$start = $json->get('start',0);
		$end = $json->get('end',0);
		$mark =$json->get('mark',-1);
		$array = $json->get('array','');
		if(strlen($user)>1&&$user!=-1){
			$user = substr($user,1);
		}
		$this->get('spider_datebase')->connection();
		if($mark==-1){
		   if($user==-1){
			  $sqldel = "update Machine set `status`=2 where status=1 and id >= $start and id <= $end";
			}else{
				$sqldel = "update Machine set `status`=2 where status=1 and user = ".$user." and id >= $start and id <= $end ";
				}
		}else{
			$sqldel = "update Machine set `status`=2 where id in ($array) ";
			}
		//return new Response($sqldel,500);
		mysql_query($sqldel);
		return new JsonResponse('', 200);
		}
	
	  //------------------------------------------------
	 //upload
	//--------------------------------------------------
	public function uploadAction(Request $request){
		//$this->get('oss')->result();
		$json = $this->get('json_parser')->parse($request);
		$user = $json->get('user',-1);
		$index = $json->get('index',0);
		$start = $index*10 - 10;
		$num = 10;
		if($user==61){$user=0;}
		if($user==62){$user=1;}
		if($user==63){$user=2;}
		if($user==64){$user=3;}
		if($user==65){$user=4;}
		if($user==66){$user=5;}
		if($user==67){$user=6;}
		if($user==68){$user=7;}
		if($user==69){$user=8;}
		if($user==70){$user=9;}
		$this->get('spider_datebase')->connection();
		if($user==-1){
		$sqlnum = "select count(*) from Machine where status = 1";
		$sql = "select * from Machine where status = 1 limit ".$start.",".$num;
		}else{
			$sql = "select * from Machine where status = 1 and user =".$user." limit ".$start.",".$num;
			$sqlnum = "select count(*) from Machine where status = 1 and user =".$user;
			}
		
		$resultnum = mysql_query($sqlnum);
		$count = mysql_result($resultnum,0);
		$result = mysql_query($sql);
		$num = mysql_num_rows($result);
		if(!$num){return new JsonResponse('没有要上传的数据',400);}
		$array_img_sql = array();
		$id_str = "";
		while($row = mysql_fetch_array($result)) {
			$machine_id = $row['id'];
			//$current_time = date('Y-m-d H:i:s',$row['publish_date']);
			$current_time = date('Y-m-d H:i:s',time());
			$id_str = $id_str .",".$machine_id;
			//for($i=0;$i<count($row_img);$i++){}
			$sql_ins="insert into Machine set 
			pre_id = 0,
			user_id = 1,
			linker='$row[linker]',
			linker_tel='$row[linker_tel]',
			category_id='$row[category_id]',
			province_id=$row[province_id],
			city_id=$row[city_id],
			brand_id=$row[brand_id],
			lng='$row[lng]',
			lat='$row[lat]',
			machine_name='$row[machine_name]',
			description='',
			saleway='',
			address='',
			workhours='$row[workhours]',
			factory_year='$row[factory_year]',
			rent_by_month='',
			rent_by_num='',
			tonn='$row[tonn]',
			sale_price='$row[sale_price]',
			trueprice='',
			status=1,
			mark=1,
			recommend=0,
			creation_date='$current_time',
			updated_date='$current_time',
			brand_model='$row[brand_model]'
			";
		$array_machine_sql[] = array(
						'machine_id'=>$machine_id,
						'sql'=>$sql_ins
					);
		//array_push($array_sql,$sql_ins);
		}
		$id_str = substr($id_str,1);
		$array_img_sql = explode(',',$id_str);
		//print_r($array_img_sql);
		foreach($array_img_sql as $row){
			$machine_id = $row;
			$sql_img = "select b.url,a.position,a.machine_id from Machine_pic as a left join images as b on a.image_id = b.id where a.machine_id = $row";
				$result_img = mysql_query($sql_img);
				while($rs = mysql_fetch_array($result_img,MYSQL_ASSOC)){
					$sql = "insert into images set original_image = '$rs[url]',url = '$rs[url]',thumbnail = '$rs[url]',create_date = '$current_time',status = 1";
					//echo $image_id = msyql_insert_id();
					$array_newimg_sql[] = array(
						'machine_id'=>$rs['machine_id'],
						'position'=>$rs['position'],
						'sql'=>$sql
					);
					
				}
				
			}
		
		mysql_close();
		$this->get('my_datebase')->connection();
		//执行主表Machine的插入操作
		for($i=0;$i<count($array_machine_sql);$i++){
			mysql_query($array_machine_sql[$i]['sql']);
			$new_machine_id = mysql_insert_id();
			for($j=0;$j<count($array_newimg_sql);$j++){
				if($array_machine_sql[$i]['machine_id']==$array_newimg_sql[$j]['machine_id']){
					mysql_query($array_newimg_sql[$j]['sql']);
					$position = $array_newimg_sql[$j]['position'];
					$new_image_id = mysql_insert_id();
					mysql_query("insert into Machine_pic set machine_id = $new_machine_id,image_id = $new_image_id,position=$position,creation_date='$current_time',updated_date = '$current_time',status = 1");
					}
				}
		}
	$json_result = array(
		'count'=>$count,
		'index'=>$index	
	);
	mysql_close();
	return new JsonResponse($json_result);
	}	


	  //------------------------------------------------
	 //uploadbypage
	//--------------------------------------------------
	public function uploadByPageAction(Request $request){
		//$this->get('oss')->result();
		$json = $this->get('json_parser')->parse($request);
		$user = $json->get('user',-1);
		$start = $json->get('start',0);
		$end = $json->get('end',0);
		$mark =$json->get('mark',-1);
		$array = $json->get('array','');
		
		if($user==61){$user=0;}
		if($user==62){$user=1;}
		if($user==63){$user=2;}
		if($user==64){$user=3;}
		if($user==65){$user=4;}
		if($user==66){$user=5;}
		if($user==67){$user=6;}
		if($user==68){$user=7;}
		if($user==69){$user=8;}
		if($user==70){$user=9;}
		$this->get('spider_datebase')->connection();
		if($mark==-1){
		if($user==-1){
		$sqlnum = "select count(*) from Machine where status = 1";
		$sql = "select * from Machine where status = 1 and id >=".$start." and id <=".$end;
		}else{
			$sql = "select * from Machine where status = 1 and user =".$user." and id >=".$start." and id <=".$end;
			$sqlnum = "select count(*) from Machine where status = 1 and user =".$user;
			}
		}else{
			$sql = "select * from Machine where status = 1 and user =".$user." and id in ($array)";
			$sqlnum = "select count(*) from Machine where status = 1 and user =".$user;
			}
		//return new Response($sql,200);
		$resultnum = mysql_query($sqlnum);
		$count = mysql_result($resultnum,0);
		$result = mysql_query($sql);
		$num = mysql_num_rows($result);
		if(!$num){return new JsonResponse('没有要上传的数据',400);}
		$array_img_sql = array();
		$id_str = "";
		while($row = mysql_fetch_array($result)) {
			$machine_id = $row['id'];
			//$current_time = date('Y-m-d H:i:s',$row['publish_date']);
			$current_time = date('Y-m-d H:i:s',time());
			$id_str = $id_str .",".$machine_id;
			//for($i=0;$i<count($row_img);$i++){}
			$sql_ins="insert into Machine set 
			pre_id = 0,
			user_id = 1,
			linker='$row[linker]',
			linker_tel='$row[linker_tel]',
			category_id='$row[category_id]',
			province_id=$row[province_id],
			city_id=$row[city_id],
			brand_id=$row[brand_id],
			lng='$row[lng]',
			lat='$row[lat]',
			machine_name='$row[machine_name]',
			description='',
			saleway='',
			address='',
			workhours='$row[workhours]',
			factory_year='$row[factory_year]',
			rent_by_month='',
			rent_by_num='',
			tonn='$row[tonn]',
			sale_price='$row[sale_price]',
			trueprice='',
			status=1,
			mark=1,
			recommend=0,
			creation_date='$current_time',
			updated_date='$current_time',
			brand_model='$row[brand_model]'
			";
		$array_machine_sql[] = array(
						'machine_id'=>$machine_id,
						'sql'=>$sql_ins
					);
		//array_push($array_sql,$sql_ins);
		}
		$id_str = substr($id_str,1);
		$array_img_sql = explode(',',$id_str);
		//print_r($array_img_sql);
		foreach($array_img_sql as $row){
			$machine_id = $row;
			$sql_img = "select b.url,a.position,a.machine_id from Machine_pic as a left join images as b on a.image_id = b.id where a.machine_id = $row";
				$result_img = mysql_query($sql_img);
				while($rs = mysql_fetch_array($result_img,MYSQL_ASSOC)){
					$sql = "insert into images set original_image = '$rs[url]',url = '$rs[url]',thumbnail = '$rs[url]',create_date = '$current_time',status = 1";
					//echo $image_id = msyql_insert_id();
					$array_newimg_sql[] = array(
						'machine_id'=>$rs['machine_id'],
						'position'=>$rs['position'],
						'sql'=>$sql
					);
					
				}
				
			}
		
		mysql_close();
		$this->get('my_datebase')->connection();
		//执行主表Machine的插入操作
		for($i=0;$i<count($array_machine_sql);$i++){
			mysql_query($array_machine_sql[$i]['sql']);
			$new_machine_id = mysql_insert_id();
			for($j=0;$j<count($array_newimg_sql);$j++){
				if($array_machine_sql[$i]['machine_id']==$array_newimg_sql[$j]['machine_id']){
					mysql_query($array_newimg_sql[$j]['sql']);
					$position = $array_newimg_sql[$j]['position'];
					$new_image_id = mysql_insert_id();
					mysql_query("insert into Machine_pic set machine_id = $new_machine_id,image_id = $new_image_id,position=$position,creation_date='$current_time',updated_date = '$current_time',status = 1");
					}
				}
		}
	$json_result = array(
		'count'=>$count
	);
	mysql_close();
	return new JsonResponse($json_result);
	}	




}
