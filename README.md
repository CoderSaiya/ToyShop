# ToyShop
a project for web dev UTH
# Set up
*1. Run script to create database*
```sql
  -- Drop tables if they exist
  DROP TABLE IF EXISTS ProductOrders;
  DROP TABLE IF EXISTS Wishlist;
  DROP TABLE IF EXISTS Cart;
  DROP TABLE IF EXISTS Orders;
  DROP TABLE IF EXISTS Reviews;
  DROP TABLE IF EXISTS Products;
  DROP TABLE IF EXISTS Categories;
  DROP TABLE IF EXISTS Users;
  
  -- Users Table
  CREATE TABLE Users (
      user_id INT PRIMARY KEY AUTO_INCREMENT,
      username VARCHAR(50) UNIQUE NOT NULL,
      email VARCHAR(100) UNIQUE NOT NULL,
      password_hash VARCHAR(255) NOT NULL,
      first_name VARCHAR(50),
      last_name VARCHAR(50),
      role ENUM('customer', 'admin') DEFAULT 'customer',
      created_at TIMESTAMP DEFAULT CURRENT_TIMESTAMP
  );
  
  -- Categories Table
  CREATE TABLE Categories (
      category_id INT PRIMARY KEY AUTO_INCREMENT,
      name VARCHAR(100) NOT NULL,
      description TEXT
  );
  
  -- Products Table
  CREATE TABLE Products (
      product_id INT PRIMARY KEY AUTO_INCREMENT,
      name VARCHAR(100) NOT NULL,
      description TEXT,
      price DECIMAL(10, 2) NOT NULL,
      stock INT NOT NULL,
      category_id INT,
      image_url VARCHAR(255),
      created_at TIMESTAMP DEFAULT CURRENT_TIMESTAMP,
      updated_at TIMESTAMP DEFAULT CURRENT_TIMESTAMP ON UPDATE CURRENT_TIMESTAMP,
      FOREIGN KEY (category_id) REFERENCES Categories(category_id)
  );
  
  -- Orders Table
  CREATE TABLE Orders (
      order_id INT PRIMARY KEY AUTO_INCREMENT,
      user_id INT NOT NULL,
      total_price DECIMAL(10, 2) NOT NULL,
      status ENUM('pending', 'shipped', 'delivered', 'cancelled') DEFAULT 'pending',
      created_at TIMESTAMP DEFAULT CURRENT_TIMESTAMP,
      FOREIGN KEY (user_id) REFERENCES Users(user_id)
  );
  
  -- Join table for Order-Product (Many-to-Many)
  CREATE TABLE ProductOrders (
      order_id INT NOT NULL,
      product_id INT NOT NULL,
      quantity INT NOT NULL,
      price DECIMAL(10, 2) NOT NULL,
      PRIMARY KEY (order_id, product_id),
      FOREIGN KEY (order_id) REFERENCES Orders(order_id),
      FOREIGN KEY (product_id) REFERENCES Products(product_id)
  );
  
  -- Reviews Table
  CREATE TABLE Reviews (
      review_id INT PRIMARY KEY AUTO_INCREMENT,
      product_id INT NOT NULL,
      user_id INT NOT NULL,
      rating INT CHECK (rating BETWEEN 1 AND 5),
      comment TEXT,
      created_at TIMESTAMP DEFAULT CURRENT_TIMESTAMP,
      FOREIGN KEY (product_id) REFERENCES Products(product_id),
      FOREIGN KEY (user_id) REFERENCES Users(user_id)
  );
  -- Cart Table
  CREATE TABLE Cart (
      cart_id INT PRIMARY KEY AUTO_INCREMENT,
      user_id INT NOT NULL,
      product_id INT NOT NULL,
      quantity INT NOT NULL,
      UNIQUE(user_id,product_id),
      created_at TIMESTAMP DEFAULT CURRENT_TIMESTAMP,
      FOREIGN KEY (user_id) REFERENCES Users(user_id),
      FOREIGN KEY (product_id) REFERENCES Products(product_id)
  );
  
  -- Wishlist Table
  CREATE TABLE Wishlist (
      wishlist_id INT PRIMARY KEY AUTO_INCREMENT,
      user_id INT NOT NULL UNIQUE,
      product_id INT NOT NULL,
      UNIQUE(user_id,product_id),
      created_at TIMESTAMP DEFAULT CURRENT_TIMESTAMP,
      FOREIGN KEY (user_id) REFERENCES Users(user_id),
      FOREIGN KEY (product_id) REFERENCES Products(product_id)
  );
```
*2. Clone this project to your device*
```git
  git clone <url of repository>
```
*3. Create sample data for testing*
*4. Set up enviriroment for MySQL and PHP Server by XAMPP or Docker*
```link
  XAMPP: https://www.wikihow.com/Set-up-a-Personal-Web-Server-with-XAMPP
  Docker: https://www.sitepoint.com/php-development-environment/
```
  *5. Run PHP Server and test featuring*
