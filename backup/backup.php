<?php
include("libs/backup.php");

  $backup = new BackupMyProject('../', true);
  print_r($backup);

?>