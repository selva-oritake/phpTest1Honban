<?php
  session_start();
  require_once('db_connect.php');

  $id = $_POST['id'];
  $gender = $_POST['gender'];
  $pref = $_POST['pref'];
  $freeword = $_POST['freeword'];

  $sort = $_GET['sort'] ?? ''; // クエリ文字列の'sort'パラメータを取得
  $order = $_GET['order'] ?? ''; // クエリ文字列の'order'パラメータを取得

    // ログインチェック
  if (empty($_SESSION['login_id'])) {
      header('Location: login.php');
  }

  if (!empty($_POST['search'])) {
    unset($_SESSION['join']);
    unset($_SESSION['count']);

    $sql = "SELECT * FROM members WHERE 1=1 ORDER BY id DESC";
    $conditions = [];
    $params = [];

    if (!empty($id)) {
      $conditions[] = "id = :id";
      $params[':id'] = $id;
    }
    
    if (!empty($gender)) {
      $conditions[] = "gender = :gender";
      $params[':gender'] = $gender;
    }
    
    if (!empty($pref)) {
      $conditions[] = "pref_name = :pref";
      $params[':pref'] = $pref;
    }
    
    if (!empty($freeword)) {
      $conditions[] = "(name_sei LIKE :freeword OR name_mei LIKE :freeword OR email LIKE :freeword)";
      $params[':freeword'] = "%$freeword%";
      
    }

    if (!empty($conditions)) {
      $sql .= " AND " . implode(" AND ", $conditions);
    }

    $stmt = $dbh->prepare($sql);
    $stmt->execute($params);
    $result = $stmt->fetchAll();
    $count = count($result);
    $_SESSION['join'] = $result;
    $_SESSION['count'] = $count;

    }

    if (!empty($sort) && !empty($order)) {
      // 並び替え
      usort($_SESSION['join'], function($a, $b) use ($sort, $order) {
          if ($order === 'asc') {
              return $a[$sort] <=> $b[$sort];
          } elseif ($order === 'desc') {
              return $b[$sort] <=> $a[$sort];
          }
          return 0;
      });
  }

  //ページング
  define('MAX','10');
  $max_page = ceil($_SESSION['count'] / MAX);
  if(isset($_GET['page'])){
    $now = $_GET['page'];
  }else{
    $now = 1;
  }
  //ページ送り範囲設定
  if($now == 1 || $now == $max_page) {
    $range = 2;
  } elseif ($now == 2 || $now == $max_page - 1) {
    $range = 1;
  } else {
    $range = 0;
  }
  $start_no = ($now - 1) * MAX;
  $member_list = array_slice($_SESSION['join'], $start_no, MAX, true);

?>

<!DOCTYPE html>
<html lang="ja">
<head>
<meta charset="UTF-8">
<title>会員一覧</title>
<link rel="stylesheet" href="member.css">
</head>
<body>
<header> 
  <span>会員一覧</span>
  <div class="header_button">
    <input name='back' type="button" value="トップへ戻る" onclick="location.href='index.php'">
  </div>
</header>

<form action="" method="post">
  <table>
    <tr>
      <td>ID</td>
      <td><input type="text" name="id"></td>
    </tr>
    <tr>
      <td>性別</td>
      <td>
        <input type="hidden" name="gender" value="" checked>
        <p class="radio_button"><input type="radio" name="gender" value="1" <?php if(!empty($_POST['gender']) && $_POST['gender'] == 1){echo 'checked';} ?>>男性</p>
        <p class="radio_button"><input type="radio" name="gender" value="2" <?php if(!empty($_POST['gender']) && $_POST['gender'] == 2){echo 'checked';} ?>>女性</p>
      </td>
    </tr>
    <tr>
      <td>都道府県</td>
      <td>
        <select name="pref">
          <option value="" selected>選択してください</option>
          <option value="北海道" <?php if( !empty($_POST['pref']) && $_POST['pref'] === "北海道" ){ echo 'selected'; } ?>>北海道</option>
          <option value="青森県" <?php if( !empty($_POST['pref']) && $_POST['pref'] === "青森県" ){ echo 'selected'; } ?>>青森県</option>
          <option value="岩手県" <?php if( !empty($_POST['pref']) && $_POST['pref'] === "岩手県" ){ echo 'selected'; } ?>>岩手県</option>
          <option value="宮城県" <?php if( !empty($_POST['pref']) && $_POST['pref'] === "宮城県" ){ echo 'selected'; } ?>>宮城県</option>
          <option value="秋田県" <?php if( !empty($_POST['pref']) && $_POST['pref'] === "秋田県" ){ echo 'selected'; } ?>>秋田県</option>
          <option value="山形県" <?php if( !empty($_POST['pref']) && $_POST['pref'] === "山形県" ){ echo 'selected'; } ?>>山形県</option>
          <option value="福島県" <?php if( !empty($_POST['pref']) && $_POST['pref'] === "福島県" ){ echo 'selected'; } ?>>福島県</option>
          <option value="茨城県" <?php if( !empty($_POST['pref']) && $_POST['pref'] === "茨城県" ){ echo 'selected'; } ?>>茨城県</option>
          <option value="栃木県" <?php if( !empty($_POST['pref']) && $_POST['pref'] === "栃木県" ){ echo 'selected'; } ?>>栃木県</option>
          <option value="群馬県" <?php if( !empty($_POST['pref']) && $_POST['pref'] === "群馬県" ){ echo 'selected'; } ?>>群馬県</option>
          <option value="埼玉県" <?php if( !empty($_POST['pref']) && $_POST['pref'] === "埼玉県" ){ echo 'selected'; } ?>>埼玉県</option>
          <option value="千葉県" <?php if( !empty($_POST['pref']) && $_POST['pref'] === "千葉県" ){ echo 'selected'; } ?>>千葉県</option>
          <option value="東京都" <?php if( !empty($_POST['pref']) && $_POST['pref'] === "東京都" ){ echo 'selected'; } ?>>東京都</option>
          <option value="神奈川県" <?php if( !empty($_POST['pref']) && $_POST['pref'] === "神奈川県" ){ echo 'selected'; } ?>>神奈川県</option>
          <option value="新潟県" <?php if( !empty($_POST['pref']) && $_POST['pref'] === "新潟県" ){ echo 'selected'; } ?>>新潟県</option>
          <option value="富山県" <?php if( !empty($_POST['pref']) && $_POST['pref'] === "富山県" ){ echo 'selected'; } ?>>富山県</option>
          <option value="石川県" <?php if( !empty($_POST['pref']) && $_POST['pref'] === "石川県" ){ echo 'selected'; } ?>>石川県</option>
          <option value="福井県" <?php if( !empty($_POST['pref']) && $_POST['pref'] === "福井県" ){ echo 'selected'; } ?>>福井県</option>
          <option value="山梨県" <?php if( !empty($_POST['pref']) && $_POST['pref'] === "山梨県" ){ echo 'selected'; } ?>>山梨県</option>
          <option value="長野県" <?php if( !empty($_POST['pref']) && $_POST['pref'] === "長野県" ){ echo 'selected'; } ?>>長野県</option>
          <option value="岐阜県" <?php if( !empty($_POST['pref']) && $_POST['pref'] === "岐阜県" ){ echo 'selected'; } ?>>岐阜県</option>
          <option value="静岡県" <?php if( !empty($_POST['pref']) && $_POST['pref'] === "静岡県" ){ echo 'selected'; } ?>>静岡県</option>
          <option value="愛知県" <?php if( !empty($_POST['pref']) && $_POST['pref'] === "愛知県" ){ echo 'selected'; } ?>>愛知県</option>
          <option value="三重県" <?php if( !empty($_POST['pref']) && $_POST['pref'] === "三重県" ){ echo 'selected'; } ?>>三重県</option>
          <option value="滋賀県" <?php if( !empty($_POST['pref']) && $_POST['pref'] === "滋賀県" ){ echo 'selected'; } ?>>滋賀県</option>
          <option value="京都府" <?php if( !empty($_POST['pref']) && $_POST['pref'] === "京都府" ){ echo 'selected'; } ?>>京都府</option>
          <option value="大阪府" <?php if( !empty($_POST['pref']) && $_POST['pref'] === "大阪府" ){ echo 'selected'; } ?>>大阪府</option>
          <option value="兵庫県" <?php if( !empty($_POST['pref']) && $_POST['pref'] === "兵庫県" ){ echo 'selected'; } ?>>兵庫県</option>
          <option value="奈良県" <?php if( !empty($_POST['pref']) && $_POST['pref'] === "奈良県" ){ echo 'selected'; } ?>>奈良県</option>
          <option value="和歌山県" <?php if( !empty($_POST['pref']) && $_POST['pref'] === "和歌山県" ){ echo 'selected'; } ?>>和歌山県</option>
          <option value="鳥取県" <?php if( !empty($_POST['pref']) && $_POST['pref'] === "鳥取県" ){ echo 'selected'; } ?>>鳥取県</option>
          <option value="島根県" <?php if( !empty($_POST['pref']) && $_POST['pref'] === "島根県" ){ echo 'selected'; } ?>>島根県</option>
          <option value="岡山県" <?php if( !empty($_POST['pref']) && $_POST['pref'] === "岡山県" ){ echo 'selected'; } ?>>岡山県</option>
          <option value="広島県" <?php if( !empty($_POST['pref']) && $_POST['pref'] === "広島県" ){ echo 'selected'; } ?>>広島県</option>
          <option value="山口県" <?php if( !empty($_POST['pref']) && $_POST['pref'] === "山口県" ){ echo 'selected'; } ?>>山口県</option>
          <option value="徳島県" <?php if( !empty($_POST['pref']) && $_POST['pref'] === "徳島県" ){ echo 'selected'; } ?>>徳島県</option>
          <option value="香川県" <?php if( !empty($_POST['pref']) && $_POST['pref'] === "香川県" ){ echo 'selected'; } ?>>香川県</option>
          <option value="愛媛県" <?php if( !empty($_POST['pref']) && $_POST['pref'] === "愛媛県" ){ echo 'selected'; } ?>>愛媛県</option>
          <option value="高知県" <?php if( !empty($_POST['pref']) && $_POST['pref'] === "高知県" ){ echo 'selected'; } ?>>高知県</option>
          <option value="福岡県" <?php if( !empty($_POST['pref']) && $_POST['pref'] === "福岡県" ){ echo 'selected'; } ?>>福岡県</option>
          <option value="佐賀県" <?php if( !empty($_POST['pref']) && $_POST['pref'] === "佐賀県" ){ echo 'selected'; } ?>>佐賀県</option>
          <option value="長崎県" <?php if( !empty($_POST['pref']) && $_POST['pref'] === "長崎県" ){ echo 'selected'; } ?>>長崎県</option>
          <option value="熊本県" <?php if( !empty($_POST['pref']) && $_POST['pref'] === "熊本県" ){ echo 'selected'; } ?>>熊本県</option>
          <option value="大分県" <?php if( !empty($_POST['pref']) && $_POST['pref'] === "大分県" ){ echo 'selected'; } ?>>大分県</option>
          <option value="宮崎県" <?php if( !empty($_POST['pref']) && $_POST['pref'] === "宮崎県" ){ echo 'selected'; } ?>>宮崎県</option>
          <option value="鹿児島県" <?php if( !empty($_POST['pref']) && $_POST['pref'] === "鹿児島県" ){ echo 'selected'; } ?>>鹿児島県</option>
          <option value="沖縄県" <?php if( !empty($_POST['pref']) && $_POST['pref'] === "沖縄県" ){ echo 'selected'; } ?>>沖縄県</option>
        </select>
      </td>
    </tr>
    <tr>
      <td>フリーワード</td>
      <td><input type="text" name="freeword"></td>
    </tr>
  </table>
  <div class="search_button">
    <input name="search" type="submit" value="検索する">
  </div>
</form>

<?php if (!empty($_SESSION['join'])): ?>
  <div>
    <table>
      <tr>
        <th>ID
          <?php if ($sort === 'id' && $order === 'asc'): ?>
            <a href="?sort=id&order=desc">▼</a>
          <?php else: ?>
            <a href="?sort=id&order=asc">▼</a>
          <?php endif; ?>
        </th>
        <th>氏名</th>
        <th>性別</th>
        <th>住所</th>
        <th>登録日時
          <?php if ($sort === 'created_at' && $order === 'asc'): ?>
            <a href="?sort=created_at&order=desc">▼</a>
          <?php else: ?>
            <a href="?sort=created_at&order=asc">▼</a>
          <?php endif; ?>
        </th>
      </tr>
      <?php foreach ($member_list as $row): ?>
      <tr>
        <td><?php echo $row['id']; ?></td>
        <td><?php echo $row['name_sei']."　".$row['name_mei']; ?></td>
        <td><?php if($row['gender'] == 1){ echo "男性";} elseif ($row['gender'] == 2) {echo "女性";} ?></td>
        <td><?php echo $row['pref_name'].$row['address']; ?></td>
        <td><?php echo date("n/j/y", strtotime($row['created_at'])); ?></td>
      </tr>
      <?php endforeach; ?>
    </table>
  </div>

  <div class="paging">
    <?php if($now > 1): ?> 
      <a href="member.php?page=<?php echo ($now - 1); ?>" class="prev_next">前へ＞</a>
    <?php endif ?>

    <?php for($i = 1; $i <= $max_page; $i++): ?>
      <?php if ($i >= $now - $range && $i <= $now + $range): ?>
        <?php if ($i == $now): ?>
          <span class="now_page_number"><?php echo $i; ?></span>
        <?php else: ?>
        <a href="?page=<?php echo $i; ?>" class="page_number"><?php echo $i; ?></a>
        <?php endif ?>
      <?php endif ?>
    <?php endfor ?> 

    <?php if($now < $max_page): ?>
      <a href="member.php?page=<?php echo ($now + 1); ?>" class="prev_next">次へ＞</a>
    <?php endif ?>
  </div>
<?php endif ?>

</body>
</html>