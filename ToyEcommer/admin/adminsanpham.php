<?php
require_once("./config/connect.php"); // Kết nối tới cơ sở dữ liệu
session_start();
if (isset($_POST['save_product'])) {

    // Lấy các giá trị từ form và loại bỏ khoảng trắng đầu/cuối
    $name = trim(mysqli_real_escape_string($conn, $_POST['name']));
    $description = trim(mysqli_real_escape_string($conn, $_POST['description']));
    $price = trim(mysqli_real_escape_string($conn, $_POST['price']));
    $stock = trim(mysqli_real_escape_string($conn, $_POST['stock']));
    $category_id = isset($_POST['category_id']) ? (int) mysqli_real_escape_string($conn, $_POST['category_id']) : null;
    $discount = trim(mysqli_real_escape_string($conn, $_POST['discount']));

    // Xử lý ảnh upload
    $target_dir = "../images/";
    $target_file = $target_dir . basename($_FILES["image"]["name"]);
    $uploadOk = 1;
    $imageFileType = strtolower(pathinfo($target_file, PATHINFO_EXTENSION));
    if (isset($_POST["submit"])) {
        $check = getimagesize($_FILES["fileToUpload"]["tmp_name"]);
        if ($check !== false) {
            echo "File is an image - " . $check["mime"] . ".";
            $uploadOk = 1;
        } else {
            echo "File is not an image.";
            $uploadOk = 0;
        }
    }

    // // Mảng để lưu trữ các thông báo lỗi
    $errors = [];

    // Kiểm tra nếu các trường không được để trống
    if (empty($name)) {
        $errors[] = "The product name is required.";
    }
    if (empty($description)) {
        $errors[] = "The product description is required.";
    }
    if (empty($price)) {
        $errors[] = "The product price is required.";
    }
    if (empty($stock)) {
        $errors[] = "The product stock is required.";
    }
    if (empty($discount)) {
        $errors[] = "The product discount is required.";
    }

    // Kiểm tra nếu có lỗi
    if (!empty($errors)) {
        $_SESSION['message'] = implode("<br>", $errors); // Kết hợp các lỗi thành chuỗi
        header("Location: addsanpham.php");
        exit(0);
    }

    // Xử lý ảnh upload

    if ($uploadOk == 1) {
        if (move_uploaded_file($_FILES["image"]["tmp_name"], $target_file)) {
            $image_url = $target_file;
        } else {
            echo "Sorry, there was an error uploading your file.";
            $image_url = null;
        }
    } else {
        $image_url = null;
    }

    // Tạo câu truy vấn SQL để lưu thông tin sản phẩm
    $query = "INSERT INTO products (name, description, price, stock, image_url, discount, category_id) 
          VALUES ('$name', '$description', '$price', '$stock', '$image_url', '$discount', '$category_id')";

    // move_uploaded_file($image_tmp,'images/'.$image);
    // Thực thi câu truy vấn
    $query_run = mysqli_query($conn, $query);

    if ($query_run) {
        $_SESSION['message'] = "Product Created Successfully";
        header("Location: addsanpham.php");
        exit(0);
    } else {
        $_SESSION['message'] = "Product Not Created";
        header("Location: addsanpham.php");
        exit(0);
    }
}
