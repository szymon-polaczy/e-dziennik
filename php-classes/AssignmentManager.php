<?php
  
  class AssignmentManager {
    private $pdo;

    ########################################################
    # just a constructor
    # $pdo -> class pdo that I need to interact with database [PdoManager]
    ########################################################
    public function __construct($pdo) {
      if ($pdo == NULL || empty($pdo))
        return "PDO is required but it's empty.";

      $this->pdo = $pdo;
    }
  }