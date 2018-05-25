<?php

$db_kullanici   = 'root';
$db_adres       = 'localhost';
$db_sifre       = '';
$db_adi         = 'kds';


if(session_status() == PHP_SESSION_NONE){
    session_start();
}

try {
    $vt = new PDO("mysql:host=$db_adres;dbname=$db_adi", $db_kullanici, $db_sifre, array(PDO::MYSQL_ATTR_INIT_COMMAND => "SET NAMES utf8"));
    $vt->setAttribute(PDO::ATTR_ERRMODE, PDO::ERRMODE_EXCEPTION);
    //echo 'Bağlantı başarılı';
}
catch(PDOException $e){
    echo "Bağlantı Hatası: " . $e->getMessage();
}