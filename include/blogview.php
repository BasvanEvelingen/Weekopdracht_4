<?php 
/**
 * @author Bas van Evelingen <BasvanEvelingen@me.com>
 * Weekopdracht 4 maak een blog
 * Version 3.0
 * View met sorteren op datum of auteur.
 * De zogenaamde bloglezer.
 * DB name = basblogweek3 Table = berichten
 * Veldnamen BerichtID|BerichtTitel|BerichtOmschrijving|BerichtInhoud|Auteur|BerichtDatum
 */
?>

<!DOCTYPE html>
<html>
    <head>
        <meta charset="utf-8">
        <meta http-equiv="X-UA-Compatible" content="IE=edge">
        <title>Bas Blog</title>
        <meta name="viewport" content="width=device-width, initial-scale=1">
        <link href="../css/bootstrap.css" rel="stylesheet">
        <link href="../css/style.css" rel="stylesheet">
    </head>

    <body>
        <div class="wrapper">
            <img class="blogpicture" src="../images/BasBlog.png" />
        </div>
            <div class="wrapper">
                <div class='row'>
                    <div class='col-sm-12'>
                        <div class="form-group">
                            <form action="<?php echo htmlspecialchars($_SERVER['PHP_SELF']); ?>" method="POST">
                                <label>kies categorie en druk op sorteer.</label>
                                <select name="catselect[]">
                                    <?php 
                                    echo "hier?";
                                    exit();
                                        $cat_sql = "SELECT * FROM categorieen";
                                        if ($cresult = $connection->query($cat_sql)) {
                                            echo "hier?";
                                            exit();
                                            while ($row = $cresult->fetch_array(MYSQLI_ASSOC)) {
                                                echo "hier?";
                                    ?>
                                               <option value="<?php echo $row['naam']; ?>">
                                                <?php
                                            }
                                        }
                                                ?>
                                </select>
                                <input type="submit" value="Sorteer" class="btn btn-outline-success">  
                            </form>
                        </div>
                    <?php
                        // Include config file
                        require_once "../config/db_config.php";
                        $id = $titel = $omschrijving = $inhoud =  $auteur = $datum = "";
                        // Query proberen uit te voeren
                        $sql = "SELECT * FROM berichten ORDER BY berichten.BerichtDatum DESC";
                        if ($result = $connection->query($sql)) {
                            if ($result->num_rows > 0) { 
                                while($row = mysqli_fetch_array($result)) {
                                    $id = $row['BerichtID']; 
                                    $titel = $row['BerichtTitel']; 
                                    $omschrijving = $row['BerichtOmschrijving'];
                                    $inhoud = $row['BerichtInhoud'];
                                    $auteur = $row['Auteur'];
                                    $datum =  $row['BerichtDatum'];
                    ?>
                        <hr>
                        <h1 class='mt-4'><?php echo $titel ?></h1>
                        <p class='lead'>Door: <?php echo $auteur ?></p>
                        <hr>
                        <p>Posted on: <?php echo $datum ?></p>;
                        <hr>
                        <h3><?php echo $omschrijving ?></h3>";
                        <hr>
                        <p>
                            <?php
                            $csql = "SELECT c.naam FROM categorie_bericht cb JOIN categorieen c ON cb.categorie_id=c.id WHERE cb.bericht_id = '$id'";
                            $catstring = "";
                            if ($cresult = $connection->query($csql)) {
                                while($row = $cresult->fetch_array(MYSQLI_ASSOC)) {
                                    $catstring.= $row['naam'].',';
                                }
                            }
                            echo substr_replace($catstring ,"",-1);
                            ?>
                        </p>
                        <hr>
                        <p class='lead'><?php echo $inhoud ?></p";
                        <hr>
                    <?php
                                }
                            }
                        }
                    ?>
                    </div>
                </div>
            </div>
    </body>
</html>
