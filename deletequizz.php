<?php
/**
 * Created by PhpStorm.
 * User: Fynne
 * Date: 29.01.2018
 * Time: 10:22
 */
require_once '../database/config.php';
require('../layout/header.php');
if (!isset($_SESSION['username']) || empty($_SESSION['username'])) {
    header("location: login.php");
    exit;
}

$quizID = processText($_GET['id']);

$sql = "DELETE FROM quiz WHERE quizID='$quizID' AND id='{$_SESSION['id']}'";

if (mysqli_query($link, $sql)) {
    header("location: quizzes.php");
    exit;
} else {
    http_response_code(404);
}
?>
<?php
?>
