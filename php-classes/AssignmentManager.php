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

    ########################################################
    # adds new assignment to the database
    # $id_teacher -> teacher's id of the new assignment [number]
    # $id_subject -> subject's id of the new assignment [number]
    # $id_class -> class's id of the new assignment [number]
    ########################################################
    public function add($id_teacher, $id_subject, $id_class) {
    }

    #########################################################
    # deletes assignments in the database
    # $id -> id of the assignment that you want to delete [number]
    #########################################################
    public function delete($id) {
    }

    ########################################################
    # edits teacher of the assignemnt in the database
    # $id -> id of the assignemnt that you want to edit [number]
    # $id_teacher -> teacher's id of the assignment [number]
    ########################################################
    public function editTeacher($id, $id_teacher) {
    }

    ########################################################
    # edits subject of the assignemnt in the database
    # $id -> id of the assignemnt that you want to edit [number]
    # $id_subject -> subject's id of the assignment [number]
    ########################################################
    public function editSubject($id, $id_subject) {
    }

    ########################################################
    # edits class of the assignemnt in the database
    # $id -> id of the assignemnt that you want to edit [number]
    # $id_class -> class's id of the assignment [number]
    ########################################################
    public function editClass($id, $id_class) {
    }

    ########################################################
    # gets all assignments from the database and
    # returns them in an array of assignments
    ########################################################
    public function getAll() {
    }

    ########################################################
    # gets one assignment from the database
    # $id -> id of the assignment that you want to get [number]
    ########################################################
    public function getById($id) {
    }

    ########################################################
    # gets all assignments from the database
    # $id_subject -> id of the subject that assignments must have [number]
    ########################################################
    public function getBySubject($id_subject) {
    }

    ########################################################
    # gets all assignments from the database
    # $id_teacher -> id of the teacher that assignments must have [number]
    ########################################################
    public function getByTeacher($id_teacher) {
    }

    ########################################################
    # gets all assignments from the database
    # $id_class -> id of the class that assignments must have [number]
    ########################################################
    public function getByClass($id_class) {
    }
  }