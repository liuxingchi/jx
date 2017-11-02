<?php

namespace Ydzy\UserBundle\Controller;

use Symfony\Component\HttpFoundation\Request;
use Symfony\Component\HttpFoundation\Response;
use Symfony\Component\HttpFoundation\JsonResponse;
use Symfony\Bundle\FrameworkBundle\Controller\Controller;

use Ydzy\UserBundle\Entity\Promos;

class UserController extends Controller
{
    public function CreateAction(Request $request)
    {
        return new Response("API CreateAction");
    }

    public function RetrieveAction(Request $request)
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
    
    public function LoginAction(Request $request)
    {
        return new Response("User LoginAction");
    }
    
    public function LogoutAction(Request $request)
    {
        return new Response("API LogoutAction");
    }
    
    public function ChangePasswordAction(Request $request)
    {
        return new Response("API LogoutAction");
    }
}
