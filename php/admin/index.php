<?php
  session_start();
  require_once('db_connect.php');

    // ログインチェック
  if (empty($_SESSION['login_id'])) {
      header('Location: login.php');
  }

  if (isset($_POST['logout'])) {
      session_destroy();
      header('Location: login.php');
  }
?>

<!DOCTYPE html>
<html lang="ja">
<head>
<meta charset="UTF-8">
<title>トップ画面</title>
<link rel="stylesheet" href="index.css">
</head>
<body>
<header> 
  <form action="" method="post">
    <span>掲示板管理画面メインメニュー</span>
    <div class="greeting">
      <P>ようこそ<?php echo $_SESSION['name']; ?>様</P>
    </div>
    <div class="header_button">
      <input type="submit" name="logout" value="ログアウト">
    </div>
  </form>
</header>

</body>
</html>