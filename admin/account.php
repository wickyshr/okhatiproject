<!DOCTYPE html PUBLIC "-//W3C//DTD XHTML 1.0 Transitional//EN" "http://www.w3.org/TR/xhtml1/DTD/xhtml1-transitional.dtd">
<html xmlns="http://www.w3.org/1999/xhtml">
<?php
require_once('checklogin.php');
$id=$_SESSION['id'];
include("connect.php");
?>
<head>
<meta http-equiv="Content-Type" content="text/html; charset=utf-8" />
<title>Change Settings</title>
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
                <li><a href='adddata.php'><span>Upload CSV</span></a></li>
        		<li class='active'><a href='account.php'><span>Change Settings</span></a></li>
			</ul>
        </div>
        <p style="font-family:Tahoma; margin-right: 80px;" align=right > Hello <?php echo $_SESSION['user'];?> ! <a href="logout.php"> Logout</a></p>
        <br />

        <span class="hticontent1"> <span class="hti1">ACCOUNT SETTINGS</span>
        <form action="account.php" method="POST">
        <table class="accountsettings" style=" padding-left:35px;">
        <tr><td >Username:</td><td><input type="text" name="username"></td></tr>
        <td>&nbsp;</td>
        <tr><td>Old Password:</td><td><input type="password" name="O_password" name="old_password"></td></tr>
        <td>&nbsp;</td>
        <tr><td>New Username:</td><td><input type="text" name="nusername"></td></tr>
      	<td>&nbsp;</td>
        <tr><td>New Password:</td><td><input type="password" name="password" name="old_password"></td></tr>
      	<td>&nbsp;</td>
        <tr><td>Confirm Password:</td><td><input type="password" name="N_password" name="old_password"></td></tr>
        <td>&nbsp;</td>
        <td>&nbsp;</td>
        <tr><td>&nbsp;</td><td><input type="submit" name="submit" value="Submit"></td></tr>
        </form>
                </div>
            </div>
        </body>
        </html>
        
        <?php
            if(isset($_POST['username'])&&isset($_POST['password'])&&isset($_POST['nusername'])&&isset($_POST['O_password'])&&isset($_POST['N_password']))
            {
                $username=mysql_real_escape_string($_POST['username']);
                $newusername=mysql_real_escape_string($_POST['nusername']);
                $password=md5(mysql_real_escape_string($_POST['password']));
                $oldpassword=md5(mysql_real_escape_string($_POST['O_password']));
                $newpassword=md5(mysql_real_escape_string($_POST['N_password']));
                if($username && $password && $oldpassword && $newpassword &&$newusername)
                {
                    $query_run=mysql_query("SELECT * FROM administrator WHERE id=$id");
                    while($row=mysql_fetch_assoc($query_run))
                    {
                        $table_username=$row['Username'];
                        $table_password=$row['Password'];
                    }
                    if($table_username==$username && $oldpassword==$table_password)
                    {
                        if($password==$newpassword)
                        {
                            $query="UPDATE administrator SET Username='$newusername' ,Password='$newpassword' WHERE id=$id";
                            mysql_query($query);
                            echo "USERNAME AND PASSWORD SUCCESSFULLY CHANGED";
                        }
                        else
                        {
                            echo "Password do not match";
                        }
                    }
                    else
                        echo "Wrond PAssword";
                }
                else
                {
                    echo "Fill everything";
                }
            }
        ?>
        
</div>
</body>
</html>
