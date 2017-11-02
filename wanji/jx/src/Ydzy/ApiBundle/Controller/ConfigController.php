<?php

namespace ydzy\apiBundle\Controller;

use Symfony\Bundle\FrameworkBundle\Controller\Controller;
use Symfony\Component\HttpFoundation\JsonResponse;
use Symfony\Component\HttpFoundation\Request;
use Symfony\Component\HttpFoundation\Response;

class ConfigController extends Controller
{
		public function configAction(Request $request)
    	{
			$json = $this->get('json_parser')->parse($request);
			$key = $json->get('key',0);
			$value = $json->get('value',0);
			if(!$key){
				return new Response('',403);
				}
			
			$this->get('my_datebase')->connection();
	
			$sql = "update Config set value = ".$value." WHERE `key`= '".$key."'";
			
			if(mysql_query($sql)){
				
				return new Response('', 200);
				
				}else{return new Response('', 500);}
			}
			
		
		
}