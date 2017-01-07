<?php

require 'connectionPDO.php';

$email=$_POST['email'];
$password=$_POST['mdp'];

try
{
  $sql = $dbh->prepare("SELECT * FROM users WHERE email= :email AND password= :password");
	$sql->bindValue(":email", $email);
	$sql->bindValue(":password", md5($password));
	$sql->execute();
  if ($sql->rowCount() < 1)
	{
		header('Location: main.php?error='.urlencode("connexion"));
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
	$dbh = null;
}
catch (PDOException $e)
{
    print "<div class='PDOerreur'>Erreur !: " . $e->getMessage() . "<br/>";
    $dbh = null;
    die();
}
?>
