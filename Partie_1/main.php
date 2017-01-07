<!DOCTYPE html>
<html>
  <head>
    <meta charset="utf-8">
    <title>Pictionnary</title>
    <link rel="stylesheet" media="screen" href="css/styles.css" >
  </head>
  <body>

<?php require 'header.php'; ?>

<div class="">
  <a href="paint.php">Commencer à dessiner</a>
</div>

<div>
<?php
	if(isset($_SESSION['email']))
	{
    require 'connectionPDO.php';
  	try
  	{

  		$sql = $dbh->prepare("SELECT d_did FROM drawings WHERE d_fk_u_id= :id");
  		$sql->bindValue(":id", $_SESSION['id']);
  		$sql->execute();
  		$i = 0;
      $res = $sql->fetchAll();
  		foreach ($res as $ligne)
  		{
  			echo "<a href=guess.php?id=" . $ligne['d_id'] . ">Pictionnary " . ++$i . "</a><br/>";
  		}
  	}
  	catch (PDOException $e)
  	{
  		print "<div class='PDOerreur'> Erreur !: " . $e->getMessage() . "</div>";
  		$dbh = null;
  		die();
  	}
	}
?>
</div>

  </body>
</html>
