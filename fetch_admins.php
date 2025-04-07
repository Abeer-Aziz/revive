<?php
require 'db.php';

try {
    $stmt = $conn->prepare("SELECT * FROM admins");
    $stmt->execute();
    $result = $stmt->get_result();
    $admins = $result->fetch_all(MYSQLI_ASSOC);

    echo json_encode($admins);
} catch (Exception $e) {
    echo json_encode(["error" => "حدث خطأ أثناء جلب البيانات: " . $e->getMessage()]);
}

$stmt->close();
?>