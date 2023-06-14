<?php
session_start();
session_regenerate_id(true);
if(isset($_SESSION['member_login'])==false) {
  print 'ログインされていません。<br>';
  print '<a href="shop_list.php">商品一覧へ</a>';
  exit();
}

?>
<!DOCTYPE html>
<html lang="ja">

<head>
  <meta charset="UTF-8">
  <meta name="viewport" content="width=device-width, initial-scale=1.0">
  <title>注文完了画面</title>
</head>

<body>

  <?php

  try {
    require_once('../common/common.php');

    $post = sanitize($_POST);

    $onamae = $post['onamae'];
    $email = $post['email'];
    $postal1 = $post['postal1'];
    $postal2 = $post['postal2'];
    $address = $post['address'];
    $tel = $post['tel'];

    // 完了画面での通知
    print $onamae . '様<br>';
    print 'ご注文ありがとうございました。<br>';
    print $email . 'にメールをお送りましたので、ご確認下さいませ。<br>';
    print '商品は以下の住所に発送させていただきます。<br>';
    print $postal1 . '-' . $postal2 . '<br>';
    print $address . '<br>';
    print $tel . '<br>';


    // お客様へのメール本文 開始
    $honbun = '';
    $honbun .= $onamae . "様\n\nこのたびはご注文ありがとうございました。\n";
    $honbun .= "\n";
    $honbun .= "ご注文商品\n";
    $honbun .= "--------------------\n";

    $cart = $_SESSION['cart'];
    $kazu = $_SESSION['kazu'];
    $max = count($cart);

    $dsn = 'mysql:dbname=shop;host=localhost;charset=utf8';
    $user = 'root';
    $password = '';
    $dbh = new PDO($dsn, $user, $password);
    $dbh->setAttribute(PDO::ATTR_ERRMODE, PDO::ERRMODE_EXCEPTION);

    for ($i = 0; $i < $max; $i++) {
      $sql = 'SELECT name,price FROM mst_product WHERE code=?';
      $stmt = $dbh->prepare($sql);
      $data[0] = $cart[$i];
      $stmt->execute($data);

      $rec = $stmt->fetch(PDO::FETCH_ASSOC);

      $name = $rec['name'];
      $price = $rec['price'];
      $kakaku[] = $price;
      $suryo = $kazu[$i];
      $shokei = $price * $suryo;

      $honbun .= $name . ' ';
      $honbun .= $price . '円 x ';
      $honbun .= $suryo . '個 = ';
      $honbun .= $shokei . "円\n";
    }
    // お客様へのメール本文 終了

    // ロックをかけて、注文が同時刻の時の他のユーザーと被らないようにする
    $sql = 'LOCK TABLES dat_sales WRITE,dat_sales_product WRITE,dat_member WRITE';
    $stmt = $dbh->prepare($sql);
    $stmt->execute();

    // 会員登録の処理
    $lastmembercode = $_SESSION['member_code'];


    $sql = 'INSERT INTO dat_sales(code_member,name,email,postal1,postal2,address,tel) VALUES (?,?,?,?,?,?,?)';

    $stmt = $dbh->prepare($sql);
    $data = array(); //既に配列に入っているデータを一度リセットする
    $data[] = $lastmembercode;
    // $data[] = 0;
    $data[] = $onamae;
    $data[] = $email;
    $data[] = $postal1;
    $data[] = $postal2;
    $data[] = $address;
    $data[] = $tel;
    $stmt->execute($data);

    $sql = 'SELECT LAST_INSERT_ID()'; //auto_incrementで直近に発番された注文番号を取得するという意味
    $stmt = $dbh->prepare($sql);
    $stmt->execute();
    $rec = $stmt->fetch(PDO::FETCH_ASSOC);
    $lastcode = $rec['LAST_INSERT_ID()']; //注文番号を取得

    for ($i = 0; $i < $max; $i++) {
      $sql = 'INSERT INTO dat_sales_product(code_sales,code_product,price,quantity)VALUES(?,?,?,?)';
      $stmt = $dbh->prepare($sql);
      $data = array();
      $data[] = $lastcode;
      $data[] = $cart[$i];
      $data[] = $kakaku[$i];
      $data[] = $kazu[$i];
      $stmt->execute($data);
    }

    $sql = 'UNLOCK TABLES';
    $stmt = $dbh->prepare($sql);
    $stmt->execute();

    $dbh = null;

    $honbun .= "送料は無料です。\n";
    $honbun .= "--------------------\n";
    $honbun .= "\n";
    $honbun .= "代金は以下の口座にお振込ください。\n";
    $honbun .= "ろくまる銀行 やさい支店 普通口座 １２３４５６７\n";
    $honbun .= "入金確認が取れ次第、梱包、発送させていただきます。\n";
    $honbun .= "\n";

    $honbun .= "□□□□□□□□□□□□□□\n";
    $honbun .= "　～安心野菜のろくまる農園～\n";
    $honbun .= "\n";
    $honbun .= "○○県六丸郡六丸村123-4\n";
    $honbun .= "電話 090-6060-xxxx\n";
    $honbun .= "メール info@rokumarunouen.co.jp\n";
    $honbun .= "□□□□□□□□□□□□□□\n";

    // print '<br>';
    // print nl2br($honbun);

    $title = 'ご注文ありがとうございます。';
    $header = 'Form:info[rokumarunouen.co.jp';
    $honbun = html_entity_decode($honbun, ENT_QUOTES, 'UTF-8');
    mb_language('Japanese');
    mb_internal_encoding('UTF-8');
    mb_send_mail($email, $title, $honbun, $header);


    // お店側のメール 開始
    $title = 'お客様からご注文がありました。';
    $header = 'Form:' . $email;
    $honbun = html_entity_decode($honbun, ENT_QUOTES, 'UTF-8');
    mb_language('Japanese');
    mb_internal_encoding('UTF-8');
    mb_send_mail('info@rokumarunouen.co.jp', $title, $honbun, $header);
    // お店側のメール 終了

    // 注文後にはカートの中身を空にする
    $_SESSION = array(); //セッション変数を空にする
    if (isset($_COOKIE[session_name()]) == true) {
      setcookie(session_name(), '', time() - 42000, '/'); //パソコン側のセッションIDをクッキーから削除する。
    }
    session_destroy(); //セッションを破棄する

  } catch (Exception $e) {
    print 'ただいま障害により大変ご迷惑をお掛けしております。';
    exit();
  }

  ?>
  <br>
  <a href="shop_list.php">商品画面へ</a>

</body>

</html>