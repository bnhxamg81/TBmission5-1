<?php
    
    $alert1= "<script type='text/javascript'>alert('入力が完了していません。');</script>";
    $alert2= "<script type='text/javascript'>alert('パスワードが違います。');</script>";
    
     //DB接続設定
    $dsn = '**********';
	$user = 'tb-*******';
	$password = '***********';
	$pdo = new PDO($dsn, $user, $password, array(PDO::ATTR_ERRMODE => PDO::ERRMODE_WARNING));
	        
    //データベース内にテーブルを作成
	$sql = "CREATE TABLE IF NOT EXISTS practice"
	    ." ("
	    . "id INT AUTO_INCREMENT PRIMARY KEY,"
	    . "name char(32),"
	    . "comment TEXT,"
	    . "time TEXT,"
	    . "pass TEXT"
	    .");";
	    $stmt = $pdo->query($sql);
	    
	    //入力されているデータレコードの内容を編集(準備)
	        if(isset($_POST["edit"])){
	            //入力されているか確認
                if(!empty($_POST["enumber"])&&!empty($_POST["edipass"])){
                    $id=$_POST['enumber'];
                    $epass=$_POST['edipass'];
                    //password matching
                    $sql = 'SELECT * FROM practice WHERE id=:id';
	                $stmt = $pdo->prepare($sql);                  // ←差し替えるパラメータを含めて記述したSQLを準備し、
                    $stmt->bindParam(':id', $id, PDO::PARAM_INT); // ←その差し替えるパラメータの値を指定してから、
                    $stmt->execute();                            // ←SQLを実行する。
                    $results=$stmt->fetchAll();
	                foreach($results as $row){
	                    if($row['pass']==$epass){
	                        $num1=$row['id'];
	                        $nam1=$row['name'];
	                        $com1=$row['comment'];
	                        $pass1=$row['pass'];
	                    }else{
	                        echo $alert2;
	                    }}}else{
	                        echo $alert1;
	                    }}
	  
	 //作成されたテーブル自体を削除する
	 if(isset($_POST['reset'])){
	    $sql = 'DROP TABLE practice';
		$stmt = $pdo->query($sql);
	 }
?>
    
<!DOCTYPE html>
<html lang="ja">
    <head>
        <meta charset="utf-8">
        <title>mission_5-1</title>
    </head>
    <body>
        Date Base Testing<hr>
        <form action="" method="post">
            [POST]<br>
            NAME：<input type="text" name="name" value=<?php echo $nam1;?>><br>
            COMMENT:<input type="text" name="comment" value=<?php echo $com1;?>><br>
            PASSWORD:<input type="password" name="pass" value=<?php echo $pass1;?>><br>
            <input type="" name="switch" value=<?php echo $num1;?>>
            <input type="submit" name="submit" value="SUBMIT"><hr>
            [DELETE]<br>
            NUMBER:<input type="number" name="dnumber" placeholder="削除対象番号"><br>
            PASSWORD:<input type="password" name="delpass">
            <input type="submit" name="delete" value="DELETE">
            <input type="submit" name="reset" value="ALL RESET" style="background-color:salmon;"><hr>
            [EDIT]<br>
            NUMBER:<input type="number" name="enumber" placeholder="編集対象番号">
            PASSWORD:<input type="password" name="edipass">
            <input type="submit" name="edit" value="EDIT"><hr>
            ↓TABLE CONTENTS ↓<hr>  
        </form>
<?php
            //データを入力（データレコードの挿入)
            if(isset($_POST["submit"])){
                $name=$_POST["name"];
	            $comment =$_POST["comment"];
	            $time=date("Y/m/d H:i:s");
	            $pass=$_POST["pass"];
                    //入力されているデータレコードの内容を編集
	                if(!empty($_POST["switch"])){
	                    $id=$_POST['switch'];
	                   
	                    $sql = 'UPDATE practice SET 
	                        name=:name,comment=:comment,time=:time, pass=:pass WHERE id=:id';
	                    $stmt = $pdo->prepare($sql);
	                   
	                    $stmt->bindParam(':name', $name, PDO::PARAM_STR);
	                    $stmt->bindParam(':comment', $comment, PDO::PARAM_STR);
	                    $stmt->bindParam(':time', $time, PDO::PARAM_STR);
	                    $stmt -> bindParam(':pass', $pass, PDO::PARAM_STR);
	                    $stmt->bindParam(':id', $id, PDO::PARAM_INT);
	                   
	                    $stmt->execute();
	                    
	                }elseif//新規登録：入力されているか確認から
                    (!empty($_POST["name"])&&!empty($_POST["comment"])&&!empty($_POST["pass"])){
                    $sql = $pdo -> prepare("INSERT INTO practice (name, comment,time, pass)
                    VALUES (:name,:comment,:time,:pass)");
	                $sql -> bindParam(':name', $name, PDO::PARAM_STR);
        	        $sql -> bindParam(':comment', $comment, PDO::PARAM_STR);
        	        $sql -> bindParam(':time', $time, PDO::PARAM_STR);
        	        $sql -> bindParam(':pass', $pass, PDO::PARAM_STR);
        	        
	                $sql->execute();
	                }else{
                        echo $alert1;
                    }}
?>
        
<?php
            //行の削除番号が入力されたとき
            if(isset($_POST["delete"])){
	                //入力されているか確認
                if(!empty($_POST["dnumber"])&&!empty($_POST["delpass"])){
	                $id = $_POST["dnumber"];
	                $dpass=$_POST["delpass"];
	                //Pass matching
	                //該当データを抽出
	                $sql = 'SELECT * FROM practice WHERE id=:id';
	                $stmt = $pdo->prepare($sql);                  // ←差し替えるパラメータを含めて記述したSQLを準備し、
                    $stmt->bindParam(':id', $id, PDO::PARAM_INT); // ←その差し替えるパラメータの値を指定してから、
                    $stmt->execute();                            // ←SQLを実行する。
                    $results=$stmt->fetchAll();
	                foreach ($results as $row){ //$rowの中にはテーブルのカラム名が入る
		                if($row['pass']==$dpass){
		                    //削除
	                        $sql = 'delete from practice where id=:id';
                            $stmt = $pdo->prepare($sql);
	                        $stmt->bindParam(':id', $id, PDO::PARAM_INT);
	                        $stmt->execute();
		                }else{
	                        echo $alert2;
	                    }}}else{
                echo $alert1;
            }}
?>
        
    </body>
</html>
<?php
    //データを全表示
	$sql = 'SELECT * FROM practice';
	    $stmt = $pdo->query($sql);
	    $results = $stmt->fetchAll();
	    foreach ($results as $row){
		    //$rowの中にはテーブルのカラム名が入る
		    echo $row['id'].',';
		    echo $row['name'].',';
		    echo $row['comment'].',';
		    echo $row['time'].',';
		    echo $row['pass'].'<br>';
	    }
?>