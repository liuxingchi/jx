<?php

namespace ydzy\UserBundle\Controller;

use Symfony\Bundle\FrameworkBundle\Controller\Controller;

use Symfony\Component\HttpFoundation\Request;
use Symfony\Component\HttpFoundation\Response;
use Symfony\Component\HttpFoundation\JsonResponse;
use ydzy\UserBundle\Entity\User;
use ydzy\apiBundle\Entity\Userinfo;
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
        $this->getUser()->getId();
        $json_r = array(
            'id' => $this->getUser()->getId(),
            'username' => $this->getUser()->getUserName(),
            'email' => $this->getUser()->getEmail(),
            'phone' => $this->getUser()->getPhone()
        );
        return new JsonResponse($json_r);
    }
    
    public function LoginAction(Request $request)
    {
        //$this->forward('ydzyUserBundle:Default:sendValidate');
        return new Response("API LoginAction");
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
    
    public function RegisterAction(Request $request)
    {
    $userManager = $this->container->get('fos_user.user_manager');

		$json = $this->get('json_parser')->parse($request);
		$phone = $json->get('phone', '');
		$myid = $json->get('myid', '');
		$password = $json->get('password', '');
        $manipulator = $this->get('fos_user.util.user_manipulator');
        $user = $manipulator->create($phone, $password, $phone, $phone, 'true', 'false');
				$userid = $user->getId();
				$infoname = $json->get('name', '');   //get in userinfo
				$infonumber = $json->get('number', '');
				$infoaddress = $json->get('address', '');
				$infogetname = $json->get('getname', '');
				$infogetphone = $json->get('getphone', '');
				$infogetaddress = $json->get('getaddress', '');
				$userinfo = new Userinfo();       //create info
				$userinfo -> setUser($user);
				$userinfo -> setAddress($infoaddress);
				$userinfo -> setNumber($infonumber);
				$userinfo -> setGetname($infogetname);
				$userinfo -> setGetphone($infogetphone);
				$userinfo -> setGetaddress($infogetaddress);
				$userinfo->setCreateDate(new \DateTime("now"));
				$userinfo->setUpdateDate(new \DateTime("now"));
				$userinfo->setStatus(0);
				
				$userinfo -> setIdCard(0);
				$userinfo -> setLockAccount(0);
				$userinfo -> setLevel(0);
				$userinfo -> setAccount(0);
				
				$em = $this->getDoctrine()->getEntityManager();
				$em->persist($userinfo);
				/*
				if($myid == 0){    //change mytable
						
				}else{
						
				}
				*/
				$em->flush();
        return new JsonResponse($user->getId()); 
    }
    
    public function GetTokenAction(Request $request)
    {
        $csrfToken = $this->container->has('form.csrf_provider')
            ? $this->container->get('form.csrf_provider')->generateCsrfToken('authenticate')
            : null;
        return new Response($csrfToken);
    }
    
    public function ChangePasswordAction(Request $request)
    {
        return new Response("API ChangePasswordAction");
    }
}
