<?php

namespace Ydzy\ApiBundle\Controller;
use Symfony\Component\HttpFoundation\Request;
use Symfony\Component\HttpFoundation\Response;
use Symfony\Bundle\FrameworkBundle\Controller\Controller;
use Symfony\Component\HttpFoundation\JsonResponse;


class BrandController extends Controller
{
    public function indexAction(Request $request)
    {
        return new Response("jx brand");
    }
		
    //------------------------------------------------
	// 品牌bycategory
	//------------------------------------------------
    public function retrieveAction(Request $request)
    {
		$json = $this->get('json_parser')->parse($request);
		$category_id = $json->get('category_id',0);
		$this->get('my_datebase')->connection();
		$category_id_sql=$category_id==3?" where a.category_id not in (1,2,3) and a.status=1 ":" where a.category_id=$category_id and a.status=1 ";
		$result = mysql_query("SELECT a.*,b.category_name FROM Brand as a left join Category as b on a.category_id=b.id".$category_id_sql."  and a.status=1 order by a.category_id desc");
		if($category_id==0){
			$result = mysql_query("SELECT  a.*,b.category_name FROM Brand as a left join Category as b on a.category_id=b.id where a.status = 1 order by a.id asc");
			}
		$num  = mysql_num_rows($result);
		if(!$num){
			return new Response('', 400);
			}
		while($row = mysql_fetch_array($result))
		  {
		  $json_result[] = array(
					'id' => $row['id'],
					'brand_name' => $row['brand_name'],
					'category_id' => $row['category_id'],
					'category_name' => $row['category_name'],
					'creation_date' => $row['creation_date'],
					'updated_date' => $row['updated_date']
				);
		  }

		$response = new Response(json_encode($json_result));
		return $response;
		
    }
	
	//------------------------------------------------
	// 品牌bycategory   url——Get
	//------------------------------------------------
    public function retrieveAllAction()
    {
		$category_id = $_GET['category_id'];
		$this->get('my_datebase')->connection();
		$result = mysql_query("SELECT id,brand_name FROM Brand where status = 1 and category_id=".$category_id);
		$num  = mysql_num_rows($result);
		if(!$num){
			return new Response('', 404);
			}
		while($row = mysql_fetch_array($result))
		  {
		  $json_result[] = array(
					'id' => $row['id'],
					'brand_name' => $row['brand_name']
				);
		  }

		$response = new Response(json_encode($json_result));
		return $response;
		
    }
	
	
	//brand详情
	public function infoAction(Request $request)
	{
		$json = $this->get('json_parser')->parse($request);
		$brand_id = $json->get('brand_id',0);
		$this->get('my_datebase')->connection();
		$sql = "select a.*,b.category_name from Brand as a left join Category as b on a.category_id=b.id where a.id = $brand_id";
		$result = mysql_query($sql);
		$num  = mysql_num_rows($result);
		if(!$num){
			return new Response('', 404);
			}
		while($row = mysql_fetch_array($result))
		  {
		  $json_result = array(
					'id' => $row['id'],
					'brand_name' => $row['brand_name'],
					'category_id' => $row['category_id'],
					'category_name' => $row['category_name'],
					'creation_date' => $row['creation_date'],
					'updated_date' => $row['updated_date']
				);
		  }

		$response = new Response(json_encode($json_result));
		return $response;
		
	}
	
	
	//brand修改
	public function updateAction(Request $request)
	{
		$json = $this->get('json_parser')->parse($request);
		$brand_id = $json->get('brand_id',0);
		$brand_name = $json->get('brand_name','');
		$category_id = $json->get('category',0);
		$this->get('my_datebase')->connection();
		$current_time = date('Y-m-d H:i:s',time());
		$sql = "update Brand set `brand_name`='$brand_name',`category_id`=$category_id,updated_date='$current_time' where `id`=$brand_id";
		if(mysql_query($sql)){
			return new Response('', 200);
		
			
			}else{return new Response('', 500);}
	}
	
	//brandadd
	public function addAction(Request $request)
	{
		$json = $this->get('json_parser')->parse($request);
		
		$brand_name = $json->get('brand_name','');
		$category_id = $json->get('category',0);
		$this->get('my_datebase')->connection();
		$current_time = date('Y-m-d H:i:s',time());
		$sql = "insert into Brand set `brand_name`='$brand_name',`category_id`=$category_id,updated_date='$current_time',creation_date='$current_time',status = 1";
		if(mysql_query($sql)){
			return new Response('', 200);
		
			
			}else{return new Response('', 500);}
	}
	
	//brand删除
	public function delAction(Request $request)
	{
		$json = $this->get('json_parser')->parse($request);
		$brand_id = $json->get('brand_id',0);
		$this->get('my_datebase')->connection();
		$current_time = date('Y-m-d H:i:s',time());
		$sql = "update Brand set `status`= 0,updated_date='$current_time' where `id`=$brand_id";
		if(mysql_query($sql)){
			return new Response('', 200);
		
			
			}else{return new Response('', 500);}
	}
	
}
