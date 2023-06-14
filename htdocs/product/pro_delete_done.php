<?php

session_start();
session_regenerate_id(true);//sessionIDの再生成(セッションハイジャック対策)
if (isset($_SESSION['login']) == false) {
  print 'ログインされていません。<br>';
  print '<a href="../staff_login/staff_login.html">ログイン画面へ</a>';
  exit();
} else {
  print $_SESSION['staff_name'];
  print 'さんログイン中<br>';
  print '<br>';
}

?>
<!DOCTYPE html>
<html>

<head>
  <meta charset="UTF-8">
  <title>商品削除完了画面</title>
</head>

<body>

  <h1>商品削除完了画面</h1>

  <?php

  try {

    $pro_code = $_POST['code'];
    $pro_gazou_name = $_POST['gazou_name'];

    /**
     * データベースへ接続するための設定
     */
    // DB名, ホスト名, 文字型の指定
    $dsn = 'mysql:dbname=shop;host=localhost;charset=utf8'; //''の中にはスペースはNG!!
    $user = 'root'; //ユーザー名
    $password = ''; //パスワード
    $dbh = new PDO($dsn, $user, $password);
    $dbh->setAttribute(PDO::ATTR_ERRMODE, PDO::ERRMODE_EXCEPTION);

    // VALUESの(?,?)は入力した値を埋めるためのもの
    $sql = 'DELETE FROM mst_product WHERE code=?';
    $stmt = $dbh->prepare($sql); //準備する命令をセット
    $data[] = $pro_code;
    $stmt->execute($data); //実行

    $dbh = null;

    if($pro_gazou_name!='') {
      unlink('./gazou/'.$pro_gazou_name);
    }

  } catch (Exception $e) {
    print 'ただいま障害により大変ご迷惑をお掛けしております。';
    exit();
  }

  ?>
  削除しました。<br>
  <br>

  <a href="pro_list.php"> 戻る</a>

</body>

</html>