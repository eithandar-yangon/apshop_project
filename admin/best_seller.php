<?php
session_start();
require '../config/config.php';
require '../config/common.php';


if (empty($_SESSION['user_id']) && empty($_SESSION['logged_in'])) {
 header('Location: /apshop/admin/login.php');
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
                <h3 class="card-title">Royal Users</h3>
              </div>
              <?php
                $currentDate = date("Y-m-d");
                $stmt = $pdo->prepare("SELECT * FROM sale_order_detail GROUP BY product_id HAVING SUM(quantity) > 5 ORDER BY id DESC");
                $stmt->execute();
                $result = $stmt->fetchAll();
              ?>
              <!-- /.card-header -->
              <div class="card-body">
                <table class="table table-bordered" id="d-table">
                  <thead>
                    <tr>
                      <th style="width: 10px">#</th>
                      <th>Product Name</th>
                    </tr>
                  </thead>
                  <tbody>
                    <?php
                    if ($result) {
                      $i = 1;
                      foreach ($result as $value) { ?>

                        <?php
                          $pStmt = $pdo->prepare("SELECT * FROM products WHERE id=".$value['product_id']);
                          $pStmt->execute();
                          $pResult = $pStmt->fetch(PDO::FETCH_ASSOC);
                        ?>
                        <tr>
                          <td><?php echo $i;?></td>
                          <td><?php echo escape($pResult['name'])?></td>
                        </tr>
                    <?php
                      $i++;
                      }
                    }
                    ?>
                  </tbody>
                </table><br>

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
