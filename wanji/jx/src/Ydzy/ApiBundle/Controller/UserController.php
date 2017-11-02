<?php

namespace Ydzy\ApiBundle\Controller;
use Symfony\Component\HttpFoundation\Request;
use Symfony\Component\HttpFoundation\Response;
use Symfony\Bundle\FrameworkBundle\Controller\Controller;
use Symfony\Component\HttpFoundation\JsonResponse;

use Ydzy\UserBundle\Entity\User;

class UserController extends Controller
{
    public function indexAction(Request $request)
    {
        return new Response("jx user");
    }
	
    //------------------------------------------------
	// 获取所有操作员信息功能
	//------------------------------------------------
    public function retrieveAllAction(Request $request)
    {
     	$this->get('my_datebase')->connection();
		
		$username = $request->request->get('username',"");
		$start = $request->request->get('start',"");
		$end = $request->request->get('end',"");
		$regstart = $request->request->get('regstart',"");
		$regend = $request->request->get('regend',"");
		$followend = $request->request->get('followend',"");
		$start = strtotime($start);
		$end = strtotime($end);
		$regstart = strtotime($regstart);
		$regend = strtotime($regend);
		$followend = strtotime($followend);
		
		
/*		$em = $this->getDoctrine()->getEntityManager();
		if($username==""){
			$users = $em->getRepository('YdzyUserBundle:User')->findAll();
			}else{
				$users = $em->getRepository('YdzyUserBundle:User')->findByUsername($username);
				}
		
*/		//$users = $em->createQuery(
		//'SELECT u.username,u.roles,u.enabled,u.id FROM YdzyUserBundle:User u WHERE u.username LIKE :searchTerm'
		//)->setParameter('searchTerm', '%'.$username.'%');
		//$users = $query->getResult(); 
/*		if(!$users){
			return new Response('');
			}
			
*/		
$em = $this->getDoctrine()->getEntityManager();
if($username==""){$username_sql = "";}
else{$username_sql = " and b.username=$username ";}
if($start!=""&&$end!=""){$date_sql = " and UNIX_TIMESTAMP(a.creation_date)<$end and UNIX_TIMESTAMP(a.creation_date)>$start ";}
else{$date_sql="";}
if($regstart!=""&&$regend!=""){$reg_sql = " and UNIX_TIMESTAMP(b.creation_date)<$regend and UNIX_TIMESTAMP(b.creation_date)>$regstart ";}
else{$reg_sql="";}
if($followend!=""){$followend_sql = " and UNIX_TIMESTAMP(creation_date)<$followend ";}
else{$followend_sql="";}
$sql = "SELECT count(a.id) as num,a.user_id,b.nickname,b.truename,b.username,b.enabled,b.creation_date FROM Machine AS a LEFT JOIN fos_user as b ON a.user_id = b.id where b.username!='' and a.status=1".$username_sql.$date_sql.$reg_sql." GROUP BY a.user_id";
			  $result = mysql_query($sql);
			  $num = mysql_num_rows($result);
			  if(!$num){
				 $array[] = array(
				 'total' => 0,
				 'rows' => ""
				 ); 
			return new JsonResponse($array);
			}
			  while($row = mysql_fetch_array($result)){
		$sql1 = "select count(id) as follow_num from Follow where followed_user_id = $row[user_id] and status = 1 ".$followend_sql;
		$result1 = mysql_query($sql1);
		$follow_num  = mysql_result($result1,0);
						if($row['truename']==""){$row['truename']=$row['nickname'];}
						  $array[] = array(
							 'num'=>intval($row['num']),
							 'follow_num'=>intval($follow_num),
							 'nickname'=>$row['nickname'],
							 'truename'=>$row['truename'],
							 'username'=>$row['username'],
							 'enabled'=>$row['enabled'],
							 'id'=>$row['user_id'],
							 'creation_date'=>$row['creation_date']
							 );
						 
					
				  }
				  
/*foreach($users as $user){
			$roles = $user->getRoles();
			if($roles[0]=='ROLE_ADMIN'){
				$json_result[] = array(
					'id'=>$user->getId(),
					'username'=>$user->getUsername(),
					'enabled'=>$user->isEnabled(),
					'nickname'=>$user->getNickname(),
					'count'=>$user->getCount(),
					'limit'=>$user->getLimitation(),
					'truename'=>$user->getTruename()
					);
			
				}
			
			}*/
		$response = new Response(json_encode($array));
		return $response;
		
    }
    //------------------------------------------------
	// 获取所有超级管理员信息功能
	//------------------------------------------------
    public function retrieveAllAdminAction(Request $request)
    {
     	$this->get('my_datebase')->connection();
		
		$em = $this->getDoctrine()->getEntityManager();
		$users = $em->getRepository('YdzyUserBundle:User')->findAll();
		
		foreach($users as $user){
			$roles = $user->getRoles();
			if($roles[0]=='ROLE_SUPER_ADMIN'){
			
				$json_result[] = array(
					'id'=>$user->getId(),
					'username'=>$user->getUsername()
					);
			
				}
			
			}
		$response = new Response(json_encode($json_result));
		return $response;
		
    }	
	//------------------------------------------------
	// 用户信息获取功能
	//------------------------------------------------
    public function retrieveAction(Request $request)
    {
		
    	// retrieve json object
		$json = $this->get('json_parser')->parse($request);
		$userId = $json->get('userId', 0);

		//$json = json_decode($request->getContent(),true);
		//$userId = $json['userId'];
		$em = $this->getDoctrine()->getEntityManager();
		$user = $em->getRepository('YdzyApiBundle:User')->findOneById($userId);
		
		if (!$user){
			return new Response('用户不存在', 404);
		}
		
		$response = new JsonResponse();
		$response->setData(array(
    					'name' => $user->getUsername(),
						'tel' => $user->getTel()
			));
		return $response;
		
    }
	
	//------------------------------------------------
	// 判断当前用户是否用完limitation
	//------------------------------------------------
    public function islimitAction(Request $request)
    {
		$json = $this->get('json_parser')->parse($request);
		$user_id = $json->get('user_id', 0);
		
		$em = $this->getDoctrine()->getEntityManager();
		$user = $em->getRepository('YdzyUserBundle:User')->findOneById($user_id);
		if(($user->getLimitation()<=$user->getCount())&&($user->getLimitation()!=0)){
			return new Response('',403);
			}else{
				return new Response('',200);
				}
		
	}
}
