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

    ########################################################
    # adds new user to the database
    # $user -> array of values that are needed for the user (and admin) [array]
    #   $user['name'] -> new user's name [string]
    #   $user['surname'] -> new user's surname [string]
    #   $user['email'] -> new user's email [string]
    #   $user['password'] -> new user's password [string]
    #   $user['permissions'] -> new user's permissions [string]
    # $teacher -> additional array of values that are needed only for the teacher [array]
    #   $teacher['id_room'] -> new teacher's id_room - id of teacher classroom [number]
    # $student -> additional array of values that are needed only for the student [array]
    #   $student['id_class'] -> new student's id_class - id of student class [number]
    #   $student['birthdate'] -> new student's birthdate [date]
    ########################################################
    public function add($user, $teacher = NULL, $student = NULL){

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

      $sql = "SELECT id FROM user WHERE id='$id'";
      $response = $this->pdo->sqlTable($sql);

      if (count($response) == 0)
        return "There is no user with that id.";

      $sql = "SELECT permissions FROM user WHERE id='$id'";
      $permissions = $this->pdo->sqlValue($sql);

      $who = array("a" => array('administrator', 'Administrator'), 
                   "t" => array('teacher', 'Teacher'),
                   "s" => array('student', 'Student'));

      $sql = "DELETE FROM ".$who[$permissions][0]." WHERE id_user='$id'";
      $response = $this->pdo->sqlQuery($sql);

      if ($response == 0)
        return $who[$permissions][1]." failed to be deleted.";

      $sql = "DELETE FROM user WHERE id='$id'";
      $response = $this->pdo->sqlQuery($sql);

      if ($response > 0) {
        return "Good.";
      } else {
        return "User failed to be deleted.";
      }
    }
  }