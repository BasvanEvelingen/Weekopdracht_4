<?php

function redirect($location) {
    return header("Location:" . $location);
}

function escape($string) {
    global $connection;
    return $connection->real_escape_string(trim($string));
}

function setMessage($msg) {
    if ($msg) {
        $_SESSION['message'] = $msg;
        var_dump("msg: " . $_SESSION['message']);
    } else {
        $msg = "";
    }
}

function displayMessage() {
    if (isset($_SESSION['message'])) {
        echo $_SESSION['message'];
        unset($_SESSION['message']);
    }
}

function confirmQuery($result) {
    global $connection;
    if (!$result) {
        die("QUERY FAILED ." . $connection->error);
    }
}

function isAdmin($username) {
    global $connection;
    $query = "SELECT role FROM users WHERE users.username = '$username'";
    $result = $connection->query($query);
    confirmQuery($result);
    $row = $result->fetch_array(MYSQLI_ASSOC);
    if ($row['role'] == 'Admin') {

        return true;

    } else {

        return false;
    }
}
