<?php

try {

  require_once('../common/common.php');
  $post = sanitize($_POST);

  $staff_code = $post['code'];
  $staff_pass = $post['pass'];

  // htmlspecialcharsは、送られる特殊文字を→通常の文字列に変換させる
  // $staff_code = htmlspecialchars($staff_code, ENT_QUOTES, 'UTF-8');
  // $staff_pass = htmlspecialchars($staff_pass, ENT_QUOTES, 'UTF-8');

  $staff_pass = md5($staff_pass);//md5で暗号化

  $dsn = 'mysql:dbname=shop;host=localhost;charset=utf8';
  $user = 'root';
  $password = '';
  $dbh = new PDO($dsn, $user, $password);
  $dbh->setAttribute(PDO::ATTR_ERRMODE, PDO::ERRMODE_EXCEPTION);

  // SELECT文で、ANDを使い条件文で検索する(コード番号とパスワード)
  $sql = 'SELECT name FROM mst_staff WHERE code=? AND password=?';
  $stmt = $dbh->prepare($sql);//準備セット
  $data[] = $staff_code;
  $data[] = $staff_pass;
  $stmt->execute($data);//実行

  $dbh = null;//切断

  $rec = $stmt->fetch(PDO::FETCH_ASSOC);

  if ($rec == false) {
    print 'スタッフコードかパスワードが間違っています。<br />';
    print '<a href="staff_login.html"> 戻る</a>';
  } else {
    session_start();//自動でsessionIDを生成。
    $_SESSION['login']=1; //1をログインと設定
    $_SESSION['staff_code']=$staff_code;
    $_SESSION['staff_name']=$rec['name'];
    header('Location:staff_top.php');
    exit();
  }
} catch (Exception $e) {
  print 'ただいま障害により大変ご迷惑をお掛けしております。';
  exit();
}
