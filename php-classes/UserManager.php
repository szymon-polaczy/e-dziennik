<?php

  class UserManager {
    private $pdo;

    ########################################################
    # just a constructor
    # $pdo -> class pdo that I need to interact with database [PdoManager]
    ########################################################
    public function __construct($pdo) {
      $this->pdo = $pdo;
    }

    //name | surname | email | password | permissions
    //dla nauczyciela - id_room
    //dla ucznia - id_class | birthdate
    public function add(){
      //czy któraś zmienna nie jest pusta
      //czy któraś zmienna jest złego typu
      //dodawanie użytkownika
        //dodawanie admina
          //tylko id_user
        //dodawanie nauczyciela
          //id_user
          //id_room
        //dodawanie ucznia
          //id_user
          //id_class
          //birthdate
    }

    #########################################################
    # deletes user in the database
    # $id -> id of the user that you want to delete [number]
    #########################################################
    public function delete($id) {
      if (empty($id))
        return "Id is required but it's empty.";

      if (!is_numeric($id))
        return "Id is not a valid number.";

      $sql = "SELECT permissions FROM user WHERE id='$id'";
      $permissions = $this->pdo->sqlValue($sql);

      if ($permissions == 'a') {
        $sql = "DELETE FROM administrator WHERE id_user='$id'";
        $response = $this->pdo->sqlQuery($sql);

        if ($response == 0)
          return "Administrator failed to be deleted.";
        
      } else if ($permissions == 't') {
        $sql = "DELETE FROM teacher WHERE id_user='$id'";
        $response = $this->pdo->sqlQuery($sql);

        if ($response == 0)
          return "Teacher failed to be deleted.";

      } else if ($permissions == 's') {
        $sql = "DELETE FROM student WHERE id_user='$id'";
        $response = $this->pdo->sqlQuery($sql);

        if ($response == 0)
          return "Student failed to be deleted.";
      }

      $sql = "DELETE FROM user WHERE id='$id'";
      $response = $this->pdo->sqlQuery($sql);

      if ($response > 0) {
        return "Good.";
      } else {
        return "User failed to be deleted.";
      }
    }
  }