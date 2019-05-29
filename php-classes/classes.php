<?php
  
  class CLASSES {
    /*this function adds the class*/
    public function add($pdo, $name, $description) {
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

      $class_valid_name = $this->getByName($pdo, $name);

      if (is_string($class_valid_name))
        return "Server Error: ".$class_valid_name.".";

      if (count($class_valid_name) != 0)
        return "Class with that name already exists.";

      $sql = "INSERT INTO class VALUES (NULL, '$name', '$description')";
      $response = $pdo->sql_query($sql);

      if ($response > 0) {
        return "Good.";
      } else {
        return "Class failed to be added.";
      }
    }

    /*this function edits just name of the element of that id*/
    public function editName($pdo, $id, $name) {
      $name = htmlentities($name, ENT_QUOTES, 'utf-8');

      if (empty($id))
        return "Id is required but it's empty.";

      if (!is_int($id))
        return "Id is not a valid number.";

      if (empty($name))
        return "Name is required but it's empty.";

      if (!is_string($name))
        return "Name is not a valid text.";

      $class_valid_name = $this->getByName($pdo, $name);

      if (is_string($class_valid_name))
        return "Server Error: ".$class_valid_name.".";
  
      if (count($class_valid_name) != 0)
        return "Class with that name already exists.";

      $sql = "UPDATE class SET name='$name' WHERE id='$id'";
      $response = $pdo->sql_query($sql);

      if ($response > 0) {
        return "Good.";
      } else {
        return "Class name failed to be changed.";
      }
    }

    /*this function returns all classes as a array*/
    public function getAll($pdo) {
      $sql = "SELECT * FROM class";
      $response = $pdo->sql_table($sql);

      return $response;
    }

    /*this function return an class row that have certain id*/
    public function getById($pdo, $id) {
      if (empty($id))
        return "Id is required but it's empty.";

      if(!is_numeric($id) || !is_int($id)) 
        return "Id is not a valid number.";

      $sql = "SELECT * FROM class WHERE id='$id'";
      $response = $pdo->sql_record($sql);
  
      return $response;
    }

    /*this function return an array of classes that have certain name it return an array pf classes*/
    public function getByName($pdo, $name) {
      $name = htmlentities($name, ENT_QUOTES, 'utf-8');

      if (empty($name))
        return "Name is required but it's empty.";

      if (!is_string($name))
        return "Name is not a valid text.";

      $sql = "SELECT * FROM class WHERE name='$name'";
      $response = $pdo->sql_table($sql);

      return $response;
    }
  }