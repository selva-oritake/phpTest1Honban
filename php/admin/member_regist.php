<?php
  session_start();
  unset($_SESSION['member']);
  unset($_SESSION['count']);
  require_once('db_connect.php');

  // ログインチェック
  if (empty($_SESSION['login_id'])) {
    header('Location: login.php');
}

  if (!empty($_POST)) {
    //セッション破棄
    unset($_SESSION['join']);

    /* 入力情報の不備を検知 */
    if ($_POST['name_sei'] === "" || mb_strlen($_POST['name_sei']) > 20) {
      $error['name_sei'] = "blank";
    }
    if ($_POST['name_mei'] === "" || mb_strlen($_POST['name_mei']) > 20) {
      $error['name_mei'] = "blank";
    }
    if ($_POST['gender'] != 1 && $_POST['gender'] != 2) {
      $error['gender'] = "blank";
    }
    if ($_POST['pref'] !== "北海道" &&
    $_POST['pref'] !== "青森県" &&
    $_POST['pref'] !== "岩手県" &&
    $_POST['pref'] !== "宮城県" &&
    $_POST['pref'] !== "秋田県" &&
    $_POST['pref'] !== "山形県" &&
    $_POST['pref'] !== "福島県" &&
    $_POST['pref'] !== "茨城県" &&
    $_POST['pref'] !== "栃木県" &&
    $_POST['pref'] !== "群馬県" &&
    $_POST['pref'] !== "埼玉県" &&
    $_POST['pref'] !== "千葉県" &&
    $_POST['pref'] !== "東京都" &&
    $_POST['pref'] !== "神奈川県" &&
    $_POST['pref'] !== "新潟県" &&
    $_POST['pref'] !== "富山県" &&
    $_POST['pref'] !== "石川県" &&
    $_POST['pref'] !== "福井県" &&
    $_POST['pref'] !== "山梨県" &&
    $_POST['pref'] !== "長野県" &&
    $_POST['pref'] !== "岐阜県" &&
    $_POST['pref'] !== "静岡県" &&
    $_POST['pref'] !== "愛知県" &&
    $_POST['pref'] !== "三重県" &&
    $_POST['pref'] !== "滋賀県" &&
    $_POST['pref'] !== "京都府" &&
    $_POST['pref'] !== "大阪府" &&
    $_POST['pref'] !== "兵庫県" &&
    $_POST['pref'] !== "奈良県" &&
    $_POST['pref'] !== "和歌山県" &&
    $_POST['pref'] !== "鳥取県" &&
    $_POST['pref'] !== "島根県" &&
    $_POST['pref'] !== "岡山県" &&
    $_POST['pref'] !== "広島県" &&
    $_POST['pref'] !== "山口県" &&
    $_POST['pref'] !== "徳島県" &&
    $_POST['pref'] !== "香川県" &&
    $_POST['pref'] !== "愛媛県" &&
    $_POST['pref'] !== "高知県" &&
    $_POST['pref'] !== "福岡県" &&
    $_POST['pref'] !== "佐賀県" &&
    $_POST['pref'] !== "長崎県" &&
    $_POST['pref'] !== "熊本県" &&
    $_POST['pref'] !== "大分県" &&
    $_POST['pref'] !== "宮崎県" &&
    $_POST['pref'] !== "鹿児島県" &&
    $_POST['pref'] !== "沖縄県") {
      $error['pref'] = "blank";
    }
    if (mb_strlen($_POST['etc_address']) > 100) {
      $error['etc_address'] = "blank";
    }
    if ($_POST['email'] === "" || mb_strlen($_POST['email']) > 200 || !filter_var($_POST['email'], FILTER_VALIDATE_EMAIL)) {
      $error['email'] = "blank";
    }
    if ($_POST['password'] === "" || mb_strlen($_POST['password']) > 20 || mb_strlen($_POST['password']) < 8 || !preg_match("/^[a-zA-Z0-9]{8,20}$/",$_POST['password'])){
      $error['password'] = "blank";
    }
    if ($_POST['password_check'] === "" || mb_strlen($_POST['password_check']) > 20 || mb_strlen($_POST['password_check']) < 8 || !preg_match("/^[a-zA-Z0-9]+$/",$_POST['password_check'])) {
      $error['password_check'] = "blank";
    }


    if (!isset($error)) {
      /* メールアドレスの重複を検知 */
      $member = $dbh->prepare('SELECT COUNT(*) as cnt FROM members WHERE email=?');
      $member->execute(array($_POST['email']));
      $record = $member->fetch();
      if ($record['cnt'] > 0) {
          $error['email'] = 'duplicate';
      }

      /* パスワードの不一致を検知 */
      if ($_POST['password'] !== $_POST['password_check']) {
        $error['password'] = 'disagreement';
      }

    }

  /* エラーがなければ次のページへ */
    if (!isset($error)) {
      $_SESSION['join'] = $_POST;   // フォームの内容をセッションで保存
      header('Location: member_regist_check.php');
    }
  }
?>

<!DOCTYPE html>
<html lang="ja">
<head>
<meta charset="UTF-8">
<title>会員登録フォーム</title>
<link rel="stylesheet" href="member_regist.css">

</head>
<body>
  <header> 
    <span>会員登録</span>
    <div class="header_button">
      <input name='back' type="button" value="一覧へ戻る" onclick="location.href='member.php'">
    </div>
  </header>


  <div class="member_regist_form">

    <form action="" method="POST">
      <div class="id">
        <p>ID</p>
        <p>登録後に自動採番</p>
      </div>
      <div class ="name">
        <p>氏名</p>
        <p>姓<input name="name_sei" type="text" value="<?php if(!empty($_POST['name_sei'])){echo $_POST['name_sei'];} elseif(!empty($_SESSION['join']['name_sei'])) {echo $_SESSION['join']['name_sei'];} ?>"></p>
        <p>名<input name="name_mei" type="text" value="<?php if(!empty($_POST['name_mei'])){echo $_POST['name_mei'];} elseif(!empty($_SESSION['join']['name_mei'])) {echo $_SESSION['join']['name_mei'];} ?>"></p>
      </div>
      <?php if (!empty($error['name_sei']) && $error['name_sei'] === 'blank'): ?>
        <p class="error">＊姓を20文字以内で入力してください</p>
      <?php endif ?>
      <?php if (!empty($error['name_mei']) && $error['name_mei'] === 'blank'): ?>
        <p class="error">＊名を20文字以内で入力してください</p>
      <?php endif ?>

      <div class="gender">
        <p>性別</p>
        <input class="radio_button" type="hidden" name="gender" value="" checked>
        <p><input type="radio" name="gender" value="1" <?php if($_POST['gender'] == 1 || $_SESSION['join']['gender'] == 1) {echo 'checked';} ?>>男性</p>
        <p><input type="radio" name="gender" value="2" <?php if($_POST['gender'] == 2 || $_SESSION['join']['gender'] == 2) {echo 'checked';} ?>>女性</p>
      </div>
      <?php if (!empty($error['gender']) && $error['gender'] === 'blank'): ?>
        <p class="error">＊性別を選択してください</p>
      <?php endif ?>

      <div class="address">
        <p>住所</p>
        <p>都道府県
          <select name="pref">
          <option value="" selected>選択してください</option>
          <option value="北海道" <?php if( $_POST['pref'] === "北海道" || $_SESSION['join']['pref'] === "北海道") { echo 'selected'; } ?>>北海道</option>
          <option value="青森県" <?php if( $_POST['pref'] === "青森県" || $_SESSION['join']['pref'] === "青森県") { echo 'selected'; } ?>>青森県</option>
          <option value="岩手県" <?php if( $_POST['pref'] === "岩手県" || $_SESSION['join']['pref'] === "岩手県") { echo 'selected'; } ?>>岩手県</option>
          <option value="宮城県" <?php if( $_POST['pref'] === "宮城県" || $_SESSION['join']['pref'] === "宮城県") { echo 'selected'; } ?>>宮城県</option>
          <option value="秋田県" <?php if( $_POST['pref'] === "秋田県" || $_SESSION['join']['pref'] === "秋田県") { echo 'selected'; } ?>>秋田県</option>
          <option value="山形県" <?php if( $_POST['pref'] === "山形県" || $_SESSION['join']['pref'] === "山形県") { echo 'selected'; } ?>>山形県</option>
          <option value="福島県" <?php if( $_POST['pref'] === "福島県" || $_SESSION['join']['pref'] === "福島県") { echo 'selected'; } ?>>福島県</option>
          <option value="茨城県" <?php if( $_POST['pref'] === "茨城県" || $_SESSION['join']['pref'] === "茨城県") { echo 'selected'; } ?>>茨城県</option>
          <option value="栃木県" <?php if( $_POST['pref'] === "栃木県" || $_SESSION['join']['pref'] === "栃木県") { echo 'selected'; } ?>>栃木県</option>
          <option value="群馬県" <?php if( $_POST['pref'] === "群馬県" || $_SESSION['join']['pref'] === "群馬県") { echo 'selected'; } ?>>群馬県</option>
          <option value="埼玉県" <?php if( $_POST['pref'] === "埼玉県" || $_SESSION['join']['pref'] === "埼玉県") { echo 'selected'; } ?>>埼玉県</option>
          <option value="千葉県" <?php if( $_POST['pref'] === "千葉県" || $_SESSION['join']['pref'] === "千葉県") { echo 'selected'; } ?>>千葉県</option>
          <option value="東京都" <?php if( $_POST['pref'] === "東京都" || $_SESSION['join']['pref'] === "東京都") { echo 'selected'; } ?>>東京都</option>
          <option value="神奈川県" <?php if( $_POST['pref'] === "神奈川県" || $_SESSION['join']['pref'] === "神奈川県") { echo 'selected'; } ?>>神奈川県</option>
          <option value="新潟県" <?php if( $_POST['pref'] === "新潟県" || $_SESSION['join']['pref'] === "新潟県") { echo 'selected'; } ?>>新潟県</option>
          <option value="富山県" <?php if( $_POST['pref'] === "富山県" || $_SESSION['join']['pref'] === "富山県") { echo 'selected'; } ?>>富山県</option>
          <option value="石川県" <?php if( $_POST['pref'] === "石川県" || $_SESSION['join']['pref'] === "石川県") { echo 'selected'; } ?>>石川県</option>
          <option value="福井県" <?php if( $_POST['pref'] === "福井県" || $_SESSION['join']['pref'] === "福井県") { echo 'selected'; } ?>>福井県</option>
          <option value="山梨県" <?php if( $_POST['pref'] === "山梨県" || $_SESSION['join']['pref'] === "山梨県") { echo 'selected'; } ?>>山梨県</option>
          <option value="長野県" <?php if( $_POST['pref'] === "長野県" || $_SESSION['join']['pref'] === "長野県") { echo 'selected'; } ?>>長野県</option>
          <option value="岐阜県" <?php if( $_POST['pref'] === "岐阜県" || $_SESSION['join']['pref'] === "岐阜県") { echo 'selected'; } ?>>岐阜県</option>
          <option value="静岡県" <?php if( $_POST['pref'] === "静岡県" || $_SESSION['join']['pref'] === "静岡県") { echo 'selected'; } ?>>静岡県</option>
          <option value="愛知県" <?php if( $_POST['pref'] === "愛知県" || $_SESSION['join']['pref'] === "愛知県") { echo 'selected'; } ?>>愛知県</option>
          <option value="三重県" <?php if( $_POST['pref'] === "三重県" || $_SESSION['join']['pref'] === "三重県") { echo 'selected'; } ?>>三重県</option>
          <option value="滋賀県" <?php if( $_POST['pref'] === "滋賀県" || $_SESSION['join']['pref'] === "滋賀県") { echo 'selected'; } ?>>滋賀県</option>
          <option value="京都府" <?php if( $_POST['pref'] === "京都府" || $_SESSION['join']['pref'] === "京都府") { echo 'selected'; } ?>>京都府</option>
          <option value="大阪府" <?php if( $_POST['pref'] === "大阪府" || $_SESSION['join']['pref'] === "大阪府") { echo 'selected'; } ?>>大阪府</option>
          <option value="兵庫県" <?php if( $_POST['pref'] === "兵庫県" || $_SESSION['join']['pref'] === "兵庫県") { echo 'selected'; } ?>>兵庫県</option>
          <option value="奈良県" <?php if( $_POST['pref'] === "奈良県" || $_SESSION['join']['pref'] === "奈良県") { echo 'selected'; } ?>>奈良県</option>
          <option value="和歌山県" <?php if( $_POST['pref'] === "和歌山県" || $_SESSION['join']['pref'] === "和歌山県") { echo 'selected'; } ?>>和歌山県</option>
          <option value="鳥取県" <?php if( $_POST['pref'] === "鳥取県" || $_SESSION['join']['pref'] === "鳥取県") { echo 'selected'; } ?>>鳥取県</option>
          <option value="島根県" <?php if( $_POST['pref'] === "島根県" || $_SESSION['join']['pref'] === "島根県") { echo 'selected'; } ?>>島根県</option>
          <option value="岡山県" <?php if( $_POST['pref'] === "岡山県" || $_SESSION['join']['pref'] === "岡山県") { echo 'selected'; } ?>>岡山県</option>
          <option value="広島県" <?php if( $_POST['pref'] === "広島県" || $_SESSION['join']['pref'] === "広島県") { echo 'selected'; } ?>>広島県</option>
          <option value="山口県" <?php if( $_POST['pref'] === "山口県" || $_SESSION['join']['pref'] === "山口県") { echo 'selected'; } ?>>山口県</option>
          <option value="徳島県" <?php if( $_POST['pref'] === "徳島県" || $_SESSION['join']['pref'] === "徳島県") { echo 'selected'; } ?>>徳島県</option>
          <option value="香川県" <?php if( $_POST['pref'] === "香川県" || $_SESSION['join']['pref'] === "香川県") { echo 'selected'; } ?>>香川県</option>
          <option value="愛媛県" <?php if( $_POST['pref'] === "愛媛県" || $_SESSION['join']['pref'] === "愛媛県") { echo 'selected'; } ?>>愛媛県</option>
          <option value="高知県" <?php if( $_POST['pref'] === "高知県" || $_SESSION['join']['pref'] === "高知県") { echo 'selected'; } ?>>高知県</option>
          <option value="福岡県" <?php if( $_POST['pref'] === "福岡県" || $_SESSION['join']['pref'] === "福岡県") { echo 'selected'; } ?>>福岡県</option>
          <option value="佐賀県" <?php if( $_POST['pref'] === "佐賀県" || $_SESSION['join']['pref'] === "佐賀県") { echo 'selected'; } ?>>佐賀県</option>
          <option value="長崎県" <?php if( $_POST['pref'] === "長崎県" || $_SESSION['join']['pref'] === "長崎県") { echo 'selected'; } ?>>長崎県</option>
          <option value="熊本県" <?php if( $_POST['pref'] === "熊本県" || $_SESSION['join']['pref'] === "熊本県") { echo 'selected'; } ?>>熊本県</option>
          <option value="大分県" <?php if( $_POST['pref'] === "大分県" || $_SESSION['join']['pref'] === "大分県") { echo 'selected'; } ?>>大分県</option>
          <option value="宮崎県" <?php if( $_POST['pref'] === "宮崎県" || $_SESSION['join']['pref'] === "宮崎県") { echo 'selected'; } ?>>宮崎県</option>
          <option value="鹿児島県" <?php if( $_POST['pref'] === "鹿児島県" || $_SESSION['join']['pref'] === "鹿児島県") { echo 'selected'; } ?>>鹿児島県</option>
          <option value="沖縄県" <?php if( $_POST['pref'] === "沖縄県" || $_SESSION['join']['pref'] === "沖縄県") { echo 'selected'; } ?>>沖縄県</option>
          </select>
        </p>
        <?php if (!empty($error['pref']) && $error['pref'] === 'blank'): ?>
          <p class="error">＊都道府県を選択してください</p>
        <?php endif ?>
        <p>それ以降の住所<textarea name="etc_address" cols="50"><?php if(!empty($_POST['etc_address'])){echo $_POST['etc_address'];} elseif(!empty($_SESSION['join']['etc_address'])) {echo $_SESSION['join']['etc_address'];} ?></textarea></p>
        <?php if (!empty($error['etc_address']) && $error['etc_address'] === 'blank'): ?>
          <p class="error">＊100文字以内で入力してください</p>
        <?php endif ?>
      </div>

      <div class="password">
        <p>パスワード<input name="password" type="password"></p>
        <?php if (!empty($error['password']) && $error['password'] === 'blank'): ?>
          <p class="error">＊パスワードを8~20文字の半角英数字で入力してください</p>
        <?php endif ?>
      </div>

      <div class="password_check">
        <p>パスワード確認<input name="password_check" type="password"></p>
        <?php if (!empty($error['password_check']) && $error['password_check'] === 'blank'): ?>
          <p class="error">＊パスワードを8~20文字の半角英数字で入力してください</p>
        <?php endif ?>
        <?php if(!empty($error["password"]) && $error['password'] === 'disagreement'): ?>
          <p class="error">＊パスワードが一致しません。</p>
        <?php endif; ?>
      </div>

      <div class="email">
        <p>メールアドレス<textarea name="email" cols="50"><?php if(!empty($_POST['email'])){echo $_POST['email'];} elseif(!empty($_SESSION['join']['email'])) {echo $_SESSION['join']['email'];}?></textarea></p>
        <?php if (!empty($error['email']) && $error['email'] === 'blank'): ?>
          <p class="error">＊メールアドレスを200文字以内で入力してください</p>
        <?php endif ?>
        <?php if(!empty($error["email"]) && $error['email'] === 'duplicate'): ?>
        <p>＊このメールアドレスはすでに登録済みです</p>
        <?php endif; ?>
      </div>

      <div class="btn">
        <input type="submit" value="確認画面へ"><br>
      </div>

    </form>


  </div>

</body>
</html>