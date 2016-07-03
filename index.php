<?php
	session_start();
	if (isset($_SESSION["login"]) && $_SESSION["login"] == true){
		header("Location: main.php");
		exit;
	}
	
	$error = "";
	if (isset($_POST["type"])){
		if ( !isset($_POST["acc"]) || !isset($_POST["pas"]) ) return;
		
		$acc = $_POST["acc"];
		$pas = sha1($_POST["pas"]);
		$fileName = sha1($acc);
		$check = file_exists($fileName);
		switch ($_POST["type"]){
			case "登入":
				if ($check == false){
					$error = "登入失敗";
					return;
				}
				
				$file = fopen($fileName, "r");
				$contents = fread($file, filesize($fileName));
				fclose($file);
				$user = json_decode($contents, true);
				
				if ($user["pas"] == $pas){
					$_SESSION["acc"] = $acc;
					$_SESSION["token"] = $fileName;
					$_SESSION["email"] = $user["email"];
					$_SESSION["domain"] = $user["domain"];
					$_SESSION["return"] = $user["return"];
					$_SESSION["login"] = true;
					header("Location: main.php");
					exit;
				}
				
				$error = "登入失敗";
				break;
			case "註冊":
				if ($check == true){
					$error = "帳號已存在";
					return;
				}
				
				$file = fopen($fileName, "w");
				fwrite($file, json_encode(array("pas" => $pas, "token" => $fileName, "email" => "", "domain" => "")));
				fclose($file);
				$_SESSION["acc"] = $acc;
				$_SESSION["token"] = $fileName;
				$_SESSION["email"] = "";
				$_SESSION["domain"] = "";
				$_SESSION["return"] = "";
				$_SESSION["login"] = true;
				header("Location: main.php");
				exit;
		}
	}
?>
<!DOCTYPE>
<html>
	<head>
		<title>殺老師表單系統</title>
		<meta http-equiv="Content-Type" content="text/html; charset=utf-8">
	</head>
	<body>
		<h1>登入 / 註冊<h1>
		<?php
			if ($error != ""){
				echo "<span style='color: red'>".$error."</span>";
			}
		?>
		<hr />
		<form method="post">
			<p>帳號</p>
			<input type="text" name="acc" />
			<p>密碼</p>
			<input type="password" name="pas" />
			<input type="submit" name="type" value="登入" />
			<input type="submit" name="type" value="註冊" />
		</form>
	</body>
</html>