<?php

namespace Ydzy\ApiBundle\Controller;
use Symfony\Component\HttpFoundation\Request;
use Symfony\Component\HttpFoundation\Response;
use Symfony\Bundle\FrameworkBundle\Controller\Controller;
use Symfony\Component\HttpFoundation\JsonResponse;

class FollowController extends Controller
{
    public function indexAction(Request $request)
    {
        return new Response("jx follow");
    }

	//------------------------------------------------
	// 关注
	//------------------------------------------------
    public function followAction(Request $request)
    {
		$json = $this->get('json_parser')->parse($request);
		$followed = $json->get('followed_uid',0);
		$user= $this->getUser();
		if(!$user){
			return new Response('',403);
			}
		$uid = $user->getId();
		$current_time = date('Y-m-d H:i:s',time());
		$this->get('my_datebase')->connection();

		$sql = "insert into Follow set `followed_user_id`=$followed ,`user_id`=$uid, `creation_date`='$current_time', `updated_date`='$current_time' ,status=1";
		
		if(mysql_query($sql)){
			$json_result = array('follow_id'=>mysql_insert_id());
			return new JsonResponse($json_result);
			
			}else{return new Response('', 500);}
		
    }
	
	
	
	//------------------------------------------------
	// 我的关注
	//------------------------------------------------
    public function myfollowAction(Request $request)
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
		$limit = ($start==-1&&$num==-1)?"":" limit $start,$num ";
		$sql = "SELECT a.*,b.nickname,b.phone,b.id as uid from Follow as a left join fos_user as b on a.followed_user_id = b.id where a.user_id = $uid and a.status = 1".$limit;
		$result = mysql_query($sql);
		$num   = mysql_num_rows($result);
		if (!$num){
			return new Response('', 400);
		}
		while($row = mysql_fetch_array($result)){
		 if($row['nickname']==null){$row['nickname']="";}
		 
		  $sql1 = "select count(id) as count from Machine where user_id=$row[uid] and status=1";
		  $result1 = mysql_query($sql1);
		  $num1  = mysql_result($result1,0);
			   $json_result[]=array(
			      'follow_id' => $row['id'],
				  'uid' =>$row['uid'],
		          'nickname' => $row['nickname'],
				  'phone' => $row['phone'],
				  'count' => $num1
		  		  );
		}
		
		$response = new Response(json_encode($json_result));
			
		return $response;
		
    }
	
	
			
//------------------------------------------------
// 取消关注
//------------------------------------------------
    public function cancelfollowAction(Request $request)
    {
        
		$json = $this->get('json_parser')->parse($request);
		$follow_id = $json->get('follow_id',0);
		
		$this->get('my_datebase')->connection();
		$current_time = date('Y-m-d H:i:s',time());
		$sql = "UPDATE  `Follow` SET  `status` =  '0',
`updated_date` ='$current_time'  WHERE  `Follow`.`id` =$follow_id LIMIT 1";
		if(mysql_query($sql)){
			return new Response('', 200);
			
			}else{
				
			return new Response('', 500);
			
			}
    }
	
	
	  //------------------------------------------------
	 //isfollow
	//--------------------------------------------------
    public function isfollowAction(Request $request)
    {
        
		$json = $this->get('json_parser')->parse($request);
		$followed = $json->get('uid',0);
		
		
		$this->get('my_datebase')->connection();
		
		$user = $this->getUser();
		if(!$user){
			return new Response('',400);
			}
			
		$uid = $user->getId();
		$sql = "select id from Follow where user_id = $uid and followed_user_id = $followed and status = 1";
		
		$result = mysql_query($sql);
		$num   = mysql_num_rows($result);
		if (!$num){
			return new Response('未关注', 400);
		}else{
			$follow_id = mysql_result($result,0,"id");
			return new Response($follow_id);
			}	
		
}
   
	
	  //------------------------------------------------
	 //follownews
	//--------------------------------------------------
    public function follownewsAction(Request $request)
    {
		$json = $this->get('json_parser')->parse($request);
		$start = $json->get('start',-1);
		$num = $json->get('num',-1);
		$ispost = $json->get('post',1);
        $user= $this->getUser();
		$this->get('my_datebase')->connection();
		if($ispost == 0){
			
			$limit = ($start==-1&&$num==-1)?"":" limit $start,$num ";
		$sql = "select * from User_log where uid=0 and status = 1 order by id desc ".$limit;
		$result = mysql_query($sql);
		$num   = mysql_num_rows($result);
		if (!$num){
			return new Response('', 400);
		}
		while($row = mysql_fetch_array($result)){
		 	  $json_result[]=array(
			  	  'id'=>$row['id'],
			      'title' => $row['nickname'],
				  'uid'=>$row['uid'],
				  'pid'=>$row['pid'],
				  'content' =>$row['content'],
		          'creation_date' => $row['creation_date']
		  		  );
		}
		
		$response = new Response(json_encode($json_result));
			
		return $response;
		
			}
		if(!$user){
		$limit = ($start==-1&&$num==-1)?"":" limit $start,$num ";
		$sql = "select * from User_log where uid=0 and status = 1 order by id desc ".$limit;
		$result = mysql_query($sql);
		$num   = mysql_num_rows($result);
		if (!$num){
			return new Response('', 400);
		}
		while($row = mysql_fetch_array($result)){
		 	  $json_result[]=array(
			  	  'id'=>$row['id'],
			      'title' => $row['nickname'],
				  'pid'=>$row['pid'],
				  'uid'=>$row['uid'],
				  'content' =>$row['content'],
		          'creation_date' => $row['creation_date']
		  		  );
		}
		
		$response = new Response(json_encode($json_result));
			
		return $response;
			
			}
		$uid = $user->getId();
		
		
		
		
		$sql = "select followed_user_id from Follow where user_id = $uid and status=1";
		$result = mysql_query($sql);
		while($row = mysql_fetch_array($result)){
		 	  $followed_uids[] = array('uid'=>$row['followed_user_id']); 
		}
		$str = "";
		foreach($followed_uids as $rs){
			$str = $str.",".$rs['uid'];
			}
			$instr =  substr($str,1);
		//分页
		$limit = ($start==-1&&$num==-1)?"":" limit $start,$num ";
		$sql = "select * from User_log where uid in ($instr) or uid=0 and status=1 order by id desc ".$limit;
		$result = mysql_query($sql);
		$num   = mysql_num_rows($result);
		if (!$num){
			return new Response('', 400);
		}
		while($row = mysql_fetch_array($result)){
		 	  $json_result[]=array(
			  	  'id'=>$row['id'],
				  'pid'=>$row['pid'],
			      'title' => $row['nickname'],
				  'uid'=>$row['uid'],
				  'content' =>$row['content'],
		          'creation_date' => $row['creation_date']
		  		  );
		}
		
		$response = new Response(json_encode($json_result));
			
		return $response;
	}
	
	 //------------------------------------------------
	 //count
	//--------------------------------------------------
    public function follownewsCountAction(Request $request)
    {
		$json = $this->get('json_parser')->parse($request);
		$start = $json->get('start',-1);
		$num = $json->get('num',-1);
        $user= $this->getUser();
		$this->get('my_datebase')->connection();
		if(!$user){
		$limit = ($start==-1&&$num==-1)?"":" limit $start,$num ";
		$sql = "select count(*) as num from User_log where uid=0 and status = 1 order by id desc ".$limit;
		$result = mysql_query($sql);
		$num   = mysql_num_rows($result);
		if (!$num){
			return new Response('', 400);
		}
		$num = mysql_result($result,0);
		 	  
		
		$response = new JSONResponse($num);
			
		return $response;
			
			}
		$uid = $user->getId();
		
		
		
		
		$sql = "select followed_user_id from Follow where user_id = $uid and status=1";
		$result = mysql_query($sql);
		while($row = mysql_fetch_array($result)){
		 	  $followed_uids[] = array('uid'=>$row['followed_user_id']); 
		}
		$str = "";
		foreach($followed_uids as $rs){
			$str = $str.",".$rs['uid'];
			}
			$instr =  substr($str,1);
		//分页
		$limit = ($start==-1&&$num==-1)?"":" limit $start,$num ";
		$sql = "select count(*) as num from User_log where uid in ($instr) or uid=0 and status=1 order by id desc ".$limit;
		$result = mysql_query($sql);
		$num   = mysql_num_rows($result);
		if (!$num){
			return new Response('', 400);
		}
		$num = mysql_result($result,0);
		 	  
		
		$response = new JSONResponse($num);
			
		return $response;
	}
}
