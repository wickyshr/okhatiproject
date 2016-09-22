<!DOCTYPE html PUBLIC "-//W3C//DTD XHTML 1.0 Transitional//EN" "http://www.w3.org/TR/xhtml1/DTD/xhtml1-transitional.dtd">
<html xmlns="http://www.w3.org/1999/xhtml">
<?php
	include("connect.php");
?>
<head>
<meta http-equiv="Content-Type" content="text/html; charset=utf-8" />
<title>View Database</title>
<link href="CSS/Style Sheet.css" rel="stylesheet" type="text/css" />
</head>

<body bgcolor="#E9E9E9">
	<div class="userinterface">
   		<div class="header" style=" background-color: #424242">
       		<br />
            <span class="heading1">OKHATI</span></div>
        <div id='menu'>
			<ul>
    			 <li><a href='index.php'><span>Home</span></a></li>
                <li class='active'><a href='viewdata.php'><span>View Data</span></a></li>
                <li><a href='faqs.php'><span>FAQ's</span></a></li>
                <li class='last'><a href='about.php'><span>About Us</span></a></li>
			</ul>
        </div>
            <br />
            <div style=" padding-left:25px;">
            <form align=right action="viewdata.php" method="POST">
    
        <table width="280" style=" text-align: left">
        <tr>
          <th width="117" style="padding:10px;">Table name:</th><td width="151"><select name="tablename">
        <?php

		$query = "SELECT TABLE_NAME FROM INFORMATION_SCHEMA.TABLES WHERE TABLE_TYPE = 'BASE TABLE' AND TABLE_SCHEMA='dbokhati'";
		$query_run=mysql_query($query);
		$count=mysql_num_rows($query_run);
		$tablenames = array();


		while( $row=mysql_fetch_assoc($query_run))
		{
			if($row['TABLE_NAME'] == "administrator")
			continue;
			else
			$tablenames[] = $row['TABLE_NAME'];
		}
		foreach($tablenames as $value)
		{
		echo '<option value="'.$value.'"'.( (isset($_POST['tablename']) && $_POST['tablename']==$value) || ((isset($_GET['tablenamefixed']) && $_GET['tablenamefixed']==$value)) ?' selected="selected"':'').'>'.$value.'</option>';
    	}
	
		?>
            </select></td></tr>
            <div style="font-family:Tahoma; margin-right: 80px;" align=right>
            <input type="text" name="search" placeholder="search" >
            <input type="submit" value="Search" name="Search">
            <th><td><input type="submit" name="submit" value="Show Table" /></td></tr>
            </div>
            </table>
            </form>
            <br />
            <br />
           <?php 


			$per_page = 15;
			if(!isset($_GET['page']))
			{
			$page = 1; //current page
			}
			else
			{
			$page = $_GET['page'];
			}
			if($page <= 1)
			$start = 0;
			else
			$start = $page * $per_page - $per_page;
			
			if((isset($_GET['tablenamefixed']) && isset($_GET['page'])) && isset($_GET['searchval']))
			{
			$searchval = $_GET['searchval'];
			$table=$_GET["tablenamefixed"];
			$_POST['tablename'] = $table;
			$columnnames = array();
			$columnindex = 0;
			$primaryfield = "";
				$query = "Show Columns From ".$table;
				$query_run=mysql_query($query);
				if (! $query_run){
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
				$columnindex = 0;
				$primaryfield = $columnnames[$primarykeyindex];
				//$query="SELECT * FROM $table";
		
				//$_SESSION['varname'] = $table;
				include("search.php");
				$query_run=mysql_query($query);
				$totalpages = ceil($totalrows / $per_page);
			
				if($query_run)
				{
				$count=mysql_num_rows($query_run);
				if($count <= 0)
				echo "NO ITEM Found \n <br /> ";
				else
				{?>
				<table border="2" cellpadding="5px" class="datatable">
				<tr class="datath"><?php
				foreach($columnnames as $head)
				echo "<th>$head</th>"
				?></tr>
				<?php
					while ($row=mysql_fetch_assoc($query_run))
				{
				?>
					<tr class="datatd"><?php
					foreach($columnnames as $field)
					echo  "<td>".$row["$field"]."</td>"; ?></tr>
				<?php
				}	
				?>
				</table>
                <div style="text-align:center; padding-right: 25px;">
				<?php
				$prev = $page - 1;
				$next = $page + 1;
				echo "<hr></hr>";
				if($prev > 0)
				{
				echo "<tr><td><a href = '?searchval=$searchval&tablenamefixed=$table&page=$prev'>prev</a></td> ";
				}
				echo "<td><a> $page </a></td>";
				if($next <= $totalpages)
				{
				echo "<td><a href = '?searchval=$searchval&tablenamefixed=$table&page=$next'>next</a></td></tr> ";
				}
				if( ($_GET['searchval']) == "NO")
				{
				?>
				<hr></hr>
                </div>
				</td></tr><a style="padding-left:5px;" href="<?php echo "downloadcsv.php?"."&table=$table";?>" " onClick="return confirm('Are you sure you want to download the file?');"><img src="csv.jpg" /></a> &nbsp; &nbsp;
				<a href="<?php echo "downloadxls.php?"."&table=$table";?>" " onClick="return confirm('Are you sure you want to download the file?');"><img src="xls.jpg" /></a></td></tr>
				<?php
				}
				?>
				<?php 
			} 
		}
			else
			{
			echo "<font color = \"#FF000\">Sql statement not executable.";
			}
	}
}
else if(isset($_POST['submit'])||isset($_POST['Search']) )  
{
	$table=$_POST["tablename"];
	$columnnames = array();
	$columnindex = 0;
	$primaryfield = "";
		$query = "Show Columns From ".$table;
		$query_run=mysql_query($query);
		if (! $query_run){
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
		$columnindex = 0;
		$primaryfield = $columnnames[$primarykeyindex];
		//$query="SELECT * FROM $table";
		
		//$_SESSION['varname'] = $table;
		include("search.php");
		$query_run=mysql_query($query);
		$totalpages = ceil($totalrows / $per_page);
		
	if($query_run)
	{
	$count=mysql_num_rows($query_run);
	if($count <= 0)
	echo "NO ITEM Found \n <br /> ";
	else
	{?>
	<table border="2" cellpadding="5px" class="datatable">
				<tr class="datath"><?php
				foreach($columnnames as $head)
				echo "<th>$head</th>"
				?></tr>
				<?php
					while ($row=mysql_fetch_assoc($query_run))
				{
				?>
					<tr class="datatd"><?php
					foreach($columnnames as $field)
					echo  "<td>".$row["$field"]."</td>"; ?></tr>
				<?php
				}	
				?>
				</table>
        <div style="text-align:center; padding-right: 25px;">
		<?php
		$prev = $page - 1;
		$next = $page + 1;
		if($_POST['search'] != null)
		$searchval = "YES";
		else
		$searchval = "NO";
		echo "<hr></hr>";
		if($prev > 0)
		{
		echo "<tr><td><a href = '?searchval=$searchval&tablenamefixed=$table&page=$prev'>prev</a></td> ";
		}
		echo "<td><a> $page </a></td>";
		if($next <= $totalpages)
		{
		echo "<td><a href = '?searchval=$searchval&tablenamefixed=$table&page=$next'>next</a></td></tr> ";
		}
		if( $_POST['search'] == null)
		{
		?>
		<hr></hr>
        </div>
		</td></tr><a style="padding-left:5px;" href="<?php echo "downloadcsv.php?"."&table=$table";?>" " onClick="return confirm('Are you sure you want to download the file?');"><img src="csv.jpg" /></a> &nbsp; &nbsp;
		<a href="<?php echo "downloadxls.php?"."&table=$table";?>" " onClick="return confirm('Are you sure you want to download the file?');"><img src="xls.jpg" /></a></td></tr>
		<?php
		}
		?>
		<?php 
		} 
	}
	else
	{
	echo "<font color = \"#FF000\">Sql statement not executable.";
	}
	}
	?>
	




<?php

	//}
	
}
?>

        </div>
	  	</div>	
      </div>
</body>
</html>
