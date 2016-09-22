<!DOCTYPE html PUBLIC "-//W3C//DTD XHTML 1.0 Transitional//EN" "http://www.w3.org/TR/xhtml1/DTD/xhtml1-transitional.dtd">
<html xmlns="http://www.w3.org/1999/xhtml">
<?php
require_once('checklogin.php');
include("connect.php");
?>
<head>
<meta http-equiv="Content-Type" content="text/html; charset=utf-8" />
<title>CSV Import</title>
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
	        	<li><a href='dynamic.php'><span>Edit Data</span></a></li>
        		<li><a href='addxl.php'><span>Upload XLS</span></a></li>
                <li class='active'><a href='adddata.php'><span>Upload CSV</span></a></li>
        		<li class='last'><a href='account.php'><span>Change Settings</span></a></li>
			</ul>
        </div>
        <p style="font-family:Tahoma; margin-right: 80px;" align=right > Hello <?php echo $_SESSION['user'];?> ! <a href="logout.php"> Logout</a></p>

        <form method ="post" action = "adddata.php" enctype="multipart/form-data">
        <table width="415" class="accountsettings">
        <tr>
          <th width="144" style="padding:10px; text-align:right;">Table name:</th><td width="259"><select name="tablename">
        <?php
        $query = "SELECT TABLE_NAME FROM INFORMATION_SCHEMA.TABLES WHERE TABLE_TYPE = 'BASE TABLE' AND TABLE_SCHEMA='dbokhati'";
        $query_run=mysql_query($query);
        $count=mysql_num_rows($query_run);
        $tablenames = array();
        
        reset($tablenames);
        
            while( $row=mysql_fetch_assoc($query_run))
                {
                    if($row['TABLE_NAME'] == "administrator")
                    continue;
                    else
                    $tablenames[] = $row['TABLE_NAME'];
                }
                foreach($tablenames as $value)
                {
                echo '<option value="'.$value.'"'.(isset($_POST['tablename']) &&$_POST['tablename']==$value?' selected="selected"':'').'>'.$value.'</option>';
				}
            
        ?>
        </select></td></tr>
        <tr>
          <th style="padding:10px; text-align:right;">Upload CSV file:</th><td>    <input type="file" name="file" accept = ".csv" /></td>
        
        <tr>
          <td style="padding:10px; text-align:right;">&nbsp;</td><td><input type="submit" name="submit" value="Submit" onClick="return confirm('If the file contains old data, data from file will overwrite data in database.Are you sure?');"/></td>
        </table>
        </form>
        
        <?php
        
            
        if(isset($_POST['submit']) && $_FILES['file']['tmp_name'] != NULL )
        {
            $file=$_FILES['file']['tmp_name'];
        
            $handle=fopen($file,"r");
            
            $table=$_POST["tablename"];
            
            $entrycount= 0;
            $entryerror = 0;
                
            if(($_POST['submit']))
            {
                
                $columnnames = array();
                $filearray = array();
                $rowvalues = array();
                $updatearray = array();
                $updatefiles = array();
                $heading = array();
                $filerow = 0;
                $primarykeyindex = -1;
                $columnindex = 0;
                reset($columnnames);
                reset($filearray);
                
                $query = "Show Columns From ".$table;
                $query_run=mysql_query($query);
                if (! $query_run)
                {
                echo "Database error...!!! \n <br />";
                }
                else
                {
                while( $row=mysql_fetch_assoc($query_run))
                    {
                        $columnnames[] = $row['Field'];
                        if($row['Key'] == "PRI" )
                        {
                        $primarykeyindex = $columnindex;
                        }
                        $columnindex++;
                    
                    }
            
                }
                $columnindex = 0;
                foreach($columnnames as $val)
                {
                if($columnindex != $primarykeyindex)
                    array_push($updatearray, $val);
                $columnindex++;
                }
            
            while(($filearray= fgetcsv($handle,1000,","))!== false)
            {   
                $filerow++;
                if($filerow == 1)
                {
                foreach($filearray as $headval)
                {
                array_push($heading, trim($headval));
                }
                if(count($columnnames) != count($heading))
                {
                echo "<font color = \"#FF000\">Error...Number of columns not matched. Check it and try later.";
                break;
                }
                if(! ($heading === $columnnames))
                {
                for($i = 0; $i <= count($columnnames) - 1; $i++)
                {
                echo "$columnnames[$i]--------->$heading[$i]\n <br />";
                }
                echo "<font color = \"#FF000\">Error...There are following possibilities causing errors: \n <br />1) There is no heading in file. \n <br />2) Number of headings not matching with table structure. \n <br />3) Heading columns position mismatched. \n <br /> Please check the format again and try later.";
                break;
                }
                else
                {
                continue;
                }
                }
                else
                {
                unset($updatefiles);
                $updatefiles = array();
                
                unset($rowvalues);
                $rowvalues = array();
                $updateindex = 0;
                foreach($filearray as $val)
                {
                array_push($rowvalues, "'".$val."'");
                if($updateindex != $primarykeyindex)
                    {
                    array_push($updatefiles, "'".$val."'");
                    }
                $updateindex++;
                }
                $updateindex = 0;
                /*$levelid=$filearray[0];
                $name=$filearray[1];
                $query="SELECT * FROM levels WHERE LEVELID Like '%".$levelid."%' ";
                $query_run=mysql_query($query);
                $count=mysql_num_rows($query_run);
                if($count == 1)
                $sql = mysql_query("UPDATE levels SET Name = '$name' WHERE LEVELID Like '%".$levelid."%'");
                else
                $sql = mysql_query ("INSERT INTO levels (LevelID,Name) VALUES ('$levelid','$name')");
                */
                $stringcolumnnames = "(";
                $stringvalues = "(";
                $tableidentifier = "";
                $fileidentifier = "";
                $count = 0;
                foreach( $columnnames as $name)
                {
                    $stringcolumnnames = $stringcolumnnames.$name.",";
                    if($count == $primarykeyindex)
                        $tableidentifier = $name;
                    $count++;
                }
                $stringcolumnnames = $stringcolumnnames.")";
                $count = 0;
                foreach( $filearray as $columnvalue)
                {
                    //$stringvalues = $stringvalues.$columnvalue.",";
                    //echo $columnvalue;
                    if($count == $primarykeyindex)
                        $fileidentifier = $columnvalue;
                    $count++;
                }
                $count = 0;
                $query="SELECT * FROM ".$table." WHERE ".$tableidentifier." Like '".$fileidentifier."' ";
                //echo $query;
                $query_run=mysql_query($query);
                $count=mysql_num_rows($query_run);
                
                $updatecontents = "";
                if( $primarykeyindex >= 0)
                {
                    if($count == 1)
                    {
                    if(count($updatearray) != count($updatefiles))
                    {
                    echo "Error in CSV file, Please check it again...!!!";
                    }
                    else
                    {
                    for($x = 0; $x < count($updatearray); $x++)
                    {
                    if($x == count($updatearray) - 1)
                    $updatecontents = $updatecontents.$updatearray[$x]." =".$updatefiles[$x];
                    else
                    $updatecontents = $updatecontents.$updatearray[$x]." =".$updatefiles[$x].",";
                            
                    }
                    
                    //$text = "UPDATE ".$table." SET ".$updatecontents." WHERE ".$columnnames[$primarykeyindex]." Like '".$filearray[$primarykeyindex]."'";
                    //echo $text."\n <br />";
                    $query = "UPDATE ".$table." SET ".$updatecontents." WHERE ".$columnnames[$primarykeyindex]." Like '".$filearray[$primarykeyindex]."'";
                    $sql = mysql_query($query);
                    
                    if($sql)
                    {
                    $entrycount++;
                    //echo 'data uploaded and updated successfull';
                    }
                    else
                    {
                        echo "error occured in $query \n <br />";
                        $entrycount++;
                        $entryerror++;
                        //echo 'Data upload error...!!';
                    }
                    }
                    
                    }
                else
                {
                    //echo "INSERT INTO ".$table." (".implode(',', $columnnames).") VALUES (".implode(',', $rowvalues).") \n <br />";
                    $query = "INSERT INTO ".$table." (".implode(',', $columnnames).") VALUES (".implode(',', $rowvalues).")";
                    $sql = mysql_query($query);
                    
                if($sql)
                {
                    $entrycount++;
                    //echo 'data uploaded and updated successfull';
                }
                else
                {
                    echo "error occured in $query \n <br />";
                    $entrycount++;
                        $entryerror++;
                        //echo 'Data upload error...!!';
                }
                
                
                }
                }
                else
                {
                    $query = "INSERT INTO ".$table." (".implode(',', $columnnames).") VALUES (".implode(',', $rowvalues).")";
                    $sql = mysql_query ($query);
                    if($sql)
                    {
                        $entrycount++;
                    //echo 'data uploaded and updated successfull';
                    }
                    else
                    {
                    echo "error occured in $query \n <br />";
                        $entrycount++;
                        $entryerror++;
                        //echo 'Data upload error...!!';
                    }
                }
                }
                
                
            }   
                $success = $entrycount - $entryerror;
                if($entryerror == 0 && $entrycount > 0)
                {
                echo "Data successfully uploaded and updated.\n <br />";
                echo "Out of $entrycount entry, $success successful and $entryerror unsuccessful. \n <br />";
                }
                else if($entrycount > 0 && $entryerror > 0) 
                {
                echo "There is/are error(s) in data entry process. \n <br />Please check the CSV file again. \n <br />";
                echo "Out of $entrycount entry, $success successful and $entryerror unsuccessful. \n <br />";
                }
            
            }
         
        }
        ?>
        
</div>
</body>
</html>
