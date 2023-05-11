<?php
  session_start();
  require_once('db_connect.php');

  if (!empty($_POST)) {
    /* 入力情報の不備を検知 */
    if ($_POST['email'] === "" || mb_strlen($_POST['email']) > 200 || !filter_var($_POST['email'], FILTER_VALIDATE_EMAIL)) {
        $error['email'] = "blank";
    }
    if ($_POST['password'] === "" || mb_strlen($_POST['password']) > 20 || mb_strlen($_POST['password']) < 8 || !preg_match("/^[a-zA-Z0-9]{8,20}$/",$_POST['password'])){
        $error['password'] = "blank";
    }
    
    /* メールアドレスとパスワードを照合 */
    if (!isset($error)) {
        $stmt = $dbh->prepare('SELECT * FROM members WHERE email=? AND password=?');
        $stmt->execute(array($_POST['email'], $_POST['password']));
        $result = $stmt->fetch();
        
        if (!isset($result['email'])) {
          $error['email'] = "not_exist";
        }    
        
        if (!isset($result['password'])) {
          $error['password'] = "not_exist";
        }
        

    }
    
    /* フォームの内容をセッションで保存 */
    if (!isset($error)) {
        $_SESSION['login'] = $_POST; 
        header('Location: index.php');
        exit();
    }
  }
?>

<!DOCTYPE html>
<html lang="ja">
<head>
<meta charset="UTF-8">
<title>ログイン画面</title>
<link rel="stylesheet" href="login.css">
</head>
<body>
  <div class="login">
    <h1>ログイン</h1>

  <form action="" method="POST">
    <div class="email">
      <p>メールアドレス(ID)<textarea name="email" cols="50"></textarea></p> 
      <?php if ($error['email'] === 'blank'): ?>
        <p class="error">＊メールアドレスを200文字以内で入力してください</p>
      <?php endif ?>
      <?php if($error['email'] === 'not_exist'): ?>
        <p>＊メールアドレスが存在しません</p>
      <?php endif; ?>
    </div>

    <div class="password">
      <p>パスワード<input name="password" type="password"></p>
      <?php if ($error['password'] === 'blank'): ?>
        <p class="error">＊パスワードを8~20文字の半角英数字で入力してください</p>
      <?php endif ?>
      <?php if($error['password'] === 'not_exist'): ?>
        <p>＊パスワードが一致しません</p>
      <?php endif; ?>
    </div>

    <div class="btn">
      <input type="submit" value="ログイン"><br>
      <input type="button" value="トップに戻る" onclick="location.href='index.php'">
    </div>
  </form>
</body>
</html>