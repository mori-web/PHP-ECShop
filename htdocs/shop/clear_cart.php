<?php
session_start();
$_SESSION = array(); //セッション変数を空にする
if (isset($_COOKIE[session_name()]) == true) {
  setcookie(session_name(), '', time() - 42000, '/'); //パソコン側のセッションIDをクッキーから削除する。
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

  カートを空にしました<br>
  <br>
  <a href="../staff_login/staff_login.html">ログイン画面へ</a>


</body>

</html>