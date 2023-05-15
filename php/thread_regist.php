<?php
  session_start();
  header("Expires:-1");
  header("Cache-Control:");
  header("Pragma:");
  require_once('db_connect.php');
  
  // ログインチェック
  if (empty($_SESSION['member_id'])) {
      header('Location: index.php');
  }

  if (!empty($_POST)) {
    /* 入力情報の不備を検知 */
      if ($_POST['title'] === "" || mb_strlen($_POST['title']) > 100) {
          $error['title'] = "blank";
      }
      if ($_POST['content'] === "" || mb_strlen($_POST['content']) > 500){
          $error['content'] = "blank";
      }

      /* フォームの内容をセッションで保存 */
      if (!isset($error)) {
          $_SESSION['title'] = $_POST['title'];
          $_SESSION['content'] = $_POST['content'];
          header('Location: thread_regist_check.php');
      }
  }
  
?>

<!DOCTYPE html>
<html lang="ja">
<head>
<meta charset="UTF-8">
<title>スレッド作成画面</title>
<link rel="stylesheet" href="thread_regist.css">
</head>
<body>
  <div class="thread_regist">
    <h1>スレッド作成フォーム</h1>

    <form action="" method="POST">
      <div class="thread_title">
        <p>スレッドタイトル<input name="title" type="text" size="50" value="<?php if (!empty($_POST['title'])) { echo $_POST['title']; } ?>"></p> 
        <?php if ($error['title'] === 'blank'): ?>
          <p class="error">＊タイトルを100文字以内で入力してください</p>
        <?php endif ?>
      </div>  
      <div class="content">
        <p>コメント<textarea name="content" cols="100" rows="5"><?php if (!empty($_POST['content'])) { echo $_POST['content']; } ?></textarea></p>
        <?php if ($error['content'] === 'blank'): ?>
          <p class="error">＊コメントを500文字以内で入力してください</p>
        <?php endif ?>
      </div>  
      <div class="btn">
      <input type="submit" value="確認画面へ"><br>
      <input type="button" value="一覧に戻る" onclick="location.href='thread.php'"><br>
      <input type="button" value="トップに戻る" onclick="location.href='index.php'">
      
      </div>
    </form>
</body>
</html>