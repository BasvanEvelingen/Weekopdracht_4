<?php
/**
 * @author Bas van Evelingen <BasvanEvelingen@me.com>
 * Weekopdracht 4 user(s) uit database verwijderen
 * DB name = basblogweek4 Table = users
 * Veldnamen userid|username|role|password|created_at 
 */
// User(s) verwijderen na bevestiging
// TODO data uit checkboxen hier in krijgen in een komma delemited string met ints.
if (!empty($_POST["tableCheck"])) {
    // Include config file
    $users = implode(",",$_POST["tableCheck"]);
    require_once "../config/db_config.php";
    
    // statement voorbereiden array met één of meerdere users
    $sql = "DELETE FROM users WHERE id IN ($users)";
    
    if ($stmt = $connection->prepare($sql)) {
        // Bind variabele aan parameters
        $stmt->bind_param("i", $param_id);
        
        // parameter zetten
        $param_id = trim($_POST["id"]);
        
        // poging wagen statement uit te voeren
        if ($stmt->execute()) {
            // Succes bericht verwijderd terug naar homepage
            header("location: ../index.php");
            exit();
        } else {
            echo "Er ging iets fout, probeer het later nog eens";
        }
    }
     
    // Sluit statement
    $stmt->close();
    
    // Sluit connectie
    $connection->close();
} else {
    // Nog geen bevestiging uit form maar wel data uit checkboxen?
    if (empty(trim($_POST["tableCheck"]))) {
        // Nee?, ga naar error page
        header("location: ../include/error.php");
        exit();
    } else {
        // debug
        $data = $POST["tableCheck"];
        var_dump("data: " . $data);
        exit();
    }
}
?>
<!DOCTYPE html>
<html lang="en">
    <head>
        <meta charset="UTF-8">
        <title>Verwijder user</title>
        <link href="../css/bootstrap.css" rel="stylesheet">
        <link href="../css/style.css" rel="stylesheet">
    </head>
    <body>
        <div class="wrapper">
            <div class="container-fluid">
                <div class="row">
                    <div class="col-md-12">
                        <div class="page-header">
                            <h1>Verwijder Bericht</h1>
                        </div>
                        <form action="<?php echo htmlspecialchars($_SERVER["PHP_SELF"]); ?>" method="POST">
                            <div class="alert alert-danger fade in">
                                <input type="hidden" name="users" value="<?php echo trim($_POST["tableCheck"]); ?>"/>
                                <p>Weet u zeker dat u de user(s) wilt verwijderen?</p><br>
                                <p>
                                    <input type="submit" value="Yes" class="btn btn-danger">
                                    <a href="../index.php" class="btn btn-default">No</a>
                                </p>
                            </div>
                        </form>
                    </div>
                </div>        
            </div>
        </div>
    </body>
</html>
