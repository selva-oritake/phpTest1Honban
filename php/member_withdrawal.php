<?php
  session_start();
  require_once('db_connect.php');

  $member_id = $_SESSION['member_id'];
  $deleted_at = date("Y-m-d H:i:s");

  // ログインチェック
  if (empty($_SESSION['member_id'])) {
    header('Location: index.php');
}

  if (isset($_POST['withdrawal'])) {
    $stmt = $dbh->prepare("UPDATE members SET deleted_at =? WHERE id=? ");
    $stmt->execute(array($deleted_at, $member_id));

    session_destroy();
    header('Location: index.php');
}

?>

<!DOCTYPE html>
<html lang="ja">
<head>
<meta charset="UTF-8">
<title>退会画面</title>
<link rel="stylesheet" href="member_withdrawal.css">
</head>
<body>
<header>
  <div class="top_button">
    <input type="button" value="トップに戻る" onclick="location.href='index.php'">
  </div>
</header>

<div class="container">
  <h1>退会</h1>
  <p>退会しますか？</p>
  <form action="" method="post">
    <input type="submit" name="withdrawal" value="退会する">
  </form>
</div>
</body>
</html>