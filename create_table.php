<?php
    $dsn = 'mysql:dbname=データベース名;host=localhost';
    $user = 'ユーザ名';
    $password = 'パスワード';
    $pdo = new PDO($dsn, $user, $password, array(PDO::ATTR_ERRMODE => PDO::ERRMODE_WARNING));

    $sql = "CREATE TABLE IF NOT EXISTS plant_diary"
    ." ("
    . "id INT AUTO_INCREMENT PRIMARY KEY,"
    . "memo TEXT,"
    . "image_name VARCHAR(255),"
    . "created_at DATETIME NOT NULL DEFAULT CURRENT_TIMESTAMP"
    .");";
    
    $stmt = $pdo->query($sql);
    
?>