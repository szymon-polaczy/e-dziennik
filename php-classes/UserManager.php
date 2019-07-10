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
      //Checking if user variables aren't empty
      if (empty($user))
        return "User values array is empty.";

      if (empty($user['name']))
        return "User name is empty.";

      if (empty($user['surname']))
        return "User surname is empty.";

      if (empty($user['email']))
        return "User email is empty.";

      if (empty($user['password']))
        return "User password is empty.";

      if (empty($user['permissions']))
        return "User permissions is empty.";

      //Checking if user variables have good type
      if (!is_string($user['name']))
        return "User name is not a valid text.";

      if (!is_string($user['surname']))
        return "User surname is not a valid text.";

      if (!is_string($user['email']))
        return "User email is not a valid text.";

      if (!is_string($user['password']))
        return "User password is not a valid text.";

      if (!is_string($user['permissions']))
        return "User permissions is not a valid text.";

      //I check if user email has correct format
      if (!filter_var($user['email'], FILTER_VALIDATE_EMAIL))
        return "Email format invalid.";

      //Checking if there even is user with that email
      $sql = "SELECT id FROM user WHERE email='".$user['email']."'";
      $response = $this->pdo->sqlTable($sql);

      if (count($response) > 0)
        return "There already is a user with that email.";

      if ($user['permissions' == 't']) {
        //Checking if teacher variables aren't empty
        if (empty($teacher) || $teacher == NULL)
          return "Teacher values array is empty.";

        if (empty($teacher['id_room']))
          return "Id of teacher's classroom is empty.";

        //Checking if teacher variables have good type
        if (!is_numeric($teacher['id_room']))
          return "Id of teacher's classroom is not a valid number.";
      }
      
      if ($user['permissions'] == 's') {
        //Checking if student variables aren't empty
        if (empty($student) || $student == NULL)
          return "Student values array is empty.";

        if (empty($student['id_class']))
          return "Id of student's class is not a valid number.";

        if (empty($student['birthdate']))
          return "Student birthdate is empty.";

        //Checking if student variables have good type
        if (!is_numeric($student['id_class']))
          return "Id of student's class is not a valid number.";

        list($y, $m, $d) = explode("-", $student['birthdate']);
        if (!checkdate($m, $d, $y))
          return "Student birthdate date is invalid.";
      }

      //Password hashing
      $user['password'] = password_hash($user['password'], PASSWORD_DEFAULT);

      //Actuall user sql insert
      $sql = "INSERT INTO user VALUES(NULL, '".$user['name']."', '".$user['surname']."', '".$user['email']."', '".$user['password']."', '".$user['permissions']."')";
      $response = $this->pdo->sqlQuery($sql);

      if ($response == 0)
        return "User failed to be deleted.";

      $sql = "SELECT id FROM user WHERE email='".$user['email']."'";
      $id_user = $this->pdo->sqlValue($sql);

      if ($id_user == NULL)
        return "User was added but his is was failed to be selected.";

      //Adding all necessary dependencies
      if ($user['permissions'] == 'a') {
        $sql = "INSERT INTO administrator VALUES('$id_user')";
        $response = $this->pdo->sqlQuery($sql);

        if ($response > 0)
          return "Good.";
        else
          return "User was added but administrator was failed to be added.";
      } else if ($user['permissions'] == 't') {
        $sql = "INSERT INTO teacher VALUES('$id_user', ".$teacher['id_room'].")";
        $response = $this->pdo->sqlQuery($sql);

        if ($response > 0)
          return "Good.";
        else
          return "User was added but teacher was failed to be added.";
      } else if ($user['permissions'] == 's') {
        $sql = "INSERT INTO student VALUES('$id_user', '".$student['id_class']."', '".$student['birthdate']."')";
        $response = $this->pdo->sqlQuery($sql);

        if ($response > 0)
          return "Good.";
        else
          return "User was added but student was failed to be added.";
      }
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

    ########################################################
    # edits name of the user in the database
    # $id -> id of the user that you want to edit [number]
    # $name -> new name of the user [string]
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

      $sql = "SELECT id FROM user WHERE id='$id'";
      $response = $this->pdo->sqlRecord($sql);
    
      if (empty($response) || $response == NULL)
        return "There is no user with that id.";

      $sql = "UPDATE user SET name='$name' WHERE id='$id'";
      $response = $this->pdo->sqlQuery($sql);

      if ($response > 0) {
        return "Good.";
      } else {
        return "User name failed to be changed.";
      }
    }

    ########################################################
    # edits name of the user in the database
    # $id -> id of the user that you want to edit [number]
    # $name -> new name of the user [string]
    ########################################################
    public function editSurname($id, $surname) {
      $surname = htmlentities($surname, ENT_QUOTES, 'utf-8');

      if (empty($id))
        return "Id is required but it's empty.";

      if (!is_numeric($id))
        return "Id is not a valid number.";

      if (empty($surname))
        return "Surname is required but it's empty.";

      if (!is_string($surname))
        return "Surname is not a valid text.";

      $sql = "SELECT id FROM user WHERE id='$id'";
      $response = $this->pdo->sqlRecord($sql);
    
      if (empty($response) || $response == NULL)
        return "There is no user with that id.";

      $sql = "UPDATE user SET surname='$surname' WHERE id='$id'";
      $response = $this->pdo->sqlQuery($sql);

      if ($response > 0) {
        return "Good.";
      } else {
        return "User surname failed to be changed.";
      }
    }

    ########################################################
    # gets all users from the database and
    # returns them in an array of users
    ########################################################
    public function getAll() {
      $sql = "SELECT * FROM user";
      $response = $this->pdo->sqlTable($sql);

      if (empty($response) || $response == NULL || count($response) == 0)
        return "There are no users.";

      return $response;
    }

    ########################################################
    # gets one user from the database by their id
    # $id -> id of the user that you want to get [number]
    ########################################################
    public function getById($id) {
      if (empty($id))
        return "Id is required but it's empty.";

      if(!is_numeric($id)) 
        return "Id is not a valid number."; 

      $sql = "SELECT * FROM user WHERE id='$id'";
      $response = $this->pdo->sqlRecord($sql);

      if ($response == NULL || empty($response))
        return "There is no user with that id.";

      if ($response['permissions'] == 't') {
        $sql = "SELECT room.name AS room_name FROM teacher, room 
                WHERE room.id=teacher.id_room AND teacher.id_user='$id'";
        $response['room_name'] = $this->pdo->sqlValue($sql);

        if ($response['room_name'] == NULL || empty($response['room_name']))
          return "Teacher's classroom name failed to be selected.";

      } else if ($response['permissions'] == 's') {
        $sql = "SELECT class.name AS class_name, class.description AS class_description, student.birthdate
                FROM student, class WHERE class.id=student.id_class AND student.id_user='$id'";

        $student = $this->pdo->sqlRecord($sql);
        $response['class_name'] = $student['class_name'];
        $response['class_description'] = $student['class_description'];
        $response['birthdate'] = $student['birthdate'];
      }

      return $response;
    }

    ########################################################
    # gets one user from the database by their email
    # $email -> email of the user that you want to get [string]
    ########################################################
    public function getByEmail($email) {
      $email = htmlentities($email, ENT_QUOTES, 'utf-8');

      if (empty($email))
        return "Email is required but it's empty.";

      if (!is_string($email))
        return "Email is not a valid text.";

      //Sprawdzanie poprawności danych 
      if (!filter_var($email, FILTER_VALIDATE_EMAIL))
        return "Email format invalid.";

      $sql = "SELECT * FROM user WHERE email='$email'";
      $response = $this->pdo->sqlRecord($sql);

      if ($response == NULL || empty($response))
        return "There is no user with that email.";

      //ustawiam id
      $id = $response['id'];

      if ($response['permissions'] == 't') {
        $sql = "SELECT room.name AS room_name FROM teacher, room 
                WHERE room.id=teacher.id_room AND teacher.id_user='$id'";
        $response['room_name'] = $this->pdo->sqlValue($sql);

        if ($response['room_name'] == NULL || empty($response['room_name']))
          return "Teacher's classroom name failed to be selected.";

      } else if ($response['permissions'] == 's') {
        $sql = "SELECT class.name AS class_name, class.description AS class_description, student.birthdate
                FROM student, class WHERE class.id=student.id_class AND student.id_user='$id'";

        $student = $this->pdo->sqlRecord($sql);
        $response['class_name'] = $student['class_name'];
        $response['class_description'] = $student['class_description'];
        $response['birthdate'] = $student['birthdate'];
      }

      return $response;
    }

    ########################################################
    # gets users from the database by their permissions
    # $permissions -> permissions of the users that you want to get [string]
    ########################################################
    public function getByPermissions($permissions) {
      if (empty($permissions)) 
        return "Permissions are needed but are empty.";

      if (!is_string($permissions))
        return "Permissions is not a valid text.";

      if ($permissions == 'a') {
        $sql = "SELECT user.* FROM user WHERE user.permissions='$permissions'";

      } else if ($permissions == 't') {
        $sql = "SELECT user.*, room.name AS room_name FROM user, teacher, room 
                WHERE room.id=teacher.id_room AND teacher.id_user=user.id AND user.permissions='$permissions'";

      } else if ($permissions == 's') {
        $sql = "SELECT user.*, class.name AS class_name, class.description AS class_description, student.birthdate
                FROM user, student, class WHERE class.id=student.id_class AND student.id_user=user.id AND user.permissions='$permissions'";

      } else
        return "Permissions are out of options.";

      $response = $this->pdo->sqlTable($sql);

      if (empty($response) || $response == NULL || count($response) == 0)
        return "There are no users with that permissions.";

      return $response;
    }
  }