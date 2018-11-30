<?php
/**
 * @author Bas van Evelingen <BasvanEvelingen@me.com>
 * @version 3.0.0
 * Weekopdracht 4 registreren van nieuwe user
 */
// Include config file
require_once "../config/db_config.php";
require_once "../lib/functions.php";

 
// Variabelen initialiseren met lege waardes.
$username = $password = $confirm_password = "";
$username_err = $password_err = $confirm_password_err = "";
// één Admin rol, en de rest zijn users voor nu.
$userrole = "User";
 
// Data van formulier verwerken.
if($_SERVER["REQUEST_METHOD"] == "POST") {
 
    // Gebruikersnaam controleren
    if(empty(trim($_POST["username"]))) {
        $username_err = "Vul uw gebruikersnaam in.";
    } else {
        // Maak een prepare statement 
        $sql = "SELECT id FROM users WHERE username = ?";
        
        if ($stmt = $connection->prepare($sql)) {
            // bind de variabelen aan het statement
            $stmt->bind_param("s", $param_username);
            
            // Parameters zetten
            $param_username = trim($_POST["username"]);
            
            // Statement uitvoeren
            if ($stmt->execute()) {
                // resultaat opslaan
                $stmt->store_result();
                
                if($stmt->num_rows == 1) {
                    $username_err = "Deze naam is al in gebruik.";
                } else {
                    $username = trim($_POST["username"]);
                }
            } else {
                echo "Er ging iets fout, probeer het later nog eens.";
            }
        }
         
        // Sluit statement.
        $stmt->close();
    }
    
    // Wachtwoord controleren
    if (empty(trim($_POST["password"]))) {
        $password_err = "Vul uw wachtwoord in.";     
    } elseif(strlen(trim($_POST["password"])) < 6) {
        $password_err = "Wachtwoord moet minimaal 6 karakters bevatten.";
    } else {
        $password = trim($_POST["password"]);
    }
    
    // Valideer of wachtwoord correspondeert met eerdere ingegeven wachtwoord.
    if (empty(trim($_POST["confirm_password"]))) {
        $confirm_password_err = "Bevestig uw wachtwoord.";     
    } else {
        $confirm_password = trim($_POST["confirm_password"]);
        if (empty($password_err) && ($password != $confirm_password)) {
            $confirm_password_err = "Wachtwoorden komen niet overeen.";
        }
    }
    
    // Valideren of de invoer goed is geweest
    if (empty($username_err) && empty($password_err) & empty($confirm_password_err)) {
        
        // Statement voor invoer gebruikersnaam voorbereiden
        $sql = "INSERT INTO users (username, password, role) VALUES (?, ?, ?)";
         
        if ($stmt = $connection->prepare($sql)) {
            // Bind variabelen aan statement.
            $stmt->bind_param("sss", $param_username, $param_password, $param_role);
            
            // zet parameters
            $param_username = $username;
            $param_password = password_hash($password, PASSWORD_DEFAULT); // wachtwoord hash
            $param_role = $userrole;
            // Statement uitvoeren
            if ($stmt->execute()) {
                // Naar de login pagina loodsen
                session_start();
                setMessage('U kunt nu inloggen.');
                header("location: login.php");
            } else {
                echo "Er ging iets fout, probeer het later nog eens.";
            }
        }
         
        // sluit statement
        $stmt->close();
    }
    
    // close statement
    $connection->close();
}
?>
 
<!DOCTYPE html>
<html lang="en">
<head>
    <meta charset="UTF-8">
    <title>Inschrijven</title>
    <link href="../css/bootstrap.css" type="text/css" rel="stylesheet">
    <link href="../css/style.css" type="text/css" rel="stylesheet">
   
</head>
<body>
    <div class="wrapper">
        <h2>Inschrijven</h2>
        <p>Vul dit formulier in om een gebruikersaccount te maken.</p>
        <form action="<?php echo htmlspecialchars($_SERVER["PHP_SELF"]); ?>" method="post">
            <div class="form-group <?php echo (!empty($username_err)) ? 'has-error' : ''; ?>">
                <label for="mp_username">Gebruikersnaam</label>
                <input type="text" id="mp_username" name="username" class="form-control" value="<?php echo $username; ?>">
                <span class="help-block"><?php echo $username_err; ?></span>
            </div>    
            <div class="form-group <?php echo (!empty($password_err)) ? 'has-error' : ''; ?>">
                <label for="mp_password">Wachtwoord</label>
                <input type="password" id="mp_password" name="password" class="form-control" value="<?php echo $password; ?>">
                <span class="help-block"><?php echo $password_err; ?></span>
            </div>
            <div class="form-group <?php echo (!empty($confirm_password_err)) ? 'has-error' : ''; ?>">
                <label for="mp_confirm_password">Bevestig wachtwoord</label>
                <input type="password" id="mp_confirm_password" name="confirm_password" class="form-control" value="<?php echo $confirm_password; ?>">
                <span class="help-block"><?php echo $confirm_password_err; ?></span>
            </div>
            <div class="form-group">
                <input type="submit" class="btn btn-primary" value="Bevestig">
                <input type="reset" class="btn btn-default" value="Herstel">
            </div>
            <p>Heeft u al een gebruikersnaam?<a href="login.php">Hier inloggen.</a>.</p>
        </form>
    </div>    
</body>
</html>
