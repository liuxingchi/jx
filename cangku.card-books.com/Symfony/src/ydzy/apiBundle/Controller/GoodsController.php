<?php

namespace ydzy\apiBundle\Controller;

use Symfony\Component\HttpFoundation\Request;
use Symfony\Component\HttpFoundation\Response;

use Symfony\Bundle\FrameworkBundle\Controller\Controller;

use ydzy\apiBundle\Entity\Promos;

class GoodsController extends Controller
{
    public function CreateAction(Request $request)
    {
        
        return new Response("API CreateAction");
    }

    public function RetrieveAction(Request $request)
    {
        return new Response("API RetrieveAction");
    }
    //
    public function ListAction(Request $request)
    {
        return new Response("API ListAction");
    }
    
    public function EditAction(Request $request)
    {
        return new Response("API EditAction");
    }
    
    public function DeleteAction(Request $request)
    {
        return new Response("API DeleteAction");
    }
    
    public function CollectAction(Request $request)
    {
        return new Response("API CollectAction");
    }
    
    public function SendAction(Request $request)
    {
        return new Response("API SendAction");
    }
}
