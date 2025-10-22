<?php
    $dsn = 'mysql:dbname=データベース名;host=localhost';
    $user = 'ユーザー名';
    $password = 'パスワード';
    $pdo = new PDO($dsn, $user, $password, array(PDO::ATTR_ERRMODE => PDO::ERRMODE_WARNING));
    
    if ($_SERVER['REQUEST_METHOD'] == 'POST' && isset($_FILES['image'])) {
        
        $memo = $_POST['memo'];
        
        if ($_FILES['image']['error'] == 0) {
            $uploaded_file = $_FILES['image'];
            $file_name = uniqid() . '_' . $uploaded_file['name']; 
            $upload_path = 'uploads/' . $file_name;
            
            if (move_uploaded_file($uploaded_file['tmp_name'], $upload_path)) {
                
                $sql = "INSERT INTO plant_diary (memo, image_name) VALUES (:memo, :image_name)";
                $stmt = $pdo->prepare($sql);
                $stmt->bindParam(':memo', $memo, PDO::PARAM_STR);
                $stmt->bindParam(':image_name', $file_name, PDO::PARAM_STR);
                $stmt->execute();
            }
        }
    }
?>
<!DOCTYPE html>
<html lang="ja">
<head>
    <meta charset="UTF-8">
    <title>うちの子成長日記</title>
    <link rel="stylesheet" href="style.css">
</head>
<body>
    <div class="container">
        <h1>うちの子成長日記 🪴</h1>
        
        <form action="" method="post" enctype="multipart/form-data">
            <label for="image">写真を選択：</label><br>
            <input type="file" id="image" name="image" accept="image/*" required><br>
            <textarea name="memo" placeholder="育成メモ（水やり、新しい葉が出た！など）"></textarea><br>
            <input type="submit" value="日記を投稿する">
        </form>
        
        <hr>
        <h2>これまでの記録</h2>

        <?php
            $sql = 'SELECT * FROM plant_diary ORDER BY created_at DESC';
            $stmt = $pdo->query($sql);
            $results = $stmt->fetchAll();
            foreach ($results as $row) {
                echo '<div class="post">';
                echo '<p class="date">' . htmlspecialchars($row['created_at'], ENT_QUOTES, 'UTF-8') . '</p>';
                if (!empty($row['image_name'])) {
                    echo '<img src="uploads/' . htmlspecialchars($row['image_name'], ENT_QUOTES, 'UTF-8') . '">';
                }
                if (!empty($row['memo'])) {
                    echo '<p class="memo">' . nl2br(htmlspecialchars($row['memo'], ENT_QUOTES, 'UTF-8')) . '</p>';
                }
                echo '</div>';
            }
        ?>
    </div> </body>
</html>