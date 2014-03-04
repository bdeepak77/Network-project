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
	
	
echo get_remote_size('http://www.iith.ac.in/~vchakry/ubuntu-12.10-desktop-i386.iso');

function get_remote_size($url) {
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

	$remoteFile = 'http://upload.wikimedia.org/wikipedia/commons/6/63/Wikipedia-logo.png';

	$ch = curl_init($remoteFile);
	curl_setopt($ch, CURLOPT_NOBODY, true);
	curl_setopt($ch, CURLOPT_RETURNTRANSFER, true);
	curl_setopt($ch, CURLOPT_HEADER, true);
	curl_setopt($ch, CURLOPT_FOLLOWLOCATION, true); //not necessary unless the file redirects (like the PHP example we're using here)
	$data = curl_exec($ch);
	curl_close($ch);

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