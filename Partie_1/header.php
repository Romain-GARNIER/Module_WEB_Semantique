<?php

session_start();
$connecter=false;
if(!empty($_SESSION)){
	$connecter=true;
	$id = $_SESSION["id"];
	$email = $_SESSION["email"];
  $nom = $_SESSION["nom"];
  $prenom = $_SESSION["prenom"];
  $couleur = $_SESSION["couleur"];
  $profilepic = $_SESSION["profilepic"];
}

?>

<!DOCTYPE html>
<html>
	<head>
		<meta charset="utf-8">
		<title></title>
		<link rel="stylesheet" media="screen" href="css/header.css" >
	</head>
	<body>


		<?php

		if(!empty($_GET)){
		    if(!empty($_GET["erreur"])){
		        $strHTML = "<div style='background-color: red;padding: 0.5em 1em 1em;'>".
		            "<h1>ERROR : ".$_GET["erreur"]."</h1>".
		            "</div>";
		        echo $strHTML;
		    }
		}
		?>

		<?php if(!$connecter) { ?>
		<form class="block-accueil" action="req_login.php" method="post">
      <div><label for="email">Identifiant : </label><input type="text" name="email" value=""></div>
      <div><label for="mdp">Mot de passe : </label><input type="password" name="mdp" value=""></div>
      <input type="submit" class="valider" name="valider" value="valider">
      <a class="btn" href="./inscription.php">créer un compte</a>

		</form>
			<?php }
			else {
				echo "<div class='block-accueil'>";
				echo "<div>Bonjour ".$prenom." ".$nom."</div>";
				echo "<img src=".$profilepic." alt='profile'><br>";
				echo "<a class='btn' href='lougout.php'>Deconnexion</a>";
				echo "</div>";
		 	}
			?>

	</body>
</html>
