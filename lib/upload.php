<?php
    $currentDir = getcwd();
    $uploadDirectory = "../uploads";
    $errors = []; // Errors hier opslaan
    $fileExtensions = ['jpeg','jpg','png','gif']; // De bestandsformaten die blog ondersteunt.
    $fileName = $_FILES['myfile']['name'];
    $fileSize = $_FILES['myfile']['size'];
    $fileTmpName  = $_FILES['myfile']['tmp_name'];
    $fileType = $_FILES['myfile']['type'];
    $fileExtension = strtolower(end(explode('.',$fileName)));
    $uploadPath = $currentDir . $uploadDirectory . basename($fileName); 
    echo $uploadPath;
    if (isset($_POST['submit'])) {
        if (! in_array($fileExtension,$fileExtensions)) {
            $errors[] = "Dit bestandsformaat is niet toegestaan. Gebruik alstublieft een JPEG,GIF of PNG-bestand.";
        }
        if ($fileSize > 2000000) {
            $errors[] = "Het bestand is meer dan 2 megabyte. Sorry, dat is te veel.";
        }
        if (empty($errors)) {
            $didUpload = move_uploaded_file($fileTmpName, $uploadPath);
            if ($didUpload) {
                echo "Het plaatje " . basename($fileName) . " is geplaatst.";
            } else {
                echo "Er ging iets mis.";
            }
        } else {
            foreach ($errors as $error) {
                echo $error . "These are the errors" . "\n";
            }
        }
    }
?>
