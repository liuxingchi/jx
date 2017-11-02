<?php

namespace Ydzy\ApiBundle\Controller;
use Symfony\Component\HttpFoundation\Request;
use Symfony\Component\HttpFoundation\Response;
use Symfony\Bundle\FrameworkBundle\Controller\Controller;
use Symfony\Component\HttpFoundation\JsonResponse;

class DriverController extends Controller
{
    public function indexAction(Request $request)
    {
        return new Response("jx driver");
    }

	//------------------------------------------------
	// 根据条件筛选
	//------------------------------------------------
    public function retrieveAction(Request $request)
    {
		$json = $this->get('json_parser')->parse($request);
		$uid = $json->get('uid',-1);
		$keyword = $json->get('keyword','');
		$recommend = $json->get('recommend',-1);
		$category_id = $json->get('category_id',0);
	    $salary_sort = $json->get('salary_sort',-1);
		$updated_date_sort = $json->get('updated_date_sort',-1);
		$workyears_min = $json->get('workyears_min',-1);
		$workyears_max = $json->get('workyears_max',-1);
		$pro_id = $json->get('province_id',-1);
		$city_id = $json->get('city_id',-1);
		$start = $json->get('start',-1);
		$num = $json->get('num',-1);
		
		$this->get('my_datebase')->connection();
	
		//关键词的筛选
		$filter =$keyword==''?" a.status = 1 ":" (a.model like '%$keyword%' ) ";
		//判断uid，如果0，自动获取，如果为空，不进行uid判断，其他进行筛选
		//$uid==-1?$uid_sql = "":($uid==0?(($this->getUser())?$uid_sql=" and a.uid =$user ":return new Response('',403)): $uid_sql = " and a.uid =$uid ");
    	$user = $this->getUser();
	    if($uid==-1){
			$uid_sql="";
			}else if($uid!=0){
				$uid_sql = " and a.uid =$uid ";
				}else if($uid==0&&$user){
					$user_id = $user->getId();
				$uid_sql=" and a.uid =$user_id ";
				}else {
					return new Response('',403);
					}
		//类别的筛选
		$sql_category = $category_id==0?"":" and a.category_id=$category_id ";
		$sql_recommend = $recommend==-1?"":" and a.recommend = 1 ";
		//工作经验的筛选
		$sql_workyears = ($workyears_min==-1&&$workyears_max==-1)?"":" and a.workyears_min=$workyears_min and a.workyears_max=$workyears_max ";
		//工作地点的筛选
		$sql_pro = $pro_id==-1?"":" and a.pro_id = $pro_id ";
		$sql_city = $city_id==-1?"":" and a.city_id = $city_id ";
		//薪酬的排序（唯一的排序）
		$salary_sort==-1?$sql_salary_sort = "":$sql_salary_sort =($salary_sort==0? " order by a.mark asc,a.salary desc ":" order by a.mark asc,a.salary asc ");
		
		($updated_date_sort==-1&&$salary_sort==-1)?$sql_updated_date_sort = " order by a.mark asc,a.updated_date desc ":$sql_updated_date_sort =($updated_date_sort==0? " order by a.mark asc,a.updated_date desc ":" order by a.mark asc,a.updated_date asc ");
		if($updated_date_sort==-1&&$salary_sort==-1){
			$sql_updated_date_sort = " order by a.mark asc,a.updated_date desc ";
			}else if($updated_date_sort==-1){
				$sql_updated_date_sort = " ";
				}else if($updated_date_sort==1){
					$sql_updated_date_sort = " order by a.mark asc,a.updated_date asc ";
					}else{
					  $sql_updated_date_sort = " order by a.mark asc,a.updated_date desc ";
					    }
		
		if($uid==0){$mark_sql="";}else{$mark_sql=" and mark!=-1 ";}	
		//分页
		$limit = ($start==-1&&$num==-1)?"":" limit $start,$num ";
	
		$sql = $filter.$uid_sql.$sql_category.$sql_recommend.$sql_workyears.$sql_pro.$sql_city.$mark_sql.$sql_salary_sort.$sql_updated_date_sort.$limit;
		$sql = "SELECT a.*,b.category_name,c.area as province,d.area as city from Driver as a left join Category as b on a.category_id=b.id left join Area as c on a.pro_id=c.id left join Area as d on a.city_id=d.id where ".$sql;
		$result = mysql_query($sql);
		$num   = mysql_num_rows($result);
		if (!$num){
			return new Response('', 400);
		}
		while($row = mysql_fetch_array($result))
		  {
			 if($row['province']==null){$row['province']="";}
			 if($row['city']==null){$row['city']="";}  
			  
		  $pic_result = mysql_query("select b.thumbnail from Driver_pic as a left join images as b on a.image_id=b.id where a.driver_id='$row[id]' limit 1");
		   /*while($pic_row = mysql_fetch_array($pic_result)){
			  $pic[] =array(
			  'pic' => $pic_row['thumbnail']
			  );
			 }*/	  
			 $num_pic = mysql_num_rows($pic_result);
			 if(!$num_pic){
				 $pic = "";
				 }else{
					$pic = "http://wanjiwang.cn".mysql_result($pic_result,0); 
					 }  
			  if($row['workyears_max']==0){
				  $workyear = "学徒工";
				  }else if($row['workyears_max']==1){
					  $workyear = "一年以下";
					  }else if($row['workyears_max']==3){
					  $workyear = "一年到三年";
					  }else if($row['workyears_max']==5){
					  $workyear = "三年到五年";
					  }else{
						  $workyear = "五年以上";
						  }
		  $json_result[]=array(		  
		          'driver_id' => $row['id'],
		          'model' => $row['model'],
				  'salary' => $row['salary'],
				  'workyears' => $workyear,
				  'category' => $row['category_name'],
				  'province' => $row['province'],
				  'city' => $row['city'],
				  'pic' => $pic,
				  'collection'=>$row['collection'],
				  'mark'=>$row['mark'],
				  'updated_date'=>$row['updated_date'],
				  'linker'=>$row['linker'],
				  'linker_tel'=>$row['linker_tel']
				  
		  		  );
		  }
		
		
		$response = new Response(json_encode($json_result));
			
		return $response;
		
    }
		
	  //------------------------------------------------
	 //collection
	//--------------------------------------------------
	
	public function collectionAction(Request $request){
		$json = $this->get('json_parser')->parse($request);
		$driver_id = $json->get('driver_id',-1);
		$collection = $json->get('collection',-1);
		if($collection==0){$collection_sql=" set collection = collection-1 ";}
		else if($collection==1){$collection_sql=" set `collection` = `collection`+1 ";}
		else {$collection_sql="";}
		$this->get('my_datebase')->connection();
		$sql = "update Driver ".$collection_sql." where id=$driver_id ";
		if(mysql_query($sql)){
			return new Response('', 200);
			}else{
				return new Response('', 500);
				}
		}

	
	
	//------------------------------------------------
	// driver详情
	//------------------------------------------------
    public function infoAction(Request $request)
    {

		$json = $this->get('json_parser')->parse($request);
		$driver_id = $json->get('driver_id',0);
		
		$this->get('my_datebase')->connection();
		
		$sql = "SELECT a.*,b.category_name,c.area as province,d.area as city,e.nickname,e.roles,e.username from Driver as a left join Category as b on a.category_id=b.id left join Area as c on a.pro_id=c.id left join Area as d on a.city_id=d.id left join fos_user as e on e.id = a.uid where a.id = $driver_id and a.status = 1";
		$result = mysql_query($sql);
		$num   = mysql_num_rows($result);
		if (!$num){
			return new Response('', 400);
		}
		while($row = mysql_fetch_array($result)){
		 $pic=array();
		
		$pic_result = mysql_query("select b.id,b.thumbnail from Driver_pic as a left join images as b on a.image_id=b.id  where a.driver_id='$driver_id'");
		 
		  while($pic_row = mysql_fetch_array($pic_result)){
			  $pic =array(
			  'pic' => $pic_row['thumbnail'],
			  'pic_id'=>$pic_row['id']
			  );
			 } 
			   if($row['workyears_max']==0){
				  $workyear = "学徒工";
				  }else if($row['workyears_max']==1){
					  $workyear = "一年以下";
					  }else if($row['workyears_max']==3){
					  $workyear = "二到三年";
					  }else if($row['workyears_max']==5){
					  $workyear = "三到五年";
					  }else{
						  $workyear = "五年以上";
						  }
			   $json_result=array(
			   	 'uid'=>$row['uid'],
				 'nickname'=>$row['nickname'],
				 'phone'=>$row['username'],
				 'roles'=>substr($row['roles'],4),
				 'linker'=>$row['linker'],
				 'linker_tel'=>$row['linker_tel'],
			      'driver_id' => $row['id'],
		          'model' => $row['model'],
				  'salary' => $row['salary'],
				  'mark'=>$row['mark'],
				  'workyears' => $workyear,
				  'workyears_max' => $row['workyears_max'],
				  'workyears_min' => $row['workyears_min'],
				  'province_id'=>$row['pro_id'],
				  'city_id'=>$row['city_id'],
				  'category_id'=>$row['category_id'],
				  'category' => $row['category_name'],
				  'province' => $row['province'],
				  'city' => $row['city'],
				  'image' => $pic,
				  'description'=>$row['description'],
				  'updated_date'=>$row['updated_date']
		  		  );
		}
		
		$response = new Response(json_encode($json_result));
			
		return $response;
		
    }
	
	
			
  //------------------------------------------------
	// 删除
	//------------------------------------------------
    public function delAction(Request $request)
    {
        
		$json = $this->get('json_parser')->parse($request);
		$driver_id = $json->get('driver_id',0);
		
		$this->get('my_datebase')->connection();
		$current_time = date('Y-m-d H:i:s',time());
		$sql = "UPDATE  `Driver` SET  `status` =  '0',
`updated_date` ='$current_time'  WHERE  `Driver`.`id` =$driver_id LIMIT 1";
		if(mysql_query($sql)){
			return new Response('', 200);
			
			}else{
				
			return new Response('', 500);
			
			}
    }
	
	
	//------------------------------------------------
	 //create
	//--------------------------------------------------
    public function createAction(Request $request)
    {
        
		$json = $this->get('json_parser')->parse($request);
		$category = $json->get('category',0);
		$image_url = $json->get('image_url',"");
		$salary = $json->get('salary',0);
		$model = $json->get('model',"");
		$linker= $json->get('linker',"");
		$linker_tel= $json->get('linker_tel',"");
		$workyears_min = $json->get('workyears_min',0);
		$workyears_max = $json->get('workyears_max',0);
		$province_id = $json->get('province_id',0);
		$city_id = $json->get('city_id',0);
		$description = $json->get('description',"");
		
		
		$this->get('my_datebase')->connection();
		$current_time = date('Y-m-d H:i:s',time());
		$user = $this->getUser();
		
		if(!$user){
			return new Response('',403);
			}
		$uid = $user->getId();
		$sql = "insert into Driver set 
		uid=$uid,
		category_id=$category,
		pro_id=$province_id,
		city_id=$city_id,
		model='$model',
		linker='$linker',
		linker_tel='$linker_tel',
		description='$description',
		workyears_min='$workyears_min',
		workyears_max='$workyears_max',
		salary='$salary',
		status=1,
		creation_date='$current_time',
		updated_date='$current_time'
		";
		
		if(mysql_query($sql)){
			if($image_url!=""){
			$sql = "select id from Driver order by id desc limit 1";
			$id = mysql_result(mysql_query($sql),0);
				$sql_img="insert into Driver_pic set image_id= $image_url, driver_id= $id,creation_date='$current_time',updated_date = '$current_time'";
				mysql_query($sql_img);
		  
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
		$driver_id = $json->get('driver_id',0);
		$mark = $json->get('mark',-2);
		$user_id = $json->get('user_id',-1);
		$category = $json->get('category_id',-1);
		$image_url = $json->get('image_url',0);
		$salary = $json->get('salary',-1);
		$model = $json->get('model',"");
		$linker= $json->get('linker',"");
		$linker_tel= $json->get('linker_tel',"");
		$workyears_min = $json->get('workyears_min',-1);
		$workyears_max = $json->get('workyears_max',-1);
		$province_id = $json->get('province_id',-1);
		$city_id = $json->get('city_id',-1);
		$description = $json->get('description',"");
		
		$this->get('my_datebase')->connection();
		$current_time = date('Y-m-d H:i:s',time());
		
		$mark_sql = ($mark==-2?"":" mark=$mark,");
		$user_id_sql = ($user_id==-1?"":" user_id=$user_id,");
		$category_sql = ($category==-1?"":" category_id=$category,");
	 	$salary_sql = ($salary==-1?"":" salary=$salary,");
		$model_sql = ($model==""?"":" model='$model',");
		$linker_sql = ($linker==""?"":" linker='$linker',");
		$linker_tel_sql = ($linker_tel==""?"":" linker_tel='$linker_tel',");
		$workyears_min_sql = ($workyears_min==-1?"":" workyears_min=$workyears_min,");
		$workyears_max_sql = ($workyears_max==-1?"":" workyears_max=$workyears_max,");
		$province_id_sql = ($province_id==-1?"":" pro_id=$province_id,");
		$city_id_sql = ($city_id==-1?"":" city_id=$city_id,");
		$description_sql = ($description==""?"":" description='$description',");
		
		
		$sql = "update Driver set ".$mark_sql.$user_id_sql.$category_sql.$salary_sql.$model_sql.$linker_sql.$linker_tel_sql.$workyears_min_sql.$workyears_max_sql.$province_id_sql.$city_id_sql.$description_sql."
		creation_date='$current_time',
		updated_date='$current_time' 
		where id=$driver_id
		";
		if(mysql_query($sql)){
			if($image_url!=0){
				$sql_img="UPDATE Driver_pic set image_id= $image_url,updated_date = '$current_time' where driver_id = $driver_id";
				mysql_query($sql_img);
		 		}
			return new Response('', 200);
			
			}else{return new Response('', 500);}
    }
	
	
	
	
}
