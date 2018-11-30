<!--
 * @author Bas van Evelingen <BasvanEvelingen@me.com>
 * Weekopdracht 4 maak een blog
 * CRUD opzet Hoofdpagina
 * Berichten uit blog en userinterface weergeven
 * DB name = basblog Table = berichten
 * Veldnamen BerichtID|BerichtTitel|BerichtOmschrijving|BerichtInhoud|Auteur|BerichtDatum 
-->
<?php 
    require_once('config/db_config.php');
    require_once('lib/functions.php');
    session_start();

    // Kijk of de gebruiker al ingelogd is?
    if (!isset($_SESSION["loggedin"]) || $_SESSION["loggedin"] !== true) {
        header("location: user/login.php");
        exit();
    // categorie sql 
    $cat_sql = "SELECT * FROM categorieen";

    // Welke rol heeft de user? 
    } else {
        if (isAdmin($_SESSION["username"])) {
            $deletedisplay="initial";
            $sql = "SELECT * FROM berichten ORDER BY BerichtDatum DESC";
        } else {
            $deletedisplay="none";
            $name = $_SESSION["username"];
            $sql = "SELECT * FROM berichten WHERE berichten.Auteur = '$name' ORDER BY BerichtDatum DESC";
        }
    }
?>

<!DOCTYPE html>
<html>
    <head>
        <meta charset="utf-8">
        <meta http-equiv="X-UA-Compatible" content="IE=edge">
        <title>Bas Blog</title>
        <meta name="viewport" content="width=device-width, initial-scale=1">
        <link href="css/bootstrap.css" rel="stylesheet">
        <link href="css/style.css" rel="stylesheet"> 
        <link href="https://use.fontawesome.com/releases/v5.5.0/css/all.css" integrity="sha384-B4dIYHKNBt8Bc12p+WXckhzcICo0wtJAoU8YZTY5qE0Id1GSseTk6S+L3BlXeVIU" crossorigin="anonymous" rel="stylesheet">
        <script src="js/jquery.js"></script>
        <script src="js/bootstrap.min.js"></script>
    </head>

    <body>
        <div class="wrapper">
            <div class="container-fluid">
                <div class="row">
                    <div class="col-md-12">
                        <div class="page-header clearfix">
                            <h1>Bas Blog</h1>
                            <h2 class="float-left">Berichten</h2>
                            <a href="user/welkom.php" class="btn btn-outline-warning float-right blogbutton">Hoofdmenu</a>
                            <a href="include/nieuwecategorie.php" class="btn btn-outline-success float-right blogbutton">Nieuwe Categorie</a>
                            <a href="include/nieuwbericht.php" class="btn btn-outline-primary float-right blogbutton">Nieuw Bericht</a>
                            <a style="display:<?php echo $deletedisplay; ?>" href="user/verwijderuserindex.php" class="btn btn-outline-danger float-right blogbutton">Verwijder User(s)</a>
                        </div>
                        <?php
                            if ($result = $connection->query($sql)) {
                                if ($result->num_rows > 0) {
                                    ?>
                                    <!-- Berichten tabel beginnend met de koppen van de kolommen -->
                                    <table class='table table-bordered table-striped'>
                                        <thead>
                                            <tr>
                                                <th>id</th>
                                                <th>titel</th>
                                                <th>omschrijving</th>
                                                <th>inhoud</th>
                                                <th>auteur</th>
                                                <th>datum</th>
                                                <th>acties</th>
                                            </tr>
                                        </thead>
                                        <!-- hier komen de details van de berichten-->
                                        <tbody>
                                        <?php 
                                        while($row = $result->fetch_array(MYSQLI_ASSOC)) {
                                            ?>
                                            <tr>
                                                <td><?php echo $row['BerichtID']; ?></td>
                                                <td><?php echo $row['BerichtTitel']; ?></td>
                                                <td><?php echo $row['BerichtOmschrijving']; ?></td>
                                                <td><?php echo $row['BerichtInhoud']; ?></td>
                                                <td><?php echo $row['Auteur']; ?></td>
                                                <td><?php echo $row['BerichtDatum']; ?></td>
                                                <td>

                                                    <a href='include/leesbericht.php?id=<?php echo $row['BerichtID']; ?>' title='Lees Bericht' data-toggle='tooltip'><i class='far fa-eye fa-2x'></i></a>
                                                    <a href='include/updatebericht.php?id=<?php echo $row['BerichtID']; ?>' title='Bewerk Bericht' data-toggle='tooltip'><i class='far fa-edit fa-2x'></i></a>
                                                    <a href='include/verwijderbericht.php?id=<?php echo $row['BerichtID']; ?>' title='Verwijder Bericht' data-toggle='tooltip'><i class='far fa-trash-alt fa-2x'></i></a>
                                                </td>
                                            </tr>
                                        <?php 
                                        }
                                        ?>
                                        </tbody>
                                    </table>
                                    <?php
                                    // result vrijgeven
                                    $result->free();
                                } else {
                                    echo "Geen berichten gevonden.";
                                }
                            } else {
                                echo "ERROR: Kon volgende query niet uitvoeren: $sql. " . $connection->error;
                            }
                            // sluit connectie
                            $connection->close();
                        ?>
                    </div>
                    <p>&copy; <?php echo date("Y");?></p>
                </div>
            </div>
        </div>
    </body>
</html>
