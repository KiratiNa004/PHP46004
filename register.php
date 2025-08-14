<?php
require_once 'config.php';

$error = [];// Array to hold error massage

if ($_SERVER['REQUEST_METHOD'] === 'POST') {
    //รับค่าจากฟอร์ม
    $username = trim($_POST['username']);
    $fullname = trim($_POST['fullname']);
    $email = trim($_POST['email']);
    $password = $_POST['password'];
    $confirmpassword = $_POST['confirmpassword'];

    // ตรวจสอบว่ากรอกข ้อมูลมำครบหรือไม่ (empty)

    if (
        empty($username) || empty($fullname) || empty($email) || empty($password) || empty($confirmpassword)
    ) {

        $error[] = "กรุณากรอบข้อมูลให้ครบทุกช่อง";

    } elseif (!filter_var($email, FILTER_VALIDATE_EMAIL)) {
        $error[] = "กรุณากรอกอีเมลให้ถูกต้อง";

    } elseif ($password !== $confirm_password) {
        $errors[] = "รหัสผ่านและยืนยันรหัสผ่านไม่ตรงกัน";

    } else {
        // ตรวจสอบว่ามีชื่อผู้ใช้หรืออีเมลถูกใช้ไปแล้วหรือไม่
        $sql = "SELECT * FROM users WHERE username = ? OR email = ?";
        $stmt = $conn->prepare($sql);
        $stmt->execute([$username, $email]);

        if($stmt->rowCount() > 0){
            $error[] = "ชื่อผู้ใช้หรืออีเมลนี้ถูกใช้ไปแล้ว";
        }

    }
    if (empty($error)){ //ถ้าไม่มี error 
        // นำข้อมูลไปบันทึกในฐานข้อมูล
    
        $hashedPassword = password_hash($password, PASSWORD_DEFAULT);
    
        $sql = "INSERT INTO users(username, full_name, email, password,role) VALUES (?, ?, ?, ?, 'member')";
        $stmt = $conn->prepare($sql);
        $stmt->execute([$username, $fullname, $email, $hashedPassword]);


        //ถ้าบันทึกสำเร็จ ให้เปลี่ยนเส้นทางไปหน้า login 
        header("Location: login.php?register=success");
        exit(); // หยุดการทำงานของสครปต์หลังจากเปลี่ยนเส้นทาง

    }

}

?>

<!DOCTYPE html>
<html lang="en">

<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title>Register</title>

    <!-- Boostrap CDN -->
    <link rel="stylesheet" href="https://cdn.jsdelivr.net/npm/bootstrap@5.3.7/dist/css/bootstrap.min.css">

    <style>
        body {
            background: linear-gradient(135deg, #6fb1fc, #4364f7, #2a2a72);
            min-height: 100vh;
            display: flex;
            align-items: center;
            justify-content: center;
        }

        .register-container {
            background: #fff;
            padding: 30px;
            border-radius: 15px;
            box-shadow: 0px 5px 20px rgba(0, 0, 0, 0.15);
            max-width: 450px;
            width: 100%;
        }

        h2 {
            text-align: center;
            margin-bottom: 25px;
            font-weight: bold;
            color: #2a2a72;
        }

        .form-control {
            border-radius: 10px;
        }

        .btn-primary {
            border-radius: 10px;
            background: #4364f7;
            border: none;
        }

        .btn-primary:hover {
            background: #2a2a72;
        }
    </style>
</head>

</head>

<body>
    <div class="register-container">
        <h2>สมัครสมาชิก</h2>

        <?php if (!empty($error)): // ถ ้ำมีข ้อผิดพลำด ให้แสดงข ้อควำม ?>
            <div class="alert alert-danger">
            <ul>
                <?php foreach ($error as $e): ?>
                    <li><?= htmlspecialchars($e) ?></li>

                    <!--ใช ้ htmlspecialchars เพื่อป้องกัน XSS -->
                    <!-- < ? = คือ short echo tag ?> -->
                    <!-- ถ ้ำเขียนเต็ม จะได ้แบบด ้ำนล่ำง -->
                    <?php // echo "<li>" . htmlspecialchars($e) . "</li>"; ?>

        <?php endforeach; ?>
            </ul>
            </div>
        <?php endif; ?>


        <form method="post">
        <div>
            <label for="username" class="form-label">ชื่อผู้ใช้</label>
            <input type="text" name="username" id="username" class="form-control" placeholder="กรุณากรอกชื่อผู้ใช้" 
            value="<?= isset($_POST['username']) ? htmlspecialchars($_POST['username']) : '' ?>" required>
        </div>
        <div>
            <label for="fullname" class="form-label">ชื่อ-สกุล</label>
            <input type="text" name="fullname" id="fullname" class="form-control" placeholder="กรุณากรอกชื่อ-สกุล" 
            value="<?= isset($_POST['fullname']) ? htmlspecialchars($_POST['fullname']) : '' ?>" required>
        </div>
        <div>
            <label for="email" class="form-label">อีเมล</label>
            <input type="email" name="email" id="email" class="form-control" placeholder="กรุณากรอกอีเมล" 
            value="<?= isset($_POST['email']) ? htmlspecialchars($_POST['email']) : '' ?>" required>
        </div>
        <div>
            <label for="password" class="form-label">รหัสผ่าน</label>
            <input type="password" name="password" id="password" class="form-control" placeholder="กรุณากรอกรหัสผ่าน" required>
        </div>
        <div>
            <label for="confirmpassword" class="form-label">ยืนยันรหัสผ่าน</label>
            <input type="password" name="confirmpassword" id="confirmpassword" class="form-control" placeholder="กรุณายืนยันรหัสผ่าน" required>
        </div>
        <div class="mt-3">
            <button type="submit" class="btn btn-primary">สมัครสมาชิก</button>
            <a href = "login.php" class="btn btn-link">เข้าสู่ระบบ</a>
        </div>
    </form>
    </div>

    <!-- Boostrap JS -->
    <script src="https://cdn.jsdelivr.net/npm/bootstrap@5.3.7/dist/js/bootstrap.bundle.min.js"></script>

</body>

</html>