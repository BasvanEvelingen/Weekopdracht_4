<?php
/**
 * @author Bas van Evelingen <BasvanEvelingen@me.com>
 * @version 2.0
 * Weekopdracht 3 uitlogpagina
 */
// Initialiseer sessie, gooi alle variabelen weg, en vernietig sessie.

session_start();
$_SESSION = array();
session_destroy();
header("location: login.php");
exit();
?>
