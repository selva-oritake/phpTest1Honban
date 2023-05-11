<?php
  require_once('db_connect.php');
?>

<!DOCTYPE html>
<html lang="ja">
<head>
<meta charset="UTF-8">
<title>ホーム画面</title>
<link rel="stylesheet" href="index.css">
</head>
<body>
<header>
    <div class="header_button">
      <input type="button" value="新規会員登録" onclick="location.href='member_regist.php'">
      <input type="button" value="ログイン" onclick="location.href='login.php'">
    </div>
</header>
</body>
</html>