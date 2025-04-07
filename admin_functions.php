<?php
// أهم الإعدادات الأولية
header('Content-Type: application/json'); // لإرجاع بيانات بصيغة JSON
header("Access-Control-Allow-Origin: *"); // للسماح بالاتصال من أي مصدر
header("Access-Control-Allow-Methods: GET, POST"); // السماح بطرق GET و POST فقط

// 1. الاتصال بقاعدة البيانات
$conn = new mysqli("localhost", "root", "", "revive_db");

// التحقق من وجود أخطاء في الاتصال
if ($conn->connect_error) {
    echo json_encode([
        'success' => false,
        'message' => 'خطأ في الاتصال بقاعدة البيانات',
        'error' => $conn->connect_error
    ]);
    exit;
}

// 2. استقبال البيانات المرسلة
$input = json_decode(file_get_contents('php://input'), true);

// 3. التحقق من وجود بيانات
if (!$input || !isset($input['action'])) {
    echo json_encode([
        'success' => false,
        'message' => 'لم يتم إرسال بيانات صحيحة'
    ]);
    exit;
}

// 4. معالجة الطلبات حسب نوع الإجراء (Action)
switch ($input['action']) {
    // الحالة الأولى: جلب جميع المسؤولين
    case 'get_admins':
        $result = $conn->query("SELECT * FROM admins ORDER BY id DESC");
        
        // إذا حدث خطأ في الاستعلام
        if (!$result) {
            echo json_encode([
                'success' => false,
                'message' => 'خطأ في جلب البيانات',
                'error' => $conn->error
            ]);
            exit;
        }
        
        $admins = [];
        while ($row = $result->fetch_assoc()) {
            $admins[] = $row;
        }
        
        // إرجاع النتيجة النهائية
        echo json_encode([
            'success' => true,
            'data' => $admins
        ]);
        break;
        
    // الحالة الثانية: إضافة مسؤول جديد
    case 'add_admin':
        // التحقق من وجود البيانات المطلوبة
        if (empty($input['name']) || empty($input['role'])) {
            echo json_encode([
                'success' => false,
                'message' => 'الاسم والدور مطلوبان'
            ]);
            exit;
        }
        
        // تنفيذ الاستعلام
        $stmt = $conn->prepare("INSERT INTO admins (name, role) VALUES (?, ?)");
        $stmt->bind_param("ss", $input['name'], $input['role']);
        
        if ($stmt->execute()) {
            echo json_encode([
                'success' => true,
                'message' => 'تمت الإضافة بنجاح',
                'insert_id' => $stmt->insert_id
            ]);
        } else {
            echo json_encode([
                'success' => false,
                'message' => 'خطأ في الإضافة',
                'error' => $stmt->error
            ]);
        }
        break;
        
    // يمكنك إضافة حالات أخرى هنا مثل التعديل والحذف
    
    // إذا تم إرسال إجراء غير معروف
    default:
        echo json_encode([
            'success' => false,
            'message' => 'إجراء غير معروف'
        ]);
}

// إغلاق الاتصال بقاعدة البيانات
$conn->close();
?>