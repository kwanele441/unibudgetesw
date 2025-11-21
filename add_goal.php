<?php
session_start();
require_once "database.php";
if (!isset($_SESSION['loggedUserId'])) { header("Location: index.php"); exit(); }

$name = $_POST['name'];
$description = $_POST['description'];
$target_amount = $_POST['target_amount'] ?: null;
$target_date = $_POST['target_date'] ?: null;
$db->prepare("INSERT INTO goals (user_id, name, description, target_amount, target_date) VALUES (?, ?, ?, ?, ?)")
    ->execute([$_SESSION['loggedUserId'], $name, $description, $target_amount, $target_date]);
header("Location: expense.php");
?>