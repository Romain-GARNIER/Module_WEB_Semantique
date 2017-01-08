<?php

require 'connectionPDO.php';

session_start();
$drawingCommands=stripslashes($_POST['drawingCommands']);
$picture=stripslashes($_POST['picture']);
$id = $_SESSION['id'];
try
{

  $sql = $dbh->prepare("INSERT INTO drawings(d_commandes, d_image, d_fk_u_id) VALUES (:commandes, :image, :uid);");
	$sql->bindValue(':commandes', $drawingCommands);
	$sql->bindValue(':image', $picture);
	$sql->bindValue(':uid', $id);
	if (!$sql->execute())
	{
		echo "PDO::errorInfo():<br/>";
		$err = $sql->errorInfo();
		print_r($err);
	}
	else
	{
		header('Location: main.php');
	}
	$dbh = null;
}
catch (PDOException $e)
{
  print "<div class='PDOerreur'>Erreur !: " . $e->getMessage() . "</div>";
  $dbh = null;
  die();
}
?>
