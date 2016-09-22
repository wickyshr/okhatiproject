<?php
require_once('checklogin.php');
include('connect.php');

@$field = $_GET['column'];
@$table = $_GET['table'];

$sql = "Alter Table $table Drop Column $field";
$result = mysql_query($sql);
if($result)
{
header("location:dynamic.php");

}
else
echo "Error in $sql";
?>