<?php 
require_once("./config/connect.php"); // Kết nối tới cơ sở dữ liệu
session_start();
if (isset($_POST['save_product'])) {

    // Lấy các giá trị từ form và loại bỏ khoảng trắng đầu/cuối
    $username  = trim(mysqli_real_escape_string($conn, $_POST['username']));
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
    $birthday=trim(mysqli_real_escape_string($conn, $_POST['birthday']));
    
    // Mảng để lưu trữ các thông báo lỗi
    $errors = [];

    // Kiểm tra nếu các trường không được để trống
    if (empty($username)) {
        $errors[] = "The user name is required.";
    }
    if (empty($email)) {
        $errors[] = "The user email is required.";
    }
    if (empty($password_hash)) {
        $errors[] = "The user password is required.";
    }
    if (empty($first_name)) {
        $errors[] = "The user first name is required.";
    }
    if (empty($last_name)) {
        $errors[] = "The user last name is required.";
    }
    if (empty($phone)) {
        $errors[] = "The user phone is required.";
    }
    if (empty($gender)) {
        $errors[] = "The user gender is required.";
    }
    if (empty($birthday)) {
        $errors[] = "The user birthday is required.";
    }

    // Kiểm tra trùng lặp user
    $check_name="SELECT * FROM users WHERE username ='$username' LIMIT 1";
    $result_name=mysqli_query($conn, $check_name);
    if (mysqli_num_rows($result_name) > 0) {
        $errors[] = "The user name is already taken. Please choose another one.";
    }
    
    $check_email= "SELECT * FROM users WHERE email ='$email' LIMIT 1";
    $result_email=mysqli_query($conn, $check_email);
    if (mysqli_num_rows($result_email) > 0) {
        $errors[] = "The user email is already taken. Please use another one.";
    }
    $check_phone= "SELECT *FROM users WHERE phone ='$phone' LIMIT 1";
    $result_phone=mysqli_query($conn, $check_phone);
    if (mysqli_num_rows($result_phone) > 0) {
        $errors[] = "The user phone is already taken. Please use another one.";
    }

        // Kiểm tra nếu có lỗi
    if (!empty($errors)) {
        $_SESSION['message'] = implode("<br>", $errors); // Kết hợp các lỗi thành chuỗi
        header("Location: addnguoidung.php");
        exit(0);
    }

     // Mã hóa mật khẩu
     $passwordgoc ="password_hash";
     $passwordmahoa="password_hash";
     $password = password_hash($passwordmahoa, PASSWORD_DEFAULT);
    
    

    // Tạo câu truy vấn SQL để lưu thông tin sản phẩm
    $query = "INSERT INTO users (username,email,password_hash,first_name,last_name,phone,gender,birthday,pob) 
              VALUES ('$username', '$email', '$password', '$first_name', '$last_name', '$phone','$gender','$birthday','1')";

    // Thực thi câu truy vấn
    $query_run = mysqli_query($conn, $query);

    if($query_run){
        $_SESSION['message'] = "User Created Successfully";
        header("Location: addnguoidung.php");
        exit(0);
    } else {
        $_SESSION['message'] = "User Not Created";
        header("Location: addnguoidung.php");
        exit(0);
    }
}

?>
