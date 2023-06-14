<?php

session_start();
session_regenerate_id(true); //sessionIDの再生成(セッションハイジャック対策)
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
  <title>商品編集確認画面</title>
</head>

<body>
  <h1>商品編集確認画面</h1>

  <?php
  require_once('../common/common.php');
  $post = sanitize($_POST);

  $pro_code = $post['code'];
  $pro_name = $post['name']; //送られてきたname属性を$_POST['name属性']で受け取る
  $pro_price = $post['price'];
  $pro_gazou_name_old = $post['gazou_name_old'];
  $pro_gazou = $_FILES['gazou'];

  // $pro_code = htmlspecialchars($pro_code, ENT_QUOTES, 'UTF-8'); //送られてきた文字をエスケープ処理(XSS対策)
  // $pro_name = htmlspecialchars($pro_name, ENT_QUOTES, 'UTF-8'); //送られてきた文字をエスケープ処理(XSS対策)
  // $pro_price = htmlspecialchars($pro_price, ENT_QUOTES, 'UTF-8');

  if ($pro_name == '') {
    print '商品名が入力されていません。<br />';
  } else {
    print '商品名：';
    print $pro_name;
    print '<br />';
  }

  if (preg_match('/\A[0-9]+\z/', $pro_price) == 0) {
    print '価格をきちんと入力して下さい。<br />';
  } else {
    print '価格：';
    print $pro_price;
    print '<br />';
  }

  if ($pro_gazou['size'] > 0) {
    if ($pro_gazou['size'] > 1000000) {
      print '画像が大きすぎます';
    } else {
      move_uploaded_file($pro_gazou['tmp_name'], './gazou/' . $pro_gazou['name']);
      print '<img src="./gazou/' . $pro_gazou['name'] . '">';
      print '<br>';
    }
  }

  if ($pro_name == '' || preg_match('/\A[0-9]+\z/', $pro_price) == 0 || $pro_gazou['size'] > 1000000) {
    print '<form>';
    print '<input type="button" onclick="history.back()" value="戻る">';
    print '</form>';
  } else {
    print '上記のように変更します。<br>';
    print '<form method="post" action="pro_edit_done.php">';
    print '<input type="hidden" name="code" value="' . $pro_code . '">';
    print '<input type="hidden" name="name" value="' . $pro_name . '">';
    print '<input type="hidden" name="price" value="' . $pro_price . '">';
    print '<input type="hidden" name="gazou_name_old" value="' . $pro_gazou_name_old . '">';
    print '<input type="hidden" name="gazou_name" value="' . $pro_gazou['name'] . '">';
    print '<br />';
    print '<input type="button" onclick="history.back()" value="戻る">';
    print '<input type="submit" value="ＯＫ">';
    print '</form>';
  }

  ?>

</body>

</html>