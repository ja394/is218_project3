<?php
ini_set('display_errors', 1);
ini_set('display_startup_errors', 1);
error_reporting(E_ALL);
session_start();
require_once "connect.php";

$account = $_SESSION["account"];
if ($_SERVER['REQUEST_METHOD'] === 'POST') {
    $title = filter_input(INPUT_POST, 'title');
    $body = filter_input(INPUT_POST, 'body');
    $skills = explode(",", filter_input(INPUT_POST, 'skills'));

    $valid_title = strlen($title) > 2;
    $valid_body = strlen($body) > 0;
    $valid_skills = count($skills) > 1;

    if($valid_title && $valid_body && $valid_skills) {
        // TODO: Create the question
        $query = $db->prepare("INSERT INTO is218_questions (account_email, title, body, skills)
                VALUES(:email, :title, :body, :skills)");

        $query->bindParam(':email', $account["email"]);
        $query->bindParam(':title', $title);
        $query->bindParam(':body', $body);
        $query->bindParam(':skills', implode(',', $skills));

        try {
            $query->execute();
            header("Location: ./");
            exit();
        } catch(PDOException $err) {
            echo "Account creation failed: " . $err->getMessage();
        }

        $query->closeCursor();
    }
}

?>