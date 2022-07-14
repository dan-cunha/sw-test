<?php

require_once('./config.php');

// connect to the database
$mysqli = new mysqli($database['host'], $database['username'], $database['password'], $database['database']);

$sql = 'SELECT orderid, comments 
        FROM sweetwater_test 
        WHERE comments LIKE "%Expected Ship Date:%"';
$res = $mysqli->query($sql);

echo 'There are ' . $res->num_rows . ' records with an Expected Ship Date in the comment<br><br>';

foreach ($res  as $row ) {
  $orderid = $row['orderid'];
  $comment = $row['comments'];

  // the dates are predictable enough for regex
  preg_match('/(\d+\/\d+\/\d+)/', $comment, $date_preg);
  $date_in_comment = $date_preg[0];

  // convert to MYSQL's date format
  $date_for_db = date('Y-m-d h:i:s', strtotime($date_in_comment));

  // if we were taking user input, we should sanitize / use prepared statements / etc
  $sql = 'UPDATE sweetwater_test 
          SET shipdate_expected = "' . $date_for_db . '" 
          WHERE orderid = ' . $orderid; 
  $mysqli->query($sql);
}