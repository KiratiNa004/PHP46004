<?php
    session_start();
?>


<!DOCTYPE html>
<html lang="en">
<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title>หน้าหลัก</title>

     <!-- Boostrap CDN -->
    <link rel="stylesheet" href="https://cdn.jsdelivr.net/npm/bootstrap@5.3.7/dist/css/bootstrap.min.css">
    
</head>
<body>
    <h1>ยินดีต้อนรับสู่หน้าหลัก</h1>
    <p>ผุ้ใช้: <?= htmlspecialchars($_SESSION['username']) ?> (<?= $_SESSION['role'] ?>) </p>

    <a href="logout.php" class=""> ออกจากระบบ</a>

</body>
</html>