<?php
  session_start();
  session_unset();
  header('Location: ../user-interactions/index.php');