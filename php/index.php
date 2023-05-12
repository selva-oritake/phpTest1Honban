<?php
  session_start();
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
    <div class="greeting">
      <?php if (isset($_SESSION['name_sei'])) :?>
          <P>ようこそ<?php echo $_SESSION['name_sei']. $_SESSION['name_mei']; ?>様</P>
        <?php endif ?>
    </div>
    <div class="header_button">
      <?php if (!isset($_SESSION['name_sei'])) :?>
        <input type="button" value="新規会員登録" onclick="location.href='member_regist.php'">
        <input type="button" value="ログイン" onclick="location.href='login.php'">
      <?php endif ?>
      <?php if (isset($_SESSION['name_sei'])) :?>
        <input type="button" value="新規スレッド作成" onclick="location.href='thread_regist.php'">
        <input type="button" value="ログアウト" onclick="location.href='index.php'; <?php session_destroy(); ?>">
      <?php endif ?>
    </div>
</header>
</body>
</html>