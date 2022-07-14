<?php

require_once('./config.php');

// connect to the data
$mysqli = new mysqli($database['host'], $database['username'], $database['password'], $database['database']);

class Report {
  function __construct($mysqli) {
    $this->db = $mysqli;
  }

  function getResults( $filter = ''){
    $query = 'SELECT orderid, comments, shipdate_expected FROM `sweetwater_test` ';

    // can be smarter about the WHERE/OR/AND stuff in future iterations
    switch ($filter) {
      case 'candy':
        $query .= ' WHERE comments LIKE "%candy%"';
        break;
      break;

      case 'callback':
        $query .= ' WHERE comments LIKE "%call%"';
        break;
      break;

      case 'referral':
        $query .= ' WHERE comments LIKE "%refer%"';
        break;
      break;
    }

    $res = $this->db->query($query);
    return $res->fetch_all(MYSQLI_ASSOC);
  }

  function getCandyResults(){
    return $this->getResults('candy');
  }

  function getCallBackResults(){
    return $this->getResults('callback');
  }

  function getReferralResults(){
    return $this->getResults('referral');
  }
}

$report = new Report($mysqli);
$candyResults = $report->getCandyResults();
$callbackResults = $report->getCallBackResults();
$referralResults = $report->getReferralResults();
?>
<!DOCTYPE html>
<html lang="en">
  <head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <meta http-equiv="X-UA-Compatible" content="ie=edge">
    <title>DDC - Sweetwater Code Test</title>
  </head>
  <body>
    <h1>Sweetwater Code Test</h1>

    <h3>Candy Results</h3>
    <table>
      <thead>
        <tr>
          <th>Order ID</th>
          <th>Comments</th>
          <th>Ship Date</th>
        </tr>
      </thead>
      <tbody>
        <?php foreach ($candyResults as $result) : ?>
          <tr>
            <td><?php echo $result['orderid']; ?></td>
            <td><?php echo $result['comments']; ?></td>
            <td><?php echo $result['shipdate_expected']; ?></td>
          </tr>
        <?php endforeach; ?>
      </tbody>
    </table>

    <hr />

    <h3>Calback Results</h3>
    <table>
      <thead>
        <tr>
          <th>Order ID</th>
          <th>Comments</th>
          <th>Ship Date</th>
        </tr>
      </thead>
      <tbody>
        <?php foreach ($callbackResults as $result) : ?>
          <tr>
            <td><?php echo $result['orderid']; ?></td>
            <td><?php echo $result['comments']; ?></td>
            <td><?php echo $result['shipdate_expected']; ?></td>
          </tr>
        <?php endforeach; ?>
      </tbody>
    </table>

    <hr />

    <h3>Referral Results</h3>
    <table>
      <thead>
        <tr>
          <th>Order ID</th>
          <th>Comments</th>
          <th>Ship Date</th>
        </tr>
      </thead>
      <tbody>
        <?php foreach ($referralResults as $result) : ?>
          <tr>
            <td><?php echo $result['orderid']; ?></td>
            <td><?php echo $result['comments']; ?></td>
            <td><?php echo $result['shipdate_expected']; ?></td>
          </tr>
        <?php endforeach; ?>
      </tbody>
    </table>
  </body>
</html>