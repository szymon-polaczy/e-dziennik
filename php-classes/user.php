<?php
  
  class USERS {
    /*sign in the user*/
    public function sign_in($pdo, $email, $password) {
      $email = htmlentities($email, ENT_QUOTES, 'utf-8');
      $password = htmlentities($password, ENT_QUOTES, 'utf-8');

      if (empty($email))
        return "Email is required but it's empty.";

      if (empty($password))
        return "Password is required but it's empty.";

      if (!filter_var($email, FILTER_VALIDATE_EMAIL))
        return "Email format invalid.";

      $sql = "SELECT * FROM user WHERE email='$email'";
      $response = $pdo->sql_table($sql);

      if (count($response) == 1) {
        $row = $response[0];

        if(password_verify($password, $row['password'])) {
          $_SESSION['id'] = $row['id'];
          $_SESSION['name'] = $row['name'];
          $_SESSION['surname'] = $row['surname'];
          $_SESSION['email'] = $row['email'];
          $_SESSION['permissions'] = $row['permissions'];

          $user_id = $_SESSION['id'];

          if ($_SESSION['permissions'] == 't') {
            $sql = "SELECT room.name AS room_name FROM teacher, room
                    WHERE teacher.id_user='$user_id' AND room.id=teacher.id_room";

            $response = $pdo->sql_value($sql);

            if (empty($response) || is_null($response))
              return "Additional resources were failed to retrieve.";

            $_SESSION['room_name'] = $response;
          } else if ($_SESSION['permissions'] == 's') {
            $sql = "SELECT student.birthdate, class.name AS class_name, class.description AS class_description
                    FROM student, class WHERE student.id_user='$user_id' AND class.id=student.id_class";

            $response = $pdo->sql_record($sql);

            if (empty($response) || is_null($response))
              return "Additional resources were failed to retrieve.";

            $_SESSION['birthdate'] = $response['birthdate'];
            $_SESSION['class_name'] = $response['class_name'];
            $_SESSION['class_description'] = $response['class_description'];
          }
          
          $_SESSION['signed_in'] = true;
          return "Good.";
        } else {
          return "Signing in failed, email or password is incorrect.";
        }
      } else {
        return "Signing in failed, email or password is incorrect.";
      }
    }

    /*this function return true or false depends on if the user is actually signed in*/
    public function is_signed_in() {
      return (isset($_SESSION['signed_in']) && $_SESSION['signed_in'] == true);
    }
  }