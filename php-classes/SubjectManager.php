<?php

  class SubjectManager {
    private $pdo;

    ########################################################
    # just a constructor
    # $pdo -> class pdo that I need to interact with database [PdoManager]
    ########################################################
    public function __construct($pdo) {
      $this->pdo = $pdo;
    }

    ########################################################
    # adds new subject to the database
    # $name -> name of the new subject [string]
    ########################################################
    public function add($name) {
      $name = htmlentities($name, ENT_QUOTES, 'utf-8');

      if (empty($name))
        return "Name is required but it's empty.";

      if (!is_string($name))
        return "Name is not a valid text."; 

      $sql = "SELECT id FROM subject WHERE name='$name'";
      $valid_name_response = $this->pdo->sqlTable($sql);
      
      echo count($valid_name_response);

      if (count($valid_name_response) != 0)
        return "Subject with that name already exists.";

      $sql = "INSERT INTO subject VALUES (NULL, '$name')";
      $response = $this->pdo->sqlQuery($sql);

      if ($response > 0) {
        return "Good.";
      } else {
        return "Subject failed to be added.";
      }
    }

    #########################################################
    # deletes subject in the database
    # $id -> id of the subject that you want to delete [number]
    #########################################################
    public function delete($id) {
      if (empty($id))
        return "Id is required but it's empty.";

      if (!is_numeric($id))
        return "Id is not a valid number.";

      $sql = "DELETE FROM subject WHERE id='$id'";
      $response = $this->pdo->sqlQuery($sql);

      if ($response > 0) {
        return "Good.";
      } else {
        return "Subject failed to be deleted.";
      }
    }

    ########################################################
    # edits name of the subject in the database
    # $id -> id of the subject that you want to edit [number]
    # $name -> new name of the subject [string]
    ########################################################
    public function editName($id, $name) {
      $name = htmlentities($name, ENT_QUOTES, 'utf-8');

      if (empty($id))
        return "Id is required but it's empty.";

      if (!is_numeric($id))
        return "Id is not a valid number.";

      if (empty($name))
        return "Name is required but it's empty.";

      if (!is_string($name))
        return "Name is not a valid text.";

      $sql = "SELECT id FROM subject WHERE name='$name'";
      $valid_name_response = $this->pdo->sqlTable($sql);
  
      if (count($valid_name_response) != 0)
        return "Subject with that name already exists.";

      $sql = "UPDATE subject SET name='$name' WHERE id='$id'";
      $response = $this->pdo->sqlQuery($sql);

      if ($response > 0) {
        return "Good.";
      } else {
        return "Subject name failed to be changed.";
      }
    }

    ########################################################
    # gets all subjects from the database and
    # returns them in an array of subjects
    ########################################################
    public function getAll() {
      $sql = "SELECT * FROM subject";
      $response = $this->pdo->sqlTable($sql);

      return $response;
    }

    ########################################################
    # gets one subject from the database
    # $id -> id of the subject that you want to get [number]
    ########################################################
    public function getById($id) {
      if (empty($id))
        return "Id is required but it's empty.";

      if(!is_numeric($id)) 
        return "Id is not a valid number.";

      $sql = "SELECT * FROM subject WHERE id='$id'";
      $response = $this->pdo->sqlRecord($sql);
  
      return $response;
    }

    ########################################################
    # gets one subject from the database 
    # $name -> name of the subject that you want to get [string]
    ########################################################
    public function getByName($name) {
      $name = htmlentities($name, ENT_QUOTES, 'utf-8');

      if (empty($name))
        return "Name is required but it's empty.";

      if (!is_string($name))
        return "Name is not a valid text.";

      $sql = "SELECT * FROM subject WHERE name='$name'";
      $response = $this->pdo->sqlRecord($sql);

      return $response;
    }
  }