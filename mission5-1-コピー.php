<!DOCTYPE html>
<html lang="ja">
<head>
    <meta charset="UTF-8">
    <title>mission_5-1</title>
</head>
<body>

<?php
    $dsn = "データベース名";
    $user="ユーザー名";
    $password="パスワード";
    $pdo=new PDO($dsn, $user, $password, array(PDO::ATTR_ERRMODE => PDO::ERRMODE_WARNING));
    $sql = "CREATE TABLE IF NOT EXISTS mission5"
	." ("
	. "id INT AUTO_INCREMENT PRIMARY KEY,"
	. "name char(32),"
	. "comment TEXT,"
    . "date TEXT,"
    . "password char(10)"
	.");";
	$stmt = $pdo->query($sql);
    if(isset($_POST["delete"])){
        $id=$_POST["dnum"];
        $dpass=$_POST["dpass"];
        $sql = 'SELECT * FROM mission5 WHERE id=:id';
    	$stmt = $pdo->prepare($sql);
    	$stmt->bindParam(':id', $id, PDO::PARAM_INT); 
        $stmt->execute(); 
    	$results = $stmt->fetchAll();
    	foreach ($results as $row){
    		$password = $row['password'];
    	}
        if($dpass==$password){
        	$sql = 'delete from mission5 where id=:id';
        	$stmt = $pdo->prepare($sql);
        	$stmt->bindParam(':id', $id, PDO::PARAM_INT);
        	$stmt->execute();
        }
    }elseif(isset($_POST["edit"])){
        $id=$_POST["enum"];
        $epass=$_POST["epass"];
        $sql = 'SELECT * FROM mission5 WHERE id=:id';
    	$stmt = $pdo->prepare($sql);
    	$stmt->bindParam(':id', $id, PDO::PARAM_INT); 
        $stmt->execute(); 
    	$results = $stmt->fetchAll();
    	foreach ($results as $row){
    	    if($epass==$row['password']){
        		$editnumber=$row['id'];
        		$ename = $row['name'];
        		$ecom = $row['comment'];
    	    }
    	}
    }elseif(isset($_POST["submit"])){
        if($_POST["editpost"]){
            $id=$_POST["editpost"];
            $name=$_POST["name"];
            $comment=$_POST["comment"];
            $date=date("Y/m/d H:i:s");
            $sql = 'UPDATE mission5 SET name=:name,comment=:comment,date=:date WHERE id=:id';
        	$stmt = $pdo->prepare($sql);
        	$stmt->bindParam(':name', $name, PDO::PARAM_STR);
        	$stmt->bindParam(':comment', $comment, PDO::PARAM_STR);
        	$stmt->bindParam(':date', $date, PDO::PARAM_STR);
        	$stmt->bindParam(':id', $id, PDO::PARAM_INT);
        	$stmt->execute();
        }else{
            $sql = $pdo -> prepare("INSERT INTO mission5 (name, comment,date,password) VALUES (:name, :comment,:date,:password)");
        	$sql -> bindParam(':name', $name, PDO::PARAM_STR);
        	$sql -> bindParam(':comment', $comment, PDO::PARAM_STR);
        	$sql -> bindParam(':date', $date, PDO::PARAM_STR);
        	$sql -> bindParam(':password', $password, PDO::PARAM_STR);
        	$name = $_POST["name"];
        	$comment = $_POST["comment"];
        	$date=date("Y/m/d H:i:s");
            $password=$_POST["newpass"];
        	$sql -> execute();
        }
    }
   $sql = 'SELECT * FROM mission5';
   $stmt = $pdo->query($sql);
   $results = $stmt->fetchAll();
   foreach ($results as $row){
		echo $row['id'].',';
		echo $row['name'].',';
		echo $row['comment'].',';
		echo $row['date'].'<br>';
	    echo "<hr>";
	}
?>
<form method="POST" action="">
   <input type="hidden" name="editpost" value="<?php if(!empty($editnumber)){echo $editnumber;}?>">
   <input type="text" name="name" value="<?php if(!empty($ename)){echo $ename;}?>">
   <input type="text" name="comment" value="<?php if(!empty($ecom)){echo $ecom;}?>">
   <input type="password" name="newpass">
   <input type="submit" name="submit" value="送信"><br>
   <input type="number" name="dnum">
   <input type="password" name="dpass">
   <input type="submit" name="delete" value="削除"><br>
   <input type="number" name="enum">
   <input type="password" name="epass">
   <input type="submit" name="edit" value="編集">
</form>

</body>
</html>