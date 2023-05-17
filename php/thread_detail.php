<?php
  session_start();
  require_once('db_connect.php');
  
  $stmt = $dbh->prepare('SELECT * FROM threads WHERE id=?');
  $stmt->execute(array($_GET['id']));
  $result = $stmt->fetch();

  $stmt2 = $dbh->prepare('SELECT * FROM members WHERE id=?');
  $stmt2->execute(array($result['member_id']));
  $result2 = $stmt2->fetch();  

  $stmt3 = $dbh->prepare("SELECT * FROM comments JOIN members ON comments.member_id = members.id WHERE thread_id=?");
  $stmt3->execute(array($result['id']));
  $result3 = $stmt3->fetchAll();

  $stmt = $dbh->prepare("SELECT COUNT(*) FROM comments WHERE thread_id=?");
  $stmt->execute(array($result['id']));
  $count = $stmt->fetchColumn();  

  if (!empty($_POST['comment_btn'])) {
      if($_POST['comment'] === "" || mb_strlen($_POST['coment']) > 500) {
        $error['comment'] = "blank";
      }

      if(!isset($error)) {
        $comment = $_POST['comment'];
        $member_id = $_SESSION['member_id'];
        $thread_id = $result['id'];
        $created_at = date("Y-m-d H:i:s");

        $sql = "INSERT into comments (comment, member_id, thread_id, created_at ) values (:comment, :member_id, :thread_id, :created_at)";
        $stmt = $dbh->prepare($sql);
        $params = array(':comment' => $comment, ':member_id' => $member_id, ':thread_id' => $thread_id, ':created_at' => $created_at);
        $stmt->execute($params); 

        $_SESSION['comment'] = $_POST['comment'];
        header('Location: ./');

      }
  }
  
  //ページング
  define('MAX','5');
  $max_page = ceil($count / MAX);

  if(isset($_GET['page'])){
    $now = $_GET['page'];
  }else{
    $now = 1;
  }

  $start_no = ($now - 1) * MAX;
  $comment_list = array_slice($result3, $start_no, MAX, true);
    
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
    <p><?php echo $count. "コメント   ".$result['created_at']; ?></p>
  </div>
  
  <div class="prev_next">
    <a href="thread_detail.php?id=<?php echo $_GET['id']; ?>&page=<?php echo ($now - 1); ?>" <?php if($now == 1) {echo 'class="glayout"';} ?>>前へ＞</a>
    <a href="thread_detail.php?id=<?php echo $_GET['id']; ?>&page=<?php echo ($now + 1); ?>" <?php if($now == $max_page) {echo 'class="glayout"';} ?>>次へ＞</a>
  </div>

  <div class="detail">
    <p><?php echo "投稿者:".$result2['name_sei']." ".$result2['name_mei']." ".$result['created_at']; ?></p>
    <p><?php echo nl2br($result['content']); ?></p>
  </div>

  <div class="comment_list">
    <table>
      <?php foreach ($comment_list as $row): ?>
        <tr>
          <td><?php echo $row['id']."."; ?></td>
          <td><?php echo $row['name_sei']; ?></td>
          <td><?php echo $row['name_mei']; ?></td>
          <td><?php echo $row['created_at']; ?></td>
        </tr>
        <tr>
          <td><?php echo nl2br($row['comment']); ?></td>
        </tr>
      <?php endforeach; ?>
    </table>
  </div>

  <div class="prev_next">
    <a href="thread_detail.php?id=<?php echo $_GET['id']; ?>&page=<?php echo ($now - 1); ?>" <?php if($now == 1) {echo 'class="glayout"';} ?>>前へ＞</a>
    <a href="thread_detail.php?id=<?php echo $_GET['id']; ?>&page=<?php echo ($now + 1); ?>" <?php if($now == $max_page) {echo 'class="glayout"';} ?>>次へ＞</a>
  </div>
  
  <?php if (!empty($_SESSION['member_id'])): ?>
  <form class="comment_form" action="" method="post">
    <textarea name="comment" cols="50" rows="10"></textarea>
    <?php if ($error['comment'] === 'blank'): ?>
      <p class="error">＊コメントを500文字以内で入力してください</p>
    <?php endif ?>
    <input  class="comment_btn" name="comment_btn" type="submit" value="コメントする">
  </form>
  <?php endif ?>

</body>
</html>