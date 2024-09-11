<?php

require_once("./config/connect.php");
?>

<!doctype html>
<html lang="en">
  <head>
    <!-- Required meta tags -->
    <meta charset="utf-8">
    <meta name="viewport" content="width=device-width, initial-scale=1">

    <!--  Bootstrap CSS -->
    <link href="https://cdn.jsdelivr.net/npm/bootstrap@5.1.3/dist/css/bootstrap.min.css" rel="stylesheet">

    <title>Product Create</title>
</head>
<body>
  
    <div class="container mt-5">

        <?php include('adminnguoidung.php'); 
              include('./config/message.php');
        ?>

        <div class="row">
            <div class="col-md-12">
                <div class="card">
                    <div class="card-header">
                        <h4>User Add 
                            <a href="admin.php" class="btn btn-danger float-end">BACK</a>
                        </h4>
                    </div>
                    <div class="card-body">
                        <form action="adminnguoidung.php" method="POST">
                            <div class="mb-3">
                                <label>User Name</label>
                                <input type="text" name="username" id="username" class="form-control">
                            </div>
                            <div class="mb-3">
                                <label>Email</label>
                                <input type="text" name="email" id="email" class="form-control">
                            </div>
                            <div class="mb-3">
                                <label>Password</label>
                                <input type="text" name="password_hash" id="password_hash" class="form-control">
                            </div>
                            <div class="mb-3">
                                <label>First Name</label>
                                <input type="text" name="first_name" id="first_name" class="form-control">
                            </div>
                            <div class="mb-3">
                                <label>Last Name</label>
                                <input type="text" name="last_name" id="last_name" class="form-control">
                               
                            </div>
                            <div class="mb-3">
                                <label>Phone</label>
                                <input type="text" name="phone" class="form-control">
                            </div>
                            <div class="mb-3">
                            <label>Gender</label>
                            <select name="gender" class="form-control">
                            <option value="male" name="male">Nam</option>
                            <option value="female" name="femaler">Nữ</option>
                            </select>
                            </div>

                            <div class="mb-3">
                                <label>Birthday</label>
                                <input type="date" name="birthday" class="form-control">
                            </div>
                            <div class="mb-3" >
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
