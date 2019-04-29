<?php
  
  class User {
    private $signed_in;

    function __construct() {
      $signed_in = false;
    }

    public function sign_in() {
      return "Logowanie...";
    }

    public function sign_out() {
      return "Wylogowanie...";
    }
  }