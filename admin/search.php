<?php

//require_once('checklogin.php');
include("connect.php");



//$table = $_SESSION['varname'];
if (isset($_POST['tablename']))
$table=$_POST['tablename'];
else if (isset($_GET['tablenamefixed']))
$table=$_GET['tablenamefixed'];

//echo $table;

$columnquery = "Show Columns from $table";
$result = mysql_query($columnquery); 
$columnhead = array();
	if (! $result)
	{
		//echo "Database error...!!! \n <br />";
		$query="SELECT * FROM $table";
	}
	else
	{
	while( $row=mysql_fetch_assoc($result))
	{
		$columnhead[] = $row['Field'];
	}
	}

if(isset($_POST['Search']))
{
    $name=$_POST['search'];

//    echo $tablex;
    //echo $name;

    if(!empty($name))
    {
        //$query="SELECT * FROM $table WHERE  Street_Address LIKE \"$name\"";
		$query="SELECT * FROM $table WHERE ";
		foreach($columnhead as $column)
		$query = $query."$column LIKE \"$name\" OR ";
		$query = rtrim($query, " OR ");
		$totalrows=mysql_num_rows(mysql_query($query));
		//echo $query;
    }
    else
	{
        $query="SELECT * FROM $table";
		$totalrows=mysql_num_rows(mysql_query($query));
	}
}
else
{ 
   // $tablex=$table;
    $query="SELECT * FROM $table";
	$totalrows=mysql_num_rows(mysql_query($query));
}	
	$query.=" Limit $start, $per_page";

?>  