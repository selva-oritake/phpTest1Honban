<?php
  session_start();
  require_once('db_connect.php');
?>

<!DOCTYPE html>
<html lang="ja">
<head>
<meta charset="UTF-8">
<title>会員登録完了画面</title>
<link rel="stylesheet" href="member_regist_complete.css">

</head>
<body>
  <div class="member_regist_complete">
    <h1>会員登録完了</h1>
    <p>会員登録が完了しました。</p>
  </div>

  <div class="btn">
    <input type="button" value="トップに戻る" onclick="location.href='index.php'">
  </div>


</body>
</html>