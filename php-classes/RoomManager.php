<?php

  class RoomManager {
    private $pdo;

    ########################################################
    # just a constructor
    # $pdo -> class pdo that I need to interact with database [PdoManager]
    ########################################################
    public function __construct($pdo) {
      $this->pdo = $pdo;
    }

    ########################################################
    # adds new room to the database
    # $name -> name of the new room [string]
    ########################################################
    public function add($name) {
      $name = htmlentities($name, ENT_QUOTES, 'utf-8');

      if (empty($name))
        return "Name is required but it's empty.";

      if (!is_string($name))
        return "Name is not a valid text."; 

      $sql = "SELECT id FROM room WHERE name='$name'";
      $valid_name_response = $this->pdo->sqlTable($sql);
      
      if (count($valid_name_response) != 0)
        return "Room with that name already exists.";

      $sql = "INSERT INTO room VALUES (NULL, '$name')";
      $response = $this->pdo->sqlQuery($sql);

      if ($response > 0) {
        return "Good.";
      } else {
        return "Room failed to be added.";
      }
    }

    #########################################################
    # deletes room in the database
    # $id -> id of the room that you want to delete [number]
    #########################################################
    public function delete($id) {
      if (empty($id))
        return "Id is required but it's empty.";

      if (!is_int($id))
        return "Id is not a valid number.";

      $sql = "DELETE FROM room WHERE id='$id'";
      $response = $this->pdo->sqlQuery($sql);

      if ($response > 0) {
        return "Good.";
      } else {
        return "Room failed to be deleted.";
      }
    }

    ########################################################
    # edits name of the room in the database
    # $id -> id of the room that you want to edit [number]
    # $name -> new name of the room [string]
    ########################################################
    public function editName($id, $name) {
      $name = htmlentities($name, ENT_QUOTES, 'utf-8');

      if (empty($id))
        return "Id is required but it's empty.";

      if (!is_int($id))
        return "Id is not a valid number.";

      if (empty($name))
        return "Name is required but it's empty.";

      if (!is_string($name))
        return "Name is not a valid text.";

      $sql = "SELECT id FROM room WHERE name='$name'";
      $valid_name_response = $this->pdo->sqlTable($sql);
  
      if (count($valid_name_response) != 0)
        return "Room with that name already exists.";

      $sql = "UPDATE room SET name='$name' WHERE id='$id'";
      $response = $this->pdo->sqlQuery($sql);

      if ($response > 0) {
        return "Good.";
      } else {
        return "Room name failed to be changed.";
      }
    }

    ########################################################
    # gets all rooms from the database and
    # returns them in an array of rooms
    ########################################################
    public function getAll() {
      $sql = "SELECT * FROM room";
      $response = $this->pdo->sqlTable($sql);

      return $response;
    }

    ########################################################
    # gets one room from the database
    # $id -> id of the room that you want to get [number]
    ########################################################
    public function getById($id) {
      if (empty($id))
        return "Id is required but it's empty.";

      if(!is_numeric($id) || !is_int($id)) 
        return "Id is not a valid number.";

      $sql = "SELECT * FROM room WHERE id='$id'";
      $response = $this->pdo->sqlRecord($sql);
  
      return $response;
    }

    ########################################################
    # gets one room from the database 
    # $name -> name of the room that you want to get
    ########################################################
    public function getByName($name) {
      $name = htmlentities($name, ENT_QUOTES, 'utf-8');

      if (empty($name))
        return "Name is required but it's empty.";

      if (!is_string($name))
        return "Name is not a valid text.";

      $sql = "SELECT * FROM room WHERE name='$name'";
      $response = $this->pdo->sqlRecord($sql);

      return $response;
    }
  } 