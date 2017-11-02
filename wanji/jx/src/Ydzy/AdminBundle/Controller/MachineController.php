<?php

namespace Ydzy\AdminBundle\Controller;

use Symfony\Bundle\FrameworkBundle\Controller\Controller;
use Symfony\Component\HttpFoundation\Request;
use Symfony\Component\HttpFoundation\Response;

class MachineController extends Controller
{
	
	public function searchAction()
    {
        return $this->render('YdzyAdminBundle:Machine:search.html.twig');
    }
	public function manageAction()
    {
        return $this->render('YdzyAdminBundle:Machine:manage.html.twig');
    }
	public function devideAction()
    {
        return $this->render('YdzyAdminBundle:Machine:devide.html.twig');
    }
	public function qqAction()
    {
        return $this->render('YdzyAdminBundle:Machine:qq.html.twig');
    }
	public function qqcountAction()
    {
        return $this->render('YdzyAdminBundle:Machine:qqcount.html.twig');
    }
	public function addqqAction()
    {
        return $this->render('YdzyAdminBundle:Machine:addqq.html.twig');
    }
	public function manageMyAction()
    {
        return $this->render('YdzyAdminBundle:Machine:managemy.html.twig');
    }
	public function searchMyAction()
    {
        return $this->render('YdzyAdminBundle:Machine:pre_search.html.twig');
    }
	public function prerentAction()
    {
        return $this->render('YdzyAdminBundle:Machine:pre_rent.html.twig');
    }
	public function presaleAction()
    {
        return $this->render('YdzyAdminBundle:Machine:pre_sale.html.twig');
    }
	public function modifyAction()
    {
	   return $this->render('YdzyAdminBundle:Machine:modify.html.twig',array('machine_id'=>$_GET['machine_id']));
    }
	public function premodifyAction()
    {
	   return $this->render('YdzyAdminBundle:Machine:pre_modify.html.twig',array('machine_id'=>$_GET['machine_id']));
    }
	public function managemodifyAction()
    {
	   return $this->render('YdzyAdminBundle:Machine:manage_modify.html.twig',array('machine_id'=>$_GET['machine_id']));
    }
	public function manageroleAction()
    {
	   return $this->render('YdzyAdminBundle:Machine:manage_role.html.twig');
    }
	public function usermodifyAction()
    {
	   return $this->render('YdzyAdminBundle:Machine:user_modify.html.twig',array('uid'=>$_GET['uid']));
    }
	public function manageimagesAction()
    {
	   return $this->render('YdzyAdminBundle:Machine:manage_images.html.twig',array('machine_id'=>$_GET['machine_id']));
    }

}
