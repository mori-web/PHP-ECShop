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
<html lang="ja">

<head>
  <meta charset="UTF-8">
  <title>商品参照画面</title>
</head>

<body>
  <h1>商品参照画面</h1>

  <?php

  try {
    $pro_code = $_GET['procode'];
    $dsn = 'mysql:dbname=shop;host=localhost;charset=utf8';
    $user = 'root';
    $password = '';
    $dbh = new PDO($dsn, $user, $password);
    $dbh->setAttribute(PDO::ATTR_ERRMODE, PDO::ERRMODE_EXCEPTION);

    $sql = 'SELECT name,price,gazou FROM mst_product WHERE code=?'; //「code=?」でスタッフコードを絞る
    $stmt = $dbh->prepare($sql);
    $data[] = $pro_code;
    $stmt->execute($data);

    $rec = $stmt->fetch(PDO::FETCH_ASSOC); //実行したクエリを、連想配列として受け取る
    $pro_name = $rec['name'];
    $pro_price = $rec['price'];
    $pro_gazou_name = $rec['gazou'];

    $dbh = null;

    if ($pro_gazou_name == '') {
      $disp_gazou = '';
    } else {
      $disp_gazou = '<img src="./gazou/' . $pro_gazou_name . '">';
    }
  } catch (Exception $e) {
    print '只今障害により大変ご迷惑をおかけしております。';
    exit();
  }
  ?>

  商品コード： <?php print $pro_code; ?>
  <br>
  商品名：<?php print $pro_name; ?>
  <br>
  価格：<?php print $pro_price; ?>
  <br>
  画像：<?php print $disp_gazou; ?>
  <br>
  <form>
    <input type="button" onclick="history.back()" value="戻る">
  </form>

</body>

</html>