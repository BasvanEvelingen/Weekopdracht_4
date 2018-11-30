<?php
/**
 * @author Bas van Evelingen <BasvanEvelingen@me.com>
 * Weekopdracht 4 login pagina.
 */
// sessie starten
session_start();
require_once "../config/db_config.php";
require_once "../lib/functions.php";

// Kijken of gebruiker al ingelogd is.
if(isset($_SESSION["loggedin"]) && $_SESSION["loggedin"] === true) 
{
    header("location: welkom.php");
    exit;
}

// Variabelen initialiseren
$username = $password = "";
$username_err = $password_err = "";
 
// Data van formulier verwerken
if ($_SERVER["REQUEST_METHOD"] == "POST") {
 
    // Gebruikersnaam niet leeg
    if (empty(trim($_POST["username"]))) {
        $username_err = "Vul uw gebruikersnaam in.";
    } else {
        $username = trim($_POST["username"]);
    }
    
    // Wachtwoord leeg?
    if (empty(trim($_POST["password"]))) {
        $password_err = "Vul uw wachtwoord in.";
    } else {
        $password = trim($_POST["password"]);
    }
    
    //  Wachtwoord controleren
    if (empty($username_err) && empty($password_err)) {
        // Prepare a select statement
        $sql = "SELECT id,username, password FROM users WHERE username = ?";
        
        if ($stmt = $connection->prepare($sql)) {
            // Bind variables to the prepared statement as parameters
            $stmt->bind_param("s", $param_username);
            // Set parameters
            $param_username = $username;
            
            // Attempt to execute the prepared statement
            if ($stmt->execute()) {
                // Store result
                $stmt->store_result();
                
                // Check if username exists, if yes then verify password
                if ($stmt->num_rows == 1) {                    
                    // Bind result variables
                    $stmt->bind_result($id, $username, $hashed_password);
                    if ($stmt->fetch()) {
                        if (password_verify($password, $hashed_password)) {
                            // Password is correct, so start a new session
                            session_start();

                            // Store data in session variables
                            $_SESSION["loggedin"] = true;
                            $_SESSION["id"] = $id;
                            $_SESSION["username"] = $username;                        
                            
                            // Gebruiker naar de welkompagina loodsen
                            header("location: welkom.php");
                        } else {
                            // Wachtwoord fout
                            $password_err = "Het ingegeven wachtwoord is niet juist.";
                        }
                    }
                } else {
                    // Gebruikersnaam bestaat niet
                    $username_err = "Geen gebruiker gevonden met die naam.";
                }
            } else {
                echo "Er ging iets fout, probeer het later nog eens.";
            }
        }
        
        // Close statement
        $stmt->close();
    }
    
    // Close connection
    $connection->close();
}
?>
 
<!DOCTYPE html>
<html lang="en">
<head>
    <meta charset="UTF-8">
    <title>Login</title>
    <link href="../css/bootstrap.css" rel="stylesheet">
    <link href="../fonts/font.css" type="text/css" rel="stylesheet">
    <link href="../css/style.css" type="text/css" rel="stylesheet"> 
</head>
<body>
    <div class="wrapper">
        <img class="blogpicture" src="../images/BlogLogin.png" />
        <h1>Login</h1>
        <h2><?php displayMessage(); ?></h2>
        <p>Vul uw gegevens in.</p>
        <form action="<?php echo htmlspecialchars($_SERVER["PHP_SELF"]); ?>" method="post">
            <div class="form-group <?php echo (!empty($username_err)) ? 'has-error' : ''; ?>">
                <label>Gebruikersnaam</label>
                <input type="text" name="username" class="form-control" value="<?php echo $username; ?>">
                <span class="help-block"><?php echo $username_err; ?></span>
            </div>    
            <div class="form-group <?php echo (!empty($password_err)) ? 'has-error' : ''; ?>">
                <label>Wachtwoord</label>
                <input type="password" name="password" class="form-control">
                <span class="help-block"><?php echo $password_err; ?></span>
            </div>
            <div class="form-group">
                <input type="submit" class="btn btn-outline-primary" value="Login">  
            </div>
        </form>
        <p>Bent u nog geen gebruiker? <a href="register.php">Schrijf u nu in.</a></p>
    </div>    
</body>
</html>
