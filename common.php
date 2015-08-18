<?php
// http://feed.thisamericanlife.org/talpodcast?format=xml
// http://feeds.wnyc.org/radiolab
// http://resources.h9blog.com/podcast-feeds/h9cast

ini_set('display_errors',1);
ini_set('display_startup_errors',1);
error_reporting(-1);

// Allows better downloading
ini_set("memory_limit","512M");

$root_path = getcwd() . '/';
$podcast_file_path = $root_path . 'podcasts/podcast_files/';
$podcast_data_path = $root_path . 'podcasts/podcast_data/';
$podcasts_head_file = $root_path . 'podcasts/podcasts.json';
$episode_limit = 30;

// Loading in simplepie
include_once($root_path . '/libs/simplepie/autoloader.php');

// Loading in the podcast manager libraries.
include_once($root_path . '/libs/podcast_manager/php/fetch_podcasts.php');
include_once($root_path . '/libs/podcast_manager/php/manage_podcast_files.php');

// Check if the podcast directories exist. If not, create them
if (!is_dir($root_path . 'podcasts/')) {
	mkdir($root_path . 'podcasts/');
}
if (!is_dir($podcast_file_path)) {
	mkdir($podcast_file_path);
}
if (!is_dir($podcast_data_path)) {
	mkdir($podcast_data_path);
}
if (!is_dir($root_path . 'logs/')) {
	mkdir($root_path . 'logs/');
}
if (!is_dir($root_path . 'cache/')) {
	mkdir($root_path . 'cache/');
}

if(isset($_POST['action']) && !empty($_POST['action'])) {
    $action = $_POST['action'];
    switch($action) {
		case 'download_episode' : download_episode($_POST['podcast_md5'], $_POST['episode_md5']);break;
		case 'update_feed' : update_feed($_POST['feed_url']);break;
		case 'update_all_feeds' : update_all_feeds();break;
		case 'save_time' : save_time($_POST['podcast_md5'], $_POST['episode_md5'], $_POST['time']);break;
		case 'finished_episode' : finished_episode($_POST['podcast_md5'], $_POST['episode_md5']);break;
		case 'set_status' : set_status($_POST['podcast_md5'], $_POST['episode_md5'], $_POST['status']);break;
		case 'delete_episode' : delete_episode($_POST['podcast_md5'], $_POST['episode_md5']);break;
	}
}

// Log the event in the logs folder
function log_event($event)
{
	$date = date("Y-m-d");
	$time = date("H:i:s");
	$log_file_path = $GLOBALS['root_path'] . 'logs/' . $date . '.txt';
	$log_message = $time . " - " . $event;
	file_put_contents($log_file_path, $log_message . "\n", FILE_APPEND);
}

// Loads in a simplepie object to parse RSS feeds
function load_feed_xml($url)
{
	$feed = new SimplePie();
	$feed->set_feed_url($url);
	$feed->init();
	$feed->handle_content_type();
	$feed->set_cache_location($GLOBALS['root_path'] . '/cache');
	
	return $feed;
}

// Loads in the JSON data
function load_json_data($local_path)
{
	$json_data = json_decode(file_get_contents($local_path), true);
	return $json_data;
}

// Saves the data as a JSON file
function save_json_data($json_data, $path)
{
	try
	{
		file_put_contents($path, json_encode($json_data));
		log_event("Save succeeded: " . $path);
	}
	catch (Exception $e)
	{
		log_event("Save to failed: " . $path . " ERROR: " . $e);
	}
}
?>