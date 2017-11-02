<?php

namespace Ydzy\AdminBundle\Controller;

use Symfony\Bundle\FrameworkBundle\Controller\Controller;
use Symfony\Component\HttpFoundation\JsonResponse;

use Ydzy\UserBundle\Entity\User;

class DefaultController extends Controller
{
    public function indexAction()
    {
        return $this->render('YdzyAdminBundle:Default:main.html.twig');
    }
	public function welcomeAction()
    {
        return $this->render('YdzyAdminBundle:Default:welcome.html.twig');
    }

	public function registerAction()
    {
        return $this->render('YdzyAdminBundle:Default:register.html.twig');
    }
	public function mainAction()
    {
        return $this->render('YdzyAdminBundle:Default:main.html.twig');
    }

	public function menuAction()
    {
		$user = $this->getUser();
        if(!$user)
        {
            return new Response("", 403);   
        }
		$role = $this->getUser()->getRoles();
		$uid =  $this->getUser()->getId();
		$username =  $this->getUser()->getUsername();
		return $this->render('YdzyAdminBundle:Default:menu.html.twig',array('role'=>$role,'uid'=>$uid,'username'=>$username));
    }
}
