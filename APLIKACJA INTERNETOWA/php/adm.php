<?php
  class Adm {
    private $pdo_db;

    function __construct($pdo) {
      $this->pdo_db = $pdo;
    }

    function getUserById($user_id, $get_info = "*") {
      if (!is_numeric($user_id) || !is_string($get_info))
        return NULL;

      $sql = "SELECT ".$get_info." FROM osoba WHERE id='$user_id'";

      $res = $this->pdo_db->sql_record($sql);

      if (count($res) === 1)
        return $res[$get_info];
      else
        return $res;
    }

    function getUserByCategory($cat_name) {
      if (!is_string($cat_name))
        return NULL;

      $select = ($cat_name[0] === 'u'? ", uczen.data_urodzenia, klasa.nazwa, klasa.opis" : ($cat_name[0] === 'n'? ", sala.nazwa" : ""));
      $from = ($cat_name[0] === 'u'? ", klasa" : ($cat_name[0] === 'n'? ", sala" : ""));
      $where = ($cat_name[0] === 'u'? "AND `$cat_name`.id_klasa=klasa.id" : ($cat_name[0] === 'n'? "AND `$cat_name`.id_sala=sala.id" : ""));

      $sql = "SELECT osoba.id, osoba.imie, osoba.nazwisko, osoba.email ".$select." 
              FROM osoba, `$cat_name` ".$from." 
              WHERE uprawnienia='$cat_name[0]' 
              AND osoba.id=`$cat_name`.id_osoba ".$where;

      $res = $this->pdo_db->sql_table($sql);

      return $res;
    }

    function getUserMark($user_id) {
      if (!is_numeric($user_id))
        return NULL;

      $sql = "SELECT ocena.wartosc, ocena.data, osoba.imie, osoba.nazwisko, przedmiot.nazwa 
      FROM ocena, przydzial, przedmiot, nauczyciel, osoba
      WHERE ocena.id_uczen='$user_id' AND ocena.id_przydzial=przydzial.id AND przydzial.id_przedmiot=przedmiot.id
      AND przydzial.id_nauczyciel=nauczyciel.id_osoba AND nauczyciel.id_osoba=osoba.id
      ORDER BY przedmiot.nazwa ASC";

      $res = $this->pdo_db->sql_table($sql);

      return $res;
    }

    function showDataTable($what, $task = NULL, $edit_file = NULL, $delete_file=NULL) {
      if (count($what) === 0)
        return NULL;

      echo '<table class="table">';
      echo '<thead class="thead-dark">';
        echo '<tr>';

          foreach($what[0] as $key => $val)
            if ($key != "id")
              echo '<th class="'.(is_numeric($val)? "tabela-liczby" : is_string($val)? "tabela-tekst" : '').'">'.$key.'</th>';

          if ($task != NULL)
            echo '<th class="tabela-zadania">opcje</th>'; 
        echo '</tr>';
      echo '</thead>';

      echo '<tbody>';

      foreach ($what as $ele) {
        echo '<tr>';
          foreach($ele as $key => $val)
            if ($key != "id")
              echo '<td class="'.(is_numeric($val)? "tabela-liczby" : is_string($val)? "tabela-tekst" : '').'">'.$val.'</td>';

          if ($task === true) {
            echo '<td class="tabela-zadania">';
              echo '<a href="'.$edit_file.'='.$ele['id'].'">Edytuj</a>';
              echo '<span>|</span>';
              echo '<a onclick="javascript:(confirm(\'Czy jesteś tego pewny?\')? window.location=\'zadania/'.$delete_file.'='.$ele['id'].'\':\'\')" href="#">Usuń</a>';
            echo '</td>';
          }
        echo '</tr>';
      }
      echo '</tbody>';
      echo '</table>';
    }

    function getAllFrom($from) {
      if (!is_string($from))
        return NULL;

      $sql = "SELECT * FROM `$from`";

      $res = $this->pdo_db->sql_table($sql);

      return $res;
    }
  }