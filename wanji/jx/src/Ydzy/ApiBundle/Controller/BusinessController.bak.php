<?php
namespace Ydzy\ApiBundle\Controller;
use Symfony\Component\HttpFoundation\Request;
use Symfony\Component\HttpFoundation\Response;
use Symfony\Bundle\FrameworkBundle\Controller\Controller;
use Symfony\Component\HttpFoundation\JsonResponse;

class BusinessController extends Controller
{
    public function indexAction(Request $request)
    {
        return new Response("jx Bussinesslist");
    }

	//------------------------------------------------
	// startdate
	//------------------------------------------------
    public function startdateAction(Request $request)
    {
		$json = $this->get('json_parser')->parse($request);
		$start_date = strtotime($json->get('start_date',0));
		$machine_id = $json->get('machine_id',0);
		$user= $this->getUser();
		if(!$user){
			return new Response('',403);
			}
		$uid = $user->getId();
		$current_time = date('Y-m-d H:i:s',time());
		$this->get('my_datebase')->connection();

		$sql = "insert into Business set `uid`=$uid,`machine_id`=$machine_id ,`start_date`='$start_date', `creation_date`='$current_time', `updated_date`='$current_time' ,status=1 ";
		
		if(mysql_query($sql)){
			mysql_query("update Machine set `mark`=2 where `id`=$machine_id ");	
			return new Response('', 200);
			
			}else{return new Response('', 500);}
		
    }
	
	//------------------------------------------------
	// enddate
	//------------------------------------------------
    public function enddateAction(Request $request)
    {
		$json = $this->get('json_parser')->parse($request);
		$end_date = strtotime($json->get('end_date',0));
		$machine_id = $json->get('machine_id',0);
		$money = $json->get('money',0);
		$user= $this->getUser();
		if(!$user){
			return new Response('',403);
			}
		$current_time = date('Y-m-d H:i:s',time());
		$this->get('my_datebase')->connection();

		$sql = "update Business set `end_date`='$end_date',`money`=$money,`updated_date`='$current_time' ,status=1 where `machine_id`=$machine_id order by id desc limit 1";
		//$sql = "update Business set `end_date`='$end_date',`money`=$money,`updated_date`='$current_time' ,status=1 where `machine_id`=$machine_id order by id desc limit 1";
		mysql_query("update Machine set `mark`=0 where `id`=$machine_id ");	
		if(mysql_query($sql)){
			
			return new Response('', 200);
			
			}else{return new Response('', 500);}
		
    }
	
	
	//------------------------------------------------
	// status
	//------------------------------------------------
    public function statusAction(Request $request)
    {

		$user= $this->getUser();
		if(!$user){
			return new Response('',403);
			}
		
		
		$json = $this->get('json_parser')->parse($request);
		$machine_id = $json->get('machine_id',0);
		$this->get('my_datebase')->connection();
		$sql = "SELECT end_date from Business where machine_id = $machine_id order by id desc limit 1 ";
		$result = mysql_query($sql);
		$num   = mysql_num_rows($result);
		if (!$num){
			$status = 1 ; //未起租
			return new JsonResponse($status);
		}
		$end_date = mysql_result($result,0);
		if($end_date == ""){$status=2;}//已经开始出租，但是没有结束
		else{$status = 1 ;}//已经租完，未起租
			
		return new JsonResponse($status);
		
    }
	//单个机器总金额计算
    public function countAction(Request $request)
    {
		$user= $this->getUser();
		if(!$user){
			return new Response('',403);
			}
		$uid = $user->getId();
		$json = $this->get('json_parser')->parse($request);
		$machine_id = $json->get('machine_id',0);
		$start_search_date = strtotime($json->get('start_search_date',''));
		$end_search_date = strtotime($json->get('end_search_date',''));
		$this->get('my_datebase')->connection();
		
		
		//machine_id=0查找当前用户所有机械统计信息
		if($machine_id==0){
			$sql = "select * from Business where uid=$uid group by machine_id";
			$result = mysql_query($sql);
			$machine_num   = mysql_num_rows($result);
			if (!$machine_num){
				return new Response('', 400);
			}
			while($row = mysql_fetch_array($result)){
				$arraymachine[] = array(
				 'machine_id'=>$row['machine_id']
				);
			}
			$machine_sql = "";
			foreach($arraymachine as $key=>$machine){
				if($key==0){
					$machine_sql  = " and machine_id = $machine[machine_id]";
					}else{
				$machine_sql =  $machine_sql." or machine_id= ".$machine['machine_id'];
					}
			}
			$sql ="select * from Business where status=1 ".$machine_sql;
			$result = mysql_query($sql);		
			$num   = mysql_num_rows($result);
			if (!$num){
				return new Response('', 400);
			}
		//echo strtotime("2013-12-5")."<br>";
		//echo strtotime("2013-12-4")."<br>";
		//echo date("Y-m-d","1391184000")."<br>";
		//echo date("Y-m-d","1392566400")."<br>";
		//echo date("Y-m-d","1391270400")."<br>";
		//echo date("Y-m-d","1392652800")."<br>";
		//echo date("Y-m-d","1393344000")."<br>";
		//echo date("Y-m-d","1392825600")."<br>";
		
		//判断要查找的起始时间是否在租赁的时间段内
		
		while($row = mysql_fetch_array($result)){
			$arraydate[] = array(
			 'start_date'=>$row['start_date'],
			 'end_date'=>$row['end_date'],
			 'money'=>$row['money']
			);
			
		}
		$day_search_right=0;
		$day_search_left=0;
		foreach($arraydate as $row){
			echo "a<br>";
			if(($row['start_date']<=$start_search_date)&&($start_search_date<=$row['end_date'])&&($row['start_date']<=$end_search_date)&&($end_search_date<=$row['end_date'])){
				$day_all = floor(($end_search_date-$start_search_date)/86400+1); //时间差
				//echo $day_all = ($day_all<0?0:$day_all);
				$date=floor(($row['end_date']-$row['start_date'])/86400+1);//当前租赁相差天数
				$money_day = $row['money']/$date; //每天收益
				$money = $money_day*$day_all;
				$json_result = array('money'=>$money,'machine_num'=>$machine_num,'date'=>$day_all,'ave_date'=>($day_all/$machine_num));
				return new JsonResponse($json_result);
				}
				//echo $row['start_date']."------".$start_search_date."------".$row['end_date']."<br>";
				
				//$day_all = $day_left + $day_right;
				//return new Response($day_all);
			
			}
		foreach($arraydate as $row){
			echo "b<br>";
			if(($row['start_date']<=$start_search_date)&&($start_search_date<=$row['end_date'])){
				echo "dddd";
				$date=floor(($row['end_date']-$row['start_date'])/86400+1);//当前租赁相差天数
				$money_day = $row['money']/$date; //每天收益
				$day_left = floor(($row['end_date']-$start_search_date)/86400+1); //左边时间差范围
				$day_search_right = $row['end_date'];//右边的时间
				$money_left = $money_day*$day_left;
				}
				
		}
		foreach($arraydate as $row){
			echo "c<br>";
			//echo $row['start_date']."------".$end_search_date."------".$row['end_date']."<br>";
			if(($row['start_date']<=$end_search_date)&&($end_search_date<=$row['end_date'])){
				$date=floor(($row['end_date']-$row['start_date'])/86400+1);//当前租赁相差天数
				$money_day = $row['money']/$date; //每天收益
				$day_right =floor(($end_search_date - $row['start_date'])/86400+1); //右边时间差范围
				$day_search_left = $row['start_date'];//左边的时间
				$money_right = $money_day*$day_right;
				}
				
		}
		//echo $day_search_left."------".$day_search_right;
		if($day_search_right!=0&&$day_search_left!=0){
		$array_middle_money = array();
		$array_middle_date = array();
		foreach($arraydate as $row){ //中间间隔的整个的租赁时间段
			echo "d<br>";
			//echo  date('Y-m-d',$row['start_date'])."------". date('Y-m-d',$day_search_right)."------". date('Y-m-d',$row['end_date'])."<br>";
			if(($row['start_date']>=$day_search_right)&&($day_search_left>=$row['end_date'])){
				
				$date_1=floor(($row['end_date']-$row['start_date'])/86400+1);//当前租赁相差天数
				$date_1<0?$date=0:$date=$date_1;
				//echo $date;
				//echo date('Y-m-d',$row['start_date'])."<br>";
				//echo $row['money']."<br>";
				array_push($array_middle_money,$row['money']);
				array_push($array_middle_date,$date);
				//echo $money_middle = $money_middle+$row['money']."<br>";
				}
				
			}
		$money_middle = array_sum($array_middle_money);
		//print_r($array_middle_date);
		$date_middle = array_sum($array_middle_date);
		
		$json_result = array('money'=>($money_left+$money_middle+$money_right),'machine_num'=>$machine_num,'date'=>($day_left+$date_middle+$day_right),'ave_date'=>(($day_left+$date_middle+$day_right)/$machine_num));
		return new JsonResponse($json_result);
		}
		$array_middle_money = array();
		$array_middle_date = array();
		//搜索时间点没有在租赁范围内的时间段的搜索
		foreach($arraydate as $row){ //中间间隔的整个的租赁时间段 
			echo "e<br>";
			//echo  date('Y-m-d',$row['start_date'])."------". date('Y-m-d',$day_search_right)."------". date('Y-m-d',$row['end_date'])."<br>";
			if(($row['start_date']>=$start_search_date)&&($end_search_date>=$row['end_date'])){//中间间隔整个租赁时间段
				$date=floor(($row['end_date']-$row['start_date'])/86400+1);//当前租赁相差天数
				//echo date('Y-m-d',$row['start_date'])."<br>";
				//echo $row['money']."<br>";
				array_push($array_middle_money,$row['money']);
				array_push($array_middle_date,$date);
				//echo $money_middle = $money_middle+$row['money']."<br>";
				}
				
			}
		$money_middle = array_sum($array_middle_money);
		$date_middle = array_sum($array_middle_date);
		$json_result = array('money'=>($money_middle),'machine_num'=>$machine_num,'date'=>($date_middle),'ave_date'=>(($date_middle)/$machine_num));
		return new JsonResponse($json_result);
		}
			
		
		else{	
		$sql ="select * from Business where machine_id = $machine_id";
		$result = mysql_query($sql);
		$num   = mysql_num_rows($result);
		if (!$num){
			return new Response('', 400);
		}		
		//echo strtotime("2013-12-5")."<br>";
		//echo strtotime("2013-12-4")."<br>";
		//判断要查找的起始时间是否在租赁的时间段内
		
		while($row = mysql_fetch_array($result)){
			$arraydate[] = array(
			 'start_date'=>$row['start_date'],
			 'end_date'=>$row['end_date'],
			 'money'=>$row['money']
			);
			
		}
		$day_search_right=0;
		$day_search_left=0;
		foreach($arraydate as $row){
			//echo "a<br>";
			if(($row['start_date']<=$start_search_date)&&($start_search_date<=$row['end_date'])&&($row['start_date']<=$end_search_date)&&($end_search_date<=$row['end_date'])){
				$day_all = floor(($end_search_date-$start_search_date)/86400+1); //时间差
				$date=floor(($row['end_date']-$row['start_date'])/86400+1);//当前租赁相差天数
				$money_day = $row['money']/$date; //每天收益
				$money = $money_day*$day_all;
				$json_result = array('money'=>$money,'machine_num'=>1,'date'=>$day_all,'ave_date'=>$day_all,'ave_money'=>($money/$day_all));
				return new JsonResponse($json_result);
				}
				//echo $row['start_date']."------".$start_search_date."------".$row['end_date']."<br>";
				
				//$day_all = $day_left + $day_right;
				//return new Response($day_all);
			
			}
		foreach($arraydate as $row){
			//echo "b<br>";
			if(($row['start_date']<=$start_search_date)&&($start_search_date<=$row['end_date'])){
				$date=floor(($row['end_date']-$row['start_date'])/86400+1);//当前租赁相差天数
				$money_day = $row['money']/$date; //每天收益
				$day_left = floor(($row['end_date']-$start_search_date)/86400+1); //左边时间差范围
				$day_search_right = $row['end_date'];//右边的时间
				$money_left = $money_day*$day_left;
				}
				
		}
		foreach($arraydate as $row){
			//echo "c<br>";
			//echo $row['start_date']."------".$end_search_date."------".$row['end_date']."<br>";
			if(($row['start_date']<=$end_search_date)&&($end_search_date<=$row['end_date'])){
				$date=floor(($row['end_date']-$row['start_date'])/86400+1);//当前租赁相差天数
				$money_day = $row['money']/$date; //每天收益
				$day_right =floor(($end_search_date - $row['start_date'])/86400+1); //右边时间差范围
				$day_search_left = $row['start_date'];//左边的时间
				$money_right = $money_day*$day_right;
				}
				
		}
		//echo $day_search_left."------".$day_search_right;
		if($day_search_right!=0&&$day_search_left!=0){
		$array_middle_money = array();
		$array_middle_date = array();
		foreach($arraydate as $row){ //中间间隔的整个的租赁时间段
			//echo "d<br>";
			//echo  date('Y-m-d',$row['start_date'])."------". date('Y-m-d',$day_search_right)."------". date('Y-m-d',$row['end_date'])."<br>";
			if(($row['start_date']>=$day_search_right)&&($day_search_left>=$row['end_date'])){
				$date=floor(($row['end_date']-$row['start_date'])/86400+1);//当前租赁相差天数
				//echo date('Y-m-d',$row['start_date'])."<br>";
				//echo $row['money']."<br>";
				array_push($array_middle_money,$row['money']);
				array_push($array_middle_date,$date);
				//echo $money_middle = $money_middle+$row['money']."<br>";
				}
				
			}
		$money_middle = array_sum($array_middle_money);
		$date_middle = array_sum($array_middle_date);
		$json_result = array('money'=>($money_left+$money_middle+$money_right),'machine_num'=>1,'date'=>($day_left+$date_middle+$day_right),'ave_date'=>($day_left+$date_middle+$day_right));
		return new JsonResponse($json_result);
		}
		$array_middle_money = array();
		$array_middle_date = array();
		//搜索时间点没有在租赁范围内的时间段的搜索
		foreach($arraydate as $row){ //中间间隔的整个的租赁时间段 
			//echo "e<br>";
			//echo  date('Y-m-d',$row['start_date'])."------". date('Y-m-d',$day_search_right)."------". date('Y-m-d',$row['end_date'])."<br>";
			if(($row['start_date']>=$start_search_date)&&($end_search_date>=$row['end_date'])){//中间间隔整个租赁时间段
				$date=floor(($row['end_date']-$row['start_date'])/86400+1);//当前租赁相差天数
				//echo date('Y-m-d',$row['start_date'])."<br>";
				//echo $row['money']."<br>";
				array_push($array_middle_money,$row['money']);
				array_push($array_middle_date,$date);
				//echo $money_middle = $money_middle+$row['money']."<br>";
				}
				
			}
		$money_middle = array_sum($array_middle_money);
		$date_middle = array_sum($array_middle_date);
		$json_result = array('money'=>($money_middle),'machine_num'=>1,'date'=>($date_middle),'ave_date'=>($date_middle));
		return new JsonResponse($json_result);
			}	
		}
	  
    
	
}
