<?php

use classes\db;
use classes\Like;

class Post {
    private $id = false;
    private $handle = false;
    private $geolocation = false;
    private $content = false;
    private $numLikes = false;
    private DateTime $dateCreated;
    private DateTime $dateModified;

    public function __construct($data = false) {
        if ($data) {
            if (is_numeric($data) && $data > 0) {
                $this->load($data);
            } else if (is_array($data)) {
                $this->populate($data);
            }
        }
    }

    public function setID($id) {
        if (!is_numeric($id) || $id <= 0) {
            throw new Exception("invalid ID");
        }
        return $this->id = (int)$id;
    }
    public function getID() {
        return $this->id;
    }

    public function setHandle($handle) {
        if (!is_string($handle) || empty($handle)) {
            throw new Exception("invalid handle passed to Post::setHandle");
        }
        return $this->handle = $handle;
    }
    public function getHandle() {
        return $this->handle;
    }

    public function setGeolocation($lat, $lng) {
        if ($lat < -90.0 || $lat > 90.0)
            throw new Exception("invalid latitude passed to Post::setGeolocation");
        if ($lng < -180.0 || $lng > 180.0)
            throw new Exception("invalid longitude passed to Post::setGeolocation");
        return $this->geolocation = array(
            'lat' => (float)$lat,
            'lng' => (float)$lng
        );
    }
    public function getGeolocation() {
        return $this->geolocation;
    }
    private function parseGeolocation($geom) {
        $point = unpack('x4/corder/Ltype/dlat/dlon', $geom);
        return $this->setGeolocation($point['lat'], $point['lon']);
    }

    public function setContent($content) {
        return $this->content = trim($content);
    }
    public function getContent() {
        return $this->content;
    }

    public function getNumLikes() {
        if ($this->numLikes === false) {
            $this->numLikes = Like::getCount($this->getID());
        }
        return $this->numLikes;
    }

    public function setDateCreated($date) {
        $this->dateCreated = DateTime::createFromFormat('Y-m-d H:i:s', $date);
        if (!$this->dateCreated || $this->dateCreated->format('Y-m-d H:i:s') !== $date) {
            throw new Exception("invalid date passed to Post::setDateCreated");
        }
        return true;
    }
    public function getDateCreated($format = "Y-m-d H:i:s") {
        return $this->dateCreated->format($format);
    }

    public function setDateModified($date) {
        $this->dateModified = DateTime::createFromFormat('Y-m-d H:i:s', $date);
        if (!$this->dateModified || $this->dateModified->format('Y-m-d H:i:s') !== $date) {
            throw new Exception("invalid date passed to Post::setDateModified");
        }
        return true;
    }
    public function getDateModified($format = "Y-m-d H:i:s") {
        return $this->dateModified->format($format);
    }

    private function populate($row) {
        return (
            $this->setID($row['id'])
            && $this->setHandle($row['handle'])
            && $this->parseGeolocation($row['geolocation'])
            && $this->setContent($row['content'])
            && $this->setDateCreated($row['date_created'])
            && $this->setDateModified($row['date_modified'])
        );
    }

    public function load($id) {
        if (!is_numeric($id) || $id <= 0)
            throw new Exception("invalid ID passed to Post::load");
        $db = db::connect();
        $qLoad = "SELECT * FROM `posts` WHERE `id` = {$id} LIMIT 1";
        $rLoad = $db->query($qLoad);
        if ($rLoad->num_rows !== 1)
            throw new Exception("unable to find post matching the ID passed to Post::load");
        $postData = $rLoad->fetch_assoc();
        return $this->populate($postData);
    }

    private function insert() {
        $geolocation = $this->getGeolocation();
        if (!$geolocation)
            throw new Exception("Post::insert() requires a valid geolocation");

        $db = db::connect();
        $qInsert = "INSERT INTO `posts` (`handle`, `geolocation`, `content`)
            VALUES (
                '".$db->real_escape_string($this->getHandle())."',
                ST_GeomFromText('POINT(".$geolocation['lng']." ".$geolocation['lat'].")', 4326),
                '".$db->real_escape_string($this->getContent())."'
            )";
        if (!$db->query($qInsert))
            throw new Exception("unable to insert post in Post::insert");

        $this->setID($db->insert_id);
        return true;
    }
    private function update() {
        $db = db::connect();
        $qUpdate = "UPDATE `posts` SET `content` = '".$db->real_escape_string($this->getContent())."'
            WHERE `id` = ".$this->getID()." LIMIT 1";
        if (!$db->query($qUpdate))
            throw new Exception("unable to update content in Post::update");
        return true;
    }
    public function save() {
        $id = $this->getID();
        if ($id && $id > 0)
            return $this->update();
        else
            return $this->insert();
    }

    public function delete() {
        $id = $this->getID();
        if (!$id)
            throw new Exception("no post data found to delete in Post::delete");
        $db = db::connect();
        $qDelete = "DELETE FROM `posts` WHERE `id` = {$id} LIMIT 1";
        if (!$db->query($qDelete))
            throw new Exception("unable to delete post in Post::delete");
        return true;
    }

    public static function getAll() {
        $posts = array();

        $db = db::connect();
        $qPosts = "SELECT * FROM `posts`";
        $rPosts = $db->query($qPosts);
        while ($postData = $rPosts->fetch_assoc()) {
            $posts[] = new self($postData);
        }

        return $posts;
    }
}

?>