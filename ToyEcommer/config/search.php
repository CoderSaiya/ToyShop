<?php
require_once('./connect.php');

$query = isset($_GET['q']) ? strtolower($conn->real_escape_string($_GET['q'])) : '';

$sql = "SELECT product_id, name, price, discount, image_url FROM products WHERE name LIKE ? LIMIT 10";
$stmt = $conn->prepare($sql);
$searchTerm = '%' . $query . '%';
$stmt->bind_param('s', $searchTerm);
$stmt->execute();
$result = $stmt->get_result();

$results = '';

while ($row = $result->fetch_assoc()) {
    $discountedPrice = number_format(((100 - $row['discount']) / 100 * $row['price']), 0, ",", ".");
    $results .= '<div class="item" onclick="window.location.href=\'productDetails.php?id=' . $row['product_id'] . '\'">
                    <div>
                        <img src="images/' . $row['image_url'] . '" alt="' . $row['name'] . '" />
                        <p>' . $row['name'] . '</p>
                    </div>
                    <b>' . $discountedPrice. " đ" . '</b>
                </div>';
    $results .= '<hr/>';
}

echo $results;

$stmt->close();
$conn->close();
