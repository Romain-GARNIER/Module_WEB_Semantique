<?php

require 'connectionPDO.php';

$email=$_POST['email'];
$password=$_POST['mdp'];

$sql = $dbh->prepare("SELECT * FROM users WHERE email= :email AND password= :password");
$sql->bindValue(":email", $email);
$sql->bindValue(":password", md5($password));
if (!$sql->execute())
{
	echo "<div class='PDOerreur'><h2>PDO::errorInfo():</h2>";
	$err = $sql->errorInfo();
	print_r($err);
  echo "</div>";
	$dbh = null;
}else {
	if ($sql->rowCount() < 1)
	{
		header('Location: main.php?erreur='.urlencode("login ou mot de passe incorrecte"));
	}
	else
	{
		session_start();
		$ligne = $sql->fetch();
		$_SESSION['email'] = $ligne['email'];
		$_SESSION['id'] = $ligne['id'];
		$_SESSION['nom'] = $ligne['nom'];
		$_SESSION['prenom'] = $ligne['prenom'];
		$_SESSION['couleur'] = $ligne['couleur'];
		$_SESSION['profilepic'] = $ligne['profilepic'];
		header('Location: main.php');
	}
}
?>
