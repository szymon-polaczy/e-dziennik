<?php
  class User_Adm {
    private $pdo_db;

    function __construct($pdo) {
      $this->pdo_db = $pdo;
    }

    function getUserById($user_id) {
      //check if user_id is a string
      if (!is_numeric($user_id))
        return NULL;

      //write sql
      $sql = "SELECT * FROM osoba WHERE id='$user_id'";

      //retrive data from database
      $res = $this->pdo_db->sql_record($sql);

      //return data
      return $res;
    }

    function getUserByCategory($cat_name) {
      //check if cat_name is a string
      if (!is_string($cat_name))
        return NULL;

      //write sql
      $sql = "SELECT * FROM osoba, `$cat_name` WHERE uprawnienia='$cat_name[0]' AND osoba.id=`$cat_name`.id_osoba";

      //retrive data from database
      $res = $this->pdo_db->sql_table($sql);

      //return data
      return $res;
    }
  }