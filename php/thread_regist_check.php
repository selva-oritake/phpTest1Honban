<?php
  session_start();
  require_once('db_connect.php');
  
  // ログインチェック
  if (empty($_SESSION['member_id'])) {
    header('Location: index.php');
  }

  if (!empty($_POST['thread_regist_btn'])) {
    
    // 入力情報をデータベースに登録
    $title = $_SESSION['title'];
    $content = $_SESSION['content'];
    $member_id = $_SESSION['member_id'];

    $sql = "INSERT into threads (title, content, member_id) values (:title, :content, :member_id)";
    $stmt = $dbh->prepare($sql);
    $params = array(':title' => $title, ':content' => $content, ':member_id' => $member_id);
    $stmt->execute($params);

    unset($title, $content); // セッションを破棄
    header('Location: index.php');
  }
  
?>

<!DOCTYPE html>
<html lang="ja">
<head>
<meta charset="UTF-8">
<title>スレッド作成確認画面</title>
<link rel="stylesheet" href="thread_regist_check.css">
</head>
<body>
<div class="member_regist_check">
    <h1>スレッド作成確認画面</h1>
    <form action="" method="POST">
      <div class="thread_title">
        <p>スレッドタイトル</p>
        <p><?php echo $_SESSION['title']; ?></p>
      </div>

      <div class="content">
        <p>コメント</p>
        <p><?php echo nl2br($_SESSION['content']); ?></p>
      </div>

      <div class="btn"><input  name="thread_regist_btn" type="submit" value="スレッドを作成する"></div>
      <div class="btn"><input name="prev_btn" type="button" value="前に戻る" onclick="history.back()"></div>

    </form>

  </div>
</body>
</html>