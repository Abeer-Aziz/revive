<?php
require 'db.php'; // الاتصال بقاعدة البيانات

if ($_SERVER["REQUEST_METHOD"] == "POST") {
    $id = $_POST['id'];
    $name = $_POST['name'];
    $discount = $_POST['discount'];
    $expiry_date = $_POST['expiry_date'];

    $sql = "UPDATE offers SET name='$name', discount='$discount', expiry_date='$expiry_date' WHERE id=$id";

    if ($conn->query($sql) === TRUE) {
        echo "تم تعديل العرض بنجاح";
    } else {
        echo "Error: " . $sql . "<br>" . $conn->error;
    }

    $conn->close();
}
?>