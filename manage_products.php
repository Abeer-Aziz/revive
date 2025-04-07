<?php
// إنشاء الاتصال
$conn = new mysqli("localhost", "root", "", "revive_db");

// التحقق من الاتصال
if ($conn->connect_error) {
    die("فشل الاتصال: " . $conn->connect_error);
}

// معالجة البيانات المرسلة من النموذج
if ($_SERVER['REQUEST_METHOD'] === 'POST') {
    header('Content-Type: application/json; charset=utf-8'); // ← أضف هذا السطر هنا

    // استلام البيانات من النموذج
    $name = trim($_POST['name']);
    $price = floatval($_POST['price']); // تحويل السعر إلى عدد عشري
    $quantity = intval($_POST['quantity']); // تحويل الكمية إلى عدد صحيح
    $description = trim($_POST['description']);
    $availability = isset($_POST['availability']) ? 1 : 0;
    $category = trim($_POST['category']); // استلام التصنيف

    // دالة لتسجيل المتغيرات المرسلة من النموذج
    var_dump($_POST);  // عرض القيم المرسلة للتأكد منها

    // التحقق من صحة البيانات
    if (empty($name) || empty($price) || empty($quantity) || empty($category)) {
        echo json_encode(["success" => false, "error" => "يرجى تعبئة جميع الحقول الإجبارية."]);
        exit;
    }

    // التحقق من أن التصنيف مسموح به (ENUM)
    $valid_categories = ['بخور', 'مستلزمات العناية', 'زيوت', 'ماسكات', 'اعشاب']; // القيم المسموح بها
    echo "التصنيف المرسل: " . $category . "\n";  // إضافة تتبع للعرض
    if (!in_array($category, $valid_categories)) {
        echo json_encode([
            "success" => false, 
            "error" => "التصنيف غير صحيح. القيم المسموح بها هي: " . implode(', ', $valid_categories) . " ولكن التصنيف المرسل هو: " . $category
        ]);
        exit;
    }

    // معالجة صورة المنتج
    $image = "";
    if (isset($_FILES['image']) && $_FILES['image']['error'] == 0) {
        $target_dir = "uploads/"; // مجلد لحفظ الصور
        if (!is_dir($target_dir)) {
            mkdir($target_dir, 0777, true); // إنشاء المجلد إذا لم يكن موجودًا
        }
        $target_file = $target_dir . basename($_FILES["image"]["name"]);
        if (move_uploaded_file($_FILES["image"]["tmp_name"], $target_file)) {
            $image = $target_file; // حفظ مسار الصورة
        } else {
            echo json_encode(["success" => false, "error" => "فشل نقل الصورة إلى المجلد."]);
            exit;
        }
    }

    // إدخال البيانات في قاعدة البيانات
    $sql = "INSERT INTO products (name, price, quantity, description, availability, image, category)
            VALUES (?, ?, ?, ?, ?, ?, ?)";

    $stmt = $conn->prepare($sql);
    if ($stmt) {
        $stmt->bind_param("sdissis", $name, $price, $quantity, $description, $availability, $image, $category);

        if ($stmt->execute()) {
            echo json_encode([
                "success" => true,
                "product" => [
                    "id" => $conn->insert_id,
                    "name" => $name,
                    "price" => $price,
                    "quantity" => $quantity,
                    "description" => $description,
                    "availability" => $availability,
                    "image" => $image,
                    "category" => $category,
                ],
            ]);
        } else {
            echo json_encode(["success" => false, "error" => $conn->error]);
        }

        // إغلاق العبارة
        $stmt->close();
    } else {
        echo json_encode(["success" => false, "error" => "خطأ في إعداد الاستعلام."]);
    }

    // إغلاق الاتصال بقاعدة البيانات
    $conn->close();
    exit;
}
?>
