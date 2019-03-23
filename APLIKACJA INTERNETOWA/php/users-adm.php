<?php
  class User_Adm {
    private $pdo_db;

    function __construct($pdo) {
      $this->pdo_db = $pdo;
    }

    function getUserByCategory($cat_name) {
      //check if cat_name is a string
      if (!is_string($cat_name))
        return NULL;

      //write sql
      $sql = "SELECT * FROM osoba, `$cat_name` WHERE uprawnienia='$cat_name[0]' AND osoba.id=`$cat_name`.id_osoba";

      //retrive data from data base
      $res = $this->pdo_db->sql_table($sql);

      //return data
      return $res;
    }
  }