<?php
ini_set('display_errors',1);
ini_set('display_startup_errors',1);
error_reporting(-1);

echo download_file("26952d322532dc7d7f19cbb631fb65b9", "d003820ed66eee8fd37fb2eec3ffe375");

function download_file($feed_md5, $episode_md5){
	$podcasts = json_decode(file_get_contents('podcasts/podcast_data/podcasts.json'), true);
	
	foreach ($podcasts["podcasts"] as $podcast)
	{
		if (strcmp($podcast["md5"], $feed_md5) == 0)
		{
			$podcast_title = $podcast["name"];
			foreach ($podcast["episodes"] as $episode)
			{
				if (strcmp($episode["md5"], $episode_md5) == 0)
				{
					$episode_title = $episode["title"];
					$download_url =  $episode["download_url"];
					$raw_publish_date = strtotime($episode["publish_date"]);
					$publish_date = date('Ymd',$raw_publish_date);
					$extension = $episode["extension"];
					
					$directory = 'podcasts/podcast_files/' . make_url_safe($podcast_title) . '/';
					if (!is_dir($directory)) {
						// dir doesn't exist, make it
						mkdir($directory);
					}
					
					$filename = $publish_date . make_url_safe($episode_title);
					
					$filepath = $directory . $filename . '.' . $extension;
					$podcast_file = file_get_contents($download_url);
					file_put_contents($filepath, $podcast_file);
					
					return true;
				}
			}
		}
	}
	return false;
}

function make_url_safe($string)
{
	$safe = preg_replace('/^-+|-+$/', '', strtolower(preg_replace('/[^a-zA-Z0-9]+/', '_', $string)));
	return $safe;
}

?>