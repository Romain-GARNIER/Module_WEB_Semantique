

<link rel="stylesheet" media="screen" href="css/styles.css" >
<?php
try {
  $url = 'mysql:host=localhost;dbname=pictionnary';
  $user = 'test';
  $pwd = 'test';

  $dbh = new PDO($url, $user, $pwd);
}
catch (PDOException $e)
{
    print "<div class='PDOerreur'> Erreur !: " . $e->getMessage() . "</div>";
    $dbh = null;
    die();
}
 ?>
