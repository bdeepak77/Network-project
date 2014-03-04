<!DOCTYPE html>
<html lang="en">
<head>

    <!-- Basic Page Needs
  ================================================== -->
    <meta charset="utf-8">
    <title>File List</title>
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
                    Following files are already downloaded on the server.
                </p>
                        
				<?php
					error_reporting(0);

					$con = mysqli_connect("localhost", "root", "", "network");
					if(mysqli_connect_errno())
					{	
						echo "Failed to connect to MYSQL: " . mysqli_connect_error();
					}	

					$sql = "SELECT name FROM downloads WHERE status = 1 AND file_check=0";
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
					
					$sql = "SELECT name FROM downloads WHERE status = 9 AND file_check = 0";
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
					
					echo "----------- Over -------------";
					mysqli_close($con);

				?>
				
				<p>
					<br> --------------------- Get private files ---------------------
					<br>
                    To get your private files enter your email and your password.
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
				<p></p>
            </div>
        </div>
    </div>
    <script class="cssdeck" src="jquery.min.js"></script>
</body>
</html>


