<?php

use classes\db;
use classes\Like;

class Account {
    private $handle = false;
    private $name = false;
    private $email = false;
    private $password = false;
    private DateTime $birthday;
    private $bio = "";
    private $dateModified = false;
    private DateTime $dateCreated;

    public function __construct($data = false) {
        if ($data) {
            if (is_string($data) && strlen($data) > 0) {
                $this->load($data);
            } else if (is_array($data)) {
                $this->populate($data);
            }
        }
    }

    public function setHandle($handle) {
        $handle = preg_replace("/[^A-Za-z0-9]/", "", $handle);
        if (strlen($handle) <= 7) {
            throw new Exception("Handle must be at least 7 characters in length.");
        }
        return $this->handle = $handle;
    }
    public function getHandle() {
        return $this->handle;
    }

    public function setName($name) {
        if (!is_string($name) || strlen($name) === 0) {
            throw new Exception("invalid name.");
        }
        return $this->name = $name;
    }
    public function getName() {
        return $this->name;
    }

    public function setEmail($email) {
        if (!is_string($email) || !filter_var($email, FILTER_VALIDATE_EMAIL)) {
            throw new Exception("invalid email");
        }
        return $this->email = trim(strtolower($email));
    }
    public function getEmail() {
        return $this->email;
    }
    private function isDuplicateEmail() {
        $db = db::connect();
        $qCheck = "SELECT `email` FROM `accounts` WHERE
            `email` = '".$db->real_escape_string($this->getEmail())."'";
        if ($this->getHandle()) {
            $qCheck .= " AND `handle` != '" . $this->getHandle() . "'";
        }
        $rCheck = $db->query($qCheck);
        return ($rCheck->num_rows > 0);
    }

    public function setPassword($password, $hash = true) {
        if (!is_string($password) || strlen($password) === 0) {
            throw new Exception("invalid password");
        }
        if ($hash)
            return $this->password = password_hash($password, PASSWORD_DEFAULT);
        else
            return $this->password = $password;
    }
    private function getPassword() {
        return $this->password;
    }
    public function verifyPassword($password) {
        return password_verify($password, $this->password);
    }

    public function setBirthday($date) {
        $this->birthday = DateTime::createFromFormat('Y-m-d', $date);
        if (!$this->birthday || $this->birthday->format('Y-m-d') !== $date) {
            throw new Exception("invalid date of birth");
        }

        // Make sure this user is at least 18 years old
        $today = new DateTime();
        $diff = $today->diff($this->birthday);
        if ($diff->y < 18) {
            throw new Exception("user is not 18 years old");
        }

        return true;
    }
    public function getBirthday($format = "Y-m-d") {
        return $this->birthday->format($format);
    }

    public function setBio($bio) {
        if (is_string($bio)) {
            return $this->bio = trim($bio);
        }
        return false;
    }
    public function getBio() { return $this->bio; }

    public function setDateModified($date) {
        return $this->dateModified = $date;
    }
    public function getDateModified() { return $this->dateModified; }

    public function setDateCreated($date) {
        $this->dateCreated = DateTime::createFromFormat('Y-m-d H:i:s', $date);
        if (!$this->dateCreated || $this->dateCreated->format('Y-m-d H:i:s') !== $date) {
            throw new Exception("invalid date created");
        }
        return true;
    }
    public function getDateCreated($format = "Y-m-d H:i:s") {
        return $this->dateCreated->format($format);
    }

    private function populate($row) {
        $this->setHandle($row['handle']);
        $this->setName($row['name']);
        $this->setEmail($row['email']);
        $this->setPassword($row['password'], false);
        $this->setBirthday($row['birthday']);
        $this->setBio($row['bio']);
        $this->setDateCreated($row['date_created']);
        $this->setDateModified($row['date_modified']);
        return true;
    }

    public function load($handle) {
        if (!is_string($handle) || empty($handle))
            throw new Exception("invalid handle passed to Account::load");

        $db = db::connect();
        $qLoad = "SELECT * FROM `accounts` WHERE `handle` = '{$handle}' LIMIT 1";
        $rLoad = $db->query($qLoad);
        if ($rLoad->num_rows !== 1)
            throw new Exception("unable to find user in Account::load");

        $accountData = $rLoad->fetch_assoc();
        $this->populate($accountData);
    }

    public function save() {
        if ($this->isDuplicateEmail()) {
            throw new Exception("Email already in use.");
        }
        $db = db::connect();
        $qSave = "INSERT INTO `accounts` (`handle`, `name`, `email`, `password`, `birthday`, `bio`)
            VALUES (
                '".$db->real_escape_string($this->getHandle())."',
                '".$db->real_escape_string($this->getName())."',
                '".$db->real_escape_string($this->getEmail())."',
                '".$this->getPassword()."',
                '".$db->real_escape_string($this->getBirthday())."',
                '".$db->real_escape_string($this->getBio())."'
            ) ON DUPLICATE KEY UPDATE
                `name` = '".$db->real_escape_string($this->getName())."',
                `email` = '".$db->real_escape_string($this->getEmail())."',
                `password` = '".$this->getPassword()."',
                `bio` = '".$db->real_escape_string($this->getBio())."'";
        if (!$db->query($qSave)) {
            throw new Exception("unable to insert account record");
        }
    }

    // Check if this user liked a specific post
    public function likedPost($postID) {
        return (Like::getCount($postID, $this->getHandle()) > 0);
    }

    public static function getByHandle($handle) {
        $handle = preg_replace("/[^A-Za-z0-9]/", "", $handle);
        $db = db::connect();
        $qAccount = "SELECT * FROM `accounts` WHERE `handle` = '{$handle}' LIMIT 1";
        $rAccount = $db->query($qAccount);
        if ($rAccount->num_rows === 0) {
            throw new Exception("account not found");
        }
        return new self($rAccount->fetch_assoc());
    }

    public static function getByEmail($email) {
        $db = db::connect();
        $qAccount = "SELECT * FROM `accounts` WHERE `email` = '{$email}' LIMIT 1";
        $rAccount = $db->query($qAccount);
        if ($rAccount->num_rows === 0) {
            throw new Exception("account not found");
        }
        return new self($rAccount->fetch_assoc());
    }
}

?>