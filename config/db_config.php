<?php
/**
 * @author Bas van Evelingen <BasvanEvelingen@me.com>
 * Weekopdracht 4 update de blog
 * Configuratiebestand voor het openen van een database connectie.
 * Database credenties , require once in andere php-bestanden
 */
define('DB_SERVER', 'localhost');
define('DB_USERNAME', 'admin');
define('DB_PASSWORD', 'admin');
define('DB_NAME', 'basblogweek4');

/* Poging om connectie te maken met database */
$connection = new mysqli(DB_SERVER, DB_USERNAME, DB_PASSWORD, DB_NAME);

// Connectie controleren
if($connection === false) {
    die("ERROR: Kon geen verbinding maken. " . $mysqli->connect_error);
}
//echo "Connectie succesvol: ";
?>
