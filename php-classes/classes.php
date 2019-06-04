<?php
  
  class CLASSES {
    private $pdo;

    public function __construct($pdo) {
      $this->pdo = $pdo;
    }

    /*this function adds the class*/
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

    /*this functions deletes the class*/
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

    /*this function edits just name of the element of that id*/
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

      $class_valid_name = $this->getByName($name);

      if (is_string($class_valid_name))
        return "Server Error: ".$class_valid_name.".";
  
      if (count($class_valid_name) != 0)
        return "Class with that name already exists.";

      $sql = "UPDATE class SET name='$name' WHERE id='$id'";
      $response = $this->pdo->sql_query($sql);

      if ($response > 0) {
        return "Good.";
      } else {
        return "Class name failed to be changed.";
      }
    }

    /*this function edits just description of the element of that id*/
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

    /*this function returns all classes as a array*/
    public function getAll() {
      $sql = "SELECT * FROM class";
      $response = $this->pdo->sql_table($sql);

      return $response;
    }

    /*this function return an class row that have certain id*/
    public function getById($id) {
      if (empty($id))
        return "Id is required but it's empty.";

      if(!is_numeric($id) || !is_int($id)) 
        return "Id is not a valid number.";

      $sql = "SELECT * FROM class WHERE id='$id'";
      $response = $this->pdo->sql_record($sql);
  
      return $response;
    }

    /*this function return an array of classes that have certain name it return an array pf classes*/
    public function getByName($name) {
      $name = htmlentities($name, ENT_QUOTES, 'utf-8');

      if (empty($name))
        return "Name is required but it's empty.";

      if (!is_string($name))
        return "Name is not a valid text.";

      $sql = "SELECT * FROM class WHERE name='$name'";
      $response = $this->pdo->sql_table($sql);

      return $response;
    }
  }