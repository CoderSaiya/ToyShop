<?php
session_start();
require_once("./config/connect.php");

if(isset($_POST['delete_product']))
{
    $product_id = mysqli_real_escape_string($conn, $_POST['delete_product']);

    $query = "DELETE FROM products WHERE product_id='$product_id'";
    $query_run = mysqli_query($conn, $query);


    if($query_run)
    {
        $_SESSION['message'] = "products Deleted Successfully";
        header("Location: admin.php");
        exit(0);
    }
    else
    {
        $_SESSION['message'] = "products Not Deleted";
        header("Location: admin.php");
        exit(0);
    }
}

if(isset($_POST['update_products'])) {
    $id = mysqli_real_escape_string($conn, $_POST['products_id']);
    $name = mysqli_real_escape_string($conn, $_POST['name']);
    $description = mysqli_real_escape_string($conn, $_POST['description']);
    $price = mysqli_real_escape_string($conn, $_POST['price']);
    $stock = mysqli_real_escape_string($conn, $_POST['stock']);
    $discount = mysqli_real_escape_string($conn, $_POST['discount']);

    $query = "UPDATE products SET name='$name', description='$description', price='$price', stock='$stock', discount='$discount' WHERE product_id='$id'";
    $query_run = mysqli_query($conn, $query);

    
    if($query_run) {
        $_SESSION['message'] = "product Updated Successfully";
        header("Location: admin.php");
        exit(0);
    } else {
        $_SESSION['message'] = "product Not Updated";
        header("Location: admin.php");
        exit(0);
    }
}



?>