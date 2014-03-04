<!DOCTYPE html>
<html lang="en">
<head>

    <!-- Basic Page Needs
  ================================================== -->
    <meta charset="utf-8">
    <title>Registration</title>
    <meta name="description" content="">
    <meta name="author" content="">
	<link href="login.css" rel="stylesheet">
	<link href="reset.css" rel="stylesheet">
    <!-- Mobile Specific Metas
  ================================================== -->
    <meta name="viewport" content="width=device-width, initial-scale=1, maximum-scale=1">

    <!-- CSS
  ================================================== -->

    <!--[if lt IE 9]>
		<script src="http://html5shim.googlecode.com/svn/trunk/html5.js"></script>
	<![endif]-->
</head>
<body>

    <div class="container">
        <div class="flat-form">
            <ul class="tabs">
                <li>
					<center><a href="#" class="active">Registration</a>
                </li>
            </ul>
            <div id="register" class="form-action show">
                <center><h1>Register for <br>IITH Network Tool </h1><center>
                <p>
					--------------------- REGISTRATION ---------------------
					<br>
					
					<?php
					error_reporting(0);

				$var1 =  $_GET['email'];
				$var2 =  $_GET['rand'];
				
				$con = mysqli_connect("localhost", "root", "", "network");
				if(mysqli_connect_errno())
				{	
					echo "Failed to connect to MYSQL: " . mysqli_connect_error();
				}	

				$sql = "SELECT * FROM user WHERE email = '".$var1."' AND status = 2 AND random = '".$var2."'" ;
				$result=mysqli_query($con, $sql);
				if($result == NULL) {
							$flag=1;
				}
				else {
					$row = mysqli_fetch_array($result);
					$flag = 0;
				}
				
				if($flag>0)
					echo "Invalid User Details";
				else{
						$sql = "UPDATE user SET status = 0 WHERE email ='".$var1."'";
						
						if(!mysqli_query( $con,$sql)){
							echo('User Already Exists. Please try again.');
							}
							else {
								echo "You are now registered. You can use our chrome extension now. :)";
							}
				}
				
				mysqli_close($con);

				?>
									
					
					
                   
                </p>
				<p></p>
            </div>
        </div>
    </div>
    <script class="cssdeck" src="jquery.min.js"></script>
</body>
</html>


