<?php

$hashtags = [];
foreach ($posts as $key => $val) {
	if ($val['hashtags'] !== NULL) {
		$id = $val['post_id'];
		if (strpos($val['hashtags'], ',') !== FALSE) {
			$hashtags[$id] = explode(',', $val['hashtags']);
		} else {
			$hashtags[$id][0] = $val['hashtags'];
		}
	}
}

?>
