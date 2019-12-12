<html>
<?php
session_start();
require_once "connect.php";

if(!$_SESSION["account"]) {
    header("Location: ./login.html");
    exit();
}

$account = $_SESSION["account"];

if ($_SERVER['REQUEST_METHOD'] === 'GET') {
    $query = $db->prepare("SELECT * FROM is218_questions WHERE account_email = :email");
    $query->bindParam(':email', $account["email"]);

    try {
        $query->execute();
        $questions = $query->fetchall();
    } catch(PDOException $err) {
        echo "Can't load questions: " . $err->getMessage();
    }

    $query->closeCursor();
}
?>

<h2>Questions for User with ID: <?php echo $_SESSION["account"]["fname"] . " " . $_SESSION["account"]["lname"]; ?></h2>

<table class="table">
    <tr>
        <th>ID</th>
        <th>Name</th>
        <th>Body</th>
    </tr>
    <?php foreach ($questions as $question) : ?>
        <tr>
            <td><?php echo $question['id']; ?></td>
            <td><?php echo $question['title']; ?></td>
            <td><?php echo $question['body']; ?></td>
            <td><a href="./deleteq.php?id=<?php echo $question['id'];?>">Delete</a></td>
        </tr>
    <?php endforeach; ?>
</table>
</html>