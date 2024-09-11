<?php
session_start();
require_once('./config/connect.php');

if (isset($_POST['update_status'])) {
    $order_id = $_POST['order_id'];
    $new_status = $_POST['status'];

    $query = "UPDATE orders SET status = '$new_status' WHERE order_id = $order_id";
    $query_run = mysqli_query($conn, $query);

    if ($query_run) {
        $_SESSION['message'] = "Order status updated successfully";
        header("Location: admin.php");
        exit(0);
    } else {
        $_SESSION['message'] = "Failed to update order status";
        header("Location: admin.php");
        exit(0);
    }
}
