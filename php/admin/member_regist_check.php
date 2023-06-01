<?php
  session_start();
  require_once('db_connect.php');

  // 入力情報をデータベースに登録
  $name_sei = $_SESSION['join']['name_sei'];
  $name_mei= $_SESSION['join']['name_mei'];
  $gender = $_SESSION['join']['gender'];
  $pref = $_SESSION['join']['pref'];
  $etc_address = $_SESSION['join']['etc_address'];
  $password = $_SESSION['join']['password'];
  $email = $_SESSION['join']['email'];

  if (!empty($_POST['complete_btn'])) {
    $created_at = date("Y-m-d H:i:s");

    $sql = "INSERT into members (name_sei, name_mei, gender, pref_name, address, password, email, created_at) values (:name_sei, :name_mei, :gender, :pref_name, :address, :password, :email, :created_at)";
    $stmt = $dbh->prepare($sql);
    $params = array(':name_sei' => $name_sei, ':name_mei' => $name_mei, ':gender' => $gender, ':pref_name' => $pref, ':address' => $etc_address, ':password' => $password, ':email' => $email, ':created_at' => $created_at);
    $stmt->execute($params);

    unset($_SESSION['join']);   // セッションを破棄
    header('Location: member.php');
    exit();

  }
?>

<!DOCTYPE html>
<html lang="ja">
<head>
<meta charset="UTF-8">
<title>会員情報登録確認画面</title>
<link rel="stylesheet" href="member_regist_check.css">

</head>
<body>
<header> 
    <span>会員登録</span>
    <div class="header_button">
      <input name='back' type="button" value="一覧へ戻る" onclick="location.href='member.php'">
    </div>
  </header>
  
  <div class="member_regist_check">
    <form action="" method="POST">
      <div class="name">
        <p>氏名</p>
        <p><?php echo $_SESSION['join']['name_sei']; ?></p>
        <p><?php echo $_SESSION['join']['name_mei']; ?></p>
      </div>

      <div class="gender">
        <p>性別</p>
        <p><?php if($gender == 1){ echo "男性";} elseif ($gender == 2) {echo "女性";} ?></p>
      </div>

      <div class="address">
        <p>住所</p>
        <p><?php echo $_SESSION['join']['pref'].$_SESSION['join']['etc_address']; ?></p>

      </div>

      <div class="password">
        <p>パスワード</p>
        <p>セキュリティのため非表示</p>
      </div>

      <div class="email">
        <p>メールアドレス</p>
        <p><?php echo $_SESSION['join']['email']; ?></p>
      </div>

      <div class="btn"><input  name="complete_btn" type="submit" value="登録完了"></div>
      <div class="btn"><input name="prev_btn" type="button" value="前に戻る" onclick="location.href='member_regist.php'"></div>

    </form>

  </div>




</body>
</html>