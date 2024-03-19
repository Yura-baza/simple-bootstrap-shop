<?php
session_start(); 

include 'settings.php';

$file_id = $_POST['file_id']; // file id
$review_dir = $file_id; // each review dir has the same name as file_id
		
// name block
if (isset($_POST['name'])) {		
	$safeuserinput = htmlentities($_POST['name']);
	if($safeuserinput == NULL){
		$username = 'Anonymous';	
	} else {
		$username = $safeuserinput;
	}
}
		
// captcha
if ( isset($_POST['captcha']) && ($_POST['captcha'] !="") ) {	
	// Validation: Checking entered captcha code with the generated captcha code
	if(strcasecmp($_SESSION['captcha'], $_POST['captcha']) != 0) {
		// Note: the captcha code is compared case insensitively.
		// if you want case sensitive match, update the check above to strcmp()
		echo '<div class="alert alert-danger">Введенный код капчи не совпадает! Пожалуйста, попробуйте еще раз.</div>';
		$error = true;	
	}			
}
			
// message block
if ( isset($_POST['message']) ) {
			
	$safeuserinput = $_POST['message'];
	$usermessage = $_POST['message'];
	
	// captcha
	if ($_POST['captcha'] == NULL)  {
		echo '<div class="alert alert-danger">Please fill in the captcha code!</div>';			
		$error = true;							
	}
	
	// check empty message
	if ($usermessage == NULL){				
		echo '<div class="alert alert-danger">Поле сообщения не может быть пустым!</div>';								
	}
	// check number of characters submitted
	elseif( strlen( strip_tags($_POST['message']) ) > $max_chars ) { 
		echo '<div class="alert alert-danger">Вы превысили максимально допустимое количество символов!</div>';			
		$error = true;				
	}		
	elseif(!$error) {
		
		$review_id = 'rev_'.uniqid(); ;
		// put content in .txt file with linebreaks; review_id first
		$userinput = $review_id.PHP_EOL;
		$userinput .= date('d.m.y').PHP_EOL; // date				
		$userinput .= $username.PHP_EOL; // name
		$userinput .= $usermessage.PHP_EOL; // message
				
		$reviewfile = 'data/reviews/'.$review_dir.'/'; 
		$reviewfile .= $review_id . '.txt'; // make a unique filename 

		// mail feature
		//$to = 'name@mail.com'; // your email address if you want new reviews mailed to you.
		//$subject = $username.' has posted a review';
		//mail($to, $subject, $userinput);

		// create dir in reviews folder 
		if (!is_dir('./data/reviews/' . $review_dir.'/') ) {
		  mkdir('./data/reviews/' . $review_dir.'/', 0777, true);
		}
		
		file_put_contents($reviewfile, $userinput);
		
		echo '<div class="alert alert-success"><b>&check;</b>&nbsp;Ваш отзыв опубликован!</div>';
		
			
	}
}
	

?>
