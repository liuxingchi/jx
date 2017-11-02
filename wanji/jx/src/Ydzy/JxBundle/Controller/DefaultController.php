<?php

namespace Ydzy\JxBundle\Controller;

use Symfony\Bundle\FrameworkBundle\Controller\Controller;
use Symfony\Component\HttpFoundation\Request;
use Symfony\Component\HttpFoundation\Response;

class DefaultController extends Controller
{
    public function indexAction($name)
    {
        return $this->render('YdzyJxBundle:Default:index.html.twig', array('name' => $name));
    }
    public function csxqAction($id)
    {		
        return $this->render('YdzyJxBundle:Default:csxq.html.twig', array('id' => $id));
    }
	public function zwzpAction($driver_id)
    {			
        return $this->render('YdzyJxBundle:Default:zwzp.html.twig', array('driver_id' => $driver_id));
    }
	public function czxqAction($id){
		return $this->render('YdzyJxBundle:Default:czxq.html.twig',array('id'=>$id));	
	}
}
