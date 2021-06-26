<?php
require 'vendor/autoload.php';
use Vimeo\Vimeo;

$client = new Vimeo("e96900a0e6be5d99a731a80a45d2be237b6bfdd0", "U8/UAWqyu0KMGb/3CBnsT327zxTL/8GdnzQ29R9+fStFpO6ROmUrpOQlba/4ZAcfsnSYB+pERY6pScoAeICSxe5YPw2YsYIoqy0rJfR/qFYx7Cbs1L0J/6897JwT44Or", "c5993555d2ee3830a75c0c2b3f191c53");


// $uri = 	$lib->upload($file_name, array(
// 	        'name' => 'Vimeo API SDK test upload',
// 	        'description' => "This video was uploaded through the Vimeo API's PHP SDK."
// 	    ));

$uri='/videos/385664747';

$res = $client->request($uri, array(), 'DELETE');

print_r($res);