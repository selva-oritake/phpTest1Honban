<?php
  session_start();
  require_once('db_connect.php');
  
  $id= $_GET['id'];

  //idが一致するデータを取得
  $stmt = $dbh->prepare('SELECT * FROM members WHERE id=?');
  $stmt->execute(array($id));
  $result = $stmt->fetch();

  //削除機能
  if (!empty($_POST['delete_btn'])) {
      $deleted_at = date("Y-m-d H:i:s");

      $stmt = $dbh->prepare("UPDATE members SET deleted_at =? WHERE id=? ");
      $stmt->execute(array($deleted_at, $id));

      header('Location: member.php');
      exit();
  }
?>

<!DOCTYPE html>
<html lang="ja">
<head>
<meta charset="UTF-8">
<title>会員情報詳細画面</title>
<link rel="stylesheet" href="member_detail.css">

</head>
<body>
<header> 
    <span>会員詳細</span>
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
        <p><?php echo $result['name_sei']; ?></p>
        <p><?php echo $result['name_mei']; ?></p>
      </div>

      <div class="gender">
        <p>性別</p>
        <p><?php if($result['gender'] == 1){ echo "男性";} elseif ($result['gender'] == 2) {echo "女性";} ?></p>
      </div>

      <div class="address">
        <p>住所</p>
        <p><?php echo $result['pref_name'].$result['address']; ?></p>

      </div>

      <div class="password">
        <p>パスワード</p>
        <p>セキュリティのため非表示</p>
      </div>

      <div class="email">
        <p>メールアドレス</p>
        <p><?php echo $result['email']; ?></p>
      </div>

      <div class="btn"><input name="edit_btn" type="button" value="編集" onclick="location.href='member_edit.php?id=<?php echo $id; ?>'"></div>
      <div class="btn"><input name="delete_btn" type="submit" value="削除"></div>

    </form>

  </div>




</body>
</html>