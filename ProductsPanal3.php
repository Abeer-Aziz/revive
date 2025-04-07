<!DOCTYPE html>
<html lang="ar" dir="rtl">
<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title>إدارة المنتجات</title>
    <link rel="stylesheet" href="styles.css">
    <script src="script.js" defer></script>
    <link rel="stylesheet" href="https://cdnjs.cloudflare.com/ajax/libs/font-awesome/6.0.0-beta3/css/all.min.css">
    <link href="https://fonts.googleapis.com/css2?family=Tajawal:wght@300;400;500;700&display=swap" rel="stylesheet">
    <link rel="stylesheet" href="https://unpkg.com/swiper/swiper-bundle.min.css" />

    <style>
        /* إضافة تنسيق للزر المتحرك */
        .availability-slider {
            -webkit-appearance: none;
            appearance: none;
            width: 50px;
            height: 25px;
            background: red;
            outline: none;
            border-radius: 25px;
            position: relative;
            transition: background 0.3s;
        }

        .availability-slider:checked {
            background: green;
        }

        .availability-slider:before {
            content: '';
            position: absolute;
            top: 2px;
            left: 2px;
            width: 20px;
            height: 20px;
            background: white;
            border-radius: 50%;
            transition: 0.3s;
        }

        .availability-slider:checked:before {
            left: 28px;
        }

        .slider-label {
            display: inline-block;
            margin-left: 10px;
        }
    </style>
</head>
<header>
        <div class="top-bar">
            <p>خصومات خاصة بمناسبة اليوم الوطني الإماراتي!</p>
            <div class="top-bar-right">
                <!-- زر البريد الإلكتروني -->
                <a href="mailto:hair.revive2019.com" class="contact-button">
                    <i class="fas fa-envelope"></i>
                    <span>hair.revive2019.com</span>
                </a>
                <!-- زر الواتساب -->
                <a href="https://wa.me/971557402639" class="contact-button">
                    <i class="fab fa-whatsapp"></i>
                    <span>+971 55 740 2639</span>
                </a>
                <!-- زر تبديل اللغة -->
                <a href="indexE.html" class="language-button">
                    <img src="images/uae.png" alt="العربية" class="flag-icon">
                    <span id="lang-text">ع</span>
                </a>
            </div>
        </div>
        <nav>
            <div class="logo">
                <img src="images/logo.png" alt="شعار المتجر">
            </div>
            <ul class="menu">
                <li><a href="admin dashboard.html">الرئيسية</a></li>
                <li><a href="ProductsPanal.html">إدارة المنتجات</a></li>
                <li><a href="Admin Panal.html">إدارة المسؤولين</a></li>
                <li><a href="OfferPanal.html">إدارة العروض</a></li>
            </ul>
            
            <div class="signup-icon">
                <a href="sign up.html">
                    <i class="fas fa-user-plus"></i> <!-- أيقونة تسجيل -->
                </a>
            </div>
           
        </nav>
    </header>

<?php
$servername = "localhost";
$username = "root";
$password = "";
$dbname = "revive_db";

// الاتصال بقاعدة البيانات
$conn = new mysqli($servername, $username, $password, $dbname);

// التحقق من الاتصال
if ($conn->connect_error) {
    die("فشل الاتصال: " . $conn->connect_error);
}

// استعلام لاسترجاع المنتجات من قاعدة البيانات
$sql = "SELECT * FROM products";
$result = $conn->query($sql);

?>

<!DOCTYPE html>
<html lang="ar">
<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title>عرض المنتجات</title>
    <style>
        body {
            font-family: Arial, sans-serif;
            background-color: #f4f4f4;
            margin: 0;
            padding: 0;
        }
        .container {
            width: 80%;
            margin: 20px auto;
            display: flex;
            flex-wrap: wrap;
        }
        .product-card {
            background-color: white;
            padding: 20px;
            margin: 10px;
            border-radius: 8px;
            box-shadow: 0 0 10px rgba(0, 0, 0, 0.1);
            width: calc(33.33% - 20px);
            text-align: center;
        }
        .product-card img {
            width: 100%;
            height: auto;
            border-radius: 8px;
        }
        .product-card h3 {
            color: #333;
        }
        .product-card p {
            color: #555;
        }
        .price {
            font-size: 1.2em;
            color: #f39c12;
        }
    </style>
</head>
<body>

<div class="container">
    <?php
    if ($result->num_rows > 0) {
        // عرض المنتجات
        while ($row = $result->fetch_assoc()) {
            echo "<div class='product-card'>";
            echo "<img src='" . $row['image'] . "' alt='" . $row['name'] . "'>";
            echo "<h3>" . $row['name'] . "</h3>";
            echo "<p>" . $row['description'] . "</p>";
            echo "<p class='price'>" . $row['price'] . " ج.م</p>";
            echo "<p>الكمية المتاحة: " . $row['quantity'] . "</p>";
            echo "</div>";
        }
    } else {
        echo "<p>لا توجد منتجات لعرضها.</p>";
    }
    ?>
</div>

</body>
</html>

<?php
$conn->close();
?>
