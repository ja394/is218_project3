<?php
session_start();
require_once "connect.php";

if($_SERVER['REQUEST_METHOD'] === 'POST') {
    $email = filter_input(INPUT_POST, 'email');
    $password = filter_input(INPUT_POST,'psw');
}

$valid_email = preg_match("/.+@.+\..+/", $email);
$valid_password = strlen($password) > 7;

if ($valid_email && $valid_password) {
    $query = $db->prepare("SELECT email, fname, lname, dob FROM `is218_accounts` WHERE email = :email AND passwd = :password");
    $query->bindParam(':email', $email);
    $query->bindParam(':password', $password);

    try {
        $query->execute();
        $row = $query->fetch();
    } catch (PDOException $err) {

    }

    $query->closeCursor();

    if(!empty($row)) {
        $_SESSION["account"] = array('email' => $email, 'fname' => $row['fname'], 'lname' => $row['lname'], 'dob' => $row['dob']);
        header("Location: ./");
        exit();
    } else {
        header("Location: ./login.html");
        exit();
    }

}
?>