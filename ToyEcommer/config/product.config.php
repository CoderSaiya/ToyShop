<?php
require_once(__DIR__ . "/connect.php");


$logoSql = "SELECT A.*, B.name AS category FROM products A JOIN categories B ON A.category_id = B.category_id";
$result = $conn->query($logoSql);
$productList = [];

while ($row = $result->fetch_assoc()) {
  $productList[] = $row;
}

$newListSql = "SELECT A.*, B.name AS category FROM products A JOIN categories B ON A.category_id = B.category_id ORDER BY A.created_at DESC;";
$result = $conn->query($newListSql);
$newList = [];
while ($row = $result->fetch_assoc()) {
  $newList[] = $row;
}