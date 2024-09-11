<?php
require_once("./config/product.config.php");

if (!isset($_COOKIE['username'])) {
    die("Không tìm thấy cookie đăng nhập. Vui lòng đăng nhập lại.");
}

$username = $_COOKIE['username'];

$stmt = $conn->prepare("SELECT user_id FROM users WHERE username = ?");
$stmt->bind_param("s", $username);
$stmt->execute();
$stmt->bind_result($user_id);
$stmt->fetch();
$stmt->close();

$stmt = $conn->prepare("SELECT SUM(price * quantity) FROM cart A JOIN products B ON A.product_id = B.product_id WHERE A.user_id = ?");
$stmt->bind_param("i", $user_id);
$stmt->execute();
$stmt->bind_result($subSum);
$stmt->fetch();
$stmt->close();
$tax = 20000;
$sum = $tax + $subSum;

$countSql = "SELECT COUNT(*) as count FROM cart WHERE user_id = $user_id";
$result = $conn->query($countSql);
$row = $result->fetch_assoc();
$countCart = $row['count'];

$cartSql = "SELECT * FROM cart A JOIN products B ON A.product_id = B.product_id WHERE A.user_id = $user_id";
$result = $conn->query($cartSql);
$productList = [];

while ($row = $result->fetch_assoc()) {
    $productList[] = $row;
}

$conn->close();
?>

<!DOCTYPE html>
<html lang="en">

<head>
    <meta charset="UTF-8" />
    <meta name="viewport" content="width=device-width, initial-scale=1.0" />
    <!-- Box icons -->
    <link
        rel="stylesheet"
        href="https://cdn.jsdelivr.net/npm/boxicons@latest/css/boxicons.min.css" />
    <!-- Custom StyleSheet -->
    <link rel="stylesheet" href="./css/style.css" />
    <title>Giỏ hàng</title>
</head>

<body>
    <!-- Navigation -->
    <div class="top-nav">
        <div class="container d-flex">
            <p>Đặt hàng online qua: (+84) 090.090.090</p>
            <ul class="d-flex">
                <li><a href="#">Về chúng tôi</a></li>
                <li><a href="#">FAQ</a></li>
                <li><a href="#">Liện hệ</a></li>
            </ul>
        </div>
    </div>
    <div class="navigation">
        <div class="nav-center container d-flex">
            <a href="/" class="logo">
                <img src="./images/main-logo.png" alt="">
            </a>

            <ul class="nav-list d-flex">
                <li class="nav-item">
                    <a href="/" class="nav-link">Trang chủ</a>
                </li>
                <li class="nav-item">
                    <a href="./product.php" class="nav-link">Sản phẩm</a>
                </li>
                <li class="nav-item">
                    <a href="#terms" class="nav-link">Điều khoản</a>
                </li>
                <li class="nav-item">
                    <a href="#about" class="nav-link">Về chúng tôi</a>
                </li>
                <li class="nav-item">
                    <a href="#contact" class="nav-link">Liên hệ</a>
                </li>
                <li class="icons d-flex">
                    <a href="./profile.php" class="icon" id="user-icon">
                        <i class="bx bx-user"></i>
                    </a>
                    <div class="icon">
                        <i class="bx bx-search"></i>
                    </div>
                    <div class="icon">
                        <i class="bx bx-heart"></i>
                        <span class="d-flex">0</span>
                    </div>
                    <a onclick="handelLoadCart()" class="icon">
                        <i class="bx bx-cart"></i>
                        <span class="d-flex"><?php echo $countCart ?></span>
                    </a>
                </li>
            </ul>

            <div class="icons d-flex">
                <div class="icon" id="user-icon">
                    <a id="user-link" href="./profile.php">
                        <i class="bx bx-user"></i>
                    </a>
                    <div id="user-menu" class="user-menu">
                        <ul>
                            <li><a href="./profile.php">Chỉnh sửa thông tin</a></li>
                            <li><a onclick="handleLogout()">Đăng xuất</a></li>
                        </ul>
                    </div>
                </div>
                <div class="icon">
                    <i class="bx bx-search"></i>
                </div>
                <div class="icon">
                    <i class="bx bx-heart"></i>
                    <span class="d-flex">0</span>
                </div>
                <a onclick="handelLoadCart()" class="icon">
                    <i class="bx bx-cart"></i>
                    <span class="d-flex"><?php echo $countCart ?></span>
                </a>
            </div>

            <div class="hamburger">
                <i class="bx bx-menu-alt-left"></i>
            </div>
        </div>
    </div>

    <!-- Cart Items -->
    <div class="container cart">
        <table>
            <tr>
                <th>Sản phẩm</th>
                <th>Số Lượng</th>
                <th>Giá</th>
            </tr>
            <?php foreach ($productList as $product) : ?>
                <tr>
                    <td>
                        <div class="cart-info">
                            <img src="./images/<?php echo $product['image_url'] ?>" alt="" />
                            <div>
                                <p><?php echo $product['name'] ?></p>
                                <span>Đơn giá: <?php echo number_format($product['price'], 0, ",", ".") ?> đ</span> <br />
                                <a onclick="removeFromCart(<?= $product['product_id'] ?>)">Xóa</a>
                            </div>
                        </div>
                    </td>
                    <td><input type="number" value="<?php echo $product['quantity'] ?>" min="1" onchange="updateQuantity(<?php echo $product['product_id'] ?>, this.value)" /></td>
                    <td><?php echo number_format($product['quantity'] * $product['price'], 0, ",", ".") ?> đ</td>
                </tr>
            <?php endforeach; ?>
        </table>
        <div class="total-price">
            <table>
                <tr>
                    <td>Tổng tiền hàng</td>
                    <td><?php echo number_format($subSum, 0, ",", ".") ?> đ</td>
                </tr>
                <tr>
                    <td>Phí vận chuyển</td>
                    <td><?php echo number_format($tax, 0, ",", ".") ?> đ</td>
                </tr>
                <tr>
                    <td>Thành tiền</td>
                    <td><?php echo number_format($sum, 0, ",", ".") ?> đ</td>
                </tr>
            </table>
            <a href="./checkout.php" class="checkout btn">Tiến hành thanh toán</a>
        </div>
    </div>

    <!-- Latest Products -->
    <section class="section featured">
        <div class="top container">
            <h1>Sản phảm mới nhất</h1>
            <a href="./product.php" class="view-more">Xem thêm</a>
        </div>
        <div class="product-center container">
            <?php $countCart = 0;
            foreach ($newList as $product) : ?>
                <div class="product-item">
                    <div class="overlay">
                        <a href="" class="product-thumb">
                            <img src="./images/<?php echo $product['image_url'] ?>" alt="" />
                        </a>
                        <span class="discount"><?php $random = random_int(1, 80);
                                                echo $random; ?>%</span>
                    </div>
                    <div class="product-info">
                        <span><?php echo $product['category']; ?></span>
                        <a href=""><?php echo $product['name']; ?></a>
                        <h4><?php echo number_format(((100 - $random) / 100 * $product['price']), 0, ",", ".") ?> đ</h4>
                    </div>
                    <ul class="icons">
                        <li><i class="bx bx-heart"></i></li>
                        <li><i class="bx bx-search"></i></li>
                        <li onclick="addToCart(<?= $product['product_id'] ?>)"><i class="bx bx-cart"></i></li>
                    </ul>
                </div>
            <?php
                $countCart++;
                if ($countCart == 4) break;
            endforeach; ?>
        </div>
    </section>

    <!-- Footer -->
    <footer class="footer">
        <div class="row">
            <div class="col d-flex">
                <h4>THÔNG TIN</h4>
                <a href="">Về chúng tôi</a>
                <a href="">Liên hệ</a>
                <a href="">Điều khoản & dịch vụ</a>
                <a href="">Vận chuyển</a>
            </div>
            <div class="col d-flex">
                <h4>LIÊN KẾT</h4>
                <a href="">Cửa hàng trực tuyến</a>
                <a href="">Dịch vụ khách hàng</a>
                <a href="">Khuyến mãi</a>
                <a href="">Top bán chạy</a>
            </div>
            <div class="col d-flex">
                <span><i class='bx bxl-facebook-square'></i></span>
                <span><i class='bx bxl-instagram-alt'></i></span>
                <span><i class='bx bxl-github'></i></span>
                <span><i class='bx bxl-twitter'></i></span>
                <span><i class='bx bxl-pinterest'></i></span>
            </div>
        </div>
    </footer>

    <!-- Custom Script -->
    <script src="./js/index.js"></script>
</body>

</html>