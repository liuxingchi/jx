<?php

namespace Ydzy\ApiBundle\Service;

use Symfony\Bundle\FrameworkBundle\Controller\Controller;

class SpiderDatebase
{
	public function connection()
   {
	$con = mysql_connect("42.96.204.119","admin","sql6yhn");
	if (!$con)
	  {
	  die('Could not connect: ' . mysql_error());
	  }
	
	mysql_select_db('jx', $con);
   }

}
