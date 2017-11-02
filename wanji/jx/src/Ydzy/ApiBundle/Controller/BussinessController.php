<?php

namespace Ydzy\ApiBundle\Controller;
use Symfony\Component\HttpFoundation\Request;
use Symfony\Component\HttpFoundation\Response;
use Symfony\Bundle\FrameworkBundle\Controller\Controller;
use Symfony\Component\HttpFoundation\JsonResponse;

class BusinessController extends Controller
{
    public function indexAction(Request $request)
    {
        return new Response("jx Bussinesslist");
    }

	//------------------------------------------------
	// calllist
	//------------------------------------------------
    public function calllistAction(Request $request)
    {
		$json = $this->get('json_parser')->parse($request);
		$called_uid = $json->get('called_uid',0);
		$mark = $json->get('mark',0);
		$user= $this->getUser();
		if(!$user){
			return new Response('',403);
			}
		$uid = $user->getId();
		$current_time = date('Y-m-d H:i:s',time());
		$this->get('my_datebase')->connection();

		$sql = "insert into Calllist set `called_uid`=$called_uid ,`uid`=$uid, `creation_date`='$current_time', `updated_date`='$current_time' ,status=1";
		
		if(mysql_query($sql)){
			
			return new Response('', 200);
			
			}else{return new Response('', 500);}
		
    }
	
	
	
	//------------------------------------------------
	// 我的通话记录
	//------------------------------------------------
    public function mycalllistAction(Request $request)
    {

		$user= $this->getUser();
		if(!$user){
			return new Response('',403);
			}
		$uid = $user->getId();
		
		$json = $this->get('json_parser')->parse($request);
		$start = $json->get('start',-1);
		$num = $json->get('num',-1);
		$this->get('my_datebase')->connection();
		//分页
		$limit = ($start==-1||$num==-1)?"":" limit $start,$num ";
		$sql = "SELECT a.*,b.phone,b.username,b.id as uid from Calllist as a left join fos_user as b on a.uid = b.id where a.status = 1".$limit;
		$result = mysql_query($sql);
		$num   = mysql_num_rows($result);
		if (!$num){
			return new Response('', 400);
		}
		while($row = mysql_fetch_array($result)){
		 
			   $json_result[]=array(
		          'nickname' => $row['username'],
				  'phone' => $row['phone'],
				  'mark' => $row['mark'],
				  'creation_date' => $row['creation_date']
		  		  );
		}
		
		$response = new Response(json_encode($json_result));
			
		return $response;
		
    }
    public function delectcallAction(Request $request)
    {
    	    	return new Response('', 400);
		$user= $this->getUser();
		if(!$user){
			return new Response('',403);
			}
		$uid = $user->getId();
		
		$json = $this->get('json_parser')->parse($request);
		echo $called_uid = $json->get('called_uid',0);
		$this->get('my_datebase')->connection();
		echo $sql ="update Calllist set `status`=0 where `called_uid`=$called_uid";
		$result = mysql_query($sql);
		$num   = mysql_num_rows($result);
		if (!$num){
			return new Response('', 400);
		}		
		$response = new Response(json_encode($json_result));
			
		return $response;
    }
    public function delectallAction(Request $request)
    {
		$user= $this->getUser();
		if(!$user){
			return new Response('',403);
			}
		$uid = $user->getId();
		$this->get('my_datebase')->connection();
		echo $sql ="update Calllist set `status`=0";
		$result = mysql_query($sql);
		$num   = mysql_num_rows($result);
		if (!$num){
			return new Response('', 400);
		}		
		if(mysql_query($sql)){
			
			return new Response('', 200);
			
			}else{return new Response('', 500);}
    }
}
