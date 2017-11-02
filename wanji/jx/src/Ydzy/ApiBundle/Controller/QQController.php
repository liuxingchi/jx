<?php

namespace Ydzy\ApiBundle\Controller;
use Symfony\Component\HttpFoundation\Request;
use Symfony\Component\HttpFoundation\Response;
use Symfony\Bundle\FrameworkBundle\Controller\Controller;
use Symfony\Component\HttpFoundation\JsonResponse;

class QQController extends Controller
{
	//------------------------------------------------
	// 获得所有
	//------------------------------------------------
	public function retrieveAction(Request $request)
	{
		$keyword = $request->request->get('keyword','');
		$page = $request->request->get('page',1);
		$rows = $request->request->get('rows',1000);
		$start = ($page - 1)*$rows ;
		//return new Response("dd");
		$this->get('Spider_datebase')->connection();
		$sql="select count(id) from qq where qq like '%$keyword%' or linker like '%$keyword%' ";
		$count = mysql_result(mysql_query($sql),0);
		//$sql="select a.*,b.area as province,c.area as city,count(d.id) as num from qq as a left join Area as b on b.id = a.province_id left join Area as c on c.id = a.city_id left join Machine as d on a.id = d.qqid where datediff(now(),from_unixtime(d.publish_date))<30 and  a.qq like '%$keyword%' or a.linker like '%$keyword%' group by a.id limit $start,$rows";
		
		$sql = "select a.*,b.area as province,c.area as city from qq as a left join Area as b on b.id = a.province_id left join Area as c on c.id = a.city_id where a.qq like '%$keyword%' or a.linker like '%$keyword%' limit $start,$rows";
			  //return new Response($sql);
			  $result = mysql_query($sql);
			  while($row = mysql_fetch_array($result)){
				 		$city = $row['city_id'];
						$province = $row['province_id'];
						
						$row['creation_date'] = date("Y-m-d H:i:s",$row['creation_date']);
						
						  $json_result[] = array(
							 'id'=>$row['id'],
							 'qq'=>$row['qq'],
							 'province'=>$row['province'],
							 'city'=>$row['city'],
							 'linker'=>$row['linker'],
							 'linker_tel'=>$row['linker_tel'],
							 'creation_date'=>$row['creation_date'],
							 'status'=>$row['status']
							 );
						
					
				  }
		$json_r = array();
		//$page_rows = array_slice($json_result, ($page-1)*$rows, $rows, false);
/*		$json_r = array(
					'total' => count($json_result),
					'rows' => $page_rows
							);		  
*/		$json_r = array(
					'total' => $count,
					'rows' => $json_result
							);
		$response = new Response(json_encode($json_r));
		$response->headers->set('content-type','application/json');
		return $response;
	}		
	//------------------------------------------------
	// 获得所有
	//------------------------------------------------
	public function retrievecountAction(Request $request)
	{
		$keyword = $request->request->get('keyword','');
		$page = $request->request->get('page',1);
		$rows = $request->request->get('rows',1000);
		$start = ($page - 1)*$rows;
		//return new Response("dd");
		$this->get('Spider_datebase')->connection();
		$sql="select count(id) from qq";
		$count = mysql_result(mysql_query($sql),0);
		$sql="select a.*,count(d.id) as num from qq as a left join Machine as d on a.id = d.qqid where datediff(now(),from_unixtime(d.publish_date))<30 group by a.id order by count(d.id) desc limit $start,$rows";
		
			  //return new Response($sql);
			  $result = mysql_query($sql);
			  while($row = mysql_fetch_array($result)){
				 		
						$row['creation_date'] = date("Y-m-d H:i:s",$row['creation_date']);
						
						  $json_result[] = array(
							 'id'=>$row['id'],
							 'qq'=>$row['qq'],
							 'num'=>$row['num'],
							 'linker'=>$row['linker'],
							 'linker_tel'=>$row['linker_tel'],
							 'creation_date'=>$row['creation_date'],
							 );
						
					
				  }
		$json_r = array();
		//$page_rows = array_slice($json_result, ($page-1)*$rows, $rows, false);
/*		$json_r = array(
					'total' => count($json_result),
					'rows' => $page_rows
							);		  
*/		$json_r = array(
					'total' => $count,
					'rows' => $json_result
							);
		$response = new Response(json_encode($json_r));
		$response->headers->set('content-type','application/json');
		return $response;
	}		
	
	//------------------------------------------------
	// add
	//------------------------------------------------
	public function addAction(Request $request)
	{
		$json = $this->get('json_parser')->parse($request);
		$qq = $json->get('qq','');
		$city = $json->get('city','');
		$linker = $json->get('linker','');
		$linker_tel = $json->get('linker_tel','');
		$province = $json->get('province','');
		$creation_date = strtotime($json->get('creation_date',''));
		if($creation_date==""){
			$creation_date = time();
		}
		$this->get('Spider_datebase')->connection();
		$sql = "select lng,lat from Area where id = $city limit 1";
		$lng = mysql_result(mysql_query($sql),0,'lng');
		$lat = mysql_result(mysql_query($sql),0,'lat');
		$sql = "insert into qq set linker = '$linker', linker_tel = '$linker_tel',qq='$qq',city_id = $city ,province_id = $province,lat = '$lat',lng = '$lng',creation_date = '$creation_date'";
		if(mysql_query($sql)){
			return new JSONResponse('',200);
			}else{
				return new JsonResponse('',500);
				}
			
	}		
	//------------------------------------------------
	// update
	//------------------------------------------------
	public function updateAction(Request $request)
	{
		$json = $this->get('json_parser')->parse($request);
		$id = $json->get('id','');
		$qq = $json->get('qq','');
		$linker = $json->get('linker','');
		$linker_tel = $json->get('linker_tel','');
		$creation_date = strtotime($json->get('creation_date',''));
		$this->get('Spider_datebase')->connection();
		$sql = "update qq set linker = '$linker', linker_tel = '$linker_tel',qq='$qq',creation_date = '$creation_date' where id = $id";
		if(mysql_query($sql)){
			return new JSONResponse('',200);
			}else{
				return new JsonResponse('',500);
				}
			
	}
	//------------------------------------------------
	// status
	//------------------------------------------------
	public function statusAction(Request $request)
	{
		$json = $this->get('json_parser')->parse($request);
		$id = $json->get('id','');
		$status = $json->get('status','0');
		$current = time();
		$this->get('Spider_datebase')->connection();
		if($id == 0){
			$sql = "update qq set status = 1";
			}else if($id == -1){
				$sql = "update qq set status = 0";
				}
			else{
		$sql = "update qq set status = $status, creation_date = '$current' where id = $id";
			}
		if(mysql_query($sql)){
			return new JSONResponse('',200);
			}else{
				return new JsonResponse('',500);
				}
			
	}
	//------------------------------------------------
	// del
	//------------------------------------------------
	public function delAction(Request $request)
	{
		$json = $this->get('json_parser')->parse($request);
		$id = $json->get('id','');
		$this->get('Spider_datebase')->connection();
		$sql = "delete from qq where id = $id";
		if(mysql_query($sql)){
			return new JSONResponse('',200);
			}else{
				return new JsonResponse('',500);
				}
			
	}				
}
