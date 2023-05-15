<?php
  session_start();
  require_once('db_connect.php');
  
  if (!empty($_POST['thread_search'])) { 
    $stmt = $dbh->prepare("SELECT * FROM threads WHERE title LIKE '%{$_POST['thread_search']}%' OR content LIKE '%{$_POST['thread_search']}%' ORDER BY created_at DESC");
    $stmt->execute();  //ヒットしたデータが複数の時はfetchはいらない
  } else {
    $stmt = $dbh->prepare("SELECT * FROM threads ORDER BY created_at DESC");
    $stmt->execute();
  }
?>

<!DOCTYPE html>
<html lang="ja">
<head>
<meta charset="UTF-8">
<title>ホーム画面</title>
<link rel="stylesheet" href="thread.css">
</head>
<body>
  <header>
    <div class="header_button">
    <?php if (isset($_SESSION['member_id'])): ?>
      <input type="button" value="新規スレッド作成" onclick="location.href='thread_regist.php'">
    <?php endif ?>
    </div>
  </header>

  <form action="" method="post">
    <div class="thread_search">
      <input type="text" name="thread_search" size="50">
      <input type="submit" value="スレッド検索">
    </div>
  </form>
  
  <div class="thread">
    <table>
      <?php foreach ($stmt as $row): ?>
        <tr>
          <td><?php echo "ID:".$row['id']; ?></td>
          <td><a href ="thread_detail.php?id=<?php echo $row['id']; ?>"><?php echo $row['title']; ?></a></td>
          <td><?php echo $row['created_at']; ?></td>
        </tr>
      <?php endforeach; ?>
    </table>
  </div>
    
  <div class="btn">
    <input type="button" value="トップに戻る" onclick="location.href='index.php'">
  </div>
  
</body>
</html>