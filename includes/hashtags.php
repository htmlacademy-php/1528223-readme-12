<?php

$hashtags = [];
foreach ($posts as $post) {
    if ($post['hashtags'] !== null) {
        $id = $post['post_id'];
        if (strpos($post['hashtags'], ',') !== false) {
            $hashtags[$id] = explode(',', $post['hashtags']);
        } else {
            $hashtags[$id][0] = $post['hashtags'];
        }
    }
}
