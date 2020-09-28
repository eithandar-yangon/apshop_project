<?php
session_start();
require '../config/config.php';
require '../config/common.php';


if (empty($_SESSION['user_id']) && empty($_SESSION['logged_in'])) {
  header('Location: /admin/login.php');
}
if($_SESSION['role'] != 1){
  header('Location: /admin/login.php');
}
?>


<?php include('header.php'); ?>
    <!-- Main content -->
    <div class="content">
      <div class="container-fluid">
        <div class="row">
          <div class="col-md-12">
            <div class="card">
              <div class="card-header">
                <h3 class="card-title">Product Listings</h3>
              </div>
              <?php
              $currentDate = date('Y-m-d');
              $fromDate = date("Y-m-d", strtotime($currentDate . '+1 day'));
              $toDate = date("Y-m-d", strtotime($currentDate . '-7 day'));
              // echo $fromDate;exit();
               $stmt = $pdo->prepare("SELECT * FROM sale_order WHERE order_date <= :fromDate AND order_date >= :toDate");
                  $stmt->execute(
                    array(':fromDate'=> $fromDate, ':toDate'=>$toDate)
                  );
                  $result = $stmt->fetchAll();

              ?>
              <!-- /.card-header -->
              <div class="card-body">
                <!-- <div>
                  <a href="product_add.php" type="button" class="btn btn-success">Create New Product</a>
                </div> -->
                <br>
                <table class="table table-bordered" id="d_table">
                  <thead>
                    <tr>
                      <th style="width: 10px">#</th>
                      <th>User Name</th>
                      <th>Total Amount</th>
                      <th>Order Date</th>
                    </tr>
                  </thead>
                  <tbody>
                    <?php
                    if ($result) {
                      $i = 1;
                      foreach ($result as $value) { ?>

                        <?php
                          $userStmt = $pdo->prepare("SELECT * FROM users WHERE id=".$value['user_id']);
                          $userStmt->execute();
                          $userResult = $userStmt->fetch(PDO::FETCH_ASSOC);
                        ?>
                        <tr>
                          <td><?php echo $i;?></td>
                          <td><?php echo escape($userResult['name'])?></td>
                          <td><?php echo escape($value['total_price'])?></td>
                          <td><?php echo escape(date("Y-m-d",strtotime($value['order_date'])))?></td>
                        </tr>
                    <?php
                      $i++;
                      }
                    }
                    ?>
                  </tbody>
                </table><br>
                <!-- <nav aria-label="Page navigation example" style="float:right">
                  <ul class="pagination">
                    <li class="page-item"><a class="page-link" href="?pageno=1">First</a></li>
                    <li class="page-item <?php if($pageno <= 1){ echo 'disabled';} ?>">
                      <a class="page-link" href="<?php if($pageno <= 1) {echo '#';}else{ echo "?pageno=".($pageno-1);}?>">Previous</a>
                    </li>
                    <li class="page-item"><a class="page-link" href="#"><?php echo $pageno; ?></a></li>
                    <li class="page-item <?php if($pageno >= $total_pages){ echo 'disabled';} ?>">
                      <a class="page-link" href="<?php if($pageno >= $total_pages) {echo '#';}else{ echo "?pageno=".($pageno+1);}?>">Next</a>
                    </li>
                    <li class="page-item"><a class="page-link" href="?pageno=<?php echo $total_pages?>">Last</a></li>
                  </ul>
                </nav> -->
              </div>
              <!-- /.card-body -->

            </div>
            <!-- /.card -->
          </div>
        </div>
        <!-- /.row -->
      </div><!-- /.container-fluid -->
    </div>
    <!-- /.content -->
     
  <?php include('footer.php')?>
<script>
  $( document ).ready(function() {
    $('#d_table').DataTable();
  });
  </script>