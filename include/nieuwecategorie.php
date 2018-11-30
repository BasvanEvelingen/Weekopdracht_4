<?php
/**
 * @author Bas van Evelingen <BasvanEvelingen@me.com>
 * Weekopdracht 4 maak een blog
 * Nieuwe Categorie aanmaken
 * DB name = basblogweek4 Table = categorieen
 * Veldnamen id | naam
 */

 // Include config file
require_once "../config/db_config.php";

if (isset($_POST["naam"]) && !empty($_POST["naam"])) {
    // statement voorbereiden
    $sql = "INSERT INTO categorieen (naam) VALUES (?)";
    
    if($stmt = $connection->prepare($sql)){
        // Bind variabele aan parameters
        $stmt->bind_param("s", $param_naam);
        
        // parameter zetten
        $param_naam = trim($_POST["naam"]);
        
        // poging wagen statement uit te voeren
        if($stmt->execute()){
            // Succes bericht verwijderd terug naar homepage
            header("location: ../index.php");
            exit();
        } else {
            echo "Er ging iets fout, probeer het later nog eens";
        }
    }
     
    // Sluit statement
    $stmt->close();
    
    // Sluit connectie
    $connection->close();
} 
?>
<!DOCTYPE html>
<html lang="en">
    <head>
        <meta charset="UTF-8">
        <title>View Record</title>
        <link href="../css/bootstrap.css" rel="stylesheet">
        <link href="../css/style.css" rel="stylesheet">
    </head>
    <body>
        <div class="wrapper">
            <div class="container-fluid">
                <div class="row">
                    <div class="col-md-12">
                        <div class="page-header">
                            <h1>Verwijder Bericht</h1>
                        </div>
                        <form action="<?php echo htmlspecialchars($_SERVER["PHP_SELF"]); ?>" method="POST">
                            <div class="form-group">
                                <label>Vul naam nieuwe categorie in.</label>
                                <input class="form-control" type="text" name="naam"/>
                                    <input type="submit" value="Voeg toe" class="btn btn-outline-success">
                                    <a href="../index.php" class="btn btn-outline-danger">Annuleer</a>
                            </div>
                        </form>
                    </div>
                </div>        
            </div>
        </div>
    </body>
</html>
