<?php

namespace Ydzy\ApiBundle\Controller;
use Symfony\Component\HttpFoundation\Request;
use Symfony\Component\HttpFoundation\Response;
use Symfony\Bundle\FrameworkBundle\Controller\Controller;
use Symfony\Component\HttpFoundation\JsonResponse;

class AreaController extends Controller
{
    public function indexAction(Request $request)
    {
        return new Response("jx area");
    }
	//------------------------------------------------
	// 获得所有省坐标和当前机器数量
	//------------------------------------------------
	public function retrieveAllProLocationAction(Request $request)
	{
		$this->get('my_datebase')->connection();
		$sql = "select * from Area where upid=0 and status=1";
			  $result = mysql_query($sql);
			  while($row = mysql_fetch_array($result)){
				 	if($row['lat']!=''){
						$sql = "select id from Machine where province_id = $row[id]";
						$num = mysql_num_rows(mysql_query($sql));
						  $arrayPro[] = array(
							 'id'=>$row['id'],
							 'area'=>$row['area'],
							 'num'=>$num,
							 'lng'=>$row['lng'],
							 'lat'=>$row['lat']
							 );
						  }
					
				  }
				  
		$json = $this->get('json_encode')->JSON($arrayPro);
		$response = new Response($json);
		return $response;
	}
	//------------------------------------------------
	// 获得所有城市坐标和当前机器数量 
	//------------------------------------------------
	public function retrieveAllCityLocationAction(Request $request)
	{
		$json = $this->get('json_parser')->parse($request);
		$province = $json->get('province','');
		$this->get('my_datebase')->connection();
		$sql = "select id from Area where area like '%$province%' limit 1";
		$upid = mysql_result(mysql_query($sql),0);
		$sql = "select * from Area where upid=$upid and status=1";
			  $result = mysql_query($sql);
			  while($row = mysql_fetch_array($result)){
				 	if($row['lat']!=''){
						$sql = "select id from Machine where city_id = $row[id]";
						$num = mysql_num_rows(mysql_query($sql));
						  $arrayPro[] = array(
							 'id'=>$row['id'],
							 'area'=>$row['area'],
							 'num'=>$num,
							 'lat'=>$row['lat'],
							 'lng'=>$row['lng']
							 );
						  }
					
				  }
				  
		$json = $this->get('json_encode')->JSON($arrayPro);
		$response = new Response($json);
		return $response;
	}
	//------------------------------------------------
	// 根据条件获得机器的条目数
	//------------------------------------------------
	public function retrieveAllMachineNumAction(Request $request)
	{
		$json = $this->get('json_parser')->parse($request);
		$mark = $json->get('mark',-1);
		$uid = $json->get('uid',-1);
		$recommend = $json->get('recommend',-1);
		$category_id = $json->get('category_id',0);
		$brand_id = $json->get('brand_id',0);
		$province = $json->get('province',-1);
		$city = $json->get('city',-1);
		$ago_check = $json->get('ago_check','');
		$lat = $json->get('lat',0);
		$lng = $json->get('lng',0);
		$mianyi = $json->get('mianyi',-1);
		$distance = $json->get('distance',0);
		$tonn_min = $json->get('tonn_min',0);
		$tonn_max = $json->get('tonn_max',100);
		$workhours_min = $json->get('workhours_min',0);
		$workhours_max = $json->get('workhours_max',99999);
		$sale_price_min = $json->get('sale_price_min',0);
		$sale_price_max = $json->get('sale_price_max',99999);
		$factory_year_mins = $json->get('factory_year_min',0);
		$factory_year_maxs = $json->get('factory_year_max',100);
		$current_year = date("Y",time());
		$factory_year_min = ($current_year - $factory_year_mins)."12";
		$factory_year_max = ($current_year - $factory_year_maxs)*100;
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
							
		$category_id==0?$category=" and a.category_id!='' ":($category_id==3?$category=" and a.category_id not in (1,2) ":($category_id==4?$category=" and a.category_id = 5 ":($category_id==5?$category=" and a.category_id = 4 ":$category = " and a.category_id =$category_id ")));
		
		
		$province==-1?$province= "":$province = " and a.province_id=$province ";
		$city==-1?$city= "":$city = " and a.city_id=$city ";
		//面议状态的筛选，默认是不显示
		if($mianyi==-1){
			$mianyi_sql = " and a.sale_price!=0 ";
			}else{
				$mianyi_sql = "";
				}
		//关键词筛选		
		if($keyword==''){$filter = "";}
		else if($keynum==1){$filter = " and (a.machine_name like '%$keyword%' or a.brand_model like '%$keyword%' or b.brand_name like '%$keyword%' or e.nickname like '%$keyword%' or e.phone like '%$keyword%' or a.linker like '%$keyword%' or a.linker_tel like '%$keyword%') ";}
		else{
			$filter = "";
			for($i=0;$i<$keynum;$i++){
				$keyword_array[$i];
				$filter = $filter." (a.machine_name like '%$keyword_array[$i]%' or a.brand_model like '%$keyword_array[$i]%' or b.brand_name like '%$keyword_array[$i]%' or e.nickname like '%$keyword_array[$i]%' or e.phone like '%$keyword_array[$i]%' or a.linker like '%$keyword_array[$i]%' or a.linker_tel like '%$keyword_array[$i]%') and ";
				}
			$filter = " and ".substr($filter,0,-4);
			}
			
		//品牌筛选
		$brand = ($brand_id==0?"":" and  a.brand_id ='$brand_id'"); 
		$tonn=($tonn_min == 0&&$tonn_max == 100)?"":" and a.tonn>=$tonn_min and a.tonn<=$tonn_max ";
		
		$workhours=($workhours_min == 0&&$workhours_max ==99999)?"":" and a.workhours>=$workhours_min and a.workhours<=$workhours_max ";

		$factory_year = ($factory_year_mins==0&&$factory_year_maxs==100)?"":" and a.factory_year<= $factory_year_min and a.factory_year>=$factory_year_max ";
		
		$sale_price = ($sale_price_min==0&&$sale_price_max==99999)?"":" and a.sale_price>=$sale_price_min and a.sale_price<=$sale_price_max ";

		$recommend==-1?$recommend= "":$recommend = " and a.recommend=$recommend ";
		$ago_check!=1?$ago_check = "":$ago_check = " and a.ago_check =$ago_check ";
		
		if($mark==-1){$mark = " and a.mark!=-2 and a.mark!=-3";}
		
		else if($mark==0&&$uid==0){$mark=" and (a.mark =0 or a.mark = 2 or a.mark = -2) ";}
		else if($mark==0&&$uid!=0){$mark=" and (a.mark =0 or a.mark = 2) ";}
		else if($mark==1&&$uid==0){$mark=" and (a.mark =1 or a.mark = 3 or a.mark = -3) ";}
		else if($mark==1&&$uid!=0){$mark=" and (a.mark =1 or a.mark = 3) ";}
		
		
		//距离筛选，先获取经纬度，然后判断保存的经纬度
		if($distance!=0&&$lng!=0&&$lat!=0){
		//添加经纬度的sql，查找id
			$location_sql = " and ((abs(a.lat-$lat)+abs(a.lng-$lng))*110)<=$distance ";		
		}
		else{$location_sql="";}
		
		
		$this->get('my_datebase')->connection();
		$sql = "SELECT count(*) as num FROM Machine as a left join Brand as b on a.brand_id=b.id left join fos_user as e on e.id = a.user_id where a.status=1".$category.$uid_sql.$province.$city.$filter.$mianyi_sql.$brand.$tonn.$workhours.$factory_year.$sale_price.$mark.$recommend.$ago_check;
			// return new Response($sql);
			  $result = mysql_query($sql);
			  $num   = mysql_num_rows($result);
		
				if (!$num){
					return new JSONResponse();
				}
				
				  
		 
		
		$response = new JSONResponse(mysql_result($result,0));
			
		return $response;
		}
	//------------------------------------------------
	// 获得所有机器
	//------------------------------------------------
	public function retrieveAllMachineAction(Request $request)
	{
		$json = $this->get('json_parser')->parse($request);
		$mark = $json->get('mark',-1);
		$uid = $json->get('uid',-1);
		$recommend = $json->get('recommend',-1);
		$category_id = $json->get('category_id',0);
		$brand_id = $json->get('brand_id',0);
		$province = $json->get('province',-1);
		$city = $json->get('city',-1);
		$lat = $json->get('lat',0);
		$lng = $json->get('lng',0);
		$lat1 = $json->get('lat1',0);
		$lng1 = $json->get('lng1',0);
		$distance = $json->get('distance',0);
		$tonn_min = $json->get('tonn_min',0);
		$tonn_max = $json->get('tonn_max',100);
		$workhours_min = $json->get('workhours_min',0);
		$workhours_max = $json->get('workhours_max',99999);
		$sale_price_min = $json->get('sale_price_min',0);
		$sale_price_max = $json->get('sale_price_max',99999);
		$factory_year_mins = $json->get('factory_year_min',0);
		$factory_year_maxs = $json->get('factory_year_max',100);
		$current_year = date("Y",time());
		$factory_year_min = ($current_year - $factory_year_mins)."12";
		$factory_year_max = ($current_year - $factory_year_maxs)*100;
		$keyword = $json->get('keyword','');

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
					
		$category_id==0?$category=" and a.category_id!='' ":($category_id==3?$category=" and a.category_id not in (1,2) ":$category = " and a.category_id =$category_id ");
		
		$province==-1?$province= "":$province = " and a.province_id=$province ";
		$city==-1?$city= "":$city = " and a.city_id=$city ";
		
		//关键词筛选
		$filter =$keyword==''?"":" and (a.machine_name like '%$keyword%' or a.brand_model like '%$keyword%' or b.brand_name like '%$keyword%' or e.phone like '%$keyword%' or a.linker_tel like '%$keyword%') ";
		//品牌筛选
		$brand = ($brand_id==0?"":" and  a.brand_id ='$brand_id'"); 
		$tonn=($tonn_min == 0&&$tonn_max == 100)?"":" and a.tonn>=$tonn_min and a.tonn<=$tonn_max ";
		
		$workhours=($workhours_min == 0&&$workhours_max ==99999)?"":" and a.workhours>=$workhours_min and a.workhours<=$workhours_max ";

		$factory_year = ($factory_year_mins==0&&$factory_year_maxs==100)?"":" and a.factory_year<= $factory_year_min and a.factory_year>=$factory_year_max ";
		
		$sale_price = ($sale_price_min==0&&$sale_price_max==99999)?"":" and a.sale_price>=$sale_price_min and a.sale_price<=$sale_price_max ";

		$recommend==-1?$recommend= "":$recommend = " and a.recommend=$recommend ";
		
		if($mark==-1){$mark = " and a.mark!=-2 and a.mark!=-3";}
		else{$mark = " and a.mark =$mark";}
		
		//距离筛选，先获取经纬度，然后判断保存的经纬度
		if($lng!=0&&$lat!=0&&$lng1!=0&&$lat1!=0){
		//添加经纬度的sql，查找id
			$location_sql = " and a.lat<$lat and a.lat>$lat1 and a.lng<$lng1 and a.lng>$lng ";		
		}
		else{$location_sql="";}
		
		
		$this->get('my_datebase')->connection();
		$sql = "SELECT a.*,b.brand_name FROM Machine as a left join Brand as b on a.brand_id=b.id left join fos_user as e on e.id = a.user_id where a.status=1".$category.$uid_sql.$province.$city.$filter.$brand.$tonn.$workhours.$factory_year.$sale_price.$mark.$recommend.$location_sql;
			 //return new Response($sql);
			  $result = mysql_query($sql);
			  $num   = mysql_num_rows($result);
		
				if (!$num){
					return new JSONResponse();
				}
				
			  while($row = mysql_fetch_array($result)){
				  
				 $factory_year_full = substr($row['factory_year'],0,4)."年".substr($row['factory_year'],4)."月"; 
				 $factory_year = substr($row['factory_year'],0,4);
				  
				$json_result[]=array(
		  		  'machine_id' => $row['id'],
				  'factory_year' => $factory_year,
				  'factory_year_full'=> $factory_year_full,
				  'machine_name' => $row['machine_name'],
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
				  'creation_date' => $row['creation_date'],
				  'updated_date' => $row['updated_date'],
				  'status' =>$row['status']
		  		  );
				  
				  
		  }
		
		$response = new Response(json_encode($json_result));
			
		return $response;
		}
	//------------------------------------------------
	// 省
	//------------------------------------------------
	public function retrieveProAction(Request $request){
		$this->get('my_datebase')->connection();
		 $sql = "select id,area from Area where upid=0 and status=1";
			  $result = mysql_query($sql);
			  while($row = mysql_fetch_array($result)){
				   $arrayPro[] = array(
					 'id'=>$row['id'],
					 'area'=>$row['area']
					 );
				  }
		$json = $this->get('json_encode')->JSON($arrayPro);
		$response = new Response($json);
		return $response;
		}

	//------------------------------------------------
	// city
	//------------------------------------------------
	public function retrieveCityAction(){
		$pro_id = $_GET['pro_id'];
		$this->get('my_datebase')->connection();
		 $sql = "select id,area from Area where upid=$pro_id and status=1";
			  $result = mysql_query($sql);
			  while($row = mysql_fetch_array($result)){
				   $arrayCity[] = array(
					 'id'=>$row['id'],
					 'area'=>$row['area']
					 );
				  }
		$json = $this->get('json_encode')->JSON($arrayCity);
		$response = new Response($json);
		return $response;
		}
	//------------------------------------------------
	// city
	//------------------------------------------------
	public function retrieveCityJsonAction(Request $request){
		$json = $this->get('json_parser')->parse($request);
		$pro_id = $json->get('pro_id',-1);
		if($pro_id==-1||$pro_id==0){return new JSONResponse("");}
		$this->get('my_datebase')->connection();
		$sql = "select id,area from Area where upid=$pro_id and status=1";
			  $result = mysql_query($sql);
			  while($row = mysql_fetch_array($result)){
				   $arrayCity[] = array(
					 'id'=>$row['id'],
					 'area'=>$row['area']
					 );
				  }
		$json = $this->get('json_encode')->JSON($arrayCity);
		$response = new Response($json);
		return $response;
		}

	//------------------------------------------------
	// retrieve Id by cityname
	//------------------------------------------------
	public function retrieveIdAction(Request $request){
		$json = $this->get('json_parser')->parse($request);
		$city = $json->get('city','');
		$city = substr($city,0,6);
		$this->get('my_datebase')->connection();
		$sql = "select orderid from Area where id =(select upid from Area where area like '%$city%' and upid!=0)";
			  $result = mysql_query($sql);
			  $index = mysql_result($result,0);
		$sql = "select id from Area where area like '%$city%' and upid!=0";
			  $result = mysql_query($sql);
			  $id = mysql_result($result,0);

				   $arrayCity = array(
					 'index'=>$index-1,
					 'city_id'=>$id
					 );
				  
		$json = $this->get('json_encode')->JSON($arrayCity);
		$response = new Response($json);
		return $response;
		}



	//------------------------------------------------
	// 根据条件筛选
	//------------------------------------------------
    public function retrieveAction(Request $request)
    {
			
			$this->get('my_datebase')->connection();
			
			$json = $this->get('json_parser')->parse($request);
			$bak_date = $json->get('bak_date','');
			
			/*$sql = "select id,area from Area where upid=0";
			$result = mysql_query($sql);
			while($row = mysql_fetch_array($result)){
				$sql_city="SELECT id,area from Area where upid=$row[id]";
			    $result_city = mysql_query($sql_city);
				$arrayPro[$row['id']]=array();
				$arrayCity[$row['id']]=array();
			    while($row_city = mysql_fetch_array($result_city)){
				 
					 array_push($arrayCity[$row['id']],$row_city['area']);
					 $arrayPro[$row['id']] = array(
					 'pro_id'=>$row['id'],
					 'pro'=>$row['area'],
					 'city'=>$arrayCity[$row['id']]
					 );
				   }
				
				 }*/
				 
				 
			 $sql = "select updated_date from Area order by updated_date desc limit 1";
			 $result = mysql_query($sql);
			 $updated_date = mysql_result($result,0);
			  
			
			
			  $sql = "select id,upid,area from Area where upid!=0 and status=1";
			  $result = mysql_query($sql);
			  while($row = mysql_fetch_array($result)){
				   $arrayCity[] = array(
					 'city_id'=>$row['id'],
					 'upid'=>$row['upid'],
					 'name'=>$row['area']
					 );
				  }
	        
			 $sql = "select id,area from Area where upid=0 and status = 1 order by orderid asc";
			  $result = mysql_query($sql);
			 
			  while($row = mysql_fetch_array($result)){
				  $array[$row['id']] = array();
				   foreach($arrayCity as $rs){
					   if($row['id']==$rs['upid']){
					    array_push($array[$row['id']],$rs);
						
						   $arrayPro[$row['id']] = array(
							 'pro_id'=>$row['id'],
							 'name'=>$row['area'],
							 'citys'=>$array[$row['id']]
							 );
							 
					   }
				  }
				  
			  }
			  
			  
			
			/*$sql = "select * from Area where upid=0";
			$result = mysql_query($sql);
			  while($row = mysql_fetch_array($result)){
				   $arrayPro[$row['id']] = array(
					 'id'=>$row['id'],
					 'cities'=> array()
					 );
				  }
				  
			$sql = "select * from Area where upid!=0";
			$result = mysql_query($sql);
			  while($row = mysql_fetch_array($result)){
				   $arrayCity[] = array(
				     'id'=>$row['id'],
					 'area'=>$row['area']
					); 
					foreach($arrayCity as $rs){
				   
					array_push($arrayPro[$row['upid']]['cities'],$rs); 
					
					}
				  }	*/  
			       
			
			
			
			if(strtotime($updated_date)==strtotime($bak_date)){
				return new Response('',201);
				}else{
		    $lastarray = array(
			      'updated_date'=>$updated_date,
				  'area'=>array_values($arrayPro)
			);
			}
			
			$json = $this->get('json_encode')->JSON($lastarray);
			$response = new Response($json);
			return $response;
    }
	function allProvinceAction(Request $request){
		$this->get('my_datebase')->connection();
		 $sql = "SELECT count(a.id) AS num,a.province_id,b.area,b.lng,b.lat FROM Machine AS a LEFT JOIN Area as b on a.province_id = b.id  WHERE a.province_id !=0 GROUP BY a.province_id";
			  $result = mysql_query($sql);
			  while($row = mysql_fetch_array($result)){
				   $arrayPro[] = array(
					 'province_id'=>$row['province_id'],
					 'area'=>$row['area'],
					 'lng'=>$row['lng'],
					 'lat'=>$row['lat'],
					 'num'=>$row['num']
					 );
				  }
		$json = $this->get('json_encode')->JSON($arrayPro);
		$response = new Response($json);
		return $response;		   
		}
		
	function retrieveCityByLocationAction(Request $request){
		
		$json = $this->get('json_parser')->parse($request);
		$lat = $json->get('lat',0);
		$lng = $json->get('lng',0);
		$lat1 = $json->get('lat1',0);
		$lng1 = $json->get('lng1',0);

		$this->get('my_datebase')->connection();
		//距离筛选，先获取经纬度，然后判断保存的经纬度
		if($lng!=0&&$lat!=0&&$lng1!=0&&$lat1!=0){
		//添加经纬度的sql，查找id
			$location_sql = " and a.lat>$lat and a.lat<$lat1 and a.lng<$lng1 and a.lng>$lng ";		
		}
		else{$location_sql="";}
		 $sql = "SELECT a.id,a.brand_model,a.factory_year,a.tonn,a.workhours,a.rent_by_num,a.rent_by_month,a.sale_price,a.mark,a.lng,a.lat,b.brand_name from Machine as a left join Brand as b on a.brand_id = b.id where a.status=1 and (a.mark=1 or a.mark=0)".$location_sql;
			  $result = mysql_query($sql);
			  $num   = mysql_num_rows($result);
		
				if (!$num){
					return new Response('',400);
				}
			  while($row = mysql_fetch_array($result)){
				  if($row['factory_year']!=0){$factory_year = substr($row['factory_year'],2,2);}
				  else{$factory_year=0;}
				   $array[] = array(
				   	 'id'=>$row['id'],
					 'model'=>$row['brand_model'],
					 'brand'=>$row['brand_name'],
					 'tonn'=>$row['tonn'],
					 'factory_year'=>$factory_year,
					 'workhours'=>$row['workhours'],
					 'rent_by_month'=>$row['rent_by_month'],
					 'rent_by_num'=>$row['rent_by_num'],
					 'sale_price'=>$row['sale_price'],
					 'mark'=>$row['mark'],
					 'lng'=>$row['lng'],
					 'lat'=>$row['lat']
					 );
				  }
		//$json = $this->get('json_encode')->JSON($array);
		$response = new JSONResponse($array);
		return $response;		   
		}
		
	
}
