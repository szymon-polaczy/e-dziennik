<?php
  
  class User {
    public function sign_in() {
      return "Sign in...";
    }

    public function sign_out() {
      return "Sign out...";
    }

    #this function return true or false depends on if the user is actually signed in
    public function is_signed_in() {
      return (isset($_SESSION['signed_in']) && $_SESSION['signed_in'] == true);
    }
  }