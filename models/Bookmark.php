<?php

class Bookmark
{
    private $id;
    private $url;
    private $title;
    private $dateAdded;
    private $dbConnection;
    private $dbTable = 'bookmark';

    public function __construct($dbConnection)
    {
        $this->dbConnection = $dbConnection;
    }

    public function getId(){
        return $this->id;
    }
    public function getUrl(){
        return $this->url;
    }
    public function getDateAdded(){
        return $this->dateAdded;
    }
    public function getTitle(){
        return $this->title;
    }

    public function setId($id){
        $this->id = $id;
    }
    public function setUrl($url){
        $this->url = $url;
    }
    public function setDateAdded($dateAdded){
        $this->dateAdded = $dateAdded;
    }
    public function setTitle($title){
        $this->title = $title;
    }

    public function create()
    {
        $query = "INSERT INTO " . $this->dbTable . "(url, date_added, title) VALUES(:url, now(), :title);";
        $stmt = $this->dbConnection->prepare($query);
        $stmt->bindParam(":url", $this->url);
        $stmt->bindParam(":title", $this->title);
        if ($stmt->execute()) {
            return true;
        }
        // print an error message
        printf("Error: %s", $stmt->error);
        return false;
    }

    public function readOne()
    {
        $query = "SELECT * FROM " . $this->dbTable . " WHERE id=:id";
        $stmt = $this->dbConnection->prepare($query);
        $stmt->bindParam(":id", $this->id);
        if ($stmt->execute() && $stmt->rowCount() == 1) {
            $result = $stmt->fetch(PDO::FETCH_OBJ);
            $this->id = $result->id;
            $this->title = $result->title;
            $this->url = $result->url;
            $this->dateAdded = $result->date_added;

            return true;
        }
        return false;
    }

    public function readAll()
    {
        $query = "SELECT * FROM " . $this->dbTable;
        $stmt = $this->dbConnection->prepare($query);
        if ($stmt->execute() && $stmt->rowCount() > 0) {
            return $stmt->fetchAll(PDO::FETCH_ASSOC);
        }
        return [];
    }

    public function update()
    {
        $query = "UPDATE " . $this->dbTable . " SET title=:title WHERE id=:id";
        $stmt = $this->dbConnection->prepare($query);
        $stmt->bindParam(":title", $this->title);
        $stmt->bindParam(":id", $this->id);
        if ($stmt->execute() && $stmt->rowCount() ==1) {
            return true;
        }
        return false;
    }

    public function delete()
    {
        $query = "DELETE FROM " . $this->dbTable . " WHERE id=:id";
        $stmt = $this->dbConnection->prepare($query);
        $stmt->bindParam(":id", $this->id);
        if ($stmt->execute() && $stmt->rowCount() ==1) {
            return true;
        }
        return false;
    }
}
