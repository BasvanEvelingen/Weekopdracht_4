<?php
/** 
 * @author Bas van Evelingen <BasvanEvelingen@me.com>
 * @version 0.0.3
 * Weekopdracht 4 verwijder user selectie pagina
*/
//
session_start();
require_once "../config/db_config.php";
include "../lib/functions.php";


// Kijk of de gebruiker al ingelogd is en Admin is?
if (!isset($_SESSION["loggedin"]) || $_SESSION["loggedin"] !== true || !isAdmin($_SESSION['username'])) {
    header("location: login.php");
    exit();
}
?>

<!DOCTYPE html>
<html>
    <head>
        <meta charset="utf-8">
        <meta http-equiv="X-UA-Compatible" content="IE=edge">
        <title>Verwijder user</title>
        <meta name="viewport" content="width=device-width, initial-scale=1">
        <link href="../css/bootstrap.css" rel="stylesheet">
        <link href="../css/style.css" rel="stylesheet"> 
    </head>
    <body>
        <div class="wrapper">
            <div class="container-fluid">
                <div class="row">
                    <div class="col-md-12">
                        <div class="page-header clearfix">
                            <h1>Bas Blog</h1>
                            <h2 class="pull-left">Selecteer users om te verwijderen</h2>
                            <a href="../index.php" class="btn btn-outline-primary float-right blogbutton">Blog Administratie</a>
                        </div>
                        <?php
                            // Query proberen uit te voeren
                            $sql = "SELECT * FROM users";
                            if ($result = $connection->query($sql)) {
                                if ($result->num_rows > 0) {
                                    ?>
                                    <!-- Berichten tabel beginnend met de koppen van de kolommen -->
                                    <form action="deleteuser.php" method="POST">
                                        <table class='table table-bordered table-striped'>
                                            <thead>
                                                <tr>
                                                    <th>verwijderen</th>
                                                    <th>id</th>
                                                    <th>username</th>
                                                    <th>datum van registratie</th>
                                                </tr>
                                            </thead>
                                            <!-- hier komen de details van de berichten-->
                                            <tbody>               
                                                <?php                                                   
                                                    while($row = $result->fetch_array(MYSQLI_ASSOC)) {
                                                    ?>
                                                        <tr> 
                                                                <td><input type="checkbox" name="tableCheck[]" value="<?php echo $row['id']; ?>"></td>
                                                                <td><?php echo $row['id']; ?></td>
                                                                <td><?php echo $row['username']; ?></td>
                                                                <td><?php echo $row['created_at']; ?></td>
                                                        </tr>
                                                <?php 
                                                    }
                                                    ?>
                                            </tbody>
                                        </table>
                                        <input class="btn btn-danger" type="submit" value="Verwijder"/>
                                    </form>
                                    <?php
                                    // result vrijgeven
                                    $result->free();
                                } else {
                                    echo "Geen users gevonden.";
                                }
                            } else {
                                echo "ERROR: Kon volgende query niet uitvoeren: $sql. " . $connection->error;
                            }
                            // sluit connectie
                            $connection->close();
                        ?>
                    </div>
                </div>
            </div>
        </div>
    </body>
</html>
