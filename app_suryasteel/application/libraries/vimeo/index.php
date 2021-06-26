<?php
require 'vendor/autoload.php';
use Vimeo\Vimeo;

$client = new Vimeo("e96900a0e6be5d99a731a80a45d2be237b6bfdd0", "U8/UAWqyu0KMGb/3CBnsT327zxTL/8GdnzQ29R9+fStFpO6ROmUrpOQlba/4ZAcfsnSYB+pERY6pScoAeICSxe5YPw2YsYIoqy0rJfR/qFYx7Cbs1L0J/6897JwT44Or", "ee82dd65585d1bf106fc1114133838b6");

$response = $client->request('/tutorial', array(), 'GET');
// print_r($response);


// Instantiate the library with your client id, secret and access token (pulled from dev site)
		$lib = new Vimeo("e96900a0e6be5d99a731a80a45d2be237b6bfdd0", "U8/UAWqyu0KMGb/3CBnsT327zxTL/8GdnzQ29R9+fStFpO6ROmUrpOQlba/4ZAcfsnSYB+pERY6pScoAeICSxe5YPw2YsYIoqy0rJfR/qFYx7Cbs1L0J/6897JwT44Or", "ee82dd65585d1bf106fc1114133838b6");
		// Create a variable with a hard coded path to your file system
		$file_name = 'v1.mp4';
		echo 'Uploading: ' . $file_name . "\n";
		try {
		    // Upload the file and include the video title and description.
		    $uri = $lib->upload($file_name, array(
		        'name' => 'Vimeo API SDK test upload',
		        'description' => "This video was uploaded through the Vimeo API's PHP SDK."
		    ));
		    // Get the metadata response from the upload and log out the Vimeo.com url
		    $video_data = $lib->request($uri . '?fields=link');
		    echo '"' . $file_name . ' has been uploaded to ' . $video_data['body']['link'] . "\n";
		    // Make an API call to edit the title and description of the video.
		    $lib->request($uri, array(
		        'name' => 'Vimeo API SDK test edit',
		        'description' => "This video was edited through the Vimeo API's PHP SDK.",
		    ), 'PATCH');
		    echo 'The title and description for ' . $uri . ' has been edited.' . "\n";
		    // Make an API call to see if the video is finished transcoding.
		    $video_data = $lib->request($uri . '?fields=transcode.status');
		    echo 'The transcode status for ' . $uri . ' is: ' . $video_data['body']['transcode']['status'] . "\n";
		} catch (VimeoUploadException $e) {
		    // We may have had an error. We can't resolve it here necessarily, so report it to the user.
		    echo 'Error uploading ' . $file_name . "\n";
		    echo 'Server reported: ' . $e->getMessage() . "\n";
		} catch (VimeoRequestException $e) {
		    echo 'There was an error making the request.' . "\n";
		    echo 'Server reported: ' . $e->getMessage() . "\n";
		}