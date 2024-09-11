<?php
session_start();
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

    <title>products Edit</title>
</head>
<body>
  
    <div class="container mt-5">

    <?php include('./config/message.php'); ?>

        <div class="row">
            <div class="col-md-12">
                <div class="card">
                    <div class="card-header">
                        <h4>products Edit 
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
                                <form action="editsanpham.php" method="POST">
                                 <input type="hidden" name="products_id" value="<?= $products['product_id']; ?>">


                                    <div class="mb-3">
                                        <label>image_url</label>
                                        <input type="file" name="image_url" value="<?=$products['image_url'];?>" class="form-control">
                                    </div>
                                    <div class="mb-3">
                                        <label>Product Name</label>
                                        <input type="text" name="name" value="<?=$products['name'];?>" class="form-control">
                                    </div>
                                    <div class="mb-3">
                                        <label>Product Description</label>
                                        <input type="text" name="description" value="<?=$products['description'];?>" class="form-control">
                                    </div>
                                    <div class="mb-3">
                                        <label>Product Price</label>
                                        <input type="text" name="price" value="<?=$products['price'];?>" class="form-control">
                                    </div>
                                    <div class="mb-3">
                                        <label>Product Stock</label>
                                        <input type="text" name="stock" value="<?=$products['stock'];?>" class="form-control">
                                    </div>
                                    
                                    <div class="mb-3">
                                        <label>Discount</label>
                                        <input type="text" name="discount" value="<?=$products['discount'];?>" class="form-control">
                                    </div>
                                    
                                    
                                    <div class="mb-3">
                                        <button type="submit" name="update_products" class="btn btn-primary">
                                            Update products
                                        </button>
                                    </div>

                                </form>
                                <?php
                            }
                            else
                            {
                                echo "<h4>No Such Id Found</h4>";
                            }
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