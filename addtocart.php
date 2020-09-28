<?php 
session_start();
require 'config/config.php';
require 'config/common.php';

 if(empty($_SESSION['user_id'])&& empty($_SESSION['logged_in'])){
					 	header('location:/apshop/login.php');
					 }

if($_POST){
	$id=$_POST['id'];
	$qty=$_POST['qty'];

	$stmt = $pdo->prepare("SELECT * FROM products WHERE id=".$id);
	$stmt->execute();
	$result = $stmt->fetch(PDO::FETCH_ASSOC);

	if($qty > $result['quantity']){
		echo "<script>alert('No enough stock');window.location.href='product_detail.php?id=$id'</script>";
	}else{
		if(isset($_SESSION['cart']['$id'])){
			$_SESSION['cart']['id'.$id]+=$qty;
		}else{
			$_SESSION['cart']['id'.$id] = $qty;
	}
		header("location:cart.php");

	}


}



 ?>