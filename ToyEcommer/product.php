<?php
require_once("./config/connect.php");
require_once("./config/product.config.php");

$count  = 0;
$username = $_COOKIE['username'];
if ($username) {
  $stmt = $conn->prepare("SELECT user_id FROM users WHERE username = ?");
  $stmt->bind_param("s", $username);
  $stmt->execute();
  $stmt->bind_result($user_id);
  $stmt->fetch();
  $stmt->close();

  $countSql = "SELECT COUNT(*) as count FROM cart WHERE user_id = $user_id";
  $result = $conn->query($countSql);
  $row = $result->fetch_assoc();
  $count = $row['count'];
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
  <title>Boy’s T-Shirt</title>
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
            <span class="d-flex"><?php echo $count ?></span>
          </a>
        </li>
      </ul>

      <div class="icons d-flex">
        <div class="icon" id="user-icon">
          <a href="./profile.php">
            <i class="bx bx-user"></i>
          </a>
          <div id="user-menu" class="user-menu">
            <ul>
              <li><a href="./profile.php">Chỉnh sửa thông tin</a></li>
              <li><a href="./logout.php">Đăng xuất</a></li>
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
          <span class="d-flex"><?php echo $count ?></span>
        </a>
      </div>

      <div class="hamburger">
        <i class="bx bx-menu-alt-left"></i>
      </div>
    </div>
  </div>

  <!-- All Products -->
  <section class="section all-products" id="products">
    <div class="top container">
      <h1>All Products</h1>
      <form>
        <select>
          <option value="1">Defualt Sorting</option>
          <option value="2">Sort By Price</option>
          <option value="3">Sort By Popularity</option>
          <option value="4">Sort By Sale</option>
          <option value="5">Sort By Rating</option>
        </select>
        <span><i class="bx bx-chevron-down"></i></span>
      </form>
    </div>
    <div class="product-center container">
      <?php foreach ($productList as $product): ?>
        <div class="product-item">
          <div class="overlay">
            <a href="./productDetails.php" class="product-thumb">
              <img src="./images/<?php echo $product['image_url'] ?>" alt="" />
            </a>
            <span class="discount">40%</span>
          </div>
          <div class="product-info">
            <span><?php echo $product['category']; ?></span>
            <a href="./productDetails.php"><?php echo $product['name']; ?></a>
            <h4><?php number_format(($product['price']), 0, ",", "."); ?></h4>
          </div>
          <ul class="icons">
            <li><i class="bx bx-heart"></i></li>
            <li><i class="bx bx-search"></i></li>
            <li><i class="bx bx-cart"></i></li>
          </ul>
        </div>
      <?php endforeach; ?>
    </div>
  </section>
  <section class="pagination">
    <div class="container">
      <span>1</span> <span>2</span> <span>3</span> <span>4</span>
      <span><i class="bx bx-right-arrow-alt"></i></span>
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