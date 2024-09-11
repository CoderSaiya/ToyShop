<?php
session_start();
require_once("./config/connect.php");

if(isset($_POST['delete_user']))
{
    $user_id = mysqli_real_escape_string($conn, $_POST['delete_user']);

    $query = "DELETE FROM users WHERE user_id='$user_id'";
    $query_run = mysqli_query($conn, $query);


    if($query_run)
    {
        $_SESSION['message'] = "users Deleted Successfully";
        header("Location: admin.php");
        exit(0);
    }
    else
    {
        $_SESSION['message'] = "users Not Deleted";
        header("Location: admin.php");
        exit(0);
    }
}

if(isset($_POST['update_users'])) {
    $user_id = mysqli_real_escape_string($conn, $_POST['user_id']);

    $username = trim(mysqli_real_escape_string($conn, $_POST['username']));
    $email = trim(mysqli_real_escape_string($conn, $_POST['email']));
    $password_hash = trim(mysqli_real_escape_string($conn, $_POST['password_hash']));
    $first_name = trim(mysqli_real_escape_string($conn, $_POST['first_name']));
    $last_name = trim(mysqli_real_escape_string($conn, $_POST['last_name']));
    $phone = trim(mysqli_real_escape_string($conn, $_POST['phone']));
    $gender = trim(mysqli_real_escape_string($conn, $_POST['gender']));
    if ($gender == 'male') {
        $gender = 'Nam';
    } elseif ($gender == 'female') {
        $gender = 'Nữ';
    }
    $birthday = trim(mysqli_real_escape_string($conn, $_POST['birthday']));

    $query = "UPDATE users SET username='$username', email='$email', password_hash='$password_hash', first_name='$first_name', last_name='$last_name', phone='$phone', gender='$gender', birthday='$birthday' WHERE user_id='$user_id'";
    $query_run = mysqli_query($conn, $query);

    if($query_run) {
        $_SESSION['message'] = "User Updated Successfully";
        header("Location: admin.php");
        exit(0);
    } else {
        $_SESSION['message'] = "User Not Updated";
        header("Location: admin.php");
        exit(0);
    }
}



?>