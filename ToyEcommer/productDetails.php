<?php
require_once(__DIR__ . '/config/product.config.php');
require_once('./config/get_review.php');

$product_id = isset($_GET['id']) ? (int)$_GET['id'] : 0;
$countCart = 0;
$reviews = []; // Khởi tạo mảng để lưu đánh giá

if (isset($_COOKIE['username'])) {
  $username = $_COOKIE['username'];

  $stmt = $conn->prepare("SELECT user_id FROM users WHERE username = ?");
  $stmt->bind_param("s", $username);
  $stmt->execute();
  $stmt->bind_result($user_id);
  $stmt->fetch();
  $stmt->close();

  $countSql = "SELECT COUNT(*) as count FROM cart WHERE user_id = $user_id";
  $result = $conn->query($countSql);
  $row = $result->fetch_assoc();
  $countCart = $row['count'];
}

// Truy vấn thông tin sản phẩm
$productSql = "SELECT A.*, B.name AS category FROM products A JOIN categories B ON A.category_id = B.category_id WHERE A.product_id = ?";
$stmt = $conn->prepare($productSql);
$stmt->bind_param("i", $product_id);
$stmt->execute();
$result = $stmt->get_result();
$product = $result->fetch_assoc();

// Truy vấn đánh giá cho sản phẩm
$reviewSql = "SELECT r.rating, r.comment, u.username 
              FROM reviews r 
              JOIN users u ON r.user_id = u.user_id 
              WHERE r.product_id = ?";
$stmt = $conn->prepare($reviewSql);
$stmt->bind_param("i", $product_id);
$stmt->execute();
$reviewResult = $stmt->get_result();

// Lưu đánh giá vào mảng
while ($row = $reviewResult->fetch_assoc()) {
  $reviews[] = $row;
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
  <title><?= $product['name'] ?></title>
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

  <!-- Product Details -->
  <section class="section product-detail">
    <div class="details container">
      <div class="left image-container">
        <div class="main">
          <img src="./images/<?php echo $product['image_url'] ?>" id="zoom" alt="" />
        </div>
      </div>
      <div class="right">
        <span>Trang chủ > <?php echo $product['category'] ?></span>
        <h1><?php echo $product['name'] ?></h1>
        <div class="price">
              <p style="color:#ccc"><del><?php echo number_format(($product['price']), 0, ",", ".") ?> đ</del></p>
              <h4><?php echo number_format(((100 - $product['discount']) / 100 * $product['price']), 0, ",", ".") ?> đ</h4>
            </div>
        <form class="form">
          <input type="text" placeholder="1" />
          <a onclick="addToCart(<?= $product['product_id'] ?>)" class="addCart">Thêm vào giỏ hàng</a>
        </form>
        <h3>Mô tả</h3>
        <p><?php echo $product['description'] ?></p>
      </div>
    </div>
  </section>

  <section id="section reviews">
    <h2>Đánh giá sản phẩm</h2>

    <?php if (!empty($reviews)): ?>
      <ul class="space-y-2">
        <?php foreach ($reviews as $review): ?>
          <li class="flex p-4 bg-gray-50 rounded-lg gap-4">
            <div>
              <strong class="text-gray-800"><?php echo htmlspecialchars($review['username']); ?></strong><br>

              <!-- Hiển thị số sao -->
              <div class="stars">
                <?php
                $rating = intval($review['rating']); // Lấy số sao
                for ($i = 1; $i <= 5; $i++):
                  if ($i <= $rating): ?>
                    <span class="star filled">★</span>
                  <?php else: ?>
                    <span class="star">☆</span>
                <?php endif;
                endfor;
                ?>
              </div>

              <p class="text-gray-600"><?php echo htmlspecialchars($review['comment']); ?></p>
            </div>
          </li>
        <?php endforeach; ?>
      </ul>
    <?php else: ?>
      <p class="text-gray-600">Chưa có đánh giá nào cho sản phẩm này.</p>
    <?php endif; ?>
  </section>

  <style>
    #section\ reviews {
      display: flex;
      flex-direction: column;
      margin-left: 200px;
    }

    .stars {
      display: inline-block;
    }

    .star {
      font-size: 1.2em;
      color: #ccc;
    }

    .star.filled {
      color: #ffc107;
    }
  </style>

  <!-- Latest Products -->
  <section class="section featured">
    <div class="top container">
      <h1>Sản phẩm mới nhất</h1>
      <a href="#" class="view-more">Xem thêm</a>
    </div>
    <div class="product-center container">
      <?php $countCart = 0;
      foreach ($newList as $product) : ?>
        <div class="product-item">
          <div class="overlay">
            <a href="" class="product-thumb">
              <img src="./images/<?php echo $product['image_url'] ?>" alt="" />
            </a>
            <span class="discount"><?php echo $product['discount']; ?>%</span>
          </div>
          <div class="product-info">
            <span><?php echo $product['category']; ?></span>
            <a href=""><?php echo $product['name']; ?></a>
            <div class="price">
              <p><del><?php echo number_format(($product['price']), 0, ",", ".") ?> đ</del></p>
              <h4><?php echo number_format(((100 - $product['discount']) / 100 * $product['price']), 0, ",", ".") ?> đ</h4>
            </div>
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
        <h4>INFORMATION</h4>
        <a href="">About us</a>
        <a href="">Contact Us</a>
        <a href="">Term & Conditions</a>
        <a href="">Shipping Guide</a>
      </div>
      <div class="col d-flex">
        <h4>USEFUL LINK</h4>
        <a href="">Online Store</a>
        <a href="">Customer Services</a>
        <a href="">Promotion</a>
        <a href="">Top Brands</a>
      </div>
      <div class="col d-flex">
        <span><i class="bx bxl-facebook-square"></i></span>
        <span><i class="bx bxl-instagram-alt"></i></span>
        <span><i class="bx bxl-github"></i></span>
        <span><i class="bx bxl-twitter"></i></span>
        <span><i class="bx bxl-pinterest"></i></span>
      </div>
    </div>
  </footer>
  <!-- Custom Script -->
  <script src="./js/index.js"></script>
  <script
    src="https://code.jquery.com/jquery-3.4.0.min.js"
    integrity="sha384-JUMjoW8OzDJw4oFpWIB2Bu/c6768ObEthBMVSiIx4ruBIEdyNSUQAjJNFqT5pnJ6"
    crossorigin="anonymous"></script>
  <script src="./js/zoomsl.min.js"></script>
  <script>
    $(function() {
      console.log("hello");
      $("#zoom").imagezoomsl({
        zoomrange: [4, 4],
      });
    });
  </script>
</body>

</html>