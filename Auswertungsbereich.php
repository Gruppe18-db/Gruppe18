<!-- Max Boger -->

<!DOCTYPE html>
<html>
    <head>
        <meta charset="UTF-8">
        <title>Auswertungsbereich</title>
    </head>
    <body>
        <form action="Auswertungsseite.php" method="GET">
            <h1>Wähle ein Trainingsziel aus:</h1>
            Ausdauer <input type="radio" name="ziel" value="ausdauer" required><br>
            Sprintkraft <input type="radio" name="ziel" value="sprintkraft"><br>
            Steigungen <input type="radio" name="ziel" value="steigungen"><br>
            Alle Ziele <input type="radio" name="ziel" value="alle"><br>
            
            <h1>Wähle einen Zeiterraum aus:</h1>
            Startdatum: <input type="date" name="startdatum"><br>
            Enddatum: <input type="date" name="enddatum"><br>

            <input type="submit" name="auswAnzeigen" value="Auswertung anzeigen"><br>
        </form>

    </body>
</html>