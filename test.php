<?php
ini_set('display_errors',1);
ini_set('display_startup_errors',1);
error_reporting(-1);

include_once('common.php');
echo "Worked";

update_feed('http://feed.thisamericanlife.org/talpodcast?format=xml');

echo "complete";

?>