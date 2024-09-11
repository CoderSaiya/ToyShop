<?php
require_once("./config/connect.php");

$categories = [];

// SQL to get categories
$query = "SELECT category_id, name FROM categories";
$result = mysqli_query($conn, $query);

if ($result) {
    while ($row = mysqli_fetch_assoc($result)) {
        $categories[] = $row;
    }
}
?>

<!doctype html>
<html lang="en">

<head>
    <!-- Required meta tags -->
    <meta charset="utf-8">
    <meta name="viewport" content="width=device-width, initial-scale=1">

    <!--  Bootstrap CSS -->
    <link href="https://cdn.jsdelivr.net/npm/bootstrap@5.1.3/dist/css/bootstrap.min.css" rel="stylesheet">

    <style>
        #category {
            border-radius: 12px;
            padding: 10px;
        }
    </style>

    <title>Product Create</title>
</head>

<body>

    <div class="container mt-5">

        <?php include('adminsanpham.php');
        include('./config/message.php');
        ?>

        <div class="row">
            <div class="col-md-12">
                <div class="card">
                    <div class="card-header">
                        <h4>Product Add
                            <a href="admin.php" class="btn btn-danger float-end">BACK</a>
                        </h4>
                    </div>
                    <div class="card-body">
                        <form action="adminsanpham.php" method="POST" enctype="multipart/form-data">
                            <div class="mb-3">
                                <label>Product Name</label>
                                <input type="text" name="name" id="name" class="form-control">
                            </div>
                            <div class="mb-3">
                                <select name="category_id" id="category" required>
                                    <option value="">Select a Category</option>
                                    <?php foreach ($categories as $category): ?>
                                        <option value="<?= $category['category_id'] ?>"><?= htmlspecialchars($category['name']) ?></option>
                                    <?php endforeach; ?>
                                </select>
                            </div>
                            <div class="mb-3">
                                <label>Product Description</label>
                                <input type="text" name="description" id="description" class="form-control">
                            </div>
                            <div class="mb-3">
                                <label>Product Price</label>
                                <input type="text" name="price" id="price" class="form-control">
                            </div>
                            <div class="mb-3">
                                <label>Product Stock</label>
                                <input type="text" name="stock" id="stock" class="form-control">
                            </div>
                            <!--
                            <div class="mb-3">
                            <label>Category_ID</label>
                             <?php
                                // SQL query to get all categories
                                $sql = "SELECT category_id, name FROM categories";
                                $result = $conn->query($sql);
                                ?>
                            <select name="category_id" class="form-control">
                           <?php
                            if ($result->num_rows > 0) {
                                while ($row = $result->fetch_assoc()) {
                                    echo '<option value="' . $row['category_id'] . '">' . $row['name'] . '</option>';
                                }
                            }

                            ?>
                            </select>
                            </div> -->
                            <div class="mb-3">
                                <label>Product Image</label>
                                <input type="file" name="image" class="form-control">
                                <!-- <button type="submit" name="submit">Upload Image</button>-->
                            </div>
                            <div class="mb-3">
                                <label>Product Discount</label>
                                <input type="text" name="discount" class="form-control">
                            </div>

                            <div class="mb-3">
                                <button type="submit" name="save_product" class="btn btn-primary">Save Product</button>
                            </div>
                        </form>
                    </div>
                </div>
            </div>
        </div>
    </div>

    <script src="https://cdn.jsdelivr.net/npm/bootstrap@5.1.3/dist/js/bootstrap.bundle.min.js"></script>
</body>

</html>