<?php
session_start();
require 'connectionPDO.php';
if(!isset($_SESSION['id'])) {
    header("Location: main.php");
} else {
  $sql = $dbh->prepare("SELECT * FROM drawings WHERE d_id= :id");
  $sql->bindValue(":id", $_GET["id"]);
  $sql->execute();
  if ($sql->rowCount() < 1)
  {
    header('Location: main.php?error='.urlencode("récupération de la painture"));
  } else {
    $ligne = $sql->fetch();
    $commands = $ligne['d_commandes'];
  }
}

?>
<!DOCTYPE html>
<html>
<head>
    <meta charset=utf-8 />
    <title>Pictionnary</title>
    <link rel="stylesheet" media="screen" href="css/styles.css" >
    <script>
        // la taille et la couleur du pinceau
        var size, color;
        // la dernière position du stylo
        var x0, y0;
        // le tableau de commandes de dessin à envoyer au serveur lors de la validation du dessin
        var drawingCommands = <?php echo $commands; ?>;

        window.onload = function() {
            var canvas = document.getElementById('myCanvas');
            canvas.width = 1300;
            canvas.height= 600;
            var context = canvas.getContext('2d');

            var start = function(c) {
                // complétez
                context.beginPath();
        				context.fillStyle = c.color;
        				context.arc(c.x, c.y, c.size, 0, 2 * Math.PI);
        				context.fill();
        				context.closePath();
            }

            var draw = function(c) {
                // complétez
                context.beginPath();
        				context.fillStyle = c.color;
        				context.arc(c.x, c.y, c.size, 0, 2 * Math.PI);
        				context.fill();
        				context.closePath();
            }

            var clear = function() {
                // complétez
                context.clearRect(0, 0, canvas.width, canvas.height);
            }

            // étudiez ce bout de code
            var i = 0;
            var iterate = function() {
                if(i>=drawingCommands.length)
                    return;
                var c = drawingCommands[i];
                switch(c.command) {
                    case "start":
                        start(c);
                        break;
                    case "draw":
                        draw(c);
                        break;
                    case "clear":
                        clear();
                        break;
                    default:
                        console.error("cette commande n'existe pas "+ c.command);
                }
                i++;
                setTimeout(iterate,30);
            };

            iterate();

        };
    </script>
</head>
<body>
<canvas id="myCanvas"></canvas>
<a class="btn" href="main.php">retour</a>
</body>
</html>
