<?php
/*
Version: 1.0
Author: WebINK
Author URI: http://www.webink.com
License: GPL2
*/

require('../../../wp-load.php');

header('Content-type: text/css; charset=utf-8');

if( isset($_GET['css']) && $_GET['css' ] == 'tinymce' ) 
{

	$option = get_option('WebINK_tinycss');
	$result = "";
	if(count($option) > 0){
		header('Access-Control-Allow-Origin: *');
		header('X-WebINK-Host: '. $_SERVER['HTTP_HOST']);
		if (!function_exists('curl_init')){
			die('Sorry cURL is not installed!');
		}
		foreach($option as $url){
			// Create a new cURL resource handle
			$ch = curl_init();	 
			
			// Set URL to download
			curl_setopt($ch, CURLOPT_URL, $url);
		 
			// Set a referer
			curl_setopt($ch, CURLOPT_REFERER, "http://" . $_SERVER['HTTP_HOST']  . $_SERVER['REQUEST_URI']);
		 
			// User agent
			curl_setopt($ch, CURLOPT_USERAGENT, $_SERVER['HTTP_USER_AGENT']);
		 
			// Include header in result? (0 = yes, 1 = no)
			curl_setopt($ch, CURLOPT_HEADER, 0);
			
			// Deflate the response
			curl_setopt($ch, CURLOPT_ENCODING, "deflate");
			
			//curl_setopt($ch,CURLOPT_HTTPHEADER,array('Accept: text/css,*/*;q=0.1'));
			
			// Should cURL return or print out the data? (true = return, false = print)
			curl_setopt($ch, CURLOPT_RETURNTRANSFER, true);
		 
			// Timeout in seconds
			curl_setopt($ch, CURLOPT_TIMEOUT, 10);
		 
			// Download the given URL, and return output
			$output = curl_exec($ch);
			echo($output);
			// Close the cURL resource, and free system resources
			curl_close($ch);
		 
			
		}
	}

}elseif(isset($_GET['css']) && $_GET['css' ] == 'selector')
{
	$option = get_option('WebINK_selectorcss');
	echo($option);
}


?>
