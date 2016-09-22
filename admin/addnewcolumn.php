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
//@$table = $_GET['table'];
//session_start();
$table = $_SESSION['selectedtable'];
$sql = "Show Columns from $table";
$query_run=mysql_query($sql);
$tablefields = array("Field", "Type", "Key");
$columnindex = 0;
	if(! $query_run)
	{
	echo "Database ERROR. \n <br />";
	}
	else
	{
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
		if($tablename != $table)
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
		else
		continue;
		}
	}
	?>
	<body>
	<tr><td><font size = "5" > Structure of <?php echo "$table"; ?></td></tr><br></body>
	<body><font size = "3">
	<form method ="post" action = "addnewcolumn.php">
	<table border="1" cellpadding="5px">
	<tr>
	<?php
	foreach($tablefields as $head)
	echo "<th>$head</th>"
	?></tr>
	<?php
	while( $row=mysql_fetch_assoc($query_run))
		{?>
			<tr><?php
			foreach($tablefields as $field)
			echo  "<td>".$row["$field"]; ?>
			</td></tr>
		<?php
		}
		?>
		</table>
		<table>
		<tr><th><font size = "3"> Add new column to the table</th></tr><br></table>
		<tr>While defining size, define the size of Char and Varchar only.</tr><tr> For other data types, just ignore the size</tr><br>
		<?php
			echo "<tr><td>Column Name</td><td><input type=\"text\" name=\"columname\" size=\"10\" required><td></tr>";
			echo "<tr><td>Type </td><td><select name=\"type\">";
			echo "<option value=\"char\">char</option>";  
			echo "<option value=\"varchar\">varchar</option>";
			echo "<option value=\"int\">int</option>";
			echo "<option value=\"float\">float</option>";
			echo "<option value=\"timestamp\">timestamp</option>";
			echo "</select></td></tr> ";
			echo "<tr><td>Size</td><td><input type=\"text\" name=\"size\" size=\"5\"></td></tr>";
			echo "<tr><td>Link to: <select name = \"foreignkey\">";
			echo "<option value=\"\">None</option>";
			foreach($tablewithprikey as $value)
			echo "<option value=\"$value\">$value</option>";
			echo "</select></td></tr><br>";
			echo "<tr><td><input type=\"submit\" name=\"columnsubmit\" value=\"Add\"></td></tr>";
			
	if(isset($_POST['columnsubmit']) && $table != null)
	{
	$tablename = $table;
	$name = $_POST['columname'];
	$type = $_POST['type'];
	$size = $_POST['size'];
	$foreignkey = $_POST['foreignkey'];
	if($type == "char" || $type == "varchar")
	{
		if($size != "")
		{
		$datatype = $type."(".$size.")";
		}
		else{
		echo "Error. Define size of char.";
		return;
		}
	}
	else
	{
	$datatype = $type;
	}
	$query = "Alter Table $tablename Add $name $datatype";
	if($foreignkey != "")
	{
	echo $foreignkey."\n </br>";
	$query_run=mysql_query($query);
	if($query_run)
	{
	$query = "Alter Table $tablename Add foreign key ($name) references $foreignkey";
	$query_run=mysql_query($query);
		if($query_run)
		{
		header("location:addnewcolumn.php");
		echo "Column added successfully.";
		}
		else
		{
		$query = "Alter table $tablename drop $name";
		$query_run=mysql_query($query);
		die ("Error! Couldn't add the column. Linking error. Please try again.");
		}
		
	}
	else
	{
	die ("Error! Couldn't add the column. Please try again.");
	}
	}
	else
	{
	$query_run=mysql_query($query);
	if($query_run)
	{
	header("location:addnewcolumn.php");
	echo "Column added successfully.";
	
	}
	else
	{
	die ("Error! Couldn't add the column. Please try again.");
	}
	}
	//echo $query;
	
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