<!DOCTYPE html>
<html>
	<head>
		<title>IITH Network Tool</title>
		<link href="bootstrap/css/bootstrap.css" rel="stylesheet">
		
		<!-- Loading Flat UI -->
		<link href="css/flat-ui.css" rel="stylesheet">
		<link href="css/demo.css" rel="stylesheet">
	
	<body>
		<div class="container">
		
			<div class="progress" style="height: 5px;width: 300px">
				<div class="progress-bar" style="width: 17%;"></div>
				<div class="progress-bar progress-bar-warning" style="width: 16%;"></div>
				<div class="progress-bar progress-bar-danger" style="width: 17%;"></div>
				<div class="progress-bar progress-bar-success" style="width: 16%;"></div>
				<div class="progress-bar progress-bar-info" style="width: 16%;"></div>
			</div>			
			
			<p></p>
			<div class="row demo-tiles" style="height:200px">
				<div class="col-md-3">
				  <div class="tile tile-hot" >
					
					<h3 class="tile-title">
							<?php
							// Turn off all error reporting
							error_reporting(0);
							
							function remote_file_size($url){
								$PROXY_HOST = "192.168.0.22"; // Proxy server address
								$PROXY_PORT = "3128";    // Proxy server port
								$PROXY_USER = "ch11b013";    // Username
								$PROXY_PASS = "123";   // Password
								// Username and Password are required only if your proxy server needs basic authentication
								 
								$auth = base64_encode("$PROXY_USER:$PROXY_PASS");
								stream_context_set_default(
									 array(
											  'http' => array(
											   'proxy' => "tcp://$PROXY_HOST:$PROXY_PORT",
											   'request_fulluri' => true,
											   'header' => "Proxy-Authorization: Basic $auth"
											   // Remove the 'header' option if proxy authentication is not required
										  )
									 )
								);
								
									$headers = get_headers($url, 1);
									if (isset($headers['Content-Length'])) return $headers['Content-Length'];
									if (isset($headers['Content-length'])) return $headers['Content-length'];

									$c = curl_init();
									curl_setopt_array($c, array(
										CURLOPT_URL => $url,
										CURLOPT_RETURNTRANSFER => true,
										CURLOPT_HTTPHEADER => array('User-Agent: Mozilla/5.0 (Macintosh; U; Intel Mac OS X 10.5; en-US; rv:1.9.1.3) Gecko/20090824 Firefox/3.5.3'),
										));
									curl_exec($c);
									return curl_getinfo($c, CURLINFO_SIZE_DOWNLOAD);
							}

							$var1 = $_POST["url"];
							$var3 = $_POST["time"];
							$var4 = $_POST["email"];
							$var5 = $_POST["pass"];
							$selected_radio = $_POST['optionsRadios'];
							if($selected_radio=="yes")
								$file_check = 0;
							else
								$file_check = 1;
							
							$file=remote_file_size($var1);

							$flag=0;
							$check=0;
							$d=date("G:i", strtotime($var3));
							$con = mysqli_connect("localhost", "root", "", "network");
							if(mysqli_connect_errno())
							{	
								echo " <img src='images/icons/svg/loop.svg' alt='Chat' class='tile-image' style='height:80px; width:80px;'> <br> <span class='fui-cross'></span> Failed to connect to MYSQL: " . mysqli_connect_error();
							}	

							$sql = "SELECT * FROM downloads WHERE url = '".$var1."'";
							$result=mysqli_query($con, $sql);
							if($result == NULL) {
										$flag=0;
							}
							else {
								$row = mysqli_fetch_array($result);
								$flag = 1;
							}
							
							$sql = "SELECT * FROM user WHERE email = '".$var4."' AND pass = '".$var5."' AND status = 0";
							$result=mysqli_query($con, $sql);
							$row = mysqli_fetch_array($result);
							if($row[0] == NULL) {
										$check=0;
							}
							else {
								if( $row['usage']+$file >= 6442450944 ){
									$check = 2;
									echo "<img src='images/icons/svg/loop.svg' alt='Chat' class='tile-image' style='height:80px; width:80px;'> <br> <span class='fui-cross'></span> Your panding file size exceded !!!";
								}
								else 									
									$check = 1;
							}
							
							
							
							
							if($flag==1 AND ($check==0 OR $check ==2)){
								if( $check == 2)
									echo " please try after we download few of your files.";
								else
									echo "<img src='images/icons/svg/loop.svg' alt='Chat' class='tile-image' style='height:80px; width:80px;'> <br> <span class='fui-cross'></span>  File is Already Present on server OR Invalid Password";
							}
							else{
										$sql = "INSERT INTO downloads (url, time, size, email, file_check) VALUES ('".$var1."', '".$d."',".$file.",'".$var4."',".$file_check.")";

										if(!mysqli_query($con, $sql))
									{
										echo("<img src='images/icons/svg/loop.svg' alt='Chat' class='tile-image' style='height:80px; width:80px;'> <br><span class='fui-cross'></span> Record no added. Try Again.");
										#die('Error: '.mysqli_error());
									}
									else{
										echo " <img src='images/icons/svg/retina.svg' alt='Chat' class='tile-image' style='height:80px; width:80px;'> <br> <span class='fui-check'></span> Record added";
									}
									
									$sql = "UPDATE user SET usage =".($row['usage']+$file)." WHERE email ='".$var4."'";
									if(!mysqli_query( $con,$sql)){
										}
									else {
										echo "<br> Your usage is updated to ".($row['usage']+$file)."  bytes.";
									}
									
							}
							mysqli_close($con);

							?>
					</h3>
					</div>
				</div>
			  </div> <!-- /tiles -->
			  
			<div class="progress" style="height: 5px;">
				<div class="progress-bar" style="width: 17%;"></div>
				<div class="progress-bar progress-bar-warning" style="width: 16%;"></div>
				<div class="progress-bar progress-bar-danger" style="width: 17%;"></div>
				<div class="progress-bar progress-bar-success" style="width: 16%;"></div>
				<div class="progress-bar progress-bar-info" style="width: 16%;"></div>
			</div>
			</center>
		</div>
		<!-- Load JS here for greater good =============================-->
		<script src="js/jquery-1.8.3.min.js"></script>
		<script src="js/jquery-ui-1.10.3.custom.min.js"></script>
		<script src="js/jquery.ui.touch-punch.min.js"></script>
		<script src="js/bootstrap.min.js"></script>
		<script src="js/bootstrap-select.js"></script>
		<script src="js/bootstrap-switch.js"></script>
		<script src="js/flatui-checkbox.js"></script>
		<script src="js/flatui-radio.js"></script>
		<script src="js/jquery.tagsinput.js"></script>
		<script src="js/jquery.placeholder.js"></script>
		<script src="js/jquery.stacktable.js"></script>
		<script src="js/application.js"></script>
		
	</body>
</html>

