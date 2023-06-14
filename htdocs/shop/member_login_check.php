<?php

try {

  require_once('../common/common.php');
  $post = sanitize($_POST);

  $member_email = $post['email'];
  $member_pass = $post['pass'];

  $member_pass = md5($member_pass);//md5で暗号化

  $dsn = 'mysql:dbname=shop;host=localhost;charset=utf8';
  $user = 'root';
  $password = '';
  $dbh = new PDO($dsn, $user, $password);
  $dbh->setAttribute(PDO::ATTR_ERRMODE, PDO::ERRMODE_EXCEPTION);

  // SELECT文で、ANDを使い条件文で検索する(コード番号とパスワード)
  $sql = 'SELECT code,name FROM dat_member WHERE email=? AND password=?';
  $stmt = $dbh->prepare($sql);//準備セット
  $data[] = $member_email;
  $data[] = $member_pass;
  $stmt->execute($data);//実行

  $dbh = null;//切断

  $rec = $stmt->fetch(PDO::FETCH_ASSOC);

  if ($rec == false) {
    print 'メールアドレスかパスワードが間違っています。<br />';
    print '<a href="member_login.html"> 戻る</a>';
  } else {
    session_start();//自動でsessionIDを生成。
    $_SESSION['member_login']=1; //1をログインと設定
    $_SESSION['member_code']=$rec['code'];
    $_SESSION['member_name']=$rec['name'];
    header('Location:shop_list.php');
    exit();
  }
} catch (Exception $e) {
  print 'ただいま障害により大変ご迷惑をお掛けしております。';
  exit();
}
