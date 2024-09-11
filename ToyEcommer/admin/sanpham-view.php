<?php
require_once("./config/connect.php");
?>
<!doctype html>
<html lang="en">
  <head>
    <!-- Required meta tags -->
    <meta charset="utf-8">
    <meta name="viewport" content="width=device-width, initial-scale=1">

    <!-- Bootstrap CSS -->
    <link href="https://cdn.jsdelivr.net/npm/bootstrap@5.1.3/dist/css/bootstrap.min.css" rel="stylesheet">

    <title>User View</title>
</head>
<body>

    <div class="container mt-5">

        <div class="row">
            <div class="col-md-12">
                <div class="card">
                    <div class="card-header">
                        <h4>User View Details 
                            <a href="admin.php" class="btn btn-danger float-end">BACK</a>
                        </h4>
                    </div>
                    <div class="card-body">

                        <?php
                        if(isset($_GET['id']))
                        {
                            $id = mysqli_real_escape_string($conn, $_GET['id']);
                            $query = "SELECT * FROM products WHERE product_id='$id' ";
                            $query_run = mysqli_query($conn, $query);

                            if(mysqli_num_rows($query_run) > 0)
                            {
                                $products = mysqli_fetch_array($query_run);
                                ?>
                                
                                    <div class="mb-3">
                                        <label>User Name</label>
                                        <p class="form-control">
                                            <?= htmlspecialchars($products['name'], ENT_QUOTES, 'UTF-8'); ?>
                                        </p>
                                    </div>
                                    <div class="mb-3">
                                        <label>User Description</label>
                                        <p class="form-control">
                                            <?= htmlspecialchars($products['description'], ENT_QUOTES, 'UTF-8'); ?>
                                        </p>
                                    </div>
                                    <div class="mb-3">
                                        <label>User Price</label>
                                        <p class="form-control">
                                        
                                            <?= number_format(htmlspecialchars($products['price'], ENT_QUOTES, 'UTF-8'),0,'',','); ?>
                                        </p>
                                    </div>
                                    <div class="mb-3">
                                        <label>Stock</label>
                                        <p class="form-control">
                                            <?= htmlspecialchars($products['stock'], ENT_QUOTES, 'UTF-8'); ?>
                                        </p>
                                    </div>
                                    <div class="mb-3">
                                        <label>image_url</label>
                                        <p class="form-control">
                                            <?= htmlspecialchars($products['image_url'], ENT_QUOTES, 'UTF-8'); ?>
                                        </p>
                                    </div>
                                    <div class="mb-3">
                                        <label>Discount</label>
                                        <p class="form-control">
                                            <?= htmlspecialchars($products['discount'], ENT_QUOTES, 'UTF-8'); ?>
                                        </p>
                                    </div>
                                    

                                <?php
                            }
                            else
                            {
                                echo "<h4>No Such Id Found</h4>";
                            }
                        }
                        else
                        {
                            echo "<h4>No ID Provided</h4>";
                        }
                        ?>
                    </div>
                </div>
            </div>
        </div>
    </div>
    
    <script src="https://cdn.jsdelivr.net/npm/bootstrap@5.1.3/dist/js/bootstrap.bundle.min.js"></script>
</body>
</html>
