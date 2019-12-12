<?php
session_start();
require_once "connect.php";

$id = filter_input(INPUT_GET, 'id');

$query = $db->prepare("DELETE FROM is218_questions WHERE id = :id");
$query->bindParam(':id', $id);

try {
    $query->execute();
    header("Location: ./");
    exit();
} catch(PDOException $err) {
    echo "Account creation failed: " . $err->getMessage();
    return false;
}

$query->closeCursor();

?>