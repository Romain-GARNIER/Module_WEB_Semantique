<?php

// récupérer les éléments du formulaire
// et se protéger contre l'injection MySQL (plus de détails ici: http://us.php.net/mysql_real_escape_string)
$email=stripslashes($_POST['email']);
$password=stripslashes($_POST['password']);
$nom=stripslashes($_POST['nom']);
$prenom=stripslashes($_POST['prenom']);
$tel=stripslashes($_POST['tel']);
$website=stripslashes($_POST['website']);
$sexe='';
if (array_key_exists('sexe',$_POST)) {
    $sexe=stripslashes($_POST['sexe']);
}
$birthdate=stripslashes($_POST['birthdate']);
$ville=stripslashes($_POST['ville']);
$taille=stripslashes($_POST['taille']);
$couleur=ltrim($_POST['couleur'],"#");
$profilepic=stripslashes($_POST['profilepic']);

// Vérifier si un utilisateur avec cette adresse email existe dans la table.
// En SQL: sélectionner tous les tuples de la table USERS tels que l'email est égal à $email.
$sql = $dbh->query("Select * From users where email = '".$email."'");
if ($sql->rowCount()>1) {
  $strParametres="&email=$email&nom=$nom&prenom=$prenom&tel=$tel&wbesite=$website&sexe=$sexe&birthdate=$birthdate&ville=$ville&taille=$taille&couleur=$couleur";
  header("Location: inscription.php?erreur=".urlencode("le mail existe déjà").$strParametres);

}
else {
    // Tenter d'inscrire l'utilisateur dans la base
    $sql = $dbh->prepare("INSERT INTO users (email, password, nom, prenom, tel, website, sexe, birthdate, ville, taille, couleur, profilepic) "
            . "VALUES (:email, :password, :nom, :prenom, :tel, :website, :sexe, :birthdate, :ville, :taille, :couleur, :profilepic)");
    $sql->bindValue(":email", $email);
    $sql->bindValue(":password", md5($password));

    // de même, lier la valeur pour le mot de passe
    // lier la valeur pour le nom, attention le nom peut être nul, il faut alors lier avec NULL, ou DEFAULT
    // idem pour le prenom, tel, website, birthdate, ville, taille, profilepic
    // n.b., notez: birthdate est au bon format ici, ce serait pas le cas pour un SGBD Oracle par exemple
    // idem pour la couleur, attention au format ici (7 caractères, 6 caractères attendus seulement)
    // idem pour le prenom, tel, website
    // idem pour le sexe, attention il faut être sûr que c'est bien 'H', 'F', ou ''

    if($nom=='') $sql->bindValue(":nom", null);
    else $sql->bindValue(":nom", $nom);

    if($prenom=='') $sql->bindValue(":prenom", null);
    else $sql->bindValue(":prenom", $prenom);

    if($tel=='') $sql->bindValue(":tel", null);
    else $sql->bindValue(":tel", $tel);

    if($website=='') $sql->bindValue(":website", null);
    else $sql->bindValue(":website", $website);

    if($ville=='') $sql->bindValue(":ville", null);
    else $sql->bindValue(":ville", $ville);

    if($taille=='') $sql->bindValue(":taille", null);
    else $sql->bindValue(":taille", $taille);

    if($profilepic=='') $sql->bindValue(":profilepic", null);
    else $sql->bindValue(":profilepic", $profilepic);

    if($couleur=='') $sql->bindValue(":couleur", null);
    else $sql->bindValue(":couleur", substr($couleur,0));

    if($sexe != 'H' && $sexe!='F') $sql->bindValue(":sexe", null);
    else $sql->bindValue(":sexe", $sexe);

    if($birthdate == '') $sql->bindValue(":birthdate", "");
    else $sql->bindValue(":birthdate", $birthdate);

    // on tente d'exécuter la requête SQL, si la méthode renvoie faux alors une erreur a été rencontrée.
    if (!$sql->execute()) {
    	echo "<div class='PDOerreur'><h2>PDO::errorInfo():</h2>";
    	$err = $sql->errorInfo();
    	print_r($err);
      echo "</div>";
    	$dbh = null;
    } else {

        // ici démarrer une session
        session_start();

        // ensuite on requête à nouveau la base pour l'utilisateur qui vient d'être inscrit, et
        $sql = $dbh->query("SELECT u.id, u.email, u.nom, u.prenom, u.couleur, u.profilepic FROM USERS u WHERE u.email='".$email."'");
        if ($sql->rowCount()<1) {
            header("Location: main.php?erreur=".urlencode("un problème est survenu"));
        }
        else {
            // on récupère la ligne qui nous intéresse avec $sql->fetch(),
            // et on enregistre les données dans la session avec $_SESSION["..."]=...
            $row = $sql->fetch();
            $_SESSION["id"]=$row["id"];
            $_SESSION["email"]=$row["email"];
            $_SESSION["nom"]=$row["nom"];
            $_SESSION["prenom"]=$row["prenom"];
            $_SESSION["couleur"]=$row["couleur"];
            $_SESSION["profilepic"]=$row["profilepic"];
        }
        // ici,  rediriger vers la page main.php
        header("Location: main.php");
    }
    $dbh = null;
}
?>
