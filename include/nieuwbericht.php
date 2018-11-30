<?php
/**
 * @author Bas van Evelingen <BasvanEvelingen@me.com>
 * Weekopdracht 4 maak een blog
 * (C)RUD opzet <- Create 
 * Een nieuw bericht in de database zetten
 * DB name = basblog Table = berichten
 * Veldnamen BerichtID|BerichtTitel|BerichtOmschrijving|BerichtInhoud|Auteur|BerichtDatum 
 * 
 */
session_start();

// Include config bestand
require_once "../config/db_config.php";

// Variabelen declareren en initialiseren
$berichtTitel = "";
$berichtOmschrijving = "";
$berichtInhoud = "";
$berichtCategorie = "";


$auteur = $_SESSION["username"];
$titel_error = "";
$omschrijving_error = "";
$inhoud_error = "";
$cat_error = "";

// categorie sql 
$cat_sql = "SELECT * FROM categorieen";

// sleutels van google voor reCaptcha.
$rcfg = include "../config/rec_cfg.php";
$siteKey = $rcfg['v2-standard']['site'];
$secret = $rcfg['v2-standard']['secret'];

// Data uit formulier verwerken 
if($_SERVER["REQUEST_METHOD"] == "POST") {
    // reCaptcha response gegeven
    if (isset($_POST['g-recaptcha-response'])) {
        // Vragen aan google om de response te controleren
        $request = file_get_contents('https://www.google.com/recaptcha/api/siteverify?secret='. $secret .'&response='.$_POST['g-recaptcha-response']);
        // Het resultaat is in JSON dus decoderen
        $response = json_decode($request);
        // Wanneer succes verder gaan met opslaan
        if($response->success) {

            // valideer titel, netjes maken met trim
            $input_titel = trim($_POST["titel"]);
            // wanneer leeg foutmelding
            if (empty($input_titel)) {
                $titel_error = "Voer een titel in.";
                //reguliere expressie filter uitvoeren op nette titel, wanneer false teruggeeft foutmelding.
            //} elseif(!filter_var($input_titel, FILTER_VALIDATE_REGEXP, array("options"=>array("regexp"=>"/^[0-9a-zA-Z.,\?\s]+$/")))) {
                //$titel_error = "Voer een goede titel in.";
            } else {
                $berichtTitel = $input_titel;
            }
            // Valideer omschrijving , mag niet leeg zijn.
            $input_omschrijving = trim($_POST["omschrijving"]);
            if(empty($input_omschrijving)) {
                $omschrijving_error = "Voer een omschrijving in.";     
            } else {
                $berichtOmschrijving = $input_omschrijving;
            }
            
            // Valideer inhoud van bericht, mag ook niet leeg zijn.
            $input_inhoud = trim($_POST["inhoud"]);
            if (empty($input_inhoud)) {
                $inhoud_error = "Bericht mag niet leeg zijn.";     
            } else {
                $berichtInhoud = $input_inhoud;
            }

            // Valideer categorieën inhoud
            {
                $input_cat = $_POST['catselect'];
                if (empty($input_cat))
                {
                    $cat_error = "Categorie mag niet leeg zijn.";
                } else
                {
                    $berichtCategorie = $input_cat;
                } 
            }

            // Kijken of er fouten zijn gemaakt bij invoeren blogbericht 
            if(empty($titel_error) && empty($omschrijving_error) && empty($inhoud_error) && empty($cat_error)) {
                // insert statement declareren voor toevoegen bericht aan database tabel
                $sql = "INSERT INTO berichten (BerichtTitel,BerichtOmschrijving,BerichtInhoud,Auteur) VALUES (?, ?, ?, ?)"; 
                // statement voorbereiden en trachten uit te voeren
                if($stmt = $connection->prepare($sql)) {
                    // variabelen aan statement binden met parameters, vier keer een string vandaar "ssss" als argument
                    $stmt->bind_param("ssss", $param_titel, $param_omschrijving, $param_inhoud, $param_auteur);
                    
                    // Parameters zetten
                    $param_titel = $berichtTitel;
                    $param_omschrijving = $berichtOmschrijving;
                    $param_inhoud = $berichtInhoud;
                    $param_auteur = $auteur;

                    // Poging wagen om data te schrijven naar database
                    if($stmt->execute()) {
                        // Hoera gelukt, nu categorieen zetten
                        $bericht_id = $connection->insert_id;
                        for ($x=0; $x<count($berichtCategorie); $x++) {
                            $csql = "INSERT INTO categorie_bericht (categorie_id, bericht_id) VALUES (?, ?)";
                            if ($catst = $connection->prepare($csql)) {
                                $catst->bind_param("ii",$param_c, $param_b);
                                $param_c = $berichtCategorie[$x];
                                $param_b = $bericht_id;
                                $catst->execute();
                            }
                        }
                        header("location: ../index.php");
                        exit();
                    } else {
                        echo "Er ging iets fout, probeer later nog eens.";
                    }
                }
                // sluit statement
                $stmt->close();
            }
            else
            {
                echo "Geen goede titel";
            }
    
    
        } else {
            echo "Probeer het nogmaals, u dient de reCaptcha in te vullen/aanklikken.";

        }
    } else {
        echo "U moet de reCaptcha invullen/aanklikken.";
    }
    // sluit connectie
    $connection->close();
}
?>

<!DOCTYPE html>
<html lang="en">
    <head>
        <title>Bericht aanmaken</title>
        <meta charset="UTF-8">
        <meta name="viewport" content="width=device-width, initial-scale=1.0">
        <meta http-equiv="X-UA-Compatible" content="ie=edge">
        <link href="../css/bootstrap.css" rel="stylesheet">
        <link href="../css/style.css" rel="stylesheet">
        <script src="../js/jquery.js"></script>
        <script src="../js/bootstrap-filestyle.min.js"></script>
        <script src="https://cdnjs.cloudflare.com/ajax/libs/tinymce/4.9.0/tinymce.min.js"></script>
        <script src="../js/script.js"></script>
        <script src="https://www.google.com/recaptcha/api.js" async defer></script>
    </head>
    <body>
        <div class="wrapper">
            <div class="container-fluid">
                <div class="row">
                    <div class="col-md-12">
                        <div class="page-header">
                            <h2>Blogbericht aanmaken</h2>
                        </div>
                        <p>Maak een bericht aan.</p>
                        <!-- begin van formulier -->
                        <form action="<?php echo htmlspecialchars($_SERVER["PHP_SELF"]); ?>" method="post">
                            <div class="form-group <?php echo (!empty($titel_error)) ? 'heeft een fout' : ''; ?>">
                                <label>Titel</label>
                                <input type="text" name="titel" class="form-control" value="<?php echo $berichtTitel; ?>">
                                <span class="help-block"><?php echo $titel_error;?></span>
                            </div>
                            <div class="form-group <?php echo (!empty($omschrijving_error)) ? 'heeft een fout' : ''; ?>">
                                <label>Omschrijving</label>
                                <input type="text" name="omschrijving" class="form-control" value="<?php echo $berichtOmschrijving; ?>">
                                <span class="help-block"><?php echo $omschrijving_error;?></span>
                            </div>

                            <div class="form-group <?php echo (!empty($cat_error)) ? 'heeft een fout' : ''; ?>">
                                <?php 
                                    if ($result = $connection->query($cat_sql)) {
                                        while($row = $result->fetch_array(MYSQLI_ASSOC)) {
                                            ?>
                                        <input type="checkbox" id="<?php echo $row['naam']; ?>" name="catselect[]" value="<?php echo $row['id']; ?>">
                                        <label for="<?php echo $row['naam']; ?>"><?php echo $row['naam']; ?></label>
                                        <?php 
                                        }
                                    }
                                ?>
                                <span class="help-block"><?php echo $cat_error;?></span>
                                
                           </div>

                            <div class="form-group">
                                <input type="file" name="picture" class="filestyle" data-text="Kies plaatje" data-input="false" data-btnClass="btn btn-outline-info btn-sm" />
                            </div>
                            <div class="form-group <?php echo (!empty($inhoud_error)) ? 'heeft een fout' : ''; ?>">
                                <label>Inhoud</label>
                                <textarea name="inhoud" class="form-control"><?php echo $berichtInhoud; ?></textarea>
                                <span class="help-block"><?php echo $inhoud_error;?></span>
                            </div>
                            <input type="submit" class="btn btn-outline-primary" value="Voeg toe">
                            <a href="../index.php" class="btn btn-outline-warning">Cancel</a>
                            <div class="g-recaptcha" data-sitekey="<?php echo $siteKey ?>"></div>
                        </form>
                    </div>
                </div>        
            </div>
        </div>
    </body>
</html>
