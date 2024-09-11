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

    <title>users Edit</title>
</head>
<body>
  
    <div class="container mt-5">

    <?php include('./config/message.php'); ?>

        <div class="row">
            <div class="col-md-12">
                <div class="card">
                    <div class="card-header">
                        <h4>Users Edit 
                            <a href="admin.php" class="btn btn-danger float-end">BACK</a>
                        </h4>
                    </div>
                    <div class="card-body">

                        <?php
                        if(isset($_GET['id']))
                        {
                            $id = mysqli_real_escape_string($conn, $_GET['id']);
                            $query = "SELECT * FROM users WHERE user_id='$id' ";
                            $query_run = mysqli_query($conn, $query);

                            if(mysqli_num_rows($query_run) > 0)
                            {
                                $users = mysqli_fetch_array($query_run);
                                ?>
                                <form action="edituser.php" method="POST">
                                <input type="hidden" name="user_id" value="<?= $users['user_id']; ?>">

                                    <div class="mb-3">
                                        <label>User Name</label>
                                        <input type="text" name="username" value="<?=$users['username'];?>" class="form-control">
                                    </div>
                                    <div class="mb-3">
                                        <label>User Email</label>
                                        <input type="email" name="email" value="<?=$users['email'];?>" class="form-control">
                                    </div>
                                    <div class="mb-3">
                                        <label>User Password</label>
                                        <input type="text" name="password_hash" value="<?=$users['password_hash'];?>" class="form-control">
                                    </div>
                                    <div class="mb-3">
                                        <label>First Name</label>
                                        <input type="text" name="first_name" value="<?=$users['first_name'];?>" class="form-control">
                                    </div>
                                    <div class="mb-3">
                                        <label>Last Name</label>
                                        <input type="text" name="last_name" value="<?=$users['last_name'];?>" class="form-control">
                                    </div>
                                    <div class="mb-3">
                                        <label>Phone</label>
                                        <input type="text" name="phone" value="<?=$users['phone'];?>" class="form-control">
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
                                        <input type="date" name="birthday" value="<?=$users['birthday'];?>" class="form-control">
                                    </div>
                                    <div class="mb-3">
                                        <label>Role</label>
                                        <input type="text" name="role" value="<?=$users['role'];?>" class="form-control">
                                    </div>
                                    
                                    <div class="mb-3">
                                        <button type="submit" name="update_users" class="btn btn-primary">
                                            Update users
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