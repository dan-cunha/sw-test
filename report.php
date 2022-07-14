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

      case 'signature':
        $query .= ' WHERE comments LIKE "%signature%"';
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

  function getSignatureResults(){
    return $this->getResults('signature');
  }
}

$report = new Report($mysqli);
$results = [
  [ 
    'title' => 'Candy Records', 
    'results' => $candyResults = $report->getCandyResults()
  ],
  [ 
    'title' => 'Callback Records', 
    'results' => $callbackResults = $report->getCallBackResults()
  ],
  [ 
    'title' => 'Referral Records', 
    'results' => $referralResults = $report->getReferralResults()
  ],
  [ 
    'title' => 'Signature Records', 
    'results' => $signatureResults = $report->getSignatureResults()
  ]
];
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

    <?php foreach ($results as $result) : ?>
    <h2><?php echo $result['title']; ?></h2>
    <table>
      <thead>
        <tr>
          <th>Order ID</th>
          <th>Comments</th>
          <th>Ship Date</th>
        </tr>
      </thead>
      <tbody>
        <?php foreach ($result['results'] as $row) { ?>
          <tr>
            <td><?php echo $row['orderid']; ?></td>
            <td><?php echo $row['comments']; ?></td>
            <td><?php echo $row['shipdate_expected']; ?></td>
          </tr>
        <?php } ?>
      </tbody>
    </table>
    <?php endforeach; ?>
  </body>
</html>