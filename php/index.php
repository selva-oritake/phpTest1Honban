<?php
  session_start();
  require_once('db_connect.php');

  if (isset($_POST['logout'])) {
      session_destroy();
      header('Location: index.php');
  }
  
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
  <form action="" method="post">
    <div class="greeting">
      <?php if (isset($_SESSION['member_id'])) :?>
          <P>ようこそ<?php echo $_SESSION['name_sei']. $_SESSION['name_mei']; ?>様</P>
        <?php endif ?>
    </div>
    <div class="header_button">
      <input type="button" value="スレッド一覧" onclick="location.href='thread.php'">
      <?php if (!isset($_SESSION['member_id'])) :?>
        <input type="button" value="新規会員登録" onclick="location.href='member_regist.php'">
        <input type="button" value="ログイン" onclick="location.href='login.php'">
      <?php endif ?>
      <?php if (isset($_SESSION['member_id'])) :?>
        <input type="button" value="新規スレッド作成" onclick="location.href='thread_regist.php'">
        <input type="submit" name="logout" value="ログアウト">
      <?php endif ?>
    </div>
  </form>
</header>

<div class="container">
  <div class="space"></div>
  <footer>
    <div class="footer">
      <div class="withdrawal_button">
      <?php if (isset($_SESSION['member_id'])) :?>
        <input type="button" value="退会" onclick="location.href='member_withdrawal.php'">
      <?php endif ?>
      </div>
    </div>
  </footer>
</div>

</body>
</html>