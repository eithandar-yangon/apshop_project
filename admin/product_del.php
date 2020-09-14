<?php 
session_start();
require 'config/config.php';
require 'config/common.php';

$id=$_GET['id'];
$stmt = $pdo->prepare("DELETE FROM products WHERE id=$id");
$stmt->execute();

header('location:index.php');

 ?>