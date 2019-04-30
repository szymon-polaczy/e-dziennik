<?php

  class PDO
  {
    private $user, $database, $host, $pdo;

    public function __construct($usr, $pwd, $db, $host = 'localhost')
    {
      $this->user = $usr;
      $this->database = $db;
      $this->host = $host;

      try {
        $this->pdo = new PDO('mysql:host=' . $host . ';dbname=' . $db . ';port=3306', $usr, $pwd, array(PDO::MYSQL_ATTR_INIT_COMMAND => 'SET NAMES utf8'));
      } catch (PDOException $e) {
        die('Error: ' . $e->getMessage() . '<br />');
      }
    }

    public function sql_query($sql)
    {
      $affected_rows = $this->pdo->exec($sql);
      return $affected_rows;
    }
    
    public function sql_value($sql, $field = null)
    {
      $statement = $this->pdo->query($sql);
      if ($statement === false) return null;
      $row = $statement->fetch();
      if (is_null($field)) return $row[0];
      return (isset($row[$field]) ? $row[$field] : null);
    }

    public function sql_field($sql, $field = null)
    {
      $statement = $this->pdo->query($sql);
      if ($statement === false) return null;
      $result = array();
      foreach ($statement as $row) {
        if (!is_null($field) && !isset($row[$field])) continue;
        else $result[] = (is_null($field) ? $row[0] : $row[$field]);
      }
      return $result;
    }

    public function sql_record($sql)
    {
      $statement = $this->pdo->query($sql);
      if ($statement === false) return null;
      $result = $statement->fetch(PDO::FETCH_ASSOC);
      return $result;
    }

    public function sql_table($sql)
    {
      $statement = $this->pdo->query($sql);
      if ($statement === false) return null;
      $statement->setFetchMode(PDO::FETCH_ASSOC);
      $result = array();
      foreach ($statement as $row) $result[] = $row;
      return $result;
    }
  }
