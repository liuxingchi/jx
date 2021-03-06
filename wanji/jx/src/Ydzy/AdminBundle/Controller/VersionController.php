<?php

namespace ydzy\AdminBundle\Controller;

use Symfony\Bundle\FrameworkBundle\Controller\Controller;
use Symfony\Component\HttpFoundation\JsonResponse;
use Symfony\Component\HttpFoundation\Request;
use Symfony\Component\HttpFoundation\Response;
use Ydzy\AdminBundle\Entity\Version;

class VersionController extends Controller
{
    public function addAction()
    {
        return $this->render('YdzyAdminBundle:Version:addversion.html.twig');
    }
    public function CreateversionAction(Request $request)    //admin 创建版本
    {
    		$user = $this->getUser();  
        if(!$user)
        {
            return new JsonResponse("", 403);   
        }
    		$json = $this->get('json_parser')->parse($request);
    		$em = $this->getDoctrine()->getEntityManager();
    		$uploadedDirPrefix = '/uploadnew/';
    		$uploadedFile = $request->files->get('imageFile');
    		if (!isset($uploadedFile) || !$uploadedFile->isValid()){
						return new Response('', 400);
				}
				$uploadedFileName = $uploadedFile->getClientOriginalName();
				$uploadedFileName = preg_replace('/[\/:*?"<>|&]+/', '_', $uploadedFileName);
				$filesize = $uploadedFile->getClientSize();
				$hashName = md5_file($uploadedFile->getPathname());
				$baseDir = preg_replace('/app$/si', 'web' . $request->request->get('folder'), $this->get('kernel')->getRootDir());
				$uploadedDir = chunk_split($hashName, 3, '/');
				$newFile = $uploadedDirPrefix.$uploadedDir.$uploadedFileName;
				if (!is_dir($baseDir.$uploadedDirPrefix.$uploadedDir)){
					if (!mkdir($baseDir.$uploadedDirPrefix.$uploadedDir, 0755, true)){
						return new Response('403', 403);
					}
				}
				$uploadedFile->move($baseDir.$uploadedDirPrefix.$uploadedDir, $uploadedFileName);
				$upload = $em->getRepository('YdzyAdminBundle:Version')
									->findOneByMd5($hashName);
				if (!$upload){
					$upload = new Version();
					$upload->setName("");
					$upload->setMd5($hashName);
					$upload->setVersion("");
					$upload->setDescription("");
					$upload->setFilesize($filesize);
					$upload->setFilepath($newFile);
					$upload->setCreateDate(new \DateTime("now"));
					$upload->setUpdateDate(new \DateTime("now"));
					$upload->setStatus(0);
					$em = $this->getDoctrine()->getEntityManager();
					$em->persist($upload);
					$em->flush();
				}
				$uploadId = $upload->getId();

				$json_r = array(
						'uploadId' => $uploadId,
						'uploadFile' => $newFile
				);
				
				$response = new Response(stripslashes(json_encode($json_r)));
		    return $response;
		} 
		public function AddversionAction(Request $request)    //admin 创建版本
    {
    		$user = $this->getUser();  
        if(!$user)
        {
            return new JsonResponse("", 403);   
        }
    		$json = $this->get('json_parser')->parse($request);
    		$em = $this->getDoctrine()->getEntityManager();
    		$versionid = $json->get('versionid', '');
    		$version = $json->get('version', '');
				$vname = $json->get('vname', '');
				$descri = $json->get('descri', '');
				//return new JsonResponse($versionid.$vname.$descri);
				$upload = $em->getRepository('YdzyAdminBundle:Version')->findOneById($versionid);
				if(!$upload){
						return new JsonResponse("", 400);   	
				}
				$upload->setName($vname);
				$upload->setVersion($version);
				$upload->setDescription($descri);
				$em->persist($upload);
				$em->flush();
				return new JsonResponse("", 200);  
		} 
}