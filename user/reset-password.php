<?php
/**
 * @author Bas van Evelingen <BasvanEvelingen@me.com>
 * @version 2.0
 * Weekopdracht 3 herstel wachtwoord.
 */
// Sessie starten
session_start();
 
// Kijk of de gebruiker ingelogd is, anders naar login pagina
if (!isset($_SESSION["loggedin"]) || $_SESSION["loggedin"] !== true) {
    header("location: login.php");
    exit;
}
 
// Include config file
require_once "../config/db_config.php";
 
// Variabelen initaliseren en lege waardes.
$new_password = $confirm_password = "";
$new_password_error = $confirm_password_error = "";
 
// Verwerk het de nieuwe ingevulde data
if ($_SERVER["REQUEST_METHOD"] == "POST") {
 
    // Valideer nieuwe wachtwoord.
    if (empty(trim($_POST["new_password"]))) {
        $new_password_error = "Vul een nieuw wachtwoord in.";     
    } elseif (strlen(trim($_POST["new_password"])) < 6) {
        $new_password_error = "Wachtwoord moet minimaal bestaan uit 6 karakters.";
    } else {
        $new_password = trim($_POST["new_password"]);
    }
    
    // Valideer het nieuwe wachtwoord nogmaals.
    if (empty(trim($_POST["confirm_password"]))) {
        $confirm_password_error = "Bevestig uw nieuwe wachtwoord.";
    } else {
        $confirm_password = trim($_POST["confirm_password"]);
        if (empty($new_password_error) && ($new_password != $confirm_password)) {
            $confirm_password_error = "Wachtwoorden komen niet overeen.";
        }
    }
        
    // Controleren of er fouten zijn gemaakt bij het aanmaken van het wachtwoord
    if (empty($new_password_error) && empty($confirm_password_error)) {
        // Voorbereiden statement
        $sql = "UPDATE users SET password = ? WHERE id = ?";
        
        if ($stmt = $connection->prepare($sql)) {
            // Verbind variabelen aan statement als parameters.
            $stmt->bind_param("si", $param_password, $param_id);
            
            // Parameters zetten
            $param_password = password_hash($new_password, PASSWORD_DEFAULT);
            $param_id = $_SESSION["id"];
            
            // statement uitvoeren
            if ($stmt->execute()) {
                // wachtwoord updaten gelukt, vernietig sessie
                session_destroy();
                header("location: login.php");
                exit();
            } else {
                echo "Er ging iets mis, probeer het later nog eens.";
            }
        }
        
        // sluit statement
        $stmt->close();
    }
    
    // sluit connectie
    $connection->close();
}
?>
 
<!DOCTYPE html>
<html lang="en">
<head>
    <meta charset="UTF-8">
    <title>Herstel wachtwoord</title>
    <link href="https://fonts.googleapis.com/css?family=Bitter" rel="stylesheet">
    <link href="../css/bootstrap.css" rel="stylesheet">
    <link href="../css/style.css" rel="stylesheet">
</head>
    <body>
        <div class="wrapper">
            <h2>Herstellen wachtwoord</h2>
            <p>Vul dit formulier in om een nieuw wachtwoord aan te maken.</p>
            <form action="<?php echo htmlspecialchars($_SERVER["PHP_SELF"]); ?>" method="post"> 
                <div class="form-group <?php echo (!empty($new_password_error)) ? 'has-error' : ''; ?>">
                    <label>Nieuw wachtwoord</label>
                    <input type="password" name="new_password" class="form-control" value="<?php echo $new_password; ?>">
                    <span class="help-block"><?php echo $new_password_error; ?></span>
                </div>
                <div class="form-group <?php echo (!empty($confirm_password_error)) ? 'has-error' : ''; ?>">
                    <label>Bevestig nieuw wachtwoord</label>
                    <input type="password" name="confirm_password" class="form-control">
                    <span class="help-block"><?php echo $confirm_password_error; ?></span>
                </div>
                <div class="form-group">
                    <input type="submit" class="btn btn-primary" value="Submit">
                    <a class="btn btn-link" href="welkom.php">Cancel</a>
                </div>
            </form>
        </div>    
    </body>
</html>
