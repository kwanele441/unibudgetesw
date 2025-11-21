<?php
session_start();
require_once "database.php";
if (!isset($_SESSION['loggedUserId'])) { header("Location: index.php"); exit(); }

$id = $_GET['id'];
$db->prepare("DELETE FROM goals WHERE goal_id = ? AND user_id = ?")
    ->execute([$id, $_SESSION['loggedUserId']]);
header("Location: expense.php");
?>