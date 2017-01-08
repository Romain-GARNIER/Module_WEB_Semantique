<?php
// on démarre la session, si l'utilisateur n'est pas connecté alors on redirige vers la page main.php.
session_start();
if(!isset($_SESSION['prenom'])) {
    header("Location: main.php");
}
?>
<!DOCTYPE html>
<html>
<head>
    <meta charset=utf-8 />
    <title>Pictionnary</title>
    <link rel="stylesheet" media="screen" href="css/styles.css" >
    <script>

        // les quatre tailles de pinceau possible.
        var sizes=[8,20,44,90];
        // la taille et la couleur du pinceau
        var size, color;
        // la dernière position du stylo
        var x0, y0;
        // le tableau de commandes de dessin à envoyer au serveur lors de la validation du dessin
        var drawingCommands = [];

        var setColor = function() {
            // on récupère la valeur du champs couleur
            color = document.getElementById('color').value;
            console.log("color:" + color);
        }

        var setSize = function() {
            // ici, récupèrez la taille dans le tableau de tailles, en fonction de la valeur choisie dans le champs taille.
            size =  sizes[document.getElementById('size').value];
            console.log("size:" + size);
        }

        window.onload = function() {
            var canvas = document.getElementById('myCanvas');
            canvas.width = 1300;
            canvas.height= 600;
            var context = canvas.getContext('2d');

            setSize();
            setColor();
            document.getElementById('size').onchange = setSize;
            document.getElementById('color').onchange = setColor;

            var isDrawing = false;

            var startDrawing = function(e) {
                console.log("start");
                // crér un nouvel objet qui représente une commande de type "start", avec la position, la couleur
                var command = {};
                /*command.command="start";
                command.x=e.x;
        				command.y=e.y;
        				command.size = size;
        				command.color = color;*/
                //c'est équivalent à:
                command = {"command":"start", "x": e.x, "y":e.y,"size":size,"color":color};

                // Ce genre d'objet Javascript simple est nommé JSON. Pour apprendre ce qu'est le JSON, c.f. http://blog.xebia.fr/2008/05/29/introduction-a-json/

                // on l'ajoute à la liste des commandes
                drawingCommands.push(command);

                // ici, dessinez un cercle de la bonne couleur, de la bonne taille, et au bon endroit.
                context.beginPath();
        				context.fillStyle = color;
        				context.arc(e.x, e.y, size, 0, 2 * Math.PI);
        				context.fill();
        				context.closePath();
                isDrawing = true;
            }

            var stopDrawing = function(e) {
                console.log("stop");
                isDrawing = false;
            }

            var draw = function(e) {
                if(isDrawing) {
                    // ici, créer un nouvel objet qui représente une commande de type "draw", avec la position, et l'ajouter à la liste des commandes.
                    var command = {};
          					command = {"command":"draw", "x": e.x, "y":e.y,"size":size,"color":color};
          					drawingCommands.push(command);
                    // ici, dessinez un cercle de la bonne couleur, de la bonne taille, et au bon endroit.
                    context.beginPath();
          					context.fillStyle = color;
          					context.arc(e.x, e.y, size, 0, 2 * Math.PI);
          					context.fill();
          					context.closePath();
                }
            }

            canvas.onmousedown = startDrawing;
            canvas.onmouseout = stopDrawing;
            canvas.onmouseup = stopDrawing;
            canvas.onmousemove = draw;

            document.getElementById('restart').onclick = function() {
                console.log("clear");
                // ici ajouter à la liste des commandes une nouvelle commande de type "clear"
                var command = {};
        				command.command="clear";
        				drawingCommands.push(command);
                // ici, effacer le context, grace à la méthode clearRect.
                context.clearRect(0, 0, canvas.width, canvas.height);
            };

            document.getElementById('validate').onclick = function() {
                // la prochaine ligne transforme la liste de commandes en une chaîne de caractères, et l'ajoute en valeur au champs "drawingCommands" pour l'envoyer au serveur.
                document.getElementById('drawingCommands').value = JSON.stringify(drawingCommands);

                // ici, exportez le contenu du canvas dans un data url, et ajoutez le en valeur au champs "picture" pour l'envoyer au serveur.*
                document.getElementById('picture').value = canvas.toDataURL();
            };
        };
    </script>
</head>
<body>

<canvas id="myCanvas"></canvas>

<form name="tools" action="req_paint.php" method="post">
    <!-- ici, insérez un champs de type range avec id="size", pour choisir un entier entre 0 et 4) -->
    <input type="range" id="size" min="0" max="3" step="1" value="0"/>
    <!-- ici, insérez un champs de type color avec id="color", et comme valeur l'attribut  de session couleur (à l'aide d'une commande php echo).) -->
  	<input type="color" id="color" value=<?php echo '"#'.$_SESSION['couleur'].'"'?>/>

    <input id="restart" type="button" value="Recommencer"/>
    <input type="hidden" id="drawingCommands" name="drawingCommands"/>
    <!-- à quoi servent ces champs hidden ? -->
    <input type="hidden" id="picture" name="picture"/>
    <input id="validate" type="submit" value="Valider"/>
</form>

</body>
</html>
