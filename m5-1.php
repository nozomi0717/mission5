<?php
//データベース接続
    $dsn = "データベース名";
    $user = "ユーザー名";
    $password = "パスワード";
    $pdo = new PDO($dsn, $user, $password, array(PDO::ATTR_ERRMODE => PDO::ERRMODE_WARNING));
    
    //テーブルが無かったら作成
    $sql = "CREATE TABLE IF NOT EXISTS tbtest_2"//sql=データベースの定義
    ." ("
    . "id INT AUTO_INCREMENT PRIMARY KEY,"//もしまだこのテーブルが存在しないなら
    . "name char(32),"
    . "comment TEXT,"
    . "date DATETIME,"
    . "pass TEXT"
    .");";
    $stmt = $pdo->query($sql);//実行結果に関する情報を得たいとき
    
//送信に編集番号が空の時
if(empty($_POST["ed_num"]) && !empty($_POST["send"])){
    if(!empty($_POST["name"]) && !empty($_POST["comment"]) && !empty($_POST["post_password"])){
        $sql = $pdo -> prepare("INSERT INTO tbtest_2 (name, comment, date, pass) VALUES (:name, :comment, :date, :pass)");
        $sql -> bindParam(':name', $name, PDO::PARAM_STR);
        $sql -> bindParam(':comment', $comment, PDO::PARAM_STR);
        $sql -> bindParam(':date', $date, PDO::PARAM_STR);
        $sql -> bindParam(':pass', $post_password, PDO::PARAM_STR);
        $name = $_POST["name"];
        $comment = $_POST['comment']; 
        $date = date("Y/m/d H:i:s");
        $post_password = $_POST["post_password"];
        $sql->execute();
    } 
    // 名前が空の時
        if(empty($_POST["name"])){
            $error_message[] = "名前が入力されていません";
        }
        // コメントが空の時
        if(empty($_POST["comment"])){
            $error_message[] = "コメントが入力されていません";
        }
        // パスワードが空の時
        if(empty($_POST["pass"])){
            $error_message[] = "パスワードが入力されていません";
        }
    
    
}else if(!empty($_POST["ed_num"]) && !empty($_POST["send"])){//編集番号があるとき
    if(!empty($_POST["name"]) && !empty($_POST["comment"]) && !empty($_POST["post_password"])){
        $ed_num = $_POST["ed_num"];
        $id = $ed_num; //変更する投稿番号
        $name = $_POST["name"];
        $comment = $_POST["comment"]; //変更したい名前、変更したいコメントは自分で決める
        $post_password = $_POST["post_password"];
        
        $sql = 'UPDATE tbtest_2 SET name=:name,comment=:comment,pass=:pass WHERE id=:id';
        $stmt = $pdo->prepare($sql);
        $stmt->bindParam(':name', $name, PDO::PARAM_STR);
        $stmt->bindParam(':comment', $comment, PDO::PARAM_STR);
        $stmt->bindParam(":pass", $post_password, PDO::PARAM_STR);
        $stmt->bindParam(':id', $id, PDO::PARAM_INT);
        $stmt->execute(); 
    }
    // 名前が空の時
        if(empty($_POST["name"])){
            $error_message[] = "名前が入力されていません";
        }
        // コメントが空の時
        if(empty($_POST["comment"])){
            $error_message[] = "コメントが入力されていません";
        }
        // パスワードが空の時
        if(empty($_POST["pass"])){
            $error_message[] = "パスワードが入力されていません";
        }
}

//削除の時の操作
if(!empty($_POST["delnumber"])){
    $id = $_POST["delnumber"];
    $del_password = $_POST["del_password"];
    
    $sql = "delete from tbtest_2 where id=:id AND pass=:pass";
    $stmt = $pdo->prepare($sql);
    $stmt->bindParam(":pass", $del_password, PDO::PARAM_STR);
    $stmt->bindParam(':id', $id, PDO::PARAM_INT);
    $stmt->execute(); 
    // 削除番号が空の時
    if(empty($_POST["delnumber"])){
        $error_message[] = "削除番号が入力されていません";
    }
    // パスワードが空の時
    if(empty($_POST["del_password"])){
        $error_message[] = "パスワードが入力されていません";
    }
}

//編集の時の操作
if(!empty($_POST["editnumber"])){
    $id = $_POST["editnumber"];
    $ed_password = $_POST["ed_password"];
    
    $sql = 'SELECT* FROM tbtest_2 WHERE id=:id AND pass=:pass';
    $stmt = $pdo->prepare($sql);
    $stmt->bindParam(":pass", $ed_password, PDO::PARAM_STR);
    $stmt->bindParam(':id', $id, PDO::PARAM_INT);
    $stmt->execute(); 
    $res = $stmt->execute();
    if($res){
        $data = $stmt->fetch();
        $ed_name = $data["name"];
        $ed_comment = $data["comment"];
        $ed_password = $data["pass"];
    }
}
?>

<html lang="ja">
    <head>
        <title>mission5-1</title>
        <meta charset="UTF-8">
    </head>
    <body>
        
    <form action="" method="post">
        <div>
            <label for="name">名前</label>
            <input type="name" id="name" name="name" value="<?php if(isset($ed_name)){echo $ed_name;}?>">
        </div>
        <div>
            <label for="comment">コメント</label>
            <input type="text" id="comment" name="comment" value="<?php if(isset($ed_comment)){echo $ed_comment;}?>">
        <div>
            <label for = "post_password">パスワード</label>
            <input type = "password" id="post_password" name="post_password" value="<?php if(isset($ed_password)){echo $ed_password;}?>">
        </div>
        </div>
            <input type ="submit" name="send" value="送信">
        <div>
            <label for="delnumber">削除番号</label>
            <input type="number" id="delnumber" name="delnumber">
        </div>
        <div>
            <label for = "del_password">パスワード</label>
            <input type = "password" id="del_password" name="del_password">
        </div>
            <input type ="submit" name="delete" value="削除">
        <div>
            <label for="editnumber">編集番号</label>
            <input type="number" id="editnumber" name="editnumber">
        <div>
            <label for = "ed_password">パスワード</label>
            <input type = "password" id="ed_password" name="ed_password">
        </div>
        </div>
            <input type="submit" name="edit" value="編集">
        <div>
            <input type="hidden" name="ed_num" value="<?php if(isset($_POST["editnumber"])){echo $_POST["editnumber"];}?>">
        </div>
        
    </form>
    <?php if( !empty($error_message) ): ?>
        <ul class="error_message">
            <?php foreach( $error_message as $value ): ?>
                <li><?php echo $value; ?></li>
            <?php endforeach; ?>
        </ul>
        
    <?php endif; ?>
    <?php
        $sql = 'SELECT * FROM tbtest_2';
        $stmt = $pdo->query($sql);
        $results = $stmt->fetchAll();
             
        foreach ($results as $row){
            echo $row['id'].'<br>';
            echo '名前：'.$row['name'].'<br>';                 
            echo 'コメント：'.$row['comment'].'<br>';
            echo '日付：'.$row['date'].'<br>';
        echo "<hr>";
        }
    ?>
         
    </body>
</html>