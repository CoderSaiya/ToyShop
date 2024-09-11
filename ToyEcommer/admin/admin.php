<?php
session_start();
require_once("./config/connect.php");
require_once('../config/auth.php');
?>
<!doctype html>
<html lang="en">

<head>
    <!-- Required meta tags -->
    <meta charset="utf-8">
    <meta name="viewport" content="width=device-width, initial-scale=1">

    <!--  Bootstrap CSS -->
    <link href="https://cdn.jsdelivr.net/npm/bootstrap@5.1.3/dist/css/bootstrap.min.css" rel="stylesheet">

    <title>Product CRUD</title>
</head>
<div>
    <button type="button" onclick="handleLogout()" name="delete_product" class="btn btn-danger btn-sm">Logout</button>
    <div class="container mt-4">

        <?php include('./config/message.php'); ?>

        <div class="row">
            <div class="col-md-12">
                <div class="card">
                    <div class="card-header">
                        <h4>Product Details
                            <a href="addsanpham.php" class="btn btn-primary float-end">Add Product</a>
                        </h4>
                    </div>
                    <div class="card-body">

                        <table class="table table-bordered table-striped">
                            <thead>
                                <tr>
                                    <th>ID</th>
                                    <th>Product Image</th>
                                    <th>Product Name</th>
                                    <th>Product Description</th>
                                    <th>Product Price</th>
                                    <th>Product Stock</th>
                                    <!-- <th>Category_ID</th> -->
                                    <th>Product Discount</th>
                                </tr>
                            </thead>
                            <tbody>
                                <?php
                                $query = "SELECT * FROM products";
                                $query_run = mysqli_query($conn, $query);

                                if (mysqli_num_rows($query_run) > 0) {
                                    foreach ($query_run as $products) {
                                ?>
                                        <tr>
                                            <td><?= $products['product_id']; ?></td>
                                            <?php if (isset($products['image_url']) && !empty($products['image_url'])): ?>
                                                <td>
                                                    <img class="w-25 h-25" src="../images/<?= $products['image_url'] ?>" alt="">
                                                </td>
                                            <?php else: ?>
                                                <td>
                                                    <p>No Image Available</p>
                                                </td>
                                            <?php endif; ?>

                                            <td><?= $products['name']; ?></td>
                                            <td>

                                                <?php
                                                $description = $products['description'];

                                                if (strlen($description) > 30) {
                                                    // Nếu độ dài lớn hơn 30, cắt chuỗi và hiển thị "xem thêm"
                                                    echo '<span class="short-text">' . substr($description, 0, 30) . '...</span>';
                                                    echo '<span class="full-text" style="display: none;">' . $description . '</span>';
                                                    echo '<a href="#" class="toggle-text">xem thêm</a>';
                                                } else {
                                                    // Nếu độ dài nhỏ hơn hoặc bằng 30, hiển thị đầy đủ nội dung mà không có "xem thêm"
                                                    echo '<span class="short-text">' . $description . '</span>';
                                                }
                                                ?>

                                            </td>

                                            <td><?= number_format($products['price'], 0, '', ','); ?></td>
                                            <td><?= $products['stock']; ?></td>
                                            <!-- <td><?= $category['category_id']; ?></td> -->
                                            <td><?= $products['discount']; ?>%</td>
                                            <td>
                                                <a href="sanpham-view.php?id=<?= $products['product_id']; ?>" class="btn btn-info btn-sm">View</a>
                                                <a href="sanpham-edit.php?id=<?= $products['product_id']; ?>" class="btn btn-success btn-sm">Edit</a>
                                                <form action="editsanpham.php" method="POST" class="d-inline">
                                                    <button type="submit" name="delete_product" value="<?= $products['product_id']; ?>" class="btn btn-danger btn-sm">Delete</button>
                                                </form>
                                            </td>
                                        </tr>
                                <?php
                                    }
                                } else {
                                    echo "<h5> No Record Found </h5>";
                                }
                                ?>

                            </tbody>
                        </table>

                    </div>
                </div>
            </div>
        </div>
    </div>


    <div class="container mt-4">

        <?php include('./config/message.php'); ?>

        <div class="row">
            <div class="col-md-12">
                <div class="card">
                    <div class="card-header">
                        <h4>User Details
                            <a href="addnguoidung.php" class="btn btn-primary float-end">Add User</a>
                        </h4>
                    </div>
                    <div class="card-body">

                        <table class="table table-bordered table-striped">
                            <thead>
                                <tr>
                                    <th>ID</th>
                                    <th>User Name</th>
                                    <th>Email</th>
                                    <th>Password</th>
                                    <th>First Name</th>
                                    <th>Last Name</th>
                                    <th>Phone</th>
                                    <th>Gender</th>
                                    <th>Birthday</th>
                                    <th>Role</th>
                                </tr>
                            </thead>
                            <tbody>
                                <?php
                                $query = "SELECT * FROM users";
                                $query_run = mysqli_query($conn, $query);

                                if (mysqli_num_rows($query_run) > 0) {
                                    foreach ($query_run as $users) {
                                ?>
                                        <tr>
                                            <td><?= $users['user_id']; ?></td>
                                            <td><?= $users['username']; ?></td>
                                            <td><?= $users['email']; ?></td>
                                            <td><?= substr($users['password_hash'], 0, 10) . '*'; ?></td>
                                            <td><?= $users['first_name']; ?></td>
                                            <td><?= $users['last_name']; ?></td>
                                            <td><?= $users['phone']; ?></td>
                                            <td><?= $users['gender']; ?></td>
                                            <td><?= $users['birthday']; ?></td>
                                            <td><?= $users['role']; ?></td>
                                            <td>
                                                <a href="user-view.php?id=<?= $users['user_id']; ?>" class="btn btn-info btn-sm">View</a>
                                                <a href="user-edit.php?id=<?= $users['user_id']; ?>" class="btn btn-success btn-sm">Edit</a>

                                                <form action="edituser.php" method="POST" class="d-inline">
                                                    <button type="submit" name="delete_user" value="<?= $users['user_id']; ?>" class="btn btn-danger btn-sm">Delete</button>
                                                </form>
                                            </td>
                                        </tr>
                                <?php
                                    }
                                } else {
                                    echo "<h5> No Record Found </h5>";
                                }
                                ?>

                            </tbody>
                        </table>

                    </div>
                </div>
            </div>
        </div>

        <div class="container mt-4">
            <div class="row">
                <div class="col-md-12">
                    <div class="card">
                        <div class="card-header">
                            <h4>Order Details</h4>
                        </div>
                        <div class="card-body">
                            <table class="table table-bordered table-striped">
                                <thead>
                                    <tr>
                                        <th>Order ID</th>
                                        <th>User ID</th>
                                        <th>Total Price</th>
                                        <th>Status</th>
                                        <th>Created At</th>
                                        <th>Action</th>
                                    </tr>
                                </thead>
                                <tbody>
                                    <?php
                                    $query = "SELECT * FROM orders";
                                    $query_run = mysqli_query($conn, $query);

                                    if (mysqli_num_rows($query_run) > 0) {
                                        foreach ($query_run as $order) {
                                    ?>
                                            <tr>
                                                <td><?= $order['order_id']; ?></td>
                                                <td><?= $order['user_id']; ?></td>
                                                <td><?= number_format($order['total_price'], 0, '.', ','); ?> đ</td>
                                                <td><?= ucfirst($order['status']); ?></td>
                                                <td><?= $order['created_at']; ?></td>
                                                <td>
                                                    <form action="update_status.php" method="POST" class="d-inline">
                                                        <input type="hidden" name="order_id" value="<?= $order['order_id']; ?>">
                                                        <select name="status" class="form-select form-select-sm">
                                                            <option value="pending" <?= ($order['status'] == 'pending') ? 'selected' : ''; ?>>Pending</option>
                                                            <option value="shipped" <?= ($order['status'] == 'shipped') ? 'selected' : ''; ?>>Shipped</option>
                                                            <option value="delivered" <?= ($order['status'] == 'delivered') ? 'selected' : ''; ?>>Delivered</option>
                                                            <option value="cancelled" <?= ($order['status'] == 'cancelled') ? 'selected' : ''; ?>>Cancelled</option>
                                                        </select>
                                                        <button type="submit" name="update_status" class="btn btn-primary btn-sm mt-2">Update</button>
                                                    </form>
                                                </td>
                                            </tr>
                                    <?php
                                        }
                                    } else {
                                        echo "<h5> No Orders Found </h5>";
                                    }
                                    ?>
                                </tbody>
                            </table>
                        </div>
                    </div>
                </div>
            </div>
        </div>
    </div>

    <script src="https://cdn.jsdelivr.net/npm/bootstrap@5.1.3/dist/js/bootstrap.bundle.min.js"></script>
    <script type="text/javascript" src="../js/index.js"></script>

    </body>

</html>
<script>
    document.addEventListener('DOMContentLoaded', function() {
        const toggleLinks = document.querySelectorAll('.toggle-text');

        toggleLinks.forEach(function(link) {
            link.addEventListener('click', function(event) {
                event.preventDefault(); // Ngăn chặn hành động mặc định của thẻ <a>

                const shortText = this.previousElementSibling.previousElementSibling;
                const fullText = this.previousElementSibling;

                if (fullText.style.display === 'none') {
                    shortText.style.display = 'none';
                    fullText.style.display = 'inline';
                    this.textContent = 'thu gọn';
                } else {
                    shortText.style.display = 'inline';
                    fullText.style.display = 'none';
                    this.textContent = 'xem thêm';
                }
            });
        });
    });
</script>