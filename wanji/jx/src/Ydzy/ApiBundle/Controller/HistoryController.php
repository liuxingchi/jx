<?php

namespace Ydzy\ApiBundle\Controller;
use Symfony\Component\HttpFoundation\Request;
use Symfony\Component\HttpFoundation\Response;
use Symfony\Bundle\FrameworkBundle\Controller\Controller;
use Symfony\Component\HttpFoundation\JsonResponse;

class HistoryController extends Controller
{
    public function indexAction(Request $request)
    {
        return new Response("jx history");
    }

	//------------------------------------------------
	// create history
	//------------------------------------------------
    public function createAction(Request $request)
    {
		$json = $this->get('json_parser')->parse($request);
		$machine_id = $json->get('id','');
		$user = $this->getUser();
		if(!$user){
			return new Response('',403);
			}
			
		$uid = $user->getId();
		$current_time = date('Y-m-d H:i:s',time());
		$this->get('my_datebase')->connection();
		$sql = "insert into History set `uid`=$uid ,`machine_id`=$machine_id, `creation_date`='$current_time', `updated_date`='$current_time' ,status=1";
		if(mysql_query($sql)){
			$json_result = array('follow_id'=>mysql_insert_id());
			return new JsonResponse($json_result);
			
			}else{return new Response('', 500);}
		
    }
	//------------------------------------------------
	// 查看本人浏览历史
	//------------------------------------------------
    public function historyAction(Request $request)
    {		
		$json = $this->get('json_parser')->parse($request);
		$image_id = $json->get('image_id','');
		$this->get('my_datebase')->connection();
		$user = $this->getUser();
		if(!$user){
			return new Response('',403);
			}
			
		$uid = $user->getId();
		$sql = "select * from History where uid = $uid";
		$num   = mysql_num_rows($result);
		if (!$num){
			return new Response('', 400);
		}
		while($row = mysql_fetch_array($result)){
			   $json_result[]=array(
			      'machine_id' => $row['machine_id'],
				  'uid' =>$row['uid'],
		          'nickname' => $row['nickname'],
				  'phone' => $row['phone'],
				  'count' => $row['count']
		  		  );
		}
		
		$response = new Response(json_encode($json_result));
			
		return $response;
    }
	
}
