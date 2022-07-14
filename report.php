<?php

require_once('./config.php');

// connect to the database
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
        $query .= ' WHERE comments LIKE "%candy%" OR comments LIKE "%smarties%" OR comments LIKE "%bit o honey%"';
        break;
      break;

      case 'callback':
        $query .= ' WHERE comments LIKE "%call%"';
        break;
      break;

      case 'referral':
        $query .= ' WHERE comments LIKE "%referr%"'; // spelled incomplete to get "Referral *and* referred"
        break;
      break;

      case 'signature':
        $query .= ' WHERE comments LIKE "%signature%"';
        break;
      break;

      case 'other':
      default:
        $query .= ' WHERE comments NOT LIKE "%candy%"
                      AND comments NOT LIKE "%smarties%" 
                      AND comments NOT LIKE "%bit o honey%"
                    AND comments NOT LIKE "%call%" 
                    AND comments NOT LIKE "%referr%" 
                    AND comments NOT LIKE "%signature%"';
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

  function getOtherResults(){
    return $this->getResults('other');
  }
}

$report = new Report($mysqli);
$results = [
  [ 
    'title' => 'Candy', 
    'keyword' => 'candy|smarties|bit o honey',
    'results' => $report->getCandyResults()
  ],
  [ 
    'title' => 'Callback', 
    'keyword' => 'call',
    'results' => $report->getCallBackResults()
  ],
  [ 
    'title' => 'Referral', 
    'keyword' => 'refer',
    'results' => $report->getReferralResults()
  ],
  [ 
    'title' => 'Signature', 
    'keyword' => 'signature',
    'results' => $report->getSignatureResults()
  ],
  [ 
    'title' => 'Miscellaneous', 
    'results' => $report->getOtherResults()
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

    <style>
      /* hacky terrible CSS for at least some flavor */
      html, body { font-family: Arial, Helvetica, sans-serif;}

      .report th { background: #999; color: white; font-weight: bold; text-align: left; padding: 5px;}
      .report th:first-child { width: 150px; }
      .report th:last-child { width: 250px; }

      .report tbody tr:nth-child(even) { background: #EFEFEF; }
      .report tbody td:first-child { text-align: center; font-weight: bold; }
      .report tbody td { padding: 5px; }
      .report tbody td b { color: green; background-color: yellow; }
    </style>
  </head>
  <body>
    <a name="top"></a>
    <h1>Customer Order Comments</h1>

    Jump to results: 
    <ul>
      <?php foreach ($results as $result) : ?>
      <li><a href="#<?php echo $result['title']; ?>"><?php echo $result['title']; ?></a></li>
      <?php endforeach; ?>
    </ul>

    <?php foreach ($results as $result) : ?>
    <a name="<?php echo $result['title']; ?>"></a>
    <h2><?php echo $result['title']; ?> Records <small><a href="#top">[top]</a></small></h2>
    <table class="report">
      <thead>
        <tr>
          <th>Order ID</th>
          <th>Comments</th>
          <th>Ship Date</th>
        </tr>
      </thead>
      <tbody>
        <?php foreach ($result['results'] as $row) : ?>
        <tr>
          <td><?php echo $row['orderid']; ?></td>
          <td>
            <?php if ($result['title'] === 'Miscellaneous') : ?>
              <?php echo $row['comments']; ?>
            <?php else : ?>
              <?php $regex = "/\w*?". $result['keyword'] . "\w*/i"; ?>
              <?php echo preg_replace($regex, "<b>$0</b>", $row['comments']); ?>
            <?php endif; ?>
          </td>
          <td><?php echo $row['shipdate_expected']; ?></td>
        </tr>
        <?php endforeach; ?>
      </tbody>
    </table>
    <?php endforeach; ?>
  </body>
</html>