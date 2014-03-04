<!DOCTYPE html>
<html lang="en">
<head>

    <!-- Basic Page Needs
  ================================================== -->
    <meta charset="utf-8">
    <title>Your File List</title>
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
					<center><a href="#" class="active">Total Downloaded File List</a>
                </li>
            </ul>
            <div id="register" class="form-action show">
                <center><h1>File list </h1><center>
					------------------------------------------------------
					<br>
                    Following files you downloaded on the server.
                </p>
                        
				<?php
					error_reporting(0);
					
					$var1 = $_POST["email"];
					$var2 = $_POST["pass"];

					$con = mysqli_connect("localhost", "root", "", "network");
					if(mysqli_connect_errno())
					{	
						echo "Failed to connect to MYSQL: " . mysqli_connect_error();
					}	
					
					$sql = "SELECT * FROM user WHERE email = '".$var1."' AND pass = '".$var2."' AND status = 0 ";
					$result=mysqli_query($con, $sql);
					$row = mysqli_fetch_array($result);
					if($row[0] == NULL) {
						$check = 0;
					}
					else {	
						$check = 1;
					}
					
					if($check==0){
								echo "Please Enter correct USERNAME and PASSWORD";
								echo '<p>
											<br> --------------------- Re-Enter Password ---------------------
											<br>
										</p>
										<form action = "secretfile.php" method = "post">
											<ul>
												<li>
													<input type="text" name="email" placeholder="Email" />
												</li>
												<li>
													<input type="password" name="pass" placeholder="Password" />
												</li>
												<li>
													<input type="submit" value="Send" class="button" />
												</li>
											</ul>
										</form>
										<p></p>';
					}
					else {			
						$sql = "SELECT name FROM downloads WHERE email = '".$var1."' AND status = 1";
						$result=mysqli_query($con, $sql);
						
						if($result == NULL) {
									echo "No files found";
						}
						else {
							echo "for email : ".$var1."<br><br>";
							echo "<ol style = 'font-family:arial;color:black;'>";
							
							while($row = mysqli_fetch_array($result)) {
								echo '<li ><a href="http://192.168.8.132/Network/data/' . $row['name'] . '">' . $row['name'] . '</a><p></li>';
							}
							echo "</ol>";
						}	
						
						$sql = "SELECT name FROM downloads WHERE email = '".$var1."' AND status = 9";
						$result=mysqli_query($con, $sql);
						
						if($result == NULL) {
									echo "No files found";
						}
						else {
							echo "<ol style = 'font-family:arial;color:black;'>";
							
							while($row = mysqli_fetch_array($result)) {
								echo '<li ><a href="http://192.168.8.132/Network/data/' . $row['name'] . '">' . $row['name'] . '</a><p></li>';
							}
							echo "</ol>";
						}	
						
						
						echo "----------- Over -------------<br><br><p><br><br><p>";
					}
					mysqli_close($con);

				?>
				
            </div>
        </div>
    </div>
    <script class="cssdeck" src="jquery.min.js"></script>
</body>
</html>


