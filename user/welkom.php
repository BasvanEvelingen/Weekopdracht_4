<?php
/**
 * @author Bas van Evelingen <BasvanEvelingen@me.com>
 * @version 3.0
 * Weekopdracht 4 welkom pagina, hoofdmenu.
 */
// Initialiseer session
session_start();
 
// Kijk of de gebruiker al ingelogd is?
if (!isset($_SESSION["loggedin"]) || $_SESSION["loggedin"] !== true) {
    header("location: login.php");
    exit;
}
?>
<!DOCTYPE html>
<html lang="en">
    <head>
        <meta charset="UTF-8">
        <title>Welkom Pagina</title>
        <link href="https://fonts.googleapis.com/css?family=Bitter" rel="stylesheet">
        <link href="../fonts/font.css" type="text/css" rel="stylesheet">
        <link href="../css/bootstrap.css" type="text/css" rel="stylesheet">
        <link href="../css/style.css" type="text/css" rel="stylesheet">
    </head>
    <body>
        <div class="wrapper welkomscherm">
            <div class="page-header">
                
                <img src="../images/BasBlog.png" />
                <h3>Hallo, <b><?php echo htmlspecialchars($_SESSION["username"]); ?></b>. Welkom bij mijn Blog.</h3>
            </div>
            <p>
                <a href="../index.php" class="btn btn-outline-primary">Blog Administratie</a>
                <a href="../include/blogview.php"  class="btn btn-outline-info">Blog View</a> 
                <a href="reset-password.php" class="btn btn-outline-warning">Herstel uw wachtwoord</a>
                <a href="logout.php" class="btn btn-outline-danger">Uitloggen</a>
            </p>
            <p class="lead"> P.S. de Blog View is voor gasten en heeft geen interface om hier terug te komen.</p>
        </div>
    </body>
</html>
