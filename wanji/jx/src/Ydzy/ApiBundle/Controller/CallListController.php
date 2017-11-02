<?php

namespace Ydzy\ApiBundle\Controller;
use Symfony\Component\HttpFoundation\Request;
use Symfony\Component\HttpFoundation\Response;
use Symfony\Bundle\FrameworkBundle\Controller\Controller;
use Symfony\Component\HttpFoundation\JsonResponse;

class CallListController extends Controller
{
    public function indexAction(Request $request)
    {
        return new Response("jx calllist");
    }

	//------------------------------------------------
	// calllist
	//------------------------------------------------
    public function calllistAction(Request $request)
    {
		$json = $this->get('json_parser')->parse($request);
		$name = $json->get('name',0);
		$tel = $json->get('tel',0);
		$mark = $json->get('mark',0);
		$user= $this->getUser();
		if(!$user){
			return new Response('',403);
			}
		$uid = $user->getId();
		$current_time = date('Y-m-d H:i:s',time());
		$this->get('my_datebase')->connection();

		$sql = "insert into Calllist set `mark` = $mark,`name`='$name' ,`tel`='$tel',`uid`=$uid, `creation_date`='$current_time', `updated_date`='$current_time' ,status=1";
		
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
		$sql = "SELECT * from Calllist  where status = 1 and uid = $uid order by id desc".$limit;
		$result = mysql_query($sql);
		$num   = mysql_num_rows($result);
		if (!$num){
			return new Response('', 400);
		}
		while($row = mysql_fetch_array($result)){
		 
			   $json_result[]=array(
			   	  'calllist_id'=>$row['id'],
		          'name' => $row['name'],
				  'tel' => $row['tel'],
				  'mark' => $row['mark'],
				  'creation_date' => $row['creation_date']
		  		  );
		}
		
		$response = new Response(json_encode($json_result));
			
		return $response;
		
    }
    public function delectcallAction(Request $request)
    {
		$user= $this->getUser();
		if(!$user){
			return new Response('',403);
			}
		$uid = $user->getId();
		
		$json = $this->get('json_parser')->parse($request);
		$calllist_id = $json->get('calllist_id',0);
		$this->get('my_datebase')->connection();
		$sql ="update Calllist set `status`=0 where `id`=$calllist_id";
		
		if(mysql_query($sql)){
			
			return new Response('', 200);
			
			}else{return new Response('', 500);}
    }	
	
	
    public function delectallAction(Request $request)
    {
		$user= $this->getUser();
		if(!$user){
			return new Response('',403);
			}
		$uid = $user->getId();
		$this->get('my_datebase')->connection();
		$sql ="update Calllist set `status`=0 where uid = $uid";
				
		if(mysql_query($sql)){
			
			return new Response('', 200);
			
			}else{return new Response('', 500);}
    }
	public function countAction(Request $request)
    {
		
		
		$this->get('my_datebase')->connection();
		$sql ="SELECT count(id) as num,name,uid FROM Calllist GROUP BY tel";
				
		if(mysql_query($sql)){
			
			return new Response('', 200);
			
			}else{return new Response('', 500);}
    }
}
