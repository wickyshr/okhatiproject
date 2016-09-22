<!DOCTYPE html PUBLIC "-//W3C//DTD XHTML 1.0 Transitional//EN" "http://www.w3.org/TR/xhtml1/DTD/xhtml1-transitional.dtd">
<html xmlns="http://www.w3.org/1999/xhtml">
<?php 
include('checklogin.php');
?>
<head>
<meta http-equiv="Content-Type" content="text/html; charset=utf-8" />
<title>GE0-DATA MANIPULATION AND STORAGE</title>
<link href="CSS/Style Sheet.css" rel="stylesheet" type="text/css" />
</head>
<body bgcolor="#E9E9E9">
	<div class="userinterface">
   		<div class="header" style=" background-color: #424242">
       		<br />
            <span class="heading1">OKHATI</span></div>
        <div id='menu'>
			<ul>
    			<li class='active'><a href='adminhome.php'><span>Home</span></a></li>
                <li><a href='viewdata.php'><span>View Data</span></a></li>
	        	<li><a href='dynamic.php'><span>Edit Data</span></a></li>
        		<li><a href='addxl.php'><span>Upload XLS</span></a></li>
                <li><a href='adddata.php'><span>Upload CSV</span></a></li>
        		<li class='last'><a href='account.php'><span>Change Settings</span></a></li>
			</ul>
        </div>
        <p style="font-family:Tahoma; margin-right: 80px;" align=right > Hello <?php echo $_SESSION['user'];?> ! <a href="logout.php"> Logout</a></p>
        <div class="hticontent1">
     		<span class="hticontent1"> <span class="hti1">How to import files?</span> <span class="hti2">Note: Administrator should have knowledge about the format of the table.</span> <span class="hti3">This article will cover the basics of how to do this. You have the options to import XLS and CSV files. </span> <span class="hti4"><br />
After you have logged in with an administrator account in the login page, you will be prompted to admin homepage where you will be able to see all the administrative actions you can perform. On the navigation panel you will be able to see options for different things i.e. view data, edit data, upload XLS, upload CSV and change settings.</span> <span class="hti5">For XLS file,</span> <span class="hti6">If you would like to upload a XLS file, you have to upload a file with .xls extention. Also, you have to keep in mind that the uploaded file should match the exact format of the table structure.</span> <span class="hti7">For CSV file,</span><span class="hti8">If you would like to upload a CSV file, you have to upload a file with .csv extention keeping in mind that the format is same as the table structure. For e.g.,   if  table contains columns &quot;LevelID&quot;, &quot;Name&quot;, &quot;Address&quot; i.e. 3 columns,   CSV format should be exactly same i.e. 3 coulums of same data.</span></span> 
	</div>
</div>
</body>
</html>
