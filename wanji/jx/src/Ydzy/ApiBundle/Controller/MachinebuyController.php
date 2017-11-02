<?php   
namespace Ydzy\ApiBundle\Controller;
use Symfony\Component\HttpFoundation\Request;
use Symfony\Component\HttpFoundation\Response;
use Symfony\Bundle\FrameworkBundle\Controller\Controller;
use Symfony\Component\HttpFoundation\JsonResponse;


class MachinebuyController extends Controller
{
    public function indexAction(Request $request)
    {
        return new Response("jx machinebuy");
    }

	//------------------------------------------------
	//create
	//--------------------------------------------------
    public function createAction(Request $request)
    {
        
		$json = $this->get('json_parser')->parse($request);
		$category = $json->get('category',0);
		$sale_price = $json->get('sale_price',0);
		$brand_id = $json->get('brand_id',0);
		$brand_model = $json->get('brand_model',"");
		$workhours = $json->get('workhours',0);
		$tonn = $json->get('tonn',0);
		$province_id = $json->get('province_id',0);
		$city_id = $json->get('city_id',0);
		$description = $json->get('description',"");
		$inlet = $json->get('inlet',0);
		$linker = $json->get('linker',"");
		$linker_tel = $json->get('linker_tel',"");
		
		$this->get('my_datebase')->connection();
		$current_time = date('Y-m-d H:i:s',time());
		$current_time1 = time();
		$user = $this->getUser();
		
		if(!$user){
			return new Response('',403);
			}
		$uid = $user->getId();
		$nickname = $user->getNickname();
		$sql = "insert into machine_buy set 
		user_id=$uid,
		category_id='$category',
		province_id='$province_id',
		city_id='$city_id',
		brand_id='$brand_id',
		brand_model='$brand_model',
		description='$description',
		inlet='$inlet',
		linker='$linker',
		linker_tel='$linker_tel',
		workhours='$workhours',
		tonn='$tonn',
		sale_price='$sale_price',
		status=1,
		creation_date='$current_time1',
		updated_date='$current_time1'
		";
		
		if(mysql_query($sql))
		{
			$id = mysql_insert_id();
			mysql_query("update fos_user set buycount=buycount+1 where id = $uid");
			mysql_query("insert into User_log set uid=$uid,nickname = '$nickname',content='发布了求购$brand_model',creation_date='$current_time',updated_date = '$current_time',status=1");
			return new Response('', 200);
		}else
		{
			return new Response('', 500);
		}
	}
	//------------------------------------------------
	// 根据条件筛选求购机器列表
	//------------------------------------------------
    public function buyFilterAction(Request $request)
    {
    	$json = $this->get('json_parser')->parse($request);
		$machine_id = $json->get('machine_id',0);
		$uid = $json->get('uid',-1);
		$category_id = $json->get('category_id',0);
		$brand_id = $json->get('brand_id',0);
		$province = $json->get('province',-1);
		$city = $json->get('city',-1);
		$keyword = trim($json->get('keyword',''));
		$keyword_array = (explode(" ",$keyword));
		$keynum = count($keyword_array);
		$tonn_min = $json->get('tonn_min',0);
		$tonn_max = $json->get('tonn_max',100);
		$workhours_min = $json->get('workhours_min',0);
		$workhours_max = $json->get('workhours_max',99999);
		$sale_price_min = $json->get('sale_price_min',0);
		$sale_price_max = $json->get('sale_price_max',99999);
		$workhours_sort = $json->get('workhours_sort',-1);
		$sale_price_sort = $json->get('sale_price_sort',-1);
		$updated_date_sort = $json->get('updated_date_sort',-1);
		$type = $json->get('type',0);
		$start = $json->get('start',0);
		$num = $json->get('num',20);
		$current_year = date("Y",time());

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
		$province==-1?$province = "":$province = " a.province_id =$province and ";
		$city==-1?$city = "":$city = " a.city_id =$city and ";
		//有机器的id
		$machine = ($machine_id==0?$machine = "":$machine = " a.id =$machine_id and ");
		//品牌筛选
		$brand = ($brand_id==0?"":" a.brand_id ='$brand_id' and ");

		$types = ($type==0?"":" a.type ='$type' and ");
		//关键词筛选
		if($keyword==''){$filter = " a.status = 1 ";}
		else if($keynum==1){$filter = " (a.description like '%".$keyword."%' or a.brand_model like '%".$keyword."%' or b.brand_name like '%".$keyword."%' or e.nickname like '%".$keyword."%' or e.phone like '%".$keyword."%' or a.linker like '%".$keyword."%' or a.linker_tel like '%".$keyword."%') and a.status = 1 ";}
		else{
			$filter = "";
			for($i=0;$i<$keynum;$i++){
				$keyword_array[$i];
				$filter = $filter." (a.description like '%".$keyword_array[$i]."%' or a.brand_model like '%".$keyword_array[$i]."%' or b.brand_name like '%".$keyword_array[$i]."%' or e.nickname like '%".$keyword_array[$i]."%' or e.phone like '%".$keyword_array[$i]."%' or a.linker like '%".$keyword_array[$i]."%' or a.linker_tel like '%".$keyword_array[$i]."%') and ";
				}
			$filter = substr($filter,0,-4)." and a.status = 1 ";
			}
		
		$tonn=($tonn_min == 0&&$tonn_max == 100)?"":" and a.tonn>=$tonn_min and a.tonn<=$tonn_max ";
		$workhours=($workhours_min == 0&&$workhours_max ==99999)?"":" and a.workhours>=$workhours_min and a.workhours<=$workhours_max ";
		
		//价格的筛选
		$sale_price = ($sale_price_min==0&&$sale_price_max==99999)?"":" and a.sale_price>=$sale_price_min and a.sale_price<=$sale_price_max ";
		
		//排序部分
		$workhours_sort==-1?$workhours_sort = "":($workhours_sort == 0?$workhours_sort = " order by a.workhours desc ":$workhours_sort = " order by a.workhours asc ");
		
		$sale_price_sort==-1?$sale_price_sort = "":($sale_price_sort == 0?$sale_price_sort = " order by a.sale_price desc ":$sale_price_sort = " order by a.sale_price asc ");
		
		if($updated_date_sort==-1&&$workhours_sort==""&&$sale_price_sort==""){$updated_sort = " order by a.id desc ";}
		else if($updated_date_sort == 0){$updated_sort = " order by a.id desc ";}
		else if($updated_date_sort == 1){$updated_sort = " order by a.id asc ";}
		else {$updated_sort = "";}
		//echo $updated_sort;
		//分页
		//$limit = (($start==-1&&$num==-1)||($start==-1))?"":" limit $start,$num ";
		$limit  = " limit $start,$num";
		$sql =$machine.$uid_sql.$category.$brand.$types.$province.$city.$filter.$tonn.$workhours.$sale_price.$workhours_sort.$sale_price_sort.$updated_sort.$limit;
		
		$sql = "SELECT a.*,f.category_name,b.brand_name,c.area as province, d.area as city, e.nickname,e.phone,g.tonn as tonns,g.url FROM machine_buy as a left join Brand as b on a.brand_id=b.id left join Area as c on c.id= a.province_id left join Area as d on d.id=a.city_id left join fos_user as e on e.id = a.user_id left join Category as f on a.category_id=f.id left join brand_images as g on g.`name`=a.brand_model where g.category_id=a.category_id AND ".$sql;
//		echo $sql;

 		//return new Response($sql);
		$result = mysql_query($sql);
		$num   = mysql_num_rows($result);
		
		if (!$num){
			return new Response('', 400);
		}
		
		while($row = mysql_fetch_array($result,MYSQL_ASSOC))
		{
			$creation_date = date("Y/m/d",$row['creation_date']);
			$creation_date1 = date("Y-m-d",$row['creation_date']);
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
				  'description' => $row['description'],
				  'tonn' => $row['tonn'],
				  'tonns' => $row['tonns'],
				  'model' => $row['brand_model'],
				  'brand' => $row['brand_name'],
				  'workhours' => $row['workhours'],
				  'sale_price' => $row['sale_price'],
				  'collection'=>$row['collection'],
				  'creation_date' => $creation_date,
				  'creation_date1' => $creation_date1,
				  'updated_date' => $row['updated_date'],
				  'url' => $row['url'],
				  'title' => $row['typetitle'],
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
				  'description' => $row['description'],
				  'tonn' => $row['tonn'],
				  'tonns' => $row['tonns'],
				  'model' => $row['brand_model'],
				  'brand' => $row['brand_name'],
				  'workhours' => $row['workhours'],
				  'sale_price' => $row['sale_price'],
				  'collection'=>$row['collection'],
				  'creation_date' => $creation_date,
				  'creation_date1' => $creation_date1,
				  'updated_date' => $row['updated_date'],
				  'url' => $row['url'],
				  'title' => $row['typetitle'],
				  'status' =>$row['status']
				  );
				  
				  
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
	// 根据条件获得机器的条目数
	//------------------------------------------------
	public function buyFilterNumAction(Request $request)
	{
		$json = $this->get('json_parser')->parse($request);
		$uid = $json->get('uid',-1);
		$category_id = $json->get('category_id',0);
		$brand_id = $json->get('brand_id',0);
		$province = $json->get('province',-1);
		$city = $json->get('city',-1);
		$tonn_min = $json->get('tonn_min',0);
		$tonn_max = $json->get('tonn_max',100);
		$workhours_min = $json->get('workhours_min',0);
		$workhours_max = $json->get('workhours_max',99999);
		$sale_price_min = $json->get('sale_price_min',0);
		$sale_price_max = $json->get('sale_price_max',99999);
		$current_year = date("Y",time());
		$keyword = $json->get('keyword','');
		$keyword_array = (explode(" ",$keyword));
		//echo 
		$keynum = count($keyword_array);

		//自动获取uid
		$user = $this->getUser();
	    if($uid==-1){
			$uid_sql="";
			}else if($uid!=0){
				$uid_sql = " and a.user_id =$uid  ";
				}else if($uid==0&&$user){
					$user_id = $user->getId();
				$uid_sql=" and a.user_id =$user_id ";
				}else {
					return new Response('',403);
					}
							
		$category_id==0?$category=" and a.category_id!='' ":($category_id==3?$category=" and a.category_id not in (1,2) ":($category_id==4?$category=" and a.category_id = 4 ":($category_id==5?$category=" and a.category_id = 5 ":$category = " and a.category_id =$category_id ")));
		
		
		$province==-1?$province= "":$province = " and a.province_id=$province ";
		$city==-1?$city= "":$city = " and a.city_id=$city ";
		//关键词筛选		
		if($keyword==''){$filter = "";}
		else if($keynum==1){$filter = " and (a.description like '%$keyword%' or a.brand_model like '%$keyword%' or b.brand_name like '%$keyword%' or e.nickname like '%$keyword%' or e.phone like '%$keyword%' or a.linker like '%$keyword%' or a.linker_tel like '%$keyword%') ";}
		else{
			$filter = "";
			for($i=0;$i<$keynum;$i++){
				$keyword_array[$i];
				$filter = $filter." (a.description like '%$keyword_array[$i]%' or a.brand_model like '%$keyword_array[$i]%' or b.brand_name like '%$keyword_array[$i]%' or e.nickname like '%$keyword_array[$i]%' or e.phone like '%$keyword_array[$i]%' or a.linker like '%$keyword_array[$i]%' or a.linker_tel like '%$keyword_array[$i]%') and ";
				}
			$filter = " and ".substr($filter,0,-4);
			}
			
		//品牌筛选
		$brand = ($brand_id==0?"":" and  a.brand_id ='$brand_id'"); 
		$tonn=($tonn_min == 0&&$tonn_max == 100)?"":" and a.tonn>=$tonn_min and a.tonn<=$tonn_max ";
		
		$workhours=($workhours_min == 0&&$workhours_max ==99999)?"":" and a.workhours>=$workhours_min and a.workhours<=$workhours_max ";

		$sale_price = ($sale_price_min==0&&$sale_price_max==99999)?"":" and a.sale_price>=$sale_price_min and a.sale_price<=$sale_price_max ";

		$this->get('my_datebase')->connection();
		$sql = "SELECT count(*) as num FROM machine_buy as a left join Brand as b on a.brand_id=b.id left join fos_user as e on e.id = a.user_id where a.status=1".$category.$uid_sql.$province.$city.$filter.$brand.$tonn.$workhours.$sale_price;
		// return new Response($sql);
		$result = mysql_query($sql);
		$num   = mysql_num_rows($result);
//		echo $sql;
		if (!$num){
			return new JSONResponse();
		}
				
		$response = new JSONResponse(mysql_result($result,0));
		return $response;
	}
	//------------------------------------------------
	// 机器详情
	//------------------------------------------------
    public function buyinfoAction(Request $request)
    {

		$json = $this->get('json_parser')->parse($request);
		$machine_id = $json->get('machine_id',0);
		
		$this->get('my_datebase')->connection();
		
		$sql = "SELECT a.*,f.category_name,b.brand_name,c.area as province, d.area as city, e.nickname,e.phone,g.tonn as tonns,g.url FROM machine_buy as a left join Brand as b on a.brand_id=b.id left join Area as c on c.id= a.province_id left join Area as d on d.id=a.city_id left join fos_user as e on e.id = a.user_id left join Category as f on a.category_id=f.id left join brand_images as g on g.`name`=a.brand_model where g.category_id=a.category_id AND a.id = $machine_id and a.status=1";
//		echo $sql;
		$result = mysql_query($sql);
		$num   = mysql_num_rows($result);
		if (!$num){
			return new Response('', 400);
		}
		$row = mysql_fetch_array($result);
		$creation_date = date("Y-m-d",$row['creation_date']);
		$json_result=array(
			'machine_id' => $row['id'],
			'nickname'=> $row['nickname'],
			'phone' => $row['phone'],
			'linker'=>$row['linker'],
			'linker_tel'=>$row['linker_tel'],
			'province' => $row['province'],
			'city' => $row['city'],
			'inlet' => $row['inlet'],
			'category'=>$row['category_name'],
			'category_id' => $row['category_id'],
			'uid' => $row['user_id'],
			'description' => $row['description'],
			'tonn' => $row['tonn'],
			'tonns' => $row['tonns'],
			'model' => $row['brand_model'],
			'brand' => $row['brand_name'],
			'workhours' => $row['workhours'],
			'sale_price' => $row['sale_price'],
			'collection'=>$row['collection'],
			'creation_date' => $creation_date,
			'updated_date' => $row['updated_date'],
			'url' => $row['url'],
			'status' =>$row['status']
		);
		
		$response = new Response(json_encode($json_result));
			
		return $response;
		
    }
	//------------------------------------------------
	//create 添加到专区
	//--------------------------------------------------
    public function createprefectureAction(Request $request)
    {
        
		$json = $this->get('json_parser')->parse($request);
		$machine_id = $json->get('machine_id','');
		$machname = $json->get('machname','');
		$machimg = $json->get('machimg','');
		$type = $json->get('type','');
		
		if($machine_id != '' && $machname != '' && $machimg != '' && $type != '')
		{
			$this->get('my_datebase')->connection();
			$current_time = time();
			$user = $this->getUser();
			if(!$user){
				return new Response('',403);
				}
			$sql = "insert into wj_prefecture set machine_id='$machine_id',machine_title='$machname',machine_img='$machimg',machine_type='$type',prefecture_date='$current_time',status=1";
			
			if(mysql_query($sql))
			{
				return new Response('', 200);
			}else
			{
				return new Response('', 500);
			}
		}else
		{
			return new Response('', 500);
		}
	}
	//------------------------------------------------
	//查询专区
	//--------------------------------------------------
    public function prefectureinfoAction(Request $request)
    {
        $json = $this->get('json_parser')->parse($request);
		$type = $json->get('type',1);
		$num = $json->get('num',30);
		
		$this->get('my_datebase')->connection();

		$sql = "SELECT a.machine_id,a.machine_title,a.machine_img,b.linker,b.linker_tel,b.brand_model,b.tonn,b.updated_date,c.brand_name,d.area province,e.area city FROM `wj_prefecture` a LEFT JOIN Machine b on a.machine_id=b.id LEFT JOIN Brand c on b.brand_id=c.id LEFT JOIN Area d on b.province_id=d.id LEFT JOIN Area e on b.city_id=e.id WHERE a.status=1 and a.machine_type=".$type." and b.status=1 and b.mark=1 ORDER BY a.id DESC LIMIT ".$num;
		
		$result = mysql_query($sql);
		$num   = mysql_num_rows($result);
		
		if (!$num){
			return new Response('', 400);
		}
		
		while($row = mysql_fetch_array($result,MYSQL_ASSOC))
		{
			substr($row['machine_img'],0,6) == './imgs'?$machine_img = 'http://img.wanjiwang.cn'.substr($row['machine_img'],1):$machine_img = $row['machine_img'];
			$json_key[]=array(
		  		  'machine_id' => $row['machine_id'],
				  'machine_title'=> $row['machine_title'],
				  'machine_img' => $machine_img,
				  'linker'=>$row['linker'],
				  'linker_tel'=>$row['linker_tel'],
				  'province' => $row['province'],
				  'city' => $row['city'],
				  'tonn' => $row['tonn'],
				  'model' => $row['brand_model'],
				  'brand' => $row['brand_name'],
				  'updated_date' => $row['updated_date'],
				  );
		}
		return new JsonResponse(array_values($json_key));
	}
	//------------------------------------------------
	//create 添加到急购专区
	//--------------------------------------------------
    public function upprefectureAction(Request $request)
    {
        
		$json = $this->get('json_parser')->parse($request);
		$machine_id = $json->get('machine_id','');
		$machname = $json->get('machname','');
		$type = $json->get('type','');
		
		if($machine_id != '' && $machname != '' && $type != '')
		{
			$this->get('my_datebase')->connection();
			$current_time = time();
			$user = $this->getUser();
			if(!$user){
				return new Response('',403);
				}
			$sql = "update machine_buy set type=$type,typetitle='$machname',type_date='$current_time' where id = $machine_id";
			
			if(mysql_query($sql))
			{
				return new Response('', 200);
			}else
			{
				return new Response('', 500);
			}
		}else
		{
			return new Response('', 500);
		}
	}
	//------------------------------------------------
	//yishou 图片已售审核
	//--------------------------------------------------
    public function yishouAction(Request $request)
    {
		$this->get('spider_datebase')->connection();
		$sql = "SELECT topicid FROM `Machine` WHERE topicid <> '' and topicid <> 'shuo' and status <> '4' GROUP BY topicid HAVING COUNT(id) > 1 ORDER BY publish_date DESC LIMIT 1";
		$result = mysql_query($sql);
		$topicid = mysql_result($result,0);

		$sql1 = "SELECT id,linker,linker_tel,province_id,city_id,lng,lat,category_id,brand_id,machine_name,workhours,factory_year,tonn,sale_price,creation_date,publish_date,brand_model,topicid,topicnum FROM `Machine` WHERE topicid='".$topicid."' ORDER BY id DESC";
		$result1 = mysql_query($sql1);
		$num = mysql_num_rows($result1);
		
		if (!$num){
			return new Response('', 400);
		}

		$strids = '';
		while($row = mysql_fetch_array($result1,MYSQL_ASSOC))
		{
			mysql_close();
			$this->get('my_datebase')->connection();
			$sql2 = "SELECT id FROM `Machine` WHERE linker='".$row['linker']."' and linker_tel='".$row['linker_tel']."' and category_id='".$row['category_id']."' and province_id='".$row['province_id']."' and city_id='".$row['city_id']."' and brand_id='".$row['brand_id']."' and lng='".$row['lng']."' and lat='".$row['lat']."' and machine_name='".$row['machine_name']."' and workhours='".$row['workhours']."' and factory_year='".$row['factory_year']."' and tonn='".$row['tonn']."' and sale_price='".$row['sale_price']."' and creation_date='".$row['creation_date']."' and brand_model='".$row['brand_model']."'";

			$result2 = mysql_query($sql2);
			$wanjiid = mysql_result($result2,0);
			$strids = $strids . $row['id'] . ','; 
			
			$json_key[$row['id']]=array(
				  'machine_id' => $row['id'],
				  'wanji_id' => $wanjiid,
				  'linker'=>$row['linker'],
				  'linker_tel'=>$row['linker_tel'],
				  'machine_name' => $row['machine_name'],
				  'sale_price' => $row['sale_price'],
				  'publish_date' => $row['publish_date'],
				  'topicid' => $row['topicid'],
				  'topicnum' => $row['topicnum'],
				  'creation_date' => $row['creation_date'],
				  'pic_all'=>''
				  );
		}
		$this->get('spider_datebase')->connection();
		 //处理图片
		 if(strlen($strids) > 0)
		{
			$strids = substr($strids, 0, -1);
		}
		$pic=array();
		$pic_result = mysql_query("select a.machine_id,b.url from Machine_pic as a left join images as b on a.image_id=b.id where a.machine_id in (".$strids.")");

		while($pic_row = mysql_fetch_array($pic_result)){
			$pic[] =array(
				'machine_id'=>$pic_row['machine_id'],
				'pic' => $pic_row['url']
			);
		}
		mysql_close();
		foreach ($pic as $key => $value) {
			if($json_key[$value['machine_id']]['pic_all']!=''){
				$json_key[$value['machine_id']]['pic_all'].=",".$value['pic'];
			}else{
				$json_key[$value['machine_id']]['pic_all']=$value['pic'];
				}
		}
		return new JsonResponse(array_values($json_key));
	}
	 //------------------------------------------------
	 // 已售审核
	//------------------------------------------------
    public function yishoushenheAction(Request $request)
	{
		$json = $this->get('json_parser')->parse($request);
		$machine_id = $json->get('machine_id',0);
		$wanji_id = $json->get('wanji_id',0);
		$publish_date = $json->get('publish_date','');
		$topicid = $json->get('topicid','');
		$type = $json->get('type','');
		$this->get('spider_datebase')->connection();
		
		if($machine_id != '' || $machine_id != '0')
		{
			if($type == 1)
			{
				$sql = "update Machine set end_date=$publish_date,status='4' where id = $machine_id";
			}else if($type == 2)
			{
				$sql = "update Machine set refresh=refresh+1,end_date=$publish_date,status='4' where id = $machine_id";
			}
		}
		if(mysql_query($sql))
		{
			mysql_close();
			$flagtyep = 1;
			if($wanji_id != '' || $wanji_id != '0')
			{
				$this->get('my_datebase')->connection();
				$current_time = date('Y-m-d H:i:s',$publish_date);
				if($type == 1)
				{
					$sql1 = "update Machine set saleway='5',mark='3',updated_date='".$current_time."',end_date=$publish_date,topicid='".$topicid."' where id = $wanji_id";
				}else if($type == 2)
				{
					$sql1 = "update Machine set refresh=refresh+1,publish_date=$publish_date,updated_date='".$current_time."',end_date=$publish_date,topicid='".$topicid."',saleway='6' where id = $wanji_id";
				}
				if(mysql_query($sql1))
				{
					$flagtyep = 2;
				}else
				{
					$flagtyep = 3;
				}
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
}
