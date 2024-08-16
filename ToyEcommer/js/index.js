const hamburer = document.querySelector(".hamburger");
const navList = document.querySelector(".nav-list");

if (hamburer) {
  hamburer.addEventListener("click", () => {
    navList.classList.toggle("open");
  });
}

// Popup
const popup = document.querySelector(".popup");
const closePopup = document.querySelector(".popup-close");

if (popup) {
  closePopup.addEventListener("click", () => {
    popup.classList.add("hide-popup");
  });

  window.addEventListener("load", () => {
    setTimeout(() => {
      popup.classList.remove("hide-popup");
    }, 1000);
  });
}

function loginUser() {
  // Get username and password values
  var username = document.getElementById("username").value;
  var password = document.getElementById("password").value;

  if (username.length === 0 || password.length === 0) {
    alert("Tài khoản và mật khẩu không được bỏ trống.");
    return;
  }

  var xmlhttp = new XMLHttpRequest();
  xmlhttp.onreadystatechange = function () {
    if (this.readyState === 1) {
      document.getElementById("loadingPopup").style.display = "block";
    }

    if (this.readyState == 4) {
      setTimeout(() => {
        document.getElementById("loadingPopup").style.display = "none";

        if (this.status == 200) {
          console.log("Response received:", this.responseText);
          try {
            var response = JSON.parse(this.responseText);
            console.log("Parsed response:", response);

            if (response.success) {
              alert("Đăng nhập thành công!");
              localStorage.setItem("isLogin", true);
              localStorage.setItem("user_id", username);
              document.cookie = `username=${username}; path=/`;
              window.location.replace("http://localhost:3000");
            } else {
              alert(response.error || "Đăng nhập thất bại.");
            }
          } catch (e) {
            alert("Error parsing server response.");
            console.error("Parsing error:", e);
          }
        } else {
          alert("Lỗi kết nối đến máy chủ.");
        }
      }, 2000);
    }
  };

  xmlhttp.open("POST", "../config/login.php", true);
  xmlhttp.setRequestHeader("Content-Type", "application/x-www-form-urlencoded");
  xmlhttp.send(
    "username=" +
      encodeURIComponent(username) +
      "&password=" +
      encodeURIComponent(password)
  );
}

function signUpUser() {
  var username = document.getElementById("username").value;
  var password = document.getElementById("password").value;
  var email = document.getElementById('email').value;
  var repeat = document.getElementById("password-repeat").value;
  var fName = document.getElementById("fName").value;
  var lName = document.getElementById("lName").value;
  var role = "customer";

  if (!username || !password || !email || !repeat || !fName || !lName) {
    alert("Thông tin tài khoản không được bỏ trống.");
    return;
  }

  if (password !== repeat) {
    alert("Mật khẩu không trùng khớp!");
    return;
  }

  var xmlhttp = new XMLHttpRequest();
  xmlhttp.onreadystatechange = function () {
    if (this.readyState === 1) {
      document.getElementById("loadingPopup").style.display = "block";
    }

    if (this.readyState == 4) {
      setTimeout(() => {
        document.getElementById("loadingPopup").style.display = "none";

        if (this.status == 200) {
          console.log("Response received:", this.responseText);
          try {
            var response = JSON.parse(this.responseText);
            console.log("Parsed response:", response);

            if (response.success) {
              alert("Đăng Ký thành công!");
              window.location.replace("http://localhost:3000/login.php");
            } else {
              alert(response.error || "Đăng ký thất bại.");
            }
          } catch (e) {
            alert("Error parsing server response.");
            console.error("Parsing error:", e);
          }
        } else {
          alert("Lỗi kết nối đến máy chủ.");
        }
      }, 2000);
    }
  };

  xmlhttp.open("POST", "../config/signup.php", true);
  xmlhttp.setRequestHeader("Content-Type", "application/x-www-form-urlencoded");
  xmlhttp.send(
    "username=" +
      encodeURIComponent(username) +
      "&password=" +
      encodeURIComponent(password) +
      "&email=" +
      encodeURIComponent(email) +
      "&fName=" +
      encodeURIComponent(fName) +
      "&lName=" +
      encodeURIComponent(lName) +
      "&role=" +
      encodeURIComponent(role)
  );
}

function addToCart(productID) {
  const userId = localStorage.getItem("user_id");
  if (userId === undefined || userId === null) {
    window.location.href = "http://localhost:3000/login.php";
    return;
  }

  const data = new FormData();
  data.append("product_id", productID);
  data.append("user_id", userId);

  fetch("../config/add_to_cart.php", {
    method: "POST",
    body: data,
  })
    .then((response) => response.text())
    .then((data) => {
      alert(data);
      window.location.reload();
    })
    .catch((error) => {
      console.log(error);
    });
}

function handelLoadCart() {
  const userId = localStorage.getItem("user_id");
  if (userId === undefined || userId === null) {
    window.location.href = "http://localhost:3000/login.php";
    return;
  }

  window.location.href = "http://localhost:3000/cart.php";
}

function updateQuantity(productId, quantity) {
  console.log(productId, quantity);
  fetch("./config/update_cart.php", {
    method: "POST",
    headers: {
      "Content-Type": "application/x-www-form-urlencoded",
    },
    body: `product_id=${productId}&quantity=${quantity}`,
  })
    .then((response) => response.json())
    .then((data) => {
      if (data.status === "success") {
        console.log("Đã cập nhật số lượng");
        window.location.reload();
      } else {
        console.error("Cập nhật thất bại:", data.message);
      }
    })
    .catch((error) => console.error("Lỗi:", error));
}

function removeFromCart(productId) {
  if (confirm("Bạn có chắc chắn muốn xóa sản phẩm này khỏi giỏ hàng?")) {
    fetch("./config/remove_cart.php", {
      method: "POST",
      headers: {
        "Content-Type": "application/x-www-form-urlencoded",
      },
      body: `product_id=${productId}`,
    })
      .then((response) => response.json())
      .then((data) => {
        if (data.status === "success") {
          window.location.reload();
        } else {
          console.error("Xóa không thành công:", data.message);
        }
      })
      .catch((error) => console.error("Lỗi:", error));
  }
}

document.addEventListener("DOMContentLoaded", function () {
  const userIcon = document.getElementById("user-icon");
  const userMenu = document.getElementById("user-menu");
  const userLink = document.getElementById("user-link");

  const isLoggedIn = JSON.parse(localStorage.getItem("isLogin"));

  if (isLoggedIn) {
    userIcon.classList.add("logged-in");
    userLink.href = "./profile.php";
  } else {
    userLink.href = "./login.php";

    userMenu.style.display = "none";
    userIcon.classList.remove("hover-enabled");
  }
});

function handleLogout() {
  document.cookie =
    "username" + "=; Path=/; Expires=Thu, 01 Jan 1970 00:00:01 GMT;";

  localStorage.setItem("isLogin", false);
  localStorage.setItem("user_id", "");
  window.location.reload();
}

fetch("./config/check_cookie.php")
  .then((response) => response.json())
  .then((data) => {
    if (data.status === "missing") {
      localStorage.setItem("user_id", "");
      localStorage.setItem("isLogin", false);
      console.log("Cookie không còn, local storage đã được thiết lập lại.");
    } else {
      console.log("Cookie vẫn tồn tại.");
    }
  })
  .catch((error) => console.error("Lỗi khi kiểm tra cookie:", error));
