<?php
	// print_r($_SERVER);
	if (!isset($_POST["token"])){
		echo "error:100";
		exit;
	}
	
	$fileName = $_POST["token"];
	$check = file_exists($fileName);
	
	if ($check == false){
		echo "error:101";
		exit;
	}
	
	$file = fopen($fileName, "r");
	$contents = fread($file, filesize($fileName));
	fclose($file);
	$user = json_decode($contents, true);
	
	include("PHPMailer/PHPMailerAutoload.php"); //匯入PHPMailer類別       

	$mail= new PHPMailer(); //建立新物件   
	$mail->IsSMTP(); //設定使用SMTP方式寄信        
	$mail->SMTPAuth = true; //設定SMTP需要驗證        
	$mail->CharSet = "utf-8"; //設定郵件編碼        

	$mail->Username = ""; //設定驗證帳號
	$mail->Password = "";

	$mail->From = "form@jiebu-lang.com"; //設定寄件者信箱        
	$mail->FromName = "殺老師表單系統"; //設定寄件者姓名        

	$mail->Subject = "殺老師表單系統送通知來了"; //設定郵件標題        
	
	$html = array();
	foreach ($_POST As $key => $value){
		$html[] = $key.":".$value;
	}
	
	$mail->Body = join("<br />", $html); //設定郵件內容        
	
	$mail->IsHTML(true); //設定郵件內容為HTML        
	$mail->AddAddress($user["email"], ""); //設定收件者郵件及名稱        

	if(!$mail->Send()) {        
		echo "error:103";        
	} else {
		if (stripos($user["return"], "http") != false){
			header("Location: ".$user["return"]);
		}else{
			header("Location: http://".$user["return"]);
		}  
	}    
?>