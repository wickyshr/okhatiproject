<!DOCTYPE html PUBLIC "-//W3C//DTD XHTML 1.0 Transitional//EN" "http://www.w3.org/TR/xhtml1/DTD/xhtml1-transitional.dtd">
<html xmlns="http://www.w3.org/1999/xhtml">
<?php
	include('connect.php');
	session_start();
	if(isset($_POST['username'])&&isset($_POST['password']))
	{
		$User_name=mysql_real_escape_string($_POST['username']);
		$password=mysql_real_escape_string(md5($_POST['password']));
		if(!empty($User_name)&&!empty($password))
		{
			$query_run=mysql_query("SELECT * FROM administrator WHERE Username='$User_name' && Password='$password'");
			$count=mysql_num_rows($query_run);
			if($count==0)
			{
				echo "Username Not Found";
			}
			else
			{
				$row = mysql_fetch_assoc($query_run);
				if(!empty($row)) 
				{			
					$_SESSION['user'] = $row['Username'];
					$_SESSION['id'] = $row['id'];
					header("location:adminhome.php");
				}
			}	
		}
	}
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
              <li class='active' style="margin-left:525px"><a href='#'><span>Administrator Login</span></a></li>
			</ul>
        </div>
        <div class="content1">
            <span class="heading2">Geo-data</span>
            <br />
            <span class="heading2">Manipulation</span>
            <br />
            <span class="heading2">and</span>
            <br />
            <span class="heading2">Storage</span>
            <br />
        </div>
        <div class="content3">
       		<span class="login">Login</span><br />
            <span class="dot">........................................</span>     	
        	<div class="loginsub">
                <form name="loginForm" method="post" action="index.php">
                    <table style="text-align:right; font:Tahoma;">
                        <tr> 
                            <td style="padding:10px;">Username:</td>
                            <td style="padding:10px;"><input type="text" name="username" /></td>
                        </tr>
                        <tr> 
                            <td style="padding:10px;">Password:</td>
                            <td style="padding:10px;"><input type="password" name="password" /></td>
                        </tr>
                        <tr> 
                            <td>&nbsp;</td>
                            <td style="padding:10px;"><input type="submit" name="submit" value="Submit" /></td>
                        </tr>
                        
                    </table>
                </form>
            </div>
	</div>
</div>
</body>
</html>
