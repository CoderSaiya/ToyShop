<?php
ini_set('display_errors', 1);
ini_set('display_startup_errors', 1);
error_reporting(E_ALL);
require_once(__DIR__ . "/connect.php");

header('Content-Type: application/json');

$productsHtml = '';
$paginationHtml = '';

if (isset($_POST['sort'])) {

    $sortValue = $_POST['sort'];

    $page = isset($_GET['page']) ? (int)$_GET['page'] : 1;
    $limit = 4;
    $offset = ($page - 1) * $limit;

    $sql = "SELECT B.*, A.name as category_name FROM products B JOIN categories A ON A.category_id = B.category_id";

    switch ($sortValue) {
        case 2:
            $sql .= " ORDER BY B.price ASC";
            break;
        case 3:
            $sql .= " ORDER BY B.discount ASC";
            break;
        default:
            $sql .= " ORDER BY B.product_id ASC";
    }

    $sql .= " LIMIT $limit OFFSET $offset";

    $result = $conn->query($sql);

    $totalResult = $conn->query("SELECT COUNT(*) as total FROM products");
    $totalProducts = $totalResult->fetch_assoc()['total'];
    $totalPages = ceil($totalProducts / $limit);

    if ($result->num_rows > 0) {
        while ($product = $result->fetch_assoc()) {
            $productsHtml .= '
                <div class="product-item">
                    <div class="overlay">
                        <a href="productDetails.html" class="product-thumb">
                            <img src="./images/' . $product['image_url'] . '" alt="" />
                        </a>
                        <span class="discount">' . $product['discount'] . '%</span>
                    </div>
                    <div class="product-info">
                        <span>' . $product['category_name'] . '</span>
                        <a href="productDetails.php?id=' . $product['product_id'] . '">' . $product['name'] . '</a>
                        <h4>' . number_format(((100 - $product['discount']) / 100 * $product['price']), 0, ",", ".") . ' đ</h4>
                    </div>
                    <ul class="icons">
                        <li><i class="bx bx-heart"></i></li>
                        <li><i class="bx bx-search"></i></li>
                        <li onclick="addToCart(' . $product['product_id'] . ')"><i class="bx bx-cart"></i></li>
                    </ul>
                </div>';
        }
    } else {
        $productsHtml = '<p>No products found.</p>';
    }

    $paginationHtml = '<div class="container">';
    for ($i = 1; $i <= $totalPages; $i++) {
        $paginationHtml .= '<span id="next" class="next" href="#" onclick="loadPage(' . $i . ')">' . $i . '</span> ';
    }
    $paginationHtml .= '
    </div>';

    echo json_encode([
        'products' => $productsHtml,
        'pagination' => $paginationHtml
    ]);
}
