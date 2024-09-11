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
  <!-- <script src="https://www.google.com/recaptcha/api.js" async defer></script> -->
  <title>Login</title>
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
            <span class="d-flex">0</span>
          </a>
        </li>
      </ul>

      <div class="icons d-flex">
        <div class="icon" id="user-icon">
          <a href="./profile.php">
            <i class="bx bx-user"></i>
          </a>
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
          <span class="d-flex">0</span>
        </a>
      </div>

      <div class="hamburger">
        <i class="bx bx-menu-alt-left"></i>
      </div>
    </div>
  </div>
  <!-- Login -->
  <div class="container">
    <div class="login-form">
      <form action="" onsubmit="event.preventDefault(); loginUser();">

        <!-- load -->
        <div id="loadingPopup" style="display: none; position: fixed; top: 50%; left: 50%; transform: translate(-50%, -50%); background: rgba(0, 0, 0, 0.8); padding: 20px; border-radius: 5px; color: #fff; text-align: center; z-index: 1000;">
          <p>Đang tải...</p>
          <div class="loader"></div>
        </div>

        <h1>Đăng nhập</h1>
        <p>
          Bạn đã có tài khoản? Đăng nhập hoặc
          <a href="./signup.php">Đăng ký</a>
        </p>

        <label for="username">Tài khoản</label>
        <input id="username" type="text" placeholder="Nhập tài khoản" name="username" required />

        <label for="psw">Mật khẩu</label>
        <input
          id="password"
          type="password"
          placeholder="Nhập mật khẩu"
          name="psw"
          required />

        <label>
          <input
            type="checkbox"
            checked="checked"
            name="remember"
            style="margin-bottom: 15px" />
          Lưu đăng nhập
        </label>

        <p>
          Bằng cách tạo tài khoản, bạn đồng ý với Điều khoản & Quyền riêng tư.
          <a href="#">Điều khoản & Quyền riêng tư</a>
          của chúng tôi.
        </p>

        <!-- <div class="g-recaptcha" data-sitekey="6Lcl-jgqAAAAAJY3_LabPk5U3PAlwKhw56fPGhLL"></div> -->

        <div class="buttons">
          <button type="button" class="cancelbtn">Hủy</button>
          <button type="submit" class="signupbtn">Đăng nhập</button>
        </div>
      </form>
    </div>
  </div>

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