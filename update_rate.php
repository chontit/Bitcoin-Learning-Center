<?php
session_start();
// 1. ตั้งค่า Timezone ให้ตรงกับประเทศไทย (ICT)
date_default_timezone_set('Asia/Bangkok');

// --- CONFIGURATION ---
$json_file = 'rate.json';
$username_target = 'chontit';
$password_hash = '$2y$10$.twuOmO0YgZ/nPbajHR54uKHoHwJDpkWQor4HsciaJ1aYg6uQ7pPS';

// --- LOGOUT LOGIC ---
if (isset($_GET['logout'])) {
    session_destroy();
    header("Location: update_rate.php");
    exit;
}

// --- LOGIN LOGIC ---
if (isset($_POST['login'])) {
    if ($_POST['user'] === $username_target && password_verify($_POST['pass'], $password_hash)) {
        $_SESSION['authenticated'] = true;
    } else {
        $error = "Access Denied: Invalid Credentials";
    }
}

// --- UPDATE LOGIC ---
if (isset($_POST['update']) && isset($_SESSION['authenticated'])) {
    // รองรับทศนิยมสูงสุด 3 ตำแหน่ง (0-3)
    $input_rate = (float)$_POST['rate'];
    $new_rate = round($input_rate, 3); 
    
    $data = [
        "pair" => "USD/THB",
        "description" => "1 USD to THB",
        "rate" => $new_rate,
        "updated_at" => date("d/m/Y H:i:s"),
        "unix_timestamp" => time()
    ];
    
    // บันทึกลงไฟล์ JSON ใน Folder เดียวกัน
    if (file_put_contents($json_file, json_encode($data, JSON_PRETTY_PRINT))) {
        $success = "API Data Updated: 1 USD = " . number_format($new_rate, 3) . " THB";
    } else {
        $error = "Error: Cannot write to $json_file. Check permissions.";
    }
}

// --- LOAD DATA ---
$current_data = file_exists($json_file) ? json_decode(file_get_contents($json_file), true) : ["rate" => "0.000", "updated_at" => "-"];
?>

<!DOCTYPE html>
<html lang="th">
<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title>Rate Admin | 103SQDN Cyberpunk</title>
    <style>
        body { background: #0a0a0a; color: #ff9d00; font-family: 'Segoe UI', sans-serif; display: flex; justify-content: center; align-items: center; min-height: 100vh; margin: 0; }
        .card { background: #111; border: 2px solid #ff9d00; padding: 30px; box-shadow: 0 0 20px rgba(255, 157, 0, 0.15); width: 100%; max-width: 350px; border-radius: 2px; }
        h2 { text-transform: uppercase; letter-spacing: 2px; border-bottom: 2px solid #ff9d00; padding-bottom: 10px; margin-top: 0; font-size: 1.4em; }
        .info-box { background: #1a1a1a; border-left: 4px solid #ff9d00; padding: 15px; margin-bottom: 20px; font-size: 0.9em; line-height: 1.6; }
        label { display: block; margin-bottom: 8px; font-size: 0.8em; text-transform: uppercase; color: #888; }
        input { background: #000; border: 1px solid #333; color: #fff; padding: 12px; width: 100%; box-sizing: border-box; margin-bottom: 15px; font-size: 1.1em; border-radius: 4px; }
        input:focus { border-color: #ff9d00; outline: none; box-shadow: 0 0 5px #ff9d00; }
        button { background: #ff9d00; color: #000; border: none; padding: 14px; width: 100%; font-weight: bold; cursor: pointer; text-transform: uppercase; font-size: 1em; transition: 0.2s; }
        button:hover { background: #fff; box-shadow: 0 0 15px #fff; }
        .msg-ok { color: #00ffcc; text-align: center; margin-top: 15px; font-weight: bold; border: 1px solid #00ffcc; padding: 10px; }
        .msg-err { color: #ff4444; text-align: center; margin-top: 15px; font-weight: bold; }
        .logout-btn { display: block; text-align: center; color: #444; text-decoration: none; margin-top: 20px; font-size: 0.75em; letter-spacing: 1px; }
        .logout-btn:hover { color: #ff4444; }
    </style>
</head>
<body>

<div class="card">
    <?php if (!isset($_SESSION['authenticated'])): ?>
        <h2>System Access</h2>
        <form method="POST">
            <label>Username</label>
            <input type="text" name="user" placeholder="Admin" required autocomplete="off">
            <label>Password</label>
            <input type="password" name="pass" placeholder="••••••••" required>
            <button type="submit" name="login">Identify</button>
        </form>
        <?php if (isset($error)) echo "<p class='msg-err'>$error</p>"; ?>

    <?php else: ?>
        <h2>Rate Control</h2>
        <div class="info-box">
            Current: <strong><?= number_format($current_data['rate'], 3) ?> THB</strong><br>
            Updated: <?= $current_data['updated_at'] ?><br>
            Server: <?= date("H:i:s") ?> (ICT)
        </div>

        <form method="POST">
            <label>1 USD to THB Rate (0-3 Decimals)</label>
            <input type="number" name="rate" step="0.001" value="<?= $current_data['rate'] ?>" autofocus required>
            <button type="submit" name="update">Execute Update</button>
        </form>

        <?php 
            if (isset($success)) echo "<div class='msg-ok'>$success</div>"; 
            if (isset($error) && !isset($success)) echo "<div class='msg-err'>$error</div>";
        ?>
        
        <a href="?logout=1" class="logout-btn">[ TERMINATE ACCESS ]</a>
    <?php endif; ?>
</div>

</body>
</html>