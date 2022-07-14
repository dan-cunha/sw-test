<?php

require_once('./config.php');

// connect to the data
$mysqli = new mysqli($database['host'], $database['username'], $database['password'], $database['database']);

$res = $mysqli->query("SELECT * FROM `sweetwater_test`");

foreach ($res as $row) {
  echo $row['orderid'] . ' - ' . $row['comments'] . '<hr>';
}