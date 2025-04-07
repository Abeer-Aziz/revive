<?php
// الاتصال بقاعدة البيانات
$pdo = new PDO('mysql:host=localhost;dbname=revive_db', 'root', '');
$pdo->setAttribute(PDO::ATTR_ERRMODE, PDO::ERRMODE_EXCEPTION);

// التحقق من أن البيانات تم إرسالها
if ($_SERVER['REQUEST_METHOD'] === 'POST') {
    // استلام البيانات من FormData
    $id = $_POST['id'];
    $name = $_POST['name'];
    $role = $_POST['role'];

    // التحقق من صحة البيانات (اختياري)
    if (!empty($id) && !empty($name) && !empty($role)) {
        // استعلام التحديث
        $sql = "UPDATE admins SET name = :name, role = :role WHERE id = :id";
        $stmt = $pdo->prepare($sql);
        
        // ربط المتغيرات بالاستعلام
        $stmt->bindParam(':name', $name);
        $stmt->bindParam(':role', $role);
        $stmt->bindParam(':id', $id, PDO::PARAM_INT);
        
        // تنفيذ الاستعلام
        if ($stmt->execute()) {
            echo json_encode(['success' => true]); // إرجاع استجابة ناجحة
        } else {
            echo json_encode(['success' => false, 'error' => 'حدث خطأ أثناء التحديث.']);
        }
    } else {
        echo json_encode(['success' => false, 'error' => 'البيانات غير صحيحة.']);
    }
}
?>
