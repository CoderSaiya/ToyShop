<?php
require_once('./config/connect.php');
require_once('./config/product.config.php');

$product_id = isset($_GET['id']) ? (int)$_GET['id'] : 0;

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
  $count = $row['count'];
}


$productSql = "SELECT A.*, B.name AS category FROM products A JOIN categories B ON A.category_id = B.category_id AND A.product_id = $product_id";
$result = $conn->query($productSql);
$product = $result->fetch_assoc();

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
  <title>Boy’s T-Shirt - Codevo</title>
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
          <a href="./login.php" class="icon">
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
        <a href="./login.php" class="icon">
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
        <span>Trang chủ/<?php echo $product['category'] ?></span>
        <h1><?php echo $product['name'] ?></h1>
        <div class="price"><?php echo number_format($product['price'], 0, ",", ".") ?> đ</div>
        <form class="form">
          <input type="text" placeholder="1" />
          <a onclick="addToCart(<?= $product['product_id'] ?>)" class="addCart">Thêm vào giỏ hàng</a>
        </form>
        <h3>Mô tả</h3>
        <p><?php echo $product['description'] ?></p>
      </div>
    </div>
  </section>

  <!-- Latest Products -->
  <section class="section featured">
    <div class="top container">
      <h1>Sản phẩm mới nhất/h1>
        <a href="#" class="view-more">Xem thêm</a>
    </div>
    <div class="product-center container">
      <?php $count = 0;
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
        $count++;
        if ($count == 4) break;
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