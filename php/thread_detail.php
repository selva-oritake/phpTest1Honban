<?php
  session_start();
  require_once('db_connect.php');

  $member_id = $_SESSION['member_id'];
  $comment_id = $_POST['comment_id'];

  //チェック用
  
  //スレッドのID一致するデータをthreadsから取得
  $stmt = $dbh->prepare('SELECT * FROM threads WHERE id=?');
  $stmt->execute(array($_GET['id']));
  $result = $stmt->fetch();

  //会員IDが一致するデータをmembersから取得
  $stmt = $dbh->prepare('SELECT * FROM members WHERE id=?');
  $stmt->execute(array($result['member_id']));
  $result2 = $stmt->fetch();  

  //commentsとmembersとlikesを結合し、スレッドIDが一致するデータを取得
  $stmt = $dbh->prepare("SELECT comments.*, name_sei, name_mei, count(comment_id) AS like_count, SUM(likes.member_id = ?) AS my_like_count FROM comments LEFT JOIN members ON comments.member_id = members.id LEFT JOIN likes ON comments.id = likes.comment_id WHERE thread_id=? GROUP BY comments.id");
  $stmt->execute(array($member_id, $_GET['id']));
  $result3 = $stmt->fetchAll();

  //コメント総数を取得
  $stmt = $dbh->prepare("SELECT COUNT(*) FROM comments WHERE thread_id=?");
  $stmt->execute(array($result['id']));
  $count = $stmt->fetchColumn();

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

  if (!empty($_POST['comment_btn'])) {
      if($_POST['comment'] === "" || mb_strlen($_POST['comment']) > 500) {
        $error['comment'] = "blank";
      }

      if(!isset($error)) {
        $comment = $_POST['comment'];
        $thread_id = $result['id'];
        $created_at = date("Y-m-d H:i:s");

        $sql = "INSERT into comments (comment, member_id, thread_id, created_at ) values (:comment, :member_id, :thread_id, :created_at)";
        $stmt = $dbh->prepare($sql);
        $params = array(':comment' => $comment, ':member_id' => $member_id, ':thread_id' => $thread_id, ':created_at' => $created_at);
        $stmt->execute($params); 

        $_SESSION['comment'] = $_POST['comment'];
        header('Location: thread_detail.php?id='.$_GET['id'].'');

      }
  }

  //いいねボタン
  if (!empty($_POST['heart_clear']) && !empty($_SESSION['member_id'])) {

      $sql = "INSERT into likes (member_id, comment_id) values (:member_id, :comment_id)";
      $stmt = $dbh->prepare($sql);
      $params = array(':member_id' => $member_id, ':comment_id' => $comment_id);
      $stmt->execute($params);

      $_SESSION['comment_id'] = $_POST['comment_id'];
      header('Location: thread_detail.php?id='.$_GET['id'].'&page='.$now.'');
  } elseif (!empty($_POST['heart_red']) && !empty($_SESSION['member_id'])) {
      
      $stmt = $dbh->prepare("DELETE FROM likes WHERE comment_id=? AND member_id=? ");
      $stmt->execute(array($comment_id, $member_id));
      
      $_SESSION['comment_id'] = $_POST['comment_id'];
      header('Location: thread_detail.php?id='.$_GET['id'].'&page='.$now.'');

  } elseif (!empty($_POST['heart_clear']) && empty($_SESSION['member_id'])) {
      header('Location: member_regist.php');
  
  } else {

  }
  
  

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
    <p><?php echo $count. "コメント"."  ".date("n/j/y H:i", strtotime($result['created_at'])); ?></p>
  </div>
  
  <div class="prev_next">
    <a href="thread_detail.php?id=<?php echo $_GET['id']; ?>&page=<?php echo ($now - 1); ?>" <?php if($now == 1) {echo 'class="glayout"';} ?>>前へ＞</a>
    <a href="thread_detail.php?id=<?php echo $_GET['id']; ?>&page=<?php echo ($now + 1); ?>" <?php if($now == $max_page) {echo 'class="glayout"';} ?>>次へ＞</a>
  </div>

  <div class="detail">
    <p><?php echo "投稿者:".$result2['name_sei']." ".$result2['name_mei']."  ".date("Y.n.j H:i", strtotime($result['created_at'])); ?></p>
    <p><?php echo nl2br($result['content']); ?></p>
  </div>

  <div class="comment_list">
    <?php foreach ($comment_list as $row): ?>
      <table>
        <tr>
          <td><?php echo $row['id']."."; ?></td>
          <td><?php echo $row['name_sei']; ?></td>
          <td><?php echo $row['name_mei']; ?></td>
          <td><?php echo date("Y-m-d H:i", strtotime($row['created_at'])); ?></td> 
        </tr>
      </table>

      <p><?php echo nl2br($row['comment']); ?></p>

      
      <?php if ($row['my_like_count'] < 1 ): ?>
        <form class="like" action="" method="post">
          <input name="comment_id" type="hidden" value="<?php echo $row['id']; ?>">
          <input name="heart_clear" class="heart_clear" type="submit" value="♡">
          <span><?php echo $row['like_count']; ?></span>
        </form>
      <?php else:?>
        <form class="like" action="" method="post">
          <input name="comment_id" type="hidden" value="<?php echo $row['id']; ?>">
          <input name="heart_red" class="heart_red" type="submit" value="♥">
          <span><?php echo $row['like_count']; ?></span>
        </form>
      <?php endif; ?> 
      
    <?php endforeach; ?>
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