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
    'title' => 'Candy', 
    'results' => $candyResults = $report->getCandyResults()
  ],
  [ 
    'title' => 'Callback', 
    'results' => $callbackResults = $report->getCallBackResults()
  ],
  [ 
    'title' => 'Referral', 
    'results' => $referralResults = $report->getReferralResults()
  ],
  [ 
    'title' => 'Signature', 
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
    <a name="top"></a>
    <h1>Sweetwater Code Test</h1>

    Jump to results: 
      <?php foreach ($results as $result) : ?>
        <a href="#<?php echo $result['title']; ?>"><?php echo $result['title']; ?></a> | 
      <?php endforeach; ?>

    <?php foreach ($results as $result) : ?>
    <a name="<?php echo $result['title']; ?>"></a>
    <h2><?php echo $result['title']; ?> Records <small><a href="#top">[top]</a></small></h2>
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