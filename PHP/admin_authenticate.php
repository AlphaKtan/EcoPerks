<?php
session_start();

require_once('../Model/dbModel.php');
// データベースに接続
$pdo = dbConnect();

$_SESSION['facility'] = null;
$_SESSION['location_id'] = null;
$_SESSION['area_id'] = null;
$_SESSION['romaji'] = null;
$_SESSION['kana'] = null;

// フォームから送信されたユーザー名とパスワードを取得します
$admin_id = $_POST['admin_id'];
$password = $_POST['password'];

// パスワードの検証
$sql = "SELECT id, area_id, facility_name, address, romaji, kana, login_id FROM travel_data WHERE login_id = :admin_id AND pass = :pass";
$stmt = $pdo->prepare($sql);
$stmt->bindParam(':admin_id', $admin_id, PDO::PARAM_INT);
$stmt->bindParam(':pass', $password, PDO::PARAM_STR);
$stmt->execute();
$result = $stmt->fetchAll(PDO::FETCH_ASSOC);

if(!empty($result)) {
    foreach ($result as $row) {
        $_SESSION['facility'] = $row['facility_name'];
        $_SESSION['location_id'] = $row['id'];
        $_SESSION['area_id'] = $row['area_id'];
        $_SESSION['romaji'] = $row['romaji'];
        $_SESSION['kana'] = $row['kana'];
    }
    echo $_SESSION['facility']."でログインしました";
} else {
    $error_message = "ユーザー名もしくはパスワードが間違えています。";
    header("Location: admin_login.php?error=".urlencode($error_message));
    exit();
}
?>