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

      $sql = "INSERT INTO class VALUES (NULL, '$name', '$description')";
      $response = $pdo->sql_query($sql);

      if ($response > 0) {
        return 0;
      } else {
        return "Class failed to be added.";
      }
    } 
  }