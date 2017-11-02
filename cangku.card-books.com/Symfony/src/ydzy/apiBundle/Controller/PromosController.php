<?php

namespace ydzy\apiBundle\Controller;

use Symfony\Component\HttpFoundation\Request;
use Symfony\Component\HttpFoundation\Response;
use Symfony\Component\HttpFoundation\JsonResponse;

use Symfony\Bundle\FrameworkBundle\Controller\Controller;

use ydzy\apiBundle\Entity\Promos;
use ydzy\apiBundle\Entity\PromoList;
use ydzy\apiBundle\Entity\PromoCart;
use ydzy\apiBundle\Entity\PromoImgs;
use ydzy\FileBundle\Entity\images;
use ydzy\UserBundle\Entity\User;
class PromosController extends Controller
{
    public function CreateAction(Request $request)      					//创建宣传品
    {
		    $json = $this->get('json_parser')->parse($request);
				$promoName = $json->get('promoName', '');
				$descrption = $json->get('descrption', '');
				$price = $json->get('price', 0);
				$imgs = $json->get('imgs', array()); 
				if($promoName == '')
				{
				    return new JsonResponse('品名不能为空');   
				}
				$promo = new Promos();
				$promo->setName($promoName);
				$promo->setDescrption($descrption);
				$promo->setPrice($price);
				$promo->setCreateDate(new \DateTime("now"));
				$promo->setUpdateDate(new \DateTime("now"));
				$promo->setStatus(0);
				$em = $this->getDoctrine()->getEntityManager();
				$em->persist($promo);
				foreach ($imgs as $img)
				{
				    $promoImgs = new PromoImgs();
				    $promoImgs->setPromo($promo);
				   	
				    $image = $em->getRepository('ydzyFileBundle:Images')->findOneById($img);
				    if($image)     
				    {
				        $promoImgs->setImage($image);   
				        $promoImgs->setPromo($promo);   
				        $promoImgs->setCreateDate(new \DateTime("now"));
				        $promoImgs->setUpdateDate(new \DateTime("now"));
				        $promoImgs->setStatus(0);
				        $em->persist($promoImgs);
				    }
				}
				$em->flush();
        $json_r = array(
            'id' => $promo->getId()
        );
        return new JsonResponse($json_r);
    }
		
		public function DeleteAction(Request $request)								//删除宣传品
    {
    		
    		$json = $this->get('json_parser')->parse($request);
				$promoid = $json->get('promosId', '');  
				 	
				$em = $this->getDoctrine()->getEntityManager();
				$promo = $em->getRepository('ydzyapiBundle:Promos')->find($promoid);
				
				if(!$promo){
					return new Response('', 404);
					}
				
				$em->remove($promo);
		        $em->flush();		
        return new Response('success',200);
    }
		
    public function RetrieveAction(Request $request)				
    {
    		$json = $this->get('json_parser')->parse($request);
				$id = $json->get('promoId', '');    	
				if($id == ""){											
						return new JsonResponse("id can not empty");	
				}
				$em = $this->getDoctrine()->getEntityManager();
				$promo = $em->getRepository('ydzyapiBundle:Promos')->findOneById($id);
				$promoid = $promo->getId();
				$promoname = $promo->getName();
				$promodescrption = $promo->getDescrption();
				$promoprice = $promo->getPrice();
				$promoimgs = $promo->getImgs();
				$promocreatedate = $promo->getCreateDate();
				$promoupdatedate = $promo->getUpdateDate();
				$promostatus = $promo->getStatus();
				$json_r = array(
						'promoid' => $promoid,
						'promoname' => $promoname,
						'promodescrption' => $promodescrption,
						'promoprice' => $promoprice,
						'promoimgs' => $promoimgs,
						'promocreatedate' => $promocreatedate,
						'promoupdatedate' => $promoupdatedate,
						'promostatus' => $promostatus
				);
        return new JsonResponse($json_r);
    }
    //
    public function ListAction(Request $request)
    {		
    		$json = $this->get('json_parser')->parse($request);
				$em = $this->getDoctrine()->getEntityManager();
				$promos = $em->getRepository('ydzyapiBundle:Promos')->findAll();
				foreach ($promos as $promo){
						$promoid = $promo->getId();
						$promoname = $promo->getName();
						$promodescrption = $promo->getDescrption();
						$promoprice = $promo->getPrice();
						$promoimgs = $promo->getImgs();
						$promocreatedate = $promo->getCreateDate();
						$promoupdatedate = $promo->getUpdateDate();
						$promostatus = $promo->getStatus();
						$json_r[] = array(
								'promoid' => $promoid,
								'promoname' => $promoname,
								'promodescrption' => $promodescrption,
								'promoprice' => $promoprice,
								'promoimgs' => $promoimgs,
								'promocreatedate' => $promocreatedate,
								'promoupdatedate' => $promoupdatedate,
								'promostatus' => $promostatus
						);
				}
				
        return new JsonResponse($json_r);
    }
    
    public function EditAction(Request $request)
    {		 
    		$json = $this->get('json_parser')->parse($request);
    		$promoId = $json->get('promoId', '');
				$promoName = $json->get('promoName', '');
				$descrption = $json->get('descrption', '');
				$price = $json->get('price', 0);
				$imgs = $json->get('imgs', array());    
				if($promoId == '')
				{
				    return new JsonResponse('id can not empty');   
				}
				if($promoName == '')	
				{
				    return new JsonResponse('promoName can not empty');   
				} 
				$em = $this->getDoctrine()->getEntityManager();
				$promo = $em->getRepository('ydzyapiBundle:Promos')->findOneById($promoId);
				$promo->setName($promoName);
				$promo->setDescrption($descrption);
				$promo->setPrice($price);
				$promo->setUpdateDate(new \DateTime("now"));
				$em->flush();
        return new JsonResponse("have Edited !");
    }
    
    public function CartAction(Request $request)
    {
    		$user = $this->getUser();
        if(!$user)
        {
            return new JsonResponse("", 403);   
        }
    		$json = $this->get('json_parser')->parse($request);
    		$promoId = $json->get('promoid', '');
    	
				$amount = $json->get('amount', '');
				$id = $this->getUser()->getId();
				/*
				$em = $this->getDoctrine()->getEntityManager();
				$cart = $em->getRepository('ydzyapiBundle:PromoCart');
				$repository = $em->getRepository('MirrorCardBundle:Messages');
					->where('m.user = :user')               
					->andWhere('m.status != 2') 
					->setParameter('user', $user)
					->orderBy('m.id')
					->getQuery();
				$messages = $query->getResult();
				$json_r = array(
					'sum' => $cart
				);
				return new JsonResponse($json_r);
				if(!$cart){
					return new JsonResponse("get in");
					$cart = new PromoCart();
					$cart->setUser($user);
					$cart->setCreateDate(new \DateTime("now"));
					$cart->setUpdateDate(new \DateTime("now"));
					$cart->setStatus(0);
					$em = $this->getDoctrine()->getEntityManager();
					$em->persist($cart);
					$em->flush();
					return new JsonResponse(200);
				}else{
					return new JsonResponse("aaaaaa");
				}
				*/
				return new JsonResponse("API Create Cart !");
    }
    
    public function TransferAction(Request $request)
    {
        return new JsonResponse("API TransferAction");
    }
    
    public function MyAction(Request $request)
    {
    		$json = $this->get('json_parser')->parse($request);
				$em = $this->getDoctrine()->getEntityManager();
				
				$mys = $em->getRepository('ydzyapiBundle:PromosMy')->findAll();
				foreach ($mys as $my){
						$myid = $my->getId();
						$mypromo = $my->getAmount();
						
						$json_r[] = array(
								'myid' => $myid,
								'mypromoid' => $mypromo
						);
				}
				
        return new JsonResponse($json_r);
    }
    
    public function MyCartAction(Request $request)
    {		
  		 	return new JsonResponse("API MyCartAction");
    }
    public function BuyAction(Request $request)
    {		
    		$user = $this->getUser();
        if(!$user)
        {
            return new JsonResponse("", 403);   
        }
        $id = $this->getUser()->getId();
        $json_r = array(
						'user' => $id
				);
				
       return new JsonResponse(var_dump($json_r));
       		//return new JsonResponse($json_r[user].id);
    }
}
