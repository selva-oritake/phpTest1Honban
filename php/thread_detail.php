<?php
  session_start();
  require_once('db_connect.php');
  
  $stmt = $dbh->prepare('SELECT * FROM threads WHERE id=?');
  $stmt->execute(array($_GET['id']));
  $result = $stmt->fetch();

  $stmt = $dbh->prepare('SELECT * FROM members WHERE id=?');
  $stmt->execute(array($result['member_id']));
  $result2 = $stmt->fetch();
?>

<!DOCTYPE html>
<html lang="ja">
<head>
<meta charset="UTF-8">
<title>スレッド詳細画面</title>
<link rel="stylesheet" href="thread_detail.css">
</head>
<body>
  <header>
    <div class="header_button">
        <input type="button" value="スレッド一覧に戻る" onclick="location.href='thread.php'">
    </div>
  </header>

  <div class="title">
    <p><?php echo $result['title']; ?></p>
    <p><?php echo $result['created_at']; ?></p>
  </div>

  <div class="detail">
    <p><?php echo "投稿者:".$result2['name_sei']." ".$result2['name_mei']." ".$result['created_at']; ?></p>
    <p><?php echo $result['content']; ?></p>
  </div>
  
  <?php if (!empty($_SESSION['member_id'])): ?>
  <form class="comment_form" action="" method="post">
    <textarea name="comment" cols="50" rows="10"></textarea>
    <input  class="comment_btn" name="comment_btn" type="submit" value="コメントする">
  </form>
  <?php endif ?>

</body>
</html>