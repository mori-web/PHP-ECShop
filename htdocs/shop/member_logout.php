<?php
session_start();
$_SESSION = array();//セッション変数を空にする
if (isset($_COOKIE[session_name()]) == true) {
  setcookie(session_name(), '', time() - 42000, '/');//パソコン側のセッションIDをクッキーから削除する。
}
session_destroy(); //セッションを破棄する
?>



<!DOCTYPE html>
<html>

<head>
  <meta charset="UTF-8">
  <title>ろくまる農園</title>
</head>

<body>

ログアウトしました<br>
<br>
<a href="shop_list.php">商品一覧へ</a>


</body>

</html>