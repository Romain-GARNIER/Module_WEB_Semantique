<link rel="stylesheet" media="screen" href="css/styles.css" >
<?php

require 'connectionPDO.php';

session_start();
$drawingCommands=stripslashes($_POST['drawingCommands']);
$picture=stripslashes($_POST['picture']);
$id = $_SESSION['id'];

$sql = $dbh->prepare("INSERT INTO drawings(d_commandes, d_image, d_fk_u_id) VALUES (:commandes, :image, :uid);");
$sql->bindValue(':commandes', $drawingCommands);
$sql->bindValue(':image', $picture);
$sql->bindValue(':uid', $id);
if (!$sql->execute())
{
	echo "<div class='PDOerreur'><h2>PDO::errorInfo():</h2>";
	$err = $sql->errorInfo();
	print_r($err);
  echo "</div>";
	$dbh = null;
}
else
{
	header('Location: main.php');
}

?>
