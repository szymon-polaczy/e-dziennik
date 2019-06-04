<?php
  
  class ClassManager {
    private $pdo;

    ########################################################
    # just a constructor
    # $pdo -> class pdo that I need to interact with database [PDO_DB]
    ########################################################
    public function __construct($pdo) {
      $this->pdo = $pdo;
    }

    #########################################################
    # adds new class to the database
    # $name -> name of the new class [string]
    # $description -> description of the new class [string]
    #########################################################
    public function add($name, $description) {
      $name = htmlentities($name, ENT_QUOTES, 'utf-8');
      $description = htmlentities($description, ENT_QUOTES, 'utf-8');

      if (empty($name))
        return "Name is required but it's empty.";

      if (empty($description))
        return "Description is required but it's empty.";

      if (!is_string($name))
        return "Name is not a valid text.";

      if (!is_string($description))
        return "Description is not a valid text.";

      $class_valid_name = $this->getByName($name);

      if (is_string($class_valid_name))
        return "Server Error: ".$class_valid_name.".";

      if (count($class_valid_name) != 0)
        return "Class with that name already exists.";

      $sql = "INSERT INTO class VALUES (NULL, '$name', '$description')";
      $response = $this->pdo->sql_query($sql);

      if ($response > 0) {
        return "Good.";
      } else {
        return "Class failed to be added.";
      }
    }

    #########################################################
    # deletes class in the database
    # $id -> id of the class that you want to delete [number]
    #########################################################
    public function delete($id) {
      if (empty($id))
        return "Id is required but it's empty.";

      if (!is_int($id))
        return "Id is not a valid number.";

      $sql = "DELETE FROM class WHERE id='$id'";
      $response = $this->pdo->sql_query($sql);

      if ($response > 0) {
        return "Good.";
      } else {
        return "Class failed to be deleted.";
      }
    }

    ########################################################
    # edits name of the class in the database
    # $id -> id of the class that you want to edit [number]
    # $name -> new name of the class [string]
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

      $sql = "SELECT id FROM class WHERE name='$name'";
      $valid_name_response = $this->pdo->sql_table($sql);
  
      if (count($valid_name_response) != 0)
        return "Class with that name already exists.";

      $sql = "UPDATE class SET name='$name' WHERE id='$id'";
      $response = $this->pdo->sql_query($sql);

      if ($response > 0) {
        return "Good.";
      } else {
        return "Class name failed to be changed.";
      }
    }

    ########################################################
    # edits description of the class in the database
    # $id -> id of the class that you want to edit [number]
    # $description -> new description of the class [string]
    ########################################################
    public function editDescription($id, $description) {
      $description = htmlentities($description, ENT_QUOTES, 'utf-8');

      if (empty($id))
        return "Id is required but it's empty.";

      if (!is_int($id))
        return "Id is not a valid number.";

      if (empty($description))
        return "Description is required but it's empty.";

      if (!is_string($description))
        return "Description is not a valid text.";

      $sql = "UPDATE class SET description='$description' WHERE id='$id'";
      $response = $this->pdo->sql_query($sql);

      if ($response > 0) {
        return "Good.";
      } else {
        return "Class description failed to be changed.";
      }
    }

    ########################################################
    # gets all classes from the database and
    # returns them in an array of classes
    ########################################################
    public function getAll() {
      $sql = "SELECT * FROM class";
      $response = $this->pdo->sql_table($sql);

      return $response;
    }

    ########################################################
    # gets one class from the database
    # $id -> id of the class that you want to get [number]
    ########################################################
    public function getById($id) {
      if (empty($id))
        return "Id is required but it's empty.";

      if(!is_numeric($id) || !is_int($id)) 
        return "Id is not a valid number.";

      $sql = "SELECT * FROM class WHERE id='$id'";
      $response = $this->pdo->sql_record($sql);
  
      return $response;
    }
    
    ########################################################
    # gets one class from the database 
    # $name -> name of the class that you want to get
    ########################################################
    public function getByName($name) {
      $name = htmlentities($name, ENT_QUOTES, 'utf-8');

      if (empty($name))
        return "Name is required but it's empty.";

      if (!is_string($name))
        return "Name is not a valid text.";

      $sql = "SELECT * FROM class WHERE name='$name'";
      $response = $this->pdo->sql_record($sql);

      return $response;
    }
  }