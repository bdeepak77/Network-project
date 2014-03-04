<?php
	// Edit the four values below
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

	$remoteFile = 'http://fc05.deviantart.net/fs7/i/2005/172/f/3/Island_Beach_by_brokenfish.png';

	$ch = curl_init($remoteFile);
	curl_setopt($ch, CURLOPT_NOBODY, true);
	curl_setopt($ch, CURLOPT_RETURNTRANSFER, true);
	curl_setopt($ch, CURLOPT_HEADER, true);
	curl_setopt($ch, CURLOPT_FOLLOWLOCATION, true); //not necessary unless the file redirects (like the PHP example we're using here)
	$data = curl_exec($ch);
	curl_close($ch);

	if ($data === false) {
		  echo 'cURL failed';
		  exit;
	}

	$contentLength = 'unknown';
	$status = 'unknown';
	if (preg_match('/^HTTP\/1\.[01] (\d\d\d)/', $data, $matches)) {
		$status = (int)$matches[1];
	}
	if (preg_match('/Content-Length: (\d+)/', $data, $matches)) {
		$contentLength = (int)$matches[1];
	}

	echo 'HTTP Status: ' . $status . "\n";
	echo 'Content-Length: ' . $contentLength .' Bytes';
?>