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
<html lang="ja">

<head>
  <meta charset="UTF-8">
  <meta name="viewport" content="width=device-width, initial-scale=1.0">
  <title>orderトップ</title>
</head>

<body>
  <?php
  require_once('../common/common.php');
  ?>

  ダウンロードしたい注文日を選んで下さい。<br>

  <form action="order_download_done.php" method="post">
    <?php pulldown_year(); ?>
    年
    <?php pulldown_month(); ?>
    月
    <?php pulldown_day(); ?>
    日
    <br><br>
    <input type="submit" value="ダウンロードへ">
  </form>

</body>

</html>