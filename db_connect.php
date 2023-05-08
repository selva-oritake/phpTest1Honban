<?php
  try {
    $dbh = new PDO('mysql:dbname=mydb;host=ik1-219-79869.vs.sakura.ne.jp;charset=utf8mb4', 'vpsuser', 'oIieG6GfXG');

}   catch (PDOException $e) {
    echo "データベース接続エラー：".$e->getMessage();
}

?>