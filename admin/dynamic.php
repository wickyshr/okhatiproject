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
           
            <form method ="post" action = "dynamic.php" enctype="multipart/form-data">
            <table width="377" class="accountsettings">
            <tr>
              <th width="121" style="padding:10px; text-align:right;"><font color=#00000>Add New Table:</th><td width="105"><a href = "addnewtable.php" style=" font: Tahoma; text-align: left; padding-left: 5px; " > Click Here </a></td>   
            <tr>
              <th style="padding:10px; text-align:right;"><font color=#00000>Select Table:</th><td><select name="tablename">
            <?php
            $query = "SELECT TABLE_NAME FROM INFORMATION_SCHEMA.TABLES WHERE TABLE_TYPE = 'BASE TABLE' AND TABLE_SCHEMA='dbokhati'";
            $query_run=mysql_query($query);
            $count=mysql_num_rows($query_run);
            $tablenames = array();
            $table;
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
              <td style="padding:10px;">&nbsp;</td><td><input type="submit" name="show" value="Show Structure" /></td><td width="92"><input type="submit" name="delete" value="Delete Table"  onClick="return confirm('Deleting the table will delete entire records.Do you still want to delete selected table?');"/></td></tr>
            </table>
            
            <div style="padding-left:25px; padding-top: 25px;">
            <?php
           if(isset($_POST['show']))
			{
				if($_POST['show'])
				{
				$table = $_POST['tablename'];
				$_SESSION['selectedtable'] = $table;
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
				?>
				<table border="1" cellpadding="5px" class="datatable">
				<tr class="datath"><th><?php
				foreach($tablefields as $head)
				echo "$head</th><th>"
				?>Delete</th></tr>
				<?php
				while( $row=mysql_fetch_assoc($query_run))
					{?>
					<tr><?php
						foreach($tablefields as $field)
						echo  "<td>".$row["$field"]; ?></td>
						<td>
						<?php if($row["Key"] != "PRI" && $row["Key"] != "MUL")
						{
						$column = $row['Field'];
						echo "<a href = deletecolumn.php?table=".$table."&column=".$column." onClick=\"return confirm('Are you sure?');\">Delete</a></td></tr> ";
						}?>
					<?php
					}	
						echo "</table><br><tr><td><button type=\"button\" onClick=\"location.href='addnewcolumn.php'\"> Add Column </button></td></tr><br>";
					
						
				}			
				}
			}
		
			
			if(isset($_POST['delete']))
			{
			$table = $_POST['tablename'];
			$sql = "Drop table $table";
			$query_run=mysql_query($sql);
			if($query_run)
			{

			Header('Location: '.$_SERVER['PHP_SELF']);
			Exit(); //optional
			}
			else
			{
			echo "Delete Error. \n </br>The table is related to another table.";
			}
			}
            ?>
            </form>
            </div>
            <br />
            <br />
        </div>
	</div>
</body>
</html>
