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

    //id_user
    public function delete() {
        //usuń admina
        //usuń nauczyciela
        //usuń ucznia
      //usuń użytownika
    }
  }