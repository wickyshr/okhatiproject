<?php
include("connect.php");


@$table = $_GET['table'];

$query = "SELECT * FROM $table";
$header = "\r";
$data = '';
$export = mysql_query ($query ) or die ( "Sql error : " . mysql_error( ) );
 
// extract the field names for header
$fields = mysql_num_fields ( $export );
// $header = "" ;
 $tab= "";

for ( $i = 0; $i < $fields; $i++ )
{
    
    $header .= mysql_field_name( $export , $i ) . "\t";
}
 
// export data
while( $row = mysql_fetch_row( $export ) )
{
    $line = '';
    foreach( $row as $value )
    {                                            
        if ( ( !isset( $value ) ) || ( $value == "" ) )
        {
            $value = "\t";
        }
        else
        {
            $value = str_replace( '"' , '""' , $value );
            $value = '"' . $value . '"' . "\t";
        }
        $line .= $value;
    }
    $data .= trim( $line ) . "\n";
}
$data = str_replace( "\r" , "" , $data );
 
if ( $data == "" )
{
    $data = "\nNo Record(s) Found!\n";                        
}
 $filename ="XLSokhati$table".strtotime("now").'.xls';
// allow exported file to download forcefully
header("Content-type: application/octet-stream");
header('Content-Disposition: attachment; filename='.$filename);
header("Pragma: no-cache");
header("Expires: 0");
print "$header\n$data";
 
?>