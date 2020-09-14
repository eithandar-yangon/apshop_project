<?php 
session_start();
require 'config/config.php';
require 'config/common.php';


if(empty($_SESSION['user_id']) && empty($_SESSION['logged_in'])){
	header('location:login.php');
}


if($_POST){
	if(empty($_POST['name']) || empty($_POST['description']) || empty($_POST['category']) || empty($_POST['stock']) || empty($_POST['price']) || empty($_FILES['image'])){
		if(empty($_POST['name'])){
			$nameError = "Product Name can not be blank";
		}
		if(empty($_POST['description'])){
			$descError = "Description can not be blank";
		}
		if(empty($_POST['category'])){
			$catError = "Category can not be blank";
		}
		if(empty($_POST['stock'])){
			$stockError = "Stock can not be blank";
		}elseif (is_numeric($_POST['stock'])!= 1) {
			$stockError = "Stock should be integer";
		}
		if(empty($_POST['price'])){
			$priceError = "Price can not be blank";
		}elseif (is_numeric($_POST['price'])!= 1) {
			$priceError = "Price should be integer";
		}
		if(empty($_FILES['image'])){
			$imageError = "Image can not be blank";
		}
	}else{
    if($_FILES['image']['name']!=null){
      $file = 'images/'.($_FILES['image']['name']);
    $imageType = pathinfo($file,PATHINFO_EXTENSION);

    if($imageType != 'jpg'&& $imageType != 'jpeg' && $imageType != 'png' ){
      echo "<script>alert('Image must be jpg,jpeg or png')</script>";
    }

    $name = $_POST['name'];
    $description = $_POST['description'];
    $category = $_POST['category'];
    $stock = $_POST['stock'];
    $price = $_POST['price'];
    $image = $_FILES['image']['name'];

    move_uploaded_file($_FILES['image']['tmp_name'], $file);


    $stmt = $pdo->prepare("UPDATE products SET name=:name,description=:description,category_id=:category,price=:price,quantity=:quantity,image=:image WHERE id=".$_GET['id']);
    $result = $stmt->execute(
       array(':name' => $name,':description' => $description, ':category' => $category, ':price' => $price,':quantity' => $stock, ':image' => $image )
      );

    if($result){
         echo "<script>alert('Product successfully Updated');window.location.href='index.php';</script>";
    }
  }else{

    $name = $_POST['name'];
    $description = $_POST['description'];
    $category = $_POST['category'];
    $stock = $_POST['stock'];
    $price = $_POST['price'];

    $stmt = $pdo->prepare("UPDATE products SET name=:name,description=:description,category_id=:category,price=:price,quantity=:quantity WHERE id=".$_GET['id']);
    $result = $stmt->execute(
       array(':name' => $name,':description' => $description, ':category' => $category, ':price' => $price,':quantity' => $stock )
      );

    if($result){
         echo "<script>alert('Product successfully Updated');window.location.href='index.php';</script>";
    }
  }
  }
	
}


$stmt = $pdo->prepare("SELECT * FROM products WHERE id=".$_GET['id']);
$stmt->execute();
$result = $stmt->fetchAll();

// print "<pre>";
// print_r($result);
 ?>

 <?php include('header.php'); ?>
    <!-- Main content -->
    <div class="content">
      <div class="container-fluid">
        <div class="row">
          <div class="col-md-12">
            <div class="card">
              <div class="card-body">
                <form class="" action="" method="post" enctype="multipart/form-data">
                  <input name="_token" type="hidden" value="<?php echo $_SESSION['_token']; ?>">
                  <input name="id" type="hidden" value="<?php echo $result[0]['id']; ?>">
                  <div class="form-group">
                    <label for="">Name</label><p style="color:red"><?php echo empty($nameError) ? '' : '*'.$nameError; ?></p>
                    <input type="text" class="form-control" name="name" value="<?php echo escape($result[0]['name']); ?>">
                  </div>
                  <div class="form-group">
                    <label for="">Description</label><p style="color:red"><?php echo empty($descError) ? '' : '*'.$descError; ?></p>
                    <textarea class="form-control" name="description" rows="8" cols="80"><?php echo escape($result[0]['description']); ?></textarea>
                  </div>
                  <?php 
                  $catstmt = $pdo->prepare("SELECT * FROM categories");
                  $catstmt->execute();
                  $catresult= $catstmt->fetchAll();

                  // print "<pre>";
                  // print_r($catresult[0]['name']);exit();
                   ?>
                  <div class="form-group">
                    <label for="">Category</label><p style="color:red"><?php echo empty($catError) ? '' : '*'.$catError; ?></p>
                    <select class="form-control" name="category">
                    	<option value="">Select Category</option>
                    	<?php foreach ($catresult as $value) { ?>
                    		<?php if($value['id']==$result[0]['category_id']): ?>
                          <option value="<?php echo $value['id']; ?>" selected>
                          <?php echo $value['name']; ?>
                        </option>
                        <?php else: ?>
                          <option value="<?php echo $value['id']; ?>">
                          <?php echo $value['name']; ?>
                        </option>
                        <?php endif; ?>

                    	<?php } ?>
                    

                    </select>
                  </div>
                  <div class="form-group">
                    <label for="">In Stock</label><p style="color:red"><?php echo empty($stockError) ? '' : '*'.$stockError; ?></p>
                    <input type="number" class="form-control" name="stock" value="<?php echo escape($result[0]['quantity']); ?>">
                  </div>
                  <div class="form-group">
                    <label for="">Price</label><p style="color:red"><?php echo empty($priceError) ? '' : '*'.$priceError; ?></p>
                    <input type="number" class="form-control" name="price" value="<?php echo escape($result[0]['price']); ?>">
                  </div>
                  <div class="form-group">
                    <label for="">Image</label><p style="color:red"><?php echo empty($imageError) ? '' : '*'.$imageError; ?></p>
                    <img src="images/<?php echo $result[0]['image']; ?> " width="100" height="100"> <br>
                    <input type="file" name="image" value="">
                  </div>
                  <div class="form-group">
                    <input type="submit" class="btn btn-success" name="" value="SUBMIT">
                    <a href="index.php" class="btn btn-warning">Back</a>
                  </div>
                </form>
              </div>
            </div>
            <!-- /.card -->
          </div>
        </div>
        <!-- /.row -->
      </div><!-- /.container-fluid -->
    </div>
    <!-- /.content -->
  <?php include('footer.html')?>
