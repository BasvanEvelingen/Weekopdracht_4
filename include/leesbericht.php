<?php
/**
 * @author Bas van Evelingen <BasvanEvelingen@me.com>
 * Weekopdracht 4 maak een blog
 * C(R)UD opzet <- Read
 * Berichten uit Database lezen
 * DB name = basblog Table = berichten
 * Veldnamen BerichtID|BerichtTitel|BerichtOmschrijving|BerichtInhoud|Auteur|BerichtDatum 
 */
    require_once('../config/db_config.php');
// Is er een parameter meegegeven
if(isset($_GET["id"]) && !empty(trim($_GET["id"]))){ 
    // Query voorbereiden object-georiënteerd.
    $sql = "SELECT * FROM berichten WHERE BerichtID = ?";
    if($stmt = $connection->prepare($sql)) {
        $stmt->bind_param("i", $param_id);
        $param_id = trim($_GET["id"]);
        // query uitvoeren
        if($stmt->execute()) {
            $result = $stmt->get_result();
            if($result->num_rows == 1) {
                /* resultaat binnenhalen als associatieve array dus geen loop nodig */
                $row = $result->fetch_array(MYSQLI_ASSOC);
                // database velden toekennen aan variabelen voor verwerking in html 
                $id = $row["BerichtID"];
                $titel = $row["BerichtTitel"];
                $auteur = $row["Auteur"];
                $omschrijving = $row["BerichtOmschrijving"];
                $inhoud = $row["BerichtInhoud"];
                $datum = $row["BerichtDatum"];
            } else{
                // Geen goede id
                header("location: error.php");
                exit();
            }
            
        } else{
            echo "Er ging iets verkeerd, probeer het later nog eens.";
        }
    }
     
    // Sluit statement
    $stmt->close();
    
    // Sluit connectie
} else{
    // Geen id meegegeven
    header("location: error.php");
    exit();
}
?>
<!DOCTYPE html>
<html lang="en">
<head>
    <meta charset="UTF-8">
    <title>Bericht Bekijken</title>
    <link href="../css/bootstrap.css" rel="stylesheet">
    <link href="../css/style.css" rel="stylesheet.css">
</head>
    <body>
        <div class="wrapper">
            <div class="container-fluid">
                <div class="row">
                    <div class="col-md-12">
                        <div class="page-header clearfix">
                            <h1>Bekijk bericht</h1>
                        </div>
                        <div class="panel-group">
                            <div class="panel panel-info">
                                <div class="panel-body">
                                    <label>Titel</label>
                                    <p><?php echo "$titel"; ?></p>
                                </div>
                                <div class="panel-body">
                                    <label>Auteur</label>
                                    <p><?php echo "$auteur"; ?></p>
                                </div>
                                <div class="panel-body">
                                    <label>Omschrijving</label>
                                    <p><?php echo "$omschrijving"; ?></p>
                                </div>
                                <div class="panel-body">
                                    <label>Categorie</label>
                                    <p>
                                    <?php
                                    $id = trim($_GET["id"]);
                                    $csql = "SELECT c.naam FROM categorie_bericht cb JOIN categorieen c ON cb.categorie_id=c.id WHERE cb.bericht_id = '$id'";
                                    $catstring = "";
                                    if ($result = $connection->query($csql)) {
                                        while($row = $result->fetch_array(MYSQLI_ASSOC)) {
                                            $catstring.= $row['naam'].',';
                                        }
                                    }
                                    echo substr_replace($catstring ,"",-1);

                                    ?>
                                    </p>
                                </div>


                                <div class="panel-body">
                                    <label>Inhoud</label>
                                    <p><?php echo "$inhoud"; ?></p>
                                </div> 
                                <div class="panel-body">
                                    <label>Datum</label>
                                    <p><?php echo "$datum"; ?></p>
                                </div>
                            </div>
                        </div>
                        <p><a href="../index.php" class="btn btn-primary">Terug</a></p>
                    </div>
                </div>        
            </div>
        </div>
    </body>
</html>
