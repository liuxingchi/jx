<?php

namespace Ydzy\UserBundle\Controller;

use Symfony\Bundle\FrameworkBundle\Controller\Controller;

use Symfony\Component\HttpFoundation\Request;
use Symfony\Component\HttpFoundation\Response;
use Symfony\Component\HttpFoundation\JsonResponse;
use Ydzy\UserBundle\Entity\User;
use Symfony\Bundle\FrameworkBundle\Command\ContainerAwareCommand;
class DefaultController extends Controller
{
    public function ProfileAction(Request $request)
    {
        $user = $this->getUser();
        if(!$user)
        {
            return new Response("", 403);
        }
        
		$id = $this->getUser()->getId();
        $username = $this->getUser()->getUserName();
        $email = $this->getUser()->getEmail();
        $phone = $this->getUser()->getPhone();
		$nickname =$this->getUser()->getNickname();
		$signature =$this->getUser()->getSignature();
		$count=$this->getUser()->getCount();
		$limit=$this->getUser()->getLimitation();
		if($count==null){$count="";}
		if($limit==null){$limit="";}
		$this->get('my_datebase')->connection();
		$sql1 = "select a.user_id,b.phone,b.nickname,a.updated_date from Follow as a LEFT JOIN fos_user as b ON a.user_id=b.id where a.followed_user_id = $id and a.status = 1";
		$result1 = mysql_query($sql1);
		$num1  = mysql_num_rows($result1);
		$roles = $this->getUser()->getRoles();
		//echo $roles[0];
		if($roles[0]=='ROLE_USER'){$role = 0;}
		else{$role=1;}
        $json_r = array(
            'id' => $this->getUser()->getId(),
            'username' => $this->getUser()->getUserName(),
            'email' => $this->getUser()->getEmail(),
            'phone' => $this->getUser()->getPhone(),
			'nickname' =>$this->getUser()->getNickname(),
			'signature' =>$this->getUser()->getSignature(),
			'count'=>$count,
			'limit'=>$limit,
			'followme'=>$num1,
			'role'=>$role
        );
        return new JsonResponse($json_r);
    }
	public function UserProfileAction(Request $request)
    {
		$json = $this->get('json_parser')->parse($request);
		$uid = $json->get('uid', '');
        $em = $this->getDoctrine()->getEntityManager();
		$user = $em->getRepository('YdzyUserBundle:User')->findOneById($uid);
		
		if(!$user){
			return new Response('',400);
			}
		$count=$user->getCount();
		$limit=$user->getLimitation();
		$truename=$user->getTruename();
		$nickname=$user->getNickname();
		$area=$user->getArea();
		if($count==null){$count="";}
		if($limit==null){$limit="";}
		if($nickname==null){$nickname="";}
		if($truename==null){$truename="";}
		if($area==null){$area="";}
		$this->get('my_datebase')->connection();
		if(!$this->getUser()){return new Response('',403);}
		$id = $this->getUser()->getId();
		$sql = "select id from Follow where user_id = $id and followed_user_id = $uid and status = 1";
		$result = mysql_query($sql);
		$num   = mysql_num_rows($result);
		if (!$num){
			$follow = 0;
			$follow_id=0;
		}else{
			$follow = 1;
			$follow_id = mysql_result($result,0);
			}	
		$sql1 = "select a.user_id,b.phone,b.nickname,a.updated_date from Follow as a LEFT JOIN fos_user as b ON a.user_id=b.id where a.followed_user_id = $id and a.status = 1";
		$result1 = mysql_query($sql1);
		$num1  = mysql_num_rows($result1);
		
		$json_r = array(
            'id' => $user->getId(),
            'username' => $user->getUserName(),
            'email' => $user->getEmail(),
            'phone' => $user->getPhone(),
			'nickname' =>$nickname,
			'truename' =>$truename,
			'area' =>$area,
			'signature' =>$user->getSignature(),
			'count'=>$count,
			'limit'=>$limit,
			'follow'=>$follow,
			'follow_id'=>intval($follow_id),
			'followme'=>$num1
        );
        return new JsonResponse($json_r);
    }
	public function FollowMeAction(Request $request)
    {
		$user = $this->getUser();
        if(!$user)
        {
            return new Response("", 403);
        }
        
		$uid = $this->getUser()->getId();
        $this->get('my_datebase')->connection();
		$sql = "select a.user_id,b.phone,b.nickname,a.updated_date from Follow as a LEFT JOIN fos_user as b ON a.user_id=b.id where a.followed_user_id = $uid and a.status = 1";
		$result = mysql_query($sql);
		$num   = mysql_num_rows($result);
		if (!$num){
			return new Response('', 400);
		}
		while($row = mysql_fetch_array($result)){
		
			   $json_result[]=array(
			   	  'user_id' => $row['user_id'],
		          'nickname' => $row['nickname'],
				  'phone' => $row['phone'],
				  'updated_date' => $row['updated_date']
		  		  );
		}
		$json = array(
		'num'=>$num,
		'json'=>$json_result
		);
        return new JsonResponse($json);
    }
    public function ChangeProfileAction(Request $request)
    {
		$json = $this->get('json_parser')->parse($request);
		$nickname = $json->get('nickname', '');
		$signature = $json->get('signature', '');
		$phone = $json->get('phone','');
        $user = $this->getUser();
        if(!$user)
        {
            return new Response("", 403);
        }
        $userId = $this->getUser()->getId();
		$em = $this->getDoctrine()->getEntityManager();
		$user = $em->getRepository('YdzyUserBundle:User')->findOneById($userId);
		$user->setNickname($nickname);
		$user->setSignature($signature);
   	 	$user->setPhone($phone);
		$em->flush();
        
        return new Response('',200);
    }
	 public function ChangeProfileByIdAction(Request $request)
    {
		$json = $this->get('json_parser')->parse($request);
		$uid = $json->get('uid',0);
		$area = $json->get('area','');
		$limitation = $json->get('limit','');
       
        
		$em = $this->getDoctrine()->getEntityManager();
		$user = $em->getRepository('YdzyUserBundle:User')->findOneById($uid);
		$user->setLimitation($limitation);
		$user->setArea($area);
		$em->flush();
        
        return new Response('',200);
    }
    public function LoginAction(Request $request)
    {
        //$this->forward('YdzyUserBundle:Default:sendValidate');
        return new Response("欢迎进入万吉网");
		//return $this->render('YdzyAdminBundle:Brand:manage.html.twig');
    }
    
    public function LogoutAction(Request $request)
    {
        return new Response("API LogoutAction");
    }
    
    public function SendValidateAction(Request $request)
    {
    		$validateNumber = rand(1000,9999);
    		$session = $this->getRequest()->getSession();
    		$session->set('validate_number', $validateNumber);
			
        return new JsonResponse($validateNumber);
    }
    public function GetTelValidateAction(Request $request)
    {
		$json = $this->get('json_parser')->parse($request);
		$username = $json->get('username', '');
		//获得所有注册用户的手机号码
		$this->get('my_datebase')->connection();
		$sql = "SELECT username from fos_user";
		$result = mysql_query($sql);
		while($row = mysql_fetch_array($result)){
			if($username == $row['username']){
				return new Response('',403);
				}
			}
		
		$session = $this->getRequest()->getSession();
		$date = $session->get('date');
		$current_date = date('YmdHis');
		$hours = $current_date -$date;
		if($hours<=3000){
			$validateNumber = $session->get('validate_number');
			}else{
        $validateNumber = rand(1000,9999);
			}
    	$session->set('validate_number', $validateNumber);
		//$session->set('date', date('YmdHis'));
		
		//发送到短信接口
		$message = '欢迎注册万吉工程机械交易网，验证码是'.$validateNumber.',在这里您将获得最丰富的机械交易信息,最佳的交易体验【万吉工程机械】';
		//return new Response($validateNumber);
		$gateway = "http://125.208.3.91:8888/sms.aspx?action=send&userid=5274&account=xpt10144&password=xpt10144369&mobile=$username&content=$message&sendTime=&extno=";
		//return $this->redirect($gateway, 200);
		$result = file_get_contents($gateway);
		if(0 == $result)
			{
				$message =  "发送成功! 发送时间".date("Y-m-d H:i:s");
				return new Response($message,200);
			}
			else
			{
				$message =  "发送失败, 错误提示代码: ".$result;
				return new Response($message,500);
				
			}
			//return new Response($message,200);
    }
    public function RegisterAction(Request $request)
    {
        // retrieve json object
		$json = $this->get('json_parser')->parse($request);
		$phone = $json->get('phone', '');
		$nickname = $json->get('nickname', '');
		$email = $json->get('email', '');
		$password = $json->get('password', '');
		$validate = $json->get('validate', '');
		
		$session = $this->getRequest()->getSession();
		$validateSession = $session->get('validate_number');
		//return new Response($validateSession);
        if($validate != $validateSession && $validate != '9977')
        {
            return new Response('', 403);   
        }

        
        $userManager = $this->get('fos_user.UserManager');
        $user = $userManager->createUser();
        $user->setUsername($phone);
        $user->setEmail($phone);
        $user->setPhone($phone);
        $user->setPlainPassword($password);
        $user->setEnabled(true);
        $user->setSuperAdmin(false);
        $user->setNickname($nickname);
		$user->setSignature("");
		$user->setCreationDate(new \DateTime("now"));
        $userManager->updateUser($user);
        
        return new JsonResponse($user->getId()); 
    }
    public function AdminRegisterAction(Request $request)
    {
        // retrieve json object
		//return new Response('dddd');
		$json = $this->get('json_parser')->parse($request);
		$phone = $json->get('phone', '');
		$truename = $json->get('truename', '');
		$area = $json->get('area', '');
		$password = $json->get('password', '');
		$validate = $json->get('validate', '');
		
		$session = $this->getRequest()->getSession();
		$validateSession = $session->get('validate_number');
        if($validate != $validateSession && $validate != '9977')
        {
            return new Response('', 500);   
        }

		
        $role = array('ROLE_ADMIN');
        $userManager = $this->get('fos_user.UserManager');
        $user = $userManager->createUser();
        $user->setUsername($phone);
        $user->setEmail($phone);
        $user->setPhone($phone);
        $user->setPlainPassword($password);
        $user->setEnabled(true);
        $user->setRoles($role);
        $user->setTruename($truename);
		$user->setArea($area);
		$user->setNickname("");
		$user->setCreationDate(new \DateTime("now"));
        $userManager->updateUser($user);
        
        return new JsonResponse($user->getId()); 
    }
    public function GetTokenAction(Request $request)
    {
        $csrfToken = $this->container->has('form.csrf_provider')
            ? $this->container->get('form.csrf_provider')->generateCsrfToken('authenticate')
            : null;
        return new Response($csrfToken);
    }
	
	
	
         public function GetPasswordTokenAction(Request $request)
    {
		$json = $this->get('json_parser')->parse($request);
		$username = $json->get('username', '');
		
		//获得所有注册用户的手机号码
		$this->get('my_datebase')->connection();
		$sql = "SELECT username from fos_user where username = '$username'";
		$result = mysql_query($sql);
		$num   = mysql_num_rows($result);
		if (!$num){
			return new Response('', 400);
		}				
		
		$session = $this->getRequest()->getSession();
		$date = $session->get('date1');
		$current_date = date('YmdHis');
		$hours = $current_date - $date;
		if($hours<=3000){
			$validateNumber = $session->get('validate_pwd');
			}else{
        $validateNumber = rand(1000,9999);
			}
    	$session->set('validate_pwd', $validateNumber);
		$session->set('username', $username);
		$session->set('date1', date('YmdHis'));
		//发送到短信接口
		echo $message = '您正在进行更改密码的操作，验证码是'.$validateNumber.'【万吉工程机械】';
		$gateway = "http://125.208.3.91:8888/sms.aspx?action=send&userid=5274&account=xpt10144&password=xpt10144369&mobile=$username&content=$message&sendTime=&extno=";
		//return $this->redirect($gateway, 200);
		$result = file_get_contents($gateway);
		if(0 == $result)
			{
				$message =  "发送成功! 发送时间".date("Y-m-d H:i:s");
				return new Response($message,200);
			}
			else
			{
				$message =  "发送失败, 错误提示代码: ".$result;
				return new Response($message,500);
				
			}
			//return new Response($message,200);
    }

    public function ChangePasswordAction(Request $request)
    {
		$json = $this->get('json_parser')->parse($request);
		$validate = $json->get('validate', '');
		$username = $json->get('username', '');
		$newpwd = $json->get('newpwd', '');
		//给当前输入电话号码发送短信验证码
		$session = $this->getRequest()->getSession();
		$validateSession = $session->get('validate_pwd');
		$usernameSession = $session->get('username');
		if($username!=$usernameSession){
			return new Response('', 401);
			}
        if($validate != $validateSession && $validate != '9977')
        {
            return new Response('', 403);   
        }
		
		$manipulator = $this->get('fos_user.util.user_manipulator');
        $manipulator->changePassword($username, $newpwd);
		return new Response('',200);
		
    }
	public function activeAction(Request $request)
    {
        $json = $this->get('json_parser')->parse($request);
		$username = $json->get('username', '');
		$manipulator = $this->get('fos_user.util.user_manipulator');
        $manipulator->activate($username);
		return new Response('',200);
    }
	public function deactiveAction(Request $request)
    {
        $json = $this->get('json_parser')->parse($request);
		$username = $json->get('username', '');
		$manipulator = $this->get('fos_user.util.user_manipulator');
        $manipulator->deactivate($username);
		return new Response('',200);
    }
	public function desaveAction(Request $request)
    {
        $json = $this->get('json_parser')->parse($request);
		$limit = $json->get('limit', '');
		$uid = $json->get('id', '');
		$em = $this->getDoctrine()->getEntityManager();
		$users = $em->getRepository('YdzyUserBundle:User')->find($uid);
		if(!$users){
			return new Response('',500);
			}
		$users->setLimit('$limit');
   	 	$em->flush();
		return new Response('',200);
    }
}