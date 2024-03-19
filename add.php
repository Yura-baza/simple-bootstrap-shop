<?php 
// проверить вход
session_start();
if(!isset($_SESSION['shop_login'])){
	header("Location: login.php");
	exit();
}

include('settings.php'); 


if($_SERVER['REQUEST_METHOD'] == 'POST') {
	
	
	// создать записи в каталоге и рейтинг, если он не существует
	if (!is_dir('./data/entries/')) {
		mkdir('./data/entries/', 0777, true);
	}
	if (!is_dir('./data/rating/')) {
		mkdir('./data/rating/', 0777, true);
	}
	
	
	// установите уникальный идентификатор в качестве имени текстового файла
	$unique_id = 'id-'.date('YmdHis');

	// блок категорий
	if (isset($_POST['category'])) {		
		$category = $_POST['category'];	
	}

	// Названия блока
	if (isset($_POST['title'])) {	
		if($_POST['title'] == ''){
			$title = '';
		} else {
			$title = htmlentities($_POST['title']);
		}
	}

	// ценовой блок
	if (isset($_POST['price'])) {	
		if($_POST['price'] == '') {
			$price = '';
		} else {
			$price = htmlentities($_POST['price']);
		}
	}

	// блок описания
	if (isset($_POST['description'])) {	
		if($_POST['description'] == '') {
			$description = '';
		} else {
			$description = $_POST['description'];
		}
	}

	
	// загрузка основного изображения
	if(!empty($_FILES['main_image'])) {
		$filename = $_FILES['main_image']['name'];
		$rename_main_image = end(explode('.', $filename )); // strip name of the image
		$main_image_destination = 'uploads/mainimage_'  .uniqid(). '.' . $rename_main_image; // rename image with unique_id
		move_uploaded_file($_FILES['main_image']['tmp_name'] , $main_image_destination);
		$main_image = $main_image_destination;
	}
	
					
	// поместить содержимое формы в файл .txt с переносами строк; уникальный_ид первый
	$input_form = $unique_id.PHP_EOL;
	$input_form .= $category.PHP_EOL;
	$input_form .= $title.PHP_EOL;
	$input_form .= $price.PHP_EOL;
	$input_form .= $description.PHP_EOL;
	$input_form .= $main_image.PHP_EOL;	
	
							
	$entryfile = 'data/entries/'.$unique_id.'.txt';
	//записать данные в файл
	file_put_contents($entryfile,$input_form );
	

	// загрузка других изображений; добавить к существующему файлу
	$countfiles = count($_FILES['image']['name']);
			
	for($i=0; $i<$countfiles; $i++) {
		$filename = $_FILES['image']['name'][$i];
 
		$rename_image = end(explode('.', $filename )); // удалить название изображения
 
		$image_destination = 'uploads/image_'  .uniqid(). '.' . $rename_image; // переименовать изображение с unique_id
		// Загрузить изображение
		move_uploaded_file($_FILES['image']['tmp_name'][$i] , $image_destination);
			
		$input_image = $image_destination.'||'; // отделить каждое изображение с помощью ||
							
		// создать файл рейтинга		
		file_put_contents($entryfile, $input_image, FILE_APPEND);
 
	}
		
	
	// создать файл рейтинга
	$input_rating = 'star-1||0'.PHP_EOL;
	$input_rating .= 'star-2||0'.PHP_EOL;
	$input_rating .= 'star-3||0'.PHP_EOL;
	$input_rating .= 'star-4||0'.PHP_EOL;
	$input_rating .= 'star-5||0'.PHP_EOL;
		
	// Создать файл рейтинговых данных .txt								
	$ratingfile = 'data/rating/'.$unique_id.'.txt';

	file_put_contents($ratingfile,$input_rating);
					
	$result = '<div class="alert alert-success w-100"><b>&check;</b>&nbsp;<b>'.$title.'</b> добавлено!</div>';
	
}
?>
<!DOCTYPE html>
<html lang="en">
<head>
<meta charset="utf-8">
<meta http-equiv="X-UA-Compatible" content="IE=edge">
<meta name="viewport" content="width=device-width, initial-scale=1">

<!-- Site Title   -->
<title>Add-article</title>

<!-- Bootstrap css-->
<link rel="stylesheet" href="https://maxcdn.bootstrapcdn.com/bootstrap/4.0.0/css/bootstrap.min.css">
<!-- jQuery core -->
<script src="https://ajax.googleapis.com/ajax/libs/jquery/3.4.1/jquery.min.js"></script>
<!-- API key for TinyMCE -->
<script src="tinymce/tinymce.min.js"></script>


</head>
<body>


<section class="section">
    <div class="container">
        <div class="row">
            <div class="col-md-9 col-lg-9 my-4">               
				<div class="maincolumn">
				<?php echo $result; ?>
					<h5>Добавить статью</h5>
					
					<div class="result"></div> <!-- ajax callback -->

					<a class="float-left" href="admin.php">Вернуться ко всем статьям</a>
					<br /><br />					
					
					<form name ="entry" id="entry" action="add.php" method="POST" role="form" enctype="multipart/form-data">
					
						<div class="control-group form-group">
							<select class="selectbox form-control" name="category">	
							<option value="" selected disabled>Выберите категорию</option> 
							<?php 														
								$arr_length = count($all_categories); // count the number of categories							
								// show all categories														
								for ($x = 0; $x < $arr_length; $x++) {
							?>
							<option value="<?php echo $all_categories[$x]; ?>"><?php echo $all_categories[$x]; ?></option> 
							<?php 
							} 
							?>									 								
							</select>
						</div>
																		
						<div class="control-group form-group">
							<div class="controls">
								<input class="form-control firstname" name="title" type="text" placeholder="Заголовок" required>
							</div>
						</div>
											
						<div class="control-group form-group">
							<div class="controls">
									<textarea class="tinymce form-control custom-control description" name="description" placeholder="Описание"></textarea>						
							</div>
						</div>
						<div class="control-group form-group">
							<div class="controls">
								<input class="form-control number" name="price" type="text" placeholder="Цена">
							</div>
						</div>
						<div class="control-group form-group">
							<div class="controls">
								<b>Выберите основное изображение (одиночное) для загрузки: макс: <b>2 МБ.</b> Расширения: <b>jpg jpeg png gif</b>							
								<input type="file" name="main_image" class="image form-control-file border" />
							</div>
						</div>	
						<div class="control-group form-group">
							<div class="controls">
								<b>Дополнительные изображения (несколько)</b> для загрузки: макс: <b>2 МБ.</b> Расширения: <b>jpg jpeg png gif</b>							
								<input type="file" name="image[]" class="image form-control-file border" multiple />
							</div>
						</div>						
																		
						<button type="submit" id="cf-submit" name="submit" class="btn btn-primary">Сохранить</button>	
													
					</form>
											
				</div>
				
				
			</div> <!-- end col 9 -->
			<div class="col-md-3 col-lg-3 my-4">
				<div class="sidecolumn">
					<h5>Боковая колонка</h5>
						Место для чего-нибудь здесь																			
				</div>
				
			</div>
						
		</div> <!-- end row -->
	</div> <!-- end container -->

</section>


<script>
   //tiny texteditor
   tinyMCE.init({
	selector : ".tinymce",
	plugins: "emoticons link preview wordcount",
	elementpath: false,
	language: "ru",
	
	menubar: true,
	toolbar: 'undo redo | bold italic underline | fontsizeselect | link | emoticons | preview | wordcount',
	  
	height: 300,
	force_br_newlines : true,
	force_p_newlines : false,
	forced_root_block : '',
	paste_as_text: true,
	
	mobile: {
		theme: 'silver',
		plugins: 'emoticons link preview wordcount',
		language: "ru",
		toolbar: 'undo redo | bold italic underline | fontsizeselect | link | emoticons | preview | wordcount'
	}	
	
});
      

</script>
</body>
</html>

