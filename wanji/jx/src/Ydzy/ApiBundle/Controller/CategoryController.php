<?php

namespace Ydzy\ApiBundle\Controller;
use Symfony\Component\HttpFoundation\Request;
use Symfony\Component\HttpFoundation\Response;
use Symfony\Bundle\FrameworkBundle\Controller\Controller;
use Symfony\Component\HttpFoundation\JsonResponse;

use Ydzy\ApiBundle\Entity\Category;

class CategoryController extends Controller
{
    public function indexAction(Request $request)
    {
        return new Response("jx category");
    }
	
 //------------------------------------------------
	// 获取所有的类别
	//------------------------------------------------
    public function retrieveAllAction(Request $request)
    {
		
    	// retrieve json object
		//$json = $this->get('json_parser')->parse($request);
		//$userId = $json->get('userId', 0);
        

		$em = $this->getDoctrine()->getEntityManager();
		$categories = $em->getRepository('YdzyApiBundle:Category')->findAll();
		foreach($categories as $category){
			$id = $category->getId();
			$category_name = $category->getCategoryname();
			$json_result[] = array(
					'id' => $id,
					'category_name' => $category_name
				);
			}
		// seek records.
		if (!$categories){
			return new Response('类别不存在', 404);
		}
		$response = new Response(json_encode($json_result));
		return $response;
		
    }
}
