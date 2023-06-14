<?php 

$number = $_POST['number'];

$yasai[] ='';
$yasai[] ='ブロッコリー';
$yasai[] ='カリフラワー';
$yasai[] ='レタス';
$yasai[] ='みつば';
$yasai[] ='アスパラガス';
$yasai[] ='セロリ';
$yasai[] ='ピーマン';
$yasai[] ='オクラ';
$yasai[] ='さつまいも';
$yasai[] ='大根';
$yasai[] ='ほうれんそう';

print $number;
print '月は';
print $yasai[$number];
print 'が旬です';
