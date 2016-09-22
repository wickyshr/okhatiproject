<?php
include('connect.php');

@$id = $_GET['primarykey'];
@$field = $_GET['primaryfield'];
@$table = $_GET['table'];

$result = mysql_query("DELETE  FROM $table where $field like $id");
if(!$result)
{
die ("Record could not be deleted..First make sure that the selected record is not linked to any other table.");
}
else
header("location:viewdata.php");
?>