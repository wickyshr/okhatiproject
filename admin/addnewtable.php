<!DOCTYPE html PUBLIC "-//W3C//DTD XHTML 1.0 Transitional//EN" "http://www.w3.org/TR/xhtml1/DTD/xhtml1-transitional.dtd">
<html xmlns="http://www.w3.org/1999/xhtml">
<?php
require_once('checklogin.php');
include("connect.php");
?>
<head>
<meta http-equiv="Content-Type" content="text/html; charset=utf-8" />
<title>Database: <?php echo $database?></title>
<link href="CSS/Style Sheet.css" rel="stylesheet" type="text/css" />
</head>
<body bgcolor="#E9E9E9">
	<div class="userinterface">
   		<div class="header" style=" background-color: #424242">
       		<br />
            <span class="heading1">OKHATI</span></div>
        <div id='menu'>
			<ul>
    			<li><a href='adminhome.php'><span>Home</span></a></li>
                <li><a href='viewdata.php'><span>View Data</span></a></li>
	        	<li class='active'><a href='dynamic.php'><span>Edit Data</span></a></li>
        		<li><a href='addxl.php'><span>Upload XLS</span></a></li>
                <li><a href='adddata.php'><span>Upload CSV</span></a></li>
        		<li class='last'><a href='account.php'><span>Change Settings</span></a></li>
			</ul>
        </div>
        <div class="content">
            <p style="font-family:Tahoma; margin-right: 80px;" align=right > Hello <?php echo $_SESSION['user'];?> ! <a href="logout.php"> Logout</a></p>
            <div class="form">
			<?php 
$self = "addnewtable.php";
$fields = null;
$db = null;
$names = null;
$query1 = "SELECT TABLE_NAME FROM INFORMATION_SCHEMA.TABLES WHERE TABLE_TYPE = 'BASE TABLE' AND TABLE_SCHEMA='dbokhati'";
$query_run1=mysql_query($query1);
if(!$query_run1)
{
die("Error fetching database...\n</br>");
}
else
{
$tablenames = array();
$tablewithprikey = array();
	while( $row=mysql_fetch_assoc($query_run1))
		{
			if($row['TABLE_NAME'] == "administrator")
			continue;
			else
			$tablenames[] = $row['TABLE_NAME'];
		}
		foreach($tablenames as $tablename)
		{
		$query1 = "Show columns from $tablename";
		$query_run1=mysql_query($query1);
		if(!$query_run1)
		{
		die("Error fetching tables...\n</br>");
		}
		else
		{
		while ($row=mysql_fetch_assoc($query_run1))
		{
		if($row['Key'] == "PRI")
		array_push($tablewithprikey, $tablename."(".$row['Field'].")");
		}
		}
		}
}

if(isset($_POST['field_submit'])) {
  $fields = $_POST['fields'];
}
else if(isset($_POST['db_submit']) )
 {
	
  $db =     "dbokhati";
  if( isset($_POST['names']))
   $names =   $_POST['names'];

  $name =   $_POST['name'];
  $table =  $_POST['table'];
  $type =   $_POST['type'];
  $size =   $_POST['size'];
  $foreignkey = $_POST["foreignkey"]; 
 
}
if( !$fields and !$db )
{
  $form ="<form action=\"$self\" method=\"post\">";
  $form.="How many fields are needed in the new table?<br>";
  $form.="<input type=\"text\" name=\"fields\" size=\"5\" required>";
  $form.="<input type=\"submit\" name=\"field_submit\" value=\"Submit\">";
  echo($form);
}
else if( !$db )
{ 
  $form ="<form action=\"$self\" method=\"post\">";
  //$form.="Database:     <input type=\"text\" name=\"db\"><br>";
  $form.="Table Name:  <input type=\"text\" name=\"table\" size=\"10\" required><br> ";
  $form.="<tr>While defining size, define the size of Char and Varchar only.</tr><tr> For other data types, just ignore the size</tr><br><table>";
  for ($i = 0 ; $i <$fields; $i++) 
  {
    
    $form.="<tr><td>Primary key:<input type=\"radio\" name=\"names\" value=\"$i\" size=\"10\" required></td>";
 
    $form.="<td>Column Name:<input type=\"text\" name=\"name[$i]\" size=\"10\" required></td> ";
  //  $names =   $_POST['name'];
  
    
    $form.="<td>Type: <select name=\"type[$i]\">";
    $form.="<option value=\"char\">char</option>";  
    $form.="<option value=\"varchar\">varchar</option>";
    $form.="<option value=\"int\">int</option>";
    $form.="<option value=\"float\">float</option>";
    $form.="<option value=\"timestamp\">timestamp</option>";
    $form.="</select></td> ";
    $form.="<td>Size:<input type=\"text\" name=\"size[$i]\" size=\"5\"></td>";
	$form.="<td>Link to: <select name = \"foreignkey[$i]\">";
	$form.="<option value=\"\">None</option>";
	foreach($tablewithprikey as $value)
	$form.="<option value=\"$value\">$value</option>";
    $form.="</select></td></tr>";
     } 
     $form.=" </table><tr><input type=\"submit\" name=\"db_submit\" value=\"Submit\"></form></tr>";
  echo($form);
}
else
{
	if($names != null)
	{
	
	$conn = mysql_connect("localhost", "root", "")
	or die("Could not connect.");

	$rs = mysql_select_db($db, $conn)
	or die("Could not select database.");
  
	$num_columns = count($name);

	$sql = "create table $table (PRIMARY KEY($name[$names]),";
	for ($i = 0; $i < $num_columns; $i++) 
	{
		$sql .= "$name[$i] $type[$i]  ";
		if(($type[$i] =="char") or ($type[$i] =="varchar"))
		{
		if($size[$i] != null )
		{ $sql.= "($size[$i])"; }
		else
		{
		die ("Size not defined for data types Char or Varchar. \n </br>");
		}
		}
		if(($i+1) != $num_columns){ $sql.=","; }
	
	
	}
	
	
	for($i = 0; $i < $num_columns; $i++)
	{
	if($foreignkey[$i] != "")
	{
	$sql .= ", foreign key ($name[$i]) references $foreignkey[$i]";
	}
	}
	//$sql = rtrim($sql, ",");
	$sql .= " )";
	//echo $sql;
	//echo("SQL COMMAND: $sql <hr>");
	
	$result = mysql_query($sql,$conn)
	or die("Could not execute SQL query. There are errors in data fields. \n <br /> Please retry again.");
	
	if ($result) 
	{     
	echo("RESULT: Table \"$table\" has been created successfully. \n <br />");
	header("location:dynamic.php");
	?>
	<br><a href="adminhome.php">Back to home</a><br>
	<?php
	}
	
	}
	else
	{
	echo("Primary Key not selected..");
	}
  
  
}
?>
            </div>
            <br />
            <br />
        </div>
	</div>
</body>
</html>
