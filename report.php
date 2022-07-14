<?php

require_once('./config.php');

// connect to the data
$mysqli = new mysqli($database['host'], $database['username'], $database['password'], $database['database']);

class Report {
  function __construct($mysqli) {
    $this->db = $mysqli;
  }

  function getResults( $filter = ''){
    $query = 'SELECT orderid, comments, shipdate_expected FROM `sweetwater_test`';

    $res = $this->db->query($query);
    return $res->fetch_all();
  }
}


$report = new Report($mysqli);

echo '<pre>';
print_r($report->getResults());