<?php
  session_start();
  require_once('db_connect.php');

  // 入力情報をデータベースに登録
  $id= $_SESSION['id'];
  $name_sei = $_SESSION['join']['name_sei'];
  $name_mei= $_SESSION['join']['name_mei'];
  $gender = $_SESSION['join']['gender'];
  $pref = $_SESSION['join']['pref'];
  $etc_address = $_SESSION['join']['etc_address'];
  $password = $_SESSION['join']['password'];
  $email = $_SESSION['join']['email'];

  if (!empty($_POST['complete_btn'])) {
      $updated_at = date("Y-m-d H:i:s");
      
      if(empty($password)) {
          $sql = "UPDATE members SET name_sei = :name_sei, name_mei = :name_mei, gender = :gender, pref_name =:pref_name, address = :address, email = :email, updated_at =:updated_at WHERE id = $id";
          $stmt = $dbh->prepare($sql);
          $params = array(':name_sei' => $name_sei, ':name_mei' => $name_mei, ':gender' => $gender, ':pref_name' => $pref, ':address' => $etc_address, ':email' => $email, ':updated_at' => $updated_at);
          $stmt->execute($params);
      } else {
          $sql = "UPDATE members SET name_sei = :name_sei, name_mei = :name_mei, gender = :gender, pref_name =:pref_name, address = :address, password =:password, email = :email, updated_at =:updated_at WHERE id = $id";
          $stmt = $dbh->prepare($sql);
          $params = array(':name_sei' => $name_sei, ':name_mei' => $name_mei, ':gender' => $gender, ':pref_name' => $pref, ':address' => $etc_address, ':password' => $password, ':email' => $email, ':updated_at' => $updated_at);
          $stmt->execute($params);
      }

  
      unset($_SESSION['join']);   // セッションを破棄
      header('Location: member.php');
      exit();

  }
?>

<!DOCTYPE html>
<html lang="ja">
<head>
<meta charset="UTF-8">
<title>会員情報編集確認画面</title>
<link rel="stylesheet" href="member_regist_check.css">

</head>
<body>
<header> 
    <span>会員編集</span>
    <div class="header_button">
      <input name='back' type="button" value="一覧へ戻る" onclick="location.href='member.php'">
    </div>
  </header>
  
  <div class="member_regist_check">
    <form action="" method="POST">
      <div class="id">
        <p>ID</p>
        <p><?php echo $id; ?></p>
      </div>
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
      <div class="btn"><input name="prev_btn" type="button" value="前に戻る" onclick="location.href='member_edit.php?id=<?php echo $id; ?>'"></div>

    </form>

  </div>




</body>
</html>