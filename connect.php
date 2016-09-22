	<?php
$host="localhost";
$username="root";
$password="";
$database="dbokhati";
$connect=mysql_connect($host,$username,$password);
mysql_select_db($database,$connect) or die("Sorry for you inconivinve");



?>