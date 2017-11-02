<?php

namespace Ydzy\AdminBundle\Controller;

use Symfony\Bundle\FrameworkBundle\Controller\Controller;
use Symfony\Component\HttpFoundation\Request;
use Symfony\Component\HttpFoundation\Response;

class BrandController extends Controller
{
	
	public function manageAction()
    {
        return $this->render('YdzyAdminBundle:Brand:manage.html.twig');
    }
	public function addAction()
    {
        return $this->render('YdzyAdminBundle:Brand:add.html.twig');
    }
	public function modifyAction()
    {
	   return $this->render('YdzyAdminBundle:Brand:modify.html.twig',array('brand_id'=>$_GET['brand_id']));
    }

}
