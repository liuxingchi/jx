<?php

namespace Ydzy\ApiBundle\Controller;
use Symfony\Component\HttpFoundation\Request;
use Symfony\Component\HttpFoundation\Response;
use Symfony\Bundle\FrameworkBundle\Controller\Controller;
use Symfony\Component\HttpFoundation\JsonResponse;


class LeaveController extends Controller
{
    public function indexAction(Request $request)
    {
        return new Response("jx leaveword");
    }
	
 	//------------------------------------------------
	// 用户留言
	//------------------------------------------------
    public function leavewordAction(Request $request)
    {
		$json = $this->get('json_parser')->parse($request);
		$word = $json->get('word','');
		$this->get('my_datebase')->connection();
		$current_time = date('Y-m-d H:i:s',time());
		$user = $this->getUser();
		
		if(!$user){
			return new Response('',403);
			}
		$uid = $user->getId();
		$sql = "insert into Leaveword set word='$word',uid=$uid,creation_date='$current_time',updated_date = '$current_time', status=1";
			if(mysql_query($sql)){
				return new Response('', 200);
				}else{
					return new Response('', 500);
					}
			
    }
	
	
	//------------------------------------------------
	// chakan 用户留言
	//------------------------------------------------
    public function manageAction(Request $request)
    {
		$this->get('my_datebase')->connection();
		$sql = "select a.*,b.nickname,b.username from Leaveword as a left join fos_user as b on a.uid = b.id where a.status=1";
		$result = mysql_query($sql);
			while($row = mysql_fetch_array($result)){
				   $array[] = array(
					 'id'=>$row['id'],
					 'word'=>$row['word'],
					 'creation_date'=>$row['creation_date'],
					 'nickname'=>$row['nickname'],
					 'username'=>$row['username']
					 );
				  }
		$json = $this->get('json_encode')->JSON($array);
		$response = new Response($json);
		return $response;
			
    }
}
