<?php

namespace classes;
use classes\db;
use DateTime;
use Exception;

class Like {
    private $postID = false;
    private $handle = false;
    private DateTime $timestamp;

    public function __construct($postID = false, $handle = false) {
        if ($postID && $handle) $this->load($postID, $handle);
    }

    public function setPostID($id) {
        if (!is_numeric($id) || $id <= 0) {
            throw new Exception("invalid post ID passed to Like::setPostID");
        }
        return $this->postID = $id;
    }
    public function getPostID() {
        return $this->postID;
    }

    public function setHandle($handle) {
        if (!is_string($handle) || empty($handle)) {
            throw new Exception("invalid handle passed to Like::setHandle");
        }
        return $this->handle = $handle;
    }
    public function getHandle() {
        return $this->handle;
    }

    public function setTimestamp($timestamp) {
        $this->timestamp = DateTime::createFromFormat('Y-m-d H:i:s', $timestamp);
        if (!$this->timestamp || $this->timestamp->format('Y-m-d H:i:s') !== $timestamp) {
            throw new Exception("invalid timestamp passed to Post::setTimestamp");
        }
        return true;
    }
    public function getTimestamp($format = "Y-m-d H:i:s") {
        return $this->timestamp->format($format);
    }

    public function load($postID, $handle) {
        if (!is_numeric($postID) || $postID <= 0) {
            throw new Exception("invalid post ID passed to Like::load");
        }
        if (!is_string($handle) || empty($handle)) {
            throw new Exception("invalid handle passed to Like::load");
        }
        $db = db::connect();
        $qLike = "SELECT * FROM `likes` WHERE `post_id` = {$postID} AND `handle` = '{$handle}' LIMIT 1";
        $rLike = $db->query($qLike);
        if ($rLike->num_rows !== 1) {
            throw new Exception("unable to find like record in Like::load");
        }
        $likeData = $rLike->fetch_assoc();
        return (
            $this->setPostID($likeData['post_id'])
            && $this->setHandle($likeData['handle'])
            && $this->setTimestamp($likeData['timestamp'])
        );
    }

    public function save() {
        $db = db::connect();
        $qSave = "INSERT INTO `likes` (`post_id`, `handle`)
            VALUES (
                ".$this->getPostID().",
                '".$db->real_escape_string($this->getHandle())."'
            )";
        if (!$db->query($qSave)) {
            throw new Exception("unable to insert like record in Like::save");
        }
        return true;
    }

    public function delete() {
        $db = db::connect();
        $qDelete = "DELETE FROM `likes` WHERE `post_id` = ".$this->getPostID()." AND `handle` = '".$this->getHandle()."' LIMIT 1";
        if (!$db->query($qDelete)) {
            throw new Exception("unable to delete like record in Like::delete");
        }
        return true;
    }

    public static function getCount($postID, $handle = false) {
        if (!is_numeric($postID) || $postID <= 0) {
            throw new Exception("invalid post ID passed to Like::getCount");
        }
        $db = db::connect();
        $qLikeCount = "SELECT COUNT(*) AS `count` FROM `likes` WHERE `post_id` = {$postID}".($handle ? " AND `handle` = '{$handle}'" : "");
        $rLikeCount = $db->query($qLikeCount);
        if ($rLikeCount->num_rows !== 1) {
            throw new Exception("unable to get like count in Like::getCount");
        }
        $likeCount = $rLikeCount->fetch_assoc();
        return (int)$likeCount['count'];
    }
}

?>