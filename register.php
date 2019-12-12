<?php
ini_set('display_errors', 1);
ini_set('display_startup_errors', 1);
error_reporting(E_ALL);
require_once "connect.php";

if ($_SERVER['REQUEST_METHOD'] === 'POST') {

    $dob = filter_input(INPUT_POST, 'dob');
    $fname = filter_input(INPUT_POST, 'fname');
    $lname = filter_input(INPUT_POST, 'lname');
    $email = filter_input(INPUT_POST, 'email');
    $passwd = filter_input(INPUT_POST, 'psw');

    $valid_password = strlen($passwd) > 7;
    $valid_email = strlen($email) > 0 && strpos($email, '@') != false;
    $valid_lname = strlen($lname) > 0;
    $valid_fname = strlen($fname) > 0;
    $valid_dob = strlen($dob) > 0;

    $email_taken = false;

    if ($valid_email && $valid_password && $valid_lname && $valid_fname && $valid_dob) {
        $query = $db->prepare("SELECT COUNT(*) FROM `is218_accounts` WHERE email = :email");
        $query->bindParam(':email', $email);

        try {
            $query->execute();
            $accounts = $query->fetchColumn();
        } catch (PDOException $err) {
            echo "Account selection failed: " . $err->getMessage();
        }

        if (!$accounts) {

            $query = $db->prepare("INSERT INTO is218_accounts (email, fname, lname, dob, passwd)
                VALUES(:email, :fname, :lname, :dob, :passwd)");

            $query->bindParam(':dob', $dob);
            $query->bindParam(':email', $email);
            $query->bindParam(':fname', $fname);
            $query->bindParam(':lname', $lname);
            $query->bindParam(':passwd', $passwd);

            try {
                $query->execute();
                $_SESSION["account"] = array('email' => $email, 'fname' => $fname, 'lname' => $lname, 'dob' => $dob);
                header("Location: ./");
                exit();
            } catch (PDOException $err) {
                echo "Account creation failed: " . $err->getMessage();
            }
        } else {
            $email_taken = true;
        }

        $query->closeCursor();
    }
}
?>