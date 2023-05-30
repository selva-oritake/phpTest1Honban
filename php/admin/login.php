<?php
  session_start();
  require_once('db_connect.php');

  if (!empty($_POST)) {
    /* 入力情報の不備を検知 */
    if ($_POST['login_id'] === "" || mb_strlen($_POST['login_id']) > 10 || mb_strlen($_POST['login_id']) < 7 || !preg_match("/^[a-zA-Z0-9]{7,10}$/",$_POST['login_id'])) {
        $error['login_id'] = "blank";
    }
    if ($_POST['password'] === "" || mb_strlen($_POST['password']) > 20 || mb_strlen($_POST['password']) < 8 || !preg_match("/^[a-zA-Z0-9]{8,20}$/",$_POST['password'])){
        $error['password'] = "blank";
    }
    
    /* メールアドレスとパスワードを照合 */
    if (!isset($error)) {
        $stmt = $dbh->prepare('SELECT * FROM administers WHERE login_id=?');
        $stmt->execute(array($_POST['login_id']));
        $result = $stmt->fetch(); //ヒットしたデータが1つの場合はfetch
        
        if ($_POST['password'] !== $result['password']) {
          $error['password'] = "disagreement";
        }
    }
    
    /* フォームの内容をセッションで保存 */
    if (!isset($error)) {
        $_SESSION['login_id'] = $result['login_id'];
        $_SESSION['name'] = $result['name'];
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
    <h1>管理画面</h1>

  <form action="" method="POST">
    <div class="login_id">
      <p>ログインID<input name="login_id" value="<?php if(!empty($_POST['login_id'])){echo $_POST['login_id'];} ?>"></p> 
      <?php if ($error['login_id'] === 'blank'): ?>
        <p class="error">＊ログインIDを7~10文字の半角英数字で入力してください</p>
      <?php endif ?>
    </div>

    <div class="password">
      <p>パスワード<input name="password" type="password"></p>
      <?php if ($error['password'] === 'blank'): ?>
        <p class="error">＊パスワードを8~20文字の半角英数字で入力してください</p>
      <?php endif ?>
      <?php if($error['password'] === 'disagreement'): ?>
        <p class="error">＊IDもしくはパスワードが間違っています</p>
      <?php endif; ?>
    </div>

    <div class="btn">
      <input type="submit" value="ログイン">
    </div>
  </form>
</body>
</html>