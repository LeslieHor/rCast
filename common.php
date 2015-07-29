<?php
ini_set('display_errors',1);
ini_set('display_startup_errors',1);
error_reporting(-1);

$root_path = getcwd();

// Loading in simplepie
include_once($root_path . '/libs/simplepie/autoloader.php');

// Save an xml object as an xml file in the location requested
function save_xml($xml, $path){
	$dom = new DOMDocument('1.0');
	$dom->preserveWhiteSpace = false;
	$dom->formatOutput = true;
	$dom->loadXML($xml->asXML());
	$new_xml = $dom->saveXML();
	
	$my_file = ($GLOBALS['root_path'] . $path);
	$fh = fopen($my_file, 'w') or die("can't open file");
	fwrite($fh, $new_xml);
	fclose($fh);
}

// Loads in a simplepie object to parse rss feeds
function load_feed_xml($url){
	$feed = new SimplePie();
	$feed->set_feed_url($url);
	$feed->init();
	$feed->handle_content_type();
	$feed->set_cache_location($GLOBALS['root_path'] . '/cache');
	
	return $feed;
}

// Load in a plain XML file
function load_xml($path){
	$feed = file_get_contents($GLOBALS['root_path'] . $path);
	$xml = simplexml_load_string($feed);
	return $xml;
}

?>