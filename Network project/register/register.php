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
					<center><a href="#" class="active">Register/Forgot-Password</a>
                </li>
            </ul>
            <div id="register" class="form-action show">
                <center><h1>Register for <br>IITH Network Tool </h1><center>
                <p>
					--------------------- REGISTER ---------------------
					<br>
					
					<?php
					error_reporting(0);

				$var1 = $_POST["email"];
				$var2 = $_POST["pass"];
				$var3 = $_POST["hint"];
				$con = mysqli_connect("localhost", "root", "", "network");
				if(mysqli_connect_errno())
				{	
					echo "Failed to connect to MYSQL: " . mysqli_connect_error();
				}	

				$sql = "SELECT * FROM user WHERE email = " + $var1;
				$result=mysqli_query($con, $sql);
				if($result == NULL) {
							$flag=0;
				}
				else {
					$row = mysqli_fetch_array($result);
					$flag = 1;
				}
				
				if($flag>0)
					echo "User already registered.";
				else{
					$sql = "INSERT INTO user (email, pass, hint, status) VALUES ('$var1', '$var2', '$var3',1)";

					if(!mysqli_query($con, $sql)){
						echo('User Already Exists. Please try again.
						<form action = "register.php" method = "post">
								<ul>
									<li>
										<input type="text" name="email" placeholder="Email" />
									</li>
									<li>
										<input type="password" name="pass" placeholder="Password" />
									</li>
									<li>
										<input type="password" name="hint" placeholder="Secret Hint" />
									</li>
									<li>
										<input type="submit" value="Sign Up" class="button" />
									</li>
								</ul>
							</form>
							');
						}
					else 
						echo "Congrats !!! You are registered. Please verify your Email-id before use.";
				}
				
				mysqli_close($con);

				?>
									
					
					
                   
                </p>
                
				<p>
					<br> --------------------- RESET PASSWORD ---------------------
					<br>
                    To reset your password enter your email and your birthday
                    and we'll send you a link to reset your password.
                </p>
                <form action = "reset.php" method = "post">
                    <ul>
                        <li>
                            <input type="text" name="email" placeholder="Email" />
                        </li>
                        <li>
                            <input type="text"name="hint" placeholder="Secret Hint" />
                        </li>
                        <li>
                            <input type="submit" value="Send" class="button" />
                        </li>
                    </ul>
                </form>
				<p></p>
            </div>
        </div>
    </div>
    <script class="cssdeck" src="jquery.min.js"></script>
</body>
</html>


