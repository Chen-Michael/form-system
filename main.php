<?php
	ini_set("display_errors", "0");
	session_start();
	if (!isset($_SESSION["login"]) || $_SESSION["login"] != true){
		// echo $_SESSION["login"];
		header("Location: index.php");
		exit;
	}
	
	if (isset($_POST["type"])){
		if ($_POST["type"] == "登出"){
			$_SESSION["login"] = false;
			header("Location: index.php");
			exit;
		}
		$file = fopen($_SESSION["token"], "r");
		$contents = fread($file, filesize($_SESSION["token"]));
		fclose($file);
		$user = json_decode($contents, true);
		$user["email"] = $_POST["email"];
		// $user["domain"] = $_POST["domain"];
		$user["return"] = $_POST["return"];
		
		$file = fopen($_SESSION["token"], "w");
		fwrite($file, json_encode($user));
		fclose($file);
		
		$_SESSION["email"] = $user["email"];
		// $_SESSION["domain"] = $user["domain"];
		$_SESSION["return"] = $user["return"];
	}
?>
<!DOCTYPE>
<html>
	<head>
		<title>殺老師表單系統</title>
		<meta http-equiv="Content-Type" content="text/html; charset=utf-8">
	</head>
	<body>
		<h1>設定<h1>
		<form method="post">
			<input type="submit" name="type" value="登出" />
		</form>
		<hr />
		<form method="post">
			<p>Token</p>
			<p><?php echo $_SESSION["token"];?></p>
			<p>寄送電子郵件</p>
			<input type="text" name="email" value="<?php echo $_SESSION["email"];?>" />
			<!--p>網域名稱</p>
			<input type="text" name="domain" value="<?php echo $_SESSION["domain"];?>" /-->
			<p>返回網域</p>
			<input type="text" name="return" value="<?php echo $_SESSION["return"];?>" />
			<input type="submit" name="type" value="修改" />
		</form>
	</body>
</html>