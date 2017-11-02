<?php

namespace Ydzy\ApiBundle\Service;

use Symfony\Bundle\FrameworkBundle\Controller\Controller;

class Datebase
{
	public function connection()
   {
	$con = mysql_connect("localhost","root","sql6yhn");
	if (!$con)
	  {
	  die('Could not connect: ' . mysql_error());
	  }
	
	mysql_select_db('jx', $con);
   }

}
