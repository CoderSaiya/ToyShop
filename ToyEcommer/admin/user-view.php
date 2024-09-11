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
                            $query = "SELECT * FROM users WHERE user_id='$id' ";
                            $query_run = mysqli_query($conn, $query);

                            if(mysqli_num_rows($query_run) > 0)
                            {
                                $users = mysqli_fetch_array($query_run);
                                ?>
                                
                                    <div class="mb-3">
                                        <label>User Name</label>
                                        <p class="form-control">
                                            <?= htmlspecialchars($users['username'], ENT_QUOTES, 'UTF-8'); ?>
                                        </p>
                                    </div>
                                    <div class="mb-3">
                                        <label>User Email</label>
                                        <p class="form-control">
                                            <?= htmlspecialchars($users['email'], ENT_QUOTES, 'UTF-8'); ?>
                                        </p>
                                    </div>
                                    <div class="mb-3">
                                        <label>User Password</label>
                                        <p class="form-control">
                                            <?= htmlspecialchars($users['password_hash'], ENT_QUOTES, 'UTF-8'); ?>
                                        </p>
                                    </div>
                                    <div class="mb-3">
                                        <label>First Name</label>
                                        <p class="form-control">
                                            <?= htmlspecialchars($users['first_name'], ENT_QUOTES, 'UTF-8'); ?>
                                        </p>
                                    </div>
                                    <div class="mb-3">
                                        <label>Last Name</label>
                                        <p class="form-control">
                                            <?= htmlspecialchars($users['last_name'], ENT_QUOTES, 'UTF-8'); ?>
                                        </p>
                                    </div>
                                    <div class="mb-3">
                                        <label>Phone</label>
                                        <p class="form-control">
                                            <?= htmlspecialchars($users['phone'], ENT_QUOTES, 'UTF-8'); ?>
                                        </p>
                                    </div>
                                    <div class="mb-3">
                                        <label>Gender</label>
                                        <p class="form-control">
                                            <?= htmlspecialchars($users['gender'], ENT_QUOTES, 'UTF-8'); ?>
                                        </p>
                                    </div>
                                    <div class="mb-3">
                                        <label>User Birthday</label>
                                        <p class="form-control">
                                            <?= htmlspecialchars($users['birthday'], ENT_QUOTES, 'UTF-8'); ?>
                                        </p>
                                    </div>
                                    <div class="mb-3">
                                        <label>Role</label>
                                        <p class="form-control">
                                            <?= htmlspecialchars($users['role'], ENT_QUOTES, 'UTF-8'); ?>
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
