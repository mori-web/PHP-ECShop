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
  <title>商品修正画面</title>
</head>

<body>
  <h1>商品修正画面</h1>

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
    $pro_gazou_name_old = $rec['gazou'];

    $dbh = null;

    if ($pro_gazou_name_old == '') {
      $disp_gazou = '';
    } else {
      $disp_gazou = '<img src="./gazou/' . $pro_gazou_name_old . '">';
    }
  } catch (Exception $e) {
    print '只今障害により大変ご迷惑をおかけしております。';
    exit();
  }
  ?>

  商品コード： <?php print $pro_code; ?>
  <br>
  <br>

  <form action="pro_edit_check.php" method="post" enctype="multipart/form-data">
    <input type="hidden" name="code" value="<?php print $pro_code; ?>">
    <!-- 古い画像の名前も送る↓ -->
    <input type="hidden" name="gazou_name_old" value="<?php print $pro_gazou_name_old; ?>">
    商品名<br>
    <input type="text" name="name" style="width:200px" value="<?php print $pro_name; ?>"><br>
    価格<br>
    <input type="text" name="price" style="width:200px" value="<?php print $pro_price; ?>"><br>
    <?php print $disp_gazou; ?><br>
    画像を選んで下さい<br>
    <input type="file" name="gazou" style="width:400px"><br>
    <br>
    <input type="button" onclick="history.back()" value="戻る">
    <input type="submit" value="OK">
  </form>

</body>

</html>