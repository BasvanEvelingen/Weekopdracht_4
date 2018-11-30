<?php
/**
 * @author Bas van Evelingen <BasvanEvelingen@me.com>
 * Weekopdracht 4 maak een blog
 * CR(U)D opzet <- Update
 * Bericht in database veranderen, bijna zelfde structuur als leesbericht.php
 * DB name = basblog Table = berichten
 * Veldnamen BerichtID|BerichtTitel|BerichtOmschrijving|BerichtInhoud|Auteur|BerichtDatum 
 */
// Include config bestand
require_once "../config/db_config.php";
 
// variabelen 'declareren' en initialiseren
$titel = $omschrijving = $inhoud = "";
$titel_error = $omschrijving_error = $inhoud_error = "";
 

if(isset($_POST["id"]) && !empty($_POST["id"])){
    $id = $_POST["id"];
    
    // Validatie TODO in apart script zetten en iets met AJAX en simultane clientside validatie
    $input_titel = trim($_POST["titel"]);
    if(empty($input_titel)){
        $titel_error = "Titel invullen aub.";
    } elseif(!filter_var($input_titel, FILTER_VALIDATE_REGEXP, array("options"=>array("regexp"=>"/^[0-9a-zA-Z?.,\s]+$/")))){
        $titel_error = "Vult u alstublieft een goede titel in.";
    } else{
        $titel = $input_titel;
    }
    
    // Valideer omschrijving
    $input_omschrijving = trim($_POST["omschrijving"]);
    if(empty($input_omschrijving)){
        $omschrijving_error = "Voer een omschrijving in aub.";     
    } else {
        $omschrijving = $input_omschrijving;
    }

     // Valideer inhoud
     $input_inhoud = trim($_POST["inhoud"]);
     if(empty($input_omschrijving)){
         $inhoud_error = "Inhoud van bericht graag invullen.";     
     } else {
         $inhoud = $input_inhoud;
     }
    
    // Valideer voor inputfouten
    if(empty($titel_error) && empty($omschrijving_error) && empty($inhoud_error)){
        // statement voorbereiden
        $sql = "UPDATE berichten SET BerichtTitel=?, BerichtOmschrijving=?, BerichtInhoud=? WHERE BerichtID=?";
        if($stmt = $connection->prepare($sql)){
            // statement variable meegeven als parameters , 3 strings en een integer (sssi)
            $stmt->bind_param("sssi", $param_titel, $param_omschrijving, $param_inhoud, $param_id);
            // parameters zetten
            $param_titel = $titel;
            $param_omschrijving = $omschrijving;
            $param_inhoud = $inhoud;
            $param_id = $id;
            
            // uitvoeren
            if($stmt->execute()){
                // is gelukt terug naar index
                header("location: ../index.php");
                exit();
            } else{
                echo "Er ging iets fout, probeer het later opnieuw.";
            }
        }
         
        // sluit statement
        $stmt->close();
    }
    
    // sluit connectie
    $connection->close();
} else {
    // kijken of id gezet is
    if(isset($_GET["id"]) && !empty(trim($_GET["id"]))){
        
        $id =  trim($_GET["id"]);
        
       
        $sql = "SELECT * FROM berichten WHERE BerichtID = ?";
        if($stmt = $connection->prepare($sql)) {
            $stmt->bind_param("i", $param_id);
            $param_id = $id;
            if($stmt->execute()) {
                $result = $stmt->get_result();
                if($result->num_rows == 1){
                     // associatieve array uitlezen met fetch_array
                    $row = $result->fetch_array(MYSQLI_ASSOC);
                    $titel = $row["BerichtTitel"];
                    $omschrijving = $row["BerichtOmschrijving"];
                    $inhoud = $row["BerichtInhoud"];
                } else{
                    // Geen goede id in url dus error
                    header("location: error.php");
                    exit();
                }
                
            } else{
                echo "Er ging iets fout, probeer het later opnieuw.";
            }
        }
        
        // sluit statement
        $stmt->close();
        
        // sluit connectie
        $connection->close();
    }  else {
        // geen id meegegeven dus weer error.
        header("location: error.php");
        exit();
    }
}
?>
 
<!DOCTYPE html>
<html lang="en">
<head>
    <meta charset="UTF-8">
    <title>Update Bericht</title>
    <link href="../css/bootstrap.css" rel="stylesheet">
    <link href="../css/style.css" rel="stylesheet">
    <script src="../js/jquery.js"></script>
    <script src="../js/bootstrap-filestyle.min.js"></script>
    <script src="https://cdnjs.cloudflare.com/ajax/libs/tinymce/4.9.0/tinymce.min.js"></script>
    <script src="../js/script.js"></script>

</head>
<body>
    <div class="wrapper">
        <div class="container-fluid">
            <div class="row">
                <div class="col-md-12">
                    <div class="page-header">
                        <h2>Wijzigen Bericht</h2>
                    </div>
                    <p>Wijzig de gegevens en druk op wijzigen.</p>
                    <form action="<?php echo htmlspecialchars(basename($_SERVER['REQUEST_URI'])); ?>" method="post">
                        <div class="form-group <?php echo (!empty($titel_error)) ? 'has-error' : ''; ?>">
                            <label>titel</label>
                            <input type="text" name="titel" class="form-control" value="<?php echo $titel; ?>">
                            <span class="help-block"><?php echo $titel_error;?></span>
                        </div>
                        <div class="form-group <?php echo (!empty($omschrijving_error)) ? 'has-error' : ''; ?>">
                            <label>omschrijving</label>
                            <input type="text" name="omschrijving" class="form-control" value="<?php echo $omschrijving; ?>">
                            <span class="help-block"><?php echo $omschrijving_error;?></span>
                        </div>
                        <div class="form-group">
                                <input type="file" name="picture" class="filestyle" data-text="Kies plaatje" data-input="false" data-btnClass="btn btn-outline-info btn-sm" />
                        </div>
                        <div class="form-group <?php echo (!empty($inhoud_error)) ? 'has-error' : ''; ?>">
                            <label>Inhoud</label>
                            <textarea name="inhoud" class="form-control"><?php echo $inhoud; ?></textarea>
                            <span class="help-block"><?php echo $inhoud_error;?></span>
                        </div>
                        <input type="hidden" name="id" value="<?php echo $id; ?>"/>
                        <input type="submit" class="btn btn-primary" value="Wijzigen">
                        <a href="../index.php" class="btn btn-default">Cancel</a>
                    </form>
                </div>
            </div>        
        </div>
    </div>
</body>
</html>
