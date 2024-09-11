const hamburer = document.querySelector(".hamburger");
const navList = document.querySelector(".nav-list");

if (hamburer) {
  hamburer.addEventListener("click", () => {
    navList.classList.toggle("open");
  });
}

// Popup
// const popup = document.querySelector(".popup");
// const closePopup = document.querySelector(".popup-close");

// if (popup) {
//   closePopup.addEventListener("click", () => {
//     popup.classList.add("hide-popup");
//   });

//   window.addEventListener("load", () => {
//     setTimeout(() => {
//       popup.classList.remove("hide-popup");
//     }, 1000);
//   });
// }

let currentPage = 1;

function loadPage(page) {
  currentPage = page;

  sort(1, currentPage);
}

document.addEventListener("DOMContentLoaded", function () {
  const userIcon = document.getElementById("user-icon");
  const userMenu = document.getElementById("user-menu");
  const userLink = document.getElementById("user-link");

  const isLoggedIn = localStorage.getItem("isLogin");

  if (isLoggedIn === "true") {
    userIcon.classList.add("logged-in");
    userLink.href = "./profile.php";
  } else {
    userLink.href = "./login.php";
    userMenu.style.display = "none";
    userIcon.classList.remove("logged-in");
    userIcon.classList.remove("hover-enabled");
  }

  if (window.location.href.includes("product.php")) {
    sort();

    const paginationContainer = document.querySelector(".pagination");
    const pageLinks = paginationContainer.querySelectorAll("span[data-page]");
    const nextButton = paginationContainer.querySelector(".next");

    pageLinks.forEach((link) => {
      link.addEventListener("click", function () {
        e.preventDefault();
        const page = parseInt(this.getAttribute("data-page"));
        loadPage(page);
      });
    });

    nextButton.addEventListener("click", function () {
      if (currentPage < pageLinks.length) {
        loadPage(currentPage + 1);
      }
    });
  }
});

document.addEventListener("DOMContentLoaded", function () {
  const cardPayment = document.querySelector(".payment__type--cc");
  const codPayment = document.querySelector(".payment__type--paypal");

  cardPayment.addEventListener("click", function () {
    cardPayment.classList.add("active");
    codPayment.classList.remove("active");
    document.getElementById("paymentMethod").value = "stripe";
  });

  codPayment.addEventListener("click", function () {
    codPayment.classList.add("active");
    cardPayment.classList.remove("active");
    document.getElementById("paymentMethod").value = "cod";
  });
});

document.querySelectorAll('.rating-stars').forEach(function(starsContainer) {
  const stars = starsContainer.querySelectorAll('.star');
  const ratingInput = starsContainer.querySelector('input[type="hidden"]');

  stars.forEach(function(star, index) {
      star.addEventListener('click', function() {
          const ratingValue = index + 1;
          ratingInput.value = ratingValue;
          stars.forEach(function(s, i) {
              if (i < ratingValue) {
                  s.classList.add('selected');
              } else {
                  s.classList.remove('selected');
              }
          });
      });
  });
});

document.addEventListener('DOMContentLoaded', function () {
  const reviewForms = document.querySelectorAll('form[action="./config/submit_review.php"]');
  
  reviewForms.forEach(function (form) {
      const productId = form.querySelector('input[name="product_id"]').value;
      const orderId = form.querySelector('input[name="order_id"]').value;
      const userId = form.querySelector('input[name="user_id"]').value;

      // Perform an AJAX request to check if the review exists
      fetch('./config/check_review.php', {
          method: 'POST',
          headers: {
              'Content-Type': 'application/x-www-form-urlencoded',
          },
          body: new URLSearchParams({
              product_id: productId,
              order_id: orderId,
              user_id: userId
          })
      })
      .then(response => response.json())
      .then(data => {
          if (data.review_exists) {
              // Hide the review form if the review already exists
              form.style.display = 'none';
          }
      })
      .catch(error => console.error('Error:', error));
  });
});

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
              window.location.href = `http://localhost:3000/${response.redirect}`;
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
  var email = document.getElementById("email").value;
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

document.addEventListener("DOMContentLoaded", function () {});

function handleLogout() {
  document.cookie =
    "username" + "=; Path=/; Expires=Thu, 01 Jan 1970 00:00:01 GMT;";

  const role = localStorage.getItem("role");

  if (role) {
    localStorage.setItem("role", "");
  }
  localStorage.setItem("isLogin", false);
  localStorage.setItem("user_id", "");

  window.location.href = "http://localhost:3000/login.php";
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

//sidebar-profile
document.addEventListener("scroll", () => {
  const sections = document.querySelectorAll(".user-info-content section");
  const navLinks = document.querySelectorAll(".user-info-sidebar a");

  let current = "";

  sections.forEach((section) => {
    const sectionTop = section.offsetTop;
    const sectionHeight = section.clientHeight;

    if (pageYOffset >= sectionTop - sectionHeight / 3) {
      current = section.getAttribute("id");
    }
  });

  navLinks.forEach((link) => {
    link.classList.remove("active");
    if (link.getAttribute("href") === `#${current}`) {
      link.classList.add("active");
    }
  });

  const sidebar = document.querySelector(".user-info-sidebar");
  const navbarHeight = document.querySelector(".navigation").offsetHeight;

  if (window.scrollY > navbarHeight) {
    sidebar.classList.add("fixed");
  } else {
    sidebar.classList.remove("fixed");
  }
});

function saveUserInfo() {
  const fName = document.getElementById("user-info-fname").value;
  const lName = document.getElementById("user-info-lname").value;
  const phone = document.getElementById("user-info-phone").value;
  const email = document.getElementById("user-info-email").value;
  let gender;
  const genderValue = document.getElementsByName("user-info-gender");
  for (const radio of genderValue) {
    if (radio.checked) {
      gender = radio.value;
    }
  }
  const birthday = document.getElementById("user-info-birthday").value;
  const pob = document.getElementById("user-info-pob").value;
  const resetPassword = document.getElementById("user-info-checkpw").checked;
  const oldPassword = document.getElementById("user-info-old-pass").value;
  const newPassword = document.getElementById("user-info-new-pass").value;

  if (!fName || !lName || !phone || !email || !gender || !birthday || !pob) {
    alert("Thông tin tài khoản không được bỏ trống.");
    return;
  }

  // Create FormData object
  const formData = new FormData();
  formData.append("fName", fName);
  formData.append("lName", lName);
  formData.append("phone", phone);
  formData.append("email", email);
  formData.append("gender", gender);
  formData.append("birthday", birthday);
  formData.append("pob", pob);

  // Append password fields only if checkbox is checked
  if (resetPassword) {
    if (!oldPassword || !newPassword) {
      alert("Vui lòng nhập mật khẩu cũ và mật khẩu mới.");
      return;
    }
    formData.append("oldPassword", oldPassword);
    formData.append("newPassword", newPassword);
  }

  // Create XMLHttpRequest
  const xmlhttp = new XMLHttpRequest();
  xmlhttp.onreadystatechange = function () {
    if (this.readyState === 1) {
      document.getElementById("loadingPopup").style.display = "block";
    }

    if (this.readyState === 4) {
      setTimeout(() => {
        document.getElementById("loadingPopup").style.display = "none";

        if (this.status === 200) {
          console.log("Response received:", this.responseText);
          try {
            const response = JSON.parse(this.responseText);
            console.log("Parsed response:", response);

            if (response.success) {
              alert("Cập nhật thành công!");
              window.location.reload();
            } else {
              alert(response.error || "Cập nhật thất bại.");
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

  xmlhttp.open("POST", "../config/save_user.php", true);
  xmlhttp.send(formData);
}

document
  .getElementById("user-info-checkpw")
  .addEventListener("change", function () {
    const check = document.getElementById("user-info-checkpw");
    const passContainer = document.getElementsByClassName(
      "user-pass-container"
    )[0];

    if (check.checked) {
      passContainer.classList.remove("hidden");
      setTimeout(() => passContainer.classList.add("show"), 10);
    } else {
      passContainer.classList.remove("show");
      setTimeout(() => passContainer.classList.add("hidden"), 10);
    }
  });

var timeout = null;

function search(keyword) {
  clearTimeout(timeout);
  if (keyword.length === 0) {
    document.getElementById("search-results").innerHTML = "";
    return;
  }

  timeout = setTimeout(() => {
    document.getElementById("search-results").innerHTML =
      '<div class="loader-search"></div>';
    fetch("../config/search.php?q=" + encodeURIComponent(keyword))
      .then((response) => response.text())
      .then((data) => {
        if (data.length === 0) {
          document.getElementById("search-results").innerHTML =
            "không có kết quả";
          return;
        }
        document.getElementById("search-results").innerHTML = data;
      })
      .catch((error) => {
        console.error("Error fetching the search results:", error);
      });
  }, 300);
}

function addSkeletonLoader(count = 4) {
  const productContainer = document.querySelector(".product-center.container");
  productContainer.innerHTML = "";

  for (let i = 0; i < count; i++) {
    const skeleton = `
          <div class="skeleton skeleton-product">
              <div class="skeleton-image skeleton"></div>
              <div class="skeleton-info">
                  <div class="skeleton-title skeleton"></div>
                  <div class="skeleton-price skeleton"></div>
              </div>
          </div>
      `;
    productContainer.innerHTML += skeleton;
  }
}

function sort(select = 1, page = 1) {
  const formData = new FormData();
  formData.append("sort", select);

  document.querySelector(".product-center.container").innerHTML = "";

  addSkeletonLoader();
  fetch("../config/filtered_product.php?page=" + page, {
    method: "POST",
    body: formData,
  })
    .then((response) => response.json())
    .then((data) => {
      document.querySelector(".product-center.container").innerHTML =
        data.products;
      document.querySelector(".pagination").innerHTML = data.pagination;
    })
    .catch((err) => console.log("error: " + err));
}

function loadCategory(categoryId, sort = 1, page = 1) {
  const formData = new FormData();
  formData.append("category_id", categoryId);
  formData.append("sort", sort);

  document.querySelector(".product-center.container").innerHTML = "";

  addSkeletonLoader();

  fetch("../config/filtered_product.php?page=" + page, {
      method: "POST",
      body: formData,
  })
  .then((response) => response.json())
  .then((data) => {
      document.querySelector(".product-center.container").innerHTML = data.products;
      document.querySelector(".pagination").innerHTML = data.pagination;
  })
  .catch((err) => console.log("error: " + err));
}

function sendEmail() {
  const recipientEmail = document.getElementById("contact-email").value;

  if (!recipientEmail) {
    alert("Please enter an email address.");
    return false;
  }
  document.getElementById("loadingPopup").style.display = "block";
  fetch("../config/send_mail.php", {
    method: "POST",
    headers: {
      "Content-Type": "application/json",
    },
    body: JSON.stringify({ recipientEmail: recipientEmail }),
  })
    .then((response) => {
      document.getElementById("loadingPopup").style.display = "none";
      const contentType = response.headers.get("content-type");
      if (contentType && contentType.indexOf("application/json") !== -1) {
        return response.json();
      } else {
        return response.text().then((text) => {
          throw new Error(text);
        });
      }
    })
    .then((data) => {
      if (data.status === "success") {
        console.log("Email sent successfully:", data.message);
        alert("Email sent successfully!");
      } else {
        console.error("Error sending email:", data.message);
        alert("Error sending email: " + data.message);
      }
    })
    .catch((error) => {
      console.error("Fetch error:", error);
      alert("Đã có lỗi xảy ra trong quá tình gửi mail.");
    });

  return false;
}

function addAddress() {
  const address = document.getElementById("address");
  address.innerHTML = `
                <h2>Thêm địa chỉ</h2>
                <div class="address-info">
                  <div class="field-input-pob">
                      <label for="user-info-fname">Số nhà</label>
                      <input type="text" name="user-info-fname" id="user-info-fname" class="input-control" value="">
                  </div>
                  <div class="field-input-pob">
                     <label for="user-info-position">Quốc gia</label><br>
                     <select name="user-info-pob" id="user-info-pob"
                         class="input-control" style="width: 100%;">
                         <option selected>Việt Nam</option>
                     </select>
                 </div>
                  <div class="field-input-pob">
                     <label for="user-info-position">Tỉnh/Thành Phố</label><br>
                     <select name="user-info-city" id="city"
                         class="input-control" style="width: 100%;">
                         <option selected>Hồ Chí Minh</option>
                         <option>Hà Nội</option>
                     </select>
                 </div>
                  <div class="field-input-pob">
                     <label for="user-info-position">Quận/Huyện</label><br>
                     <select name="user-info-district" id="district"
                         class="input-control" style="width: 100%;">
                         
                     </select>
                 </div>
                  <div class="field-input-pob">
                     <label for="user-info-position">Phường/Xã</label><br>
                     <select name="user-info-pob" id="ward"
                         class="input-control" style="width: 100%;">
                         
                     </select>
                 </div>
                 <button onclick="a">Xác nhận</button>
                </div>`;

  const info = document.getElementsByClassName("address-info")[0];
  const fields = info.getElementsByClassName("field-input-pob");
  info.style.display = "grid";
  info.style.justifyContent = "center";
  info.style.gap = "20px";
  info.style.width = "100%";
  Array.from(fields).forEach(function (field) {
    field.style.width = "100%";
  });

  updateDistrictsAndWards();

  // Thêm sự kiện lắng nghe thay đổi của citySelect
  const citySelect = document.getElementById("city");
  citySelect.addEventListener("change", updateDistrictsAndWards);
}

function updateDistrictsAndWards() {
  const citySelect = document.getElementById("city");
  const districtSelect = document.getElementById("district");
  const wardSelect = document.getElementById("ward");

  const districts = {
    "Hồ Chí Minh": ["Quận 1", "Quận 2", "Quận 3"],
    "Hà Nội": ["Ba Đình", "Hoàn Kiếm", "Tây Hồ"],
  };

  const wards = {
    "Quận 1": ["Phường A", "Phường B"],
    "Quận 2": ["Phường C", "Phường D"],
    "Ba Đình": ["Phường X", "Phường Y"],
    "Hoàn Kiếm": ["Phường Z", "Phường W"],
  };

  // Clear existing options
  districtSelect.innerHTML = "";
  wardSelect.innerHTML = "";

  // Populate districts based on selected city
  const selectedCity = citySelect.value;
  if (districts[selectedCity]) {
    districts[selectedCity].forEach(function (district) {
      const option = document.createElement("option");
      option.text = district;
      option.value = district;
      districtSelect.add(option);
    });
  }

  // Tự động cập nhật wards khi district thay đổi
  districtSelect.addEventListener("change", function () {
    wardSelect.innerHTML = ""; // Clear existing wards
    const selectedDistrict = districtSelect.value;
    if (wards[selectedDistrict]) {
      wards[selectedDistrict].forEach(function (ward) {
        const option = document.createElement("option");
        option.text = ward;
        option.value = ward;
        wardSelect.add(option);
      });
    }
  });

  // Gọi sự kiện thay đổi để cập nhật wards khi district đầu tiên được chọn
  if (districtSelect.value) {
    const event = new Event("change");
    districtSelect.dispatchEvent(event);
  }
}
