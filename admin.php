<?php 
// check login
session_start();
if(!isset($_SESSION['shop_login'])){
	header("Location: login.php");
	exit();
}


include 'settings.php'; 

$current_file = basename($_SERVER['PHP_SELF']);
if($current_file == 'admin.php') {
	$admin = true;
}
?>

<!DOCTYPE html>
<html lang="ru">
<head>
<meta charset="utf-8">

<meta name="viewport" content="width=device-width, initial-scale=1">

<!-- Site Title   -->
<title>Simple Shop</title>

<!-- some basic styling -->
<link href="css/rating.css" rel="stylesheet">
<!-- Bootstrap css-->
<link rel="stylesheet" href="https://maxcdn.bootstrapcdn.com/bootstrap/4.4.1/css/bootstrap.min.css">
<!-- jQuery core -->
<script src="https://ajax.googleapis.com/ajax/libs/jquery/3.4.1/jquery.min.js"></script>
 <!-- Bootstrap js; need for modal --> 
<script src="https://maxcdn.bootstrapcdn.com/bootstrap/4.4.1/js/bootstrap.min.js"></script>

<!-- API key for TinyMCE -->
<script src="tinymce/tinymce.min.js"></script>


</head>
<body>
<!-- prevent form resubmission after refresh -->
<script>
if ( window.history.replaceState ) {
	window.history.replaceState( null, null, window.location.href );
}


</script>

<section class="section">
    <div class="container">
        <div class="row">
		            			
			<div class="col-lg-3 my-4">
				
				<a href="logout.php" class="btn btn-danger w-100 my-4">Выход</a>
				
				<br/><br/>
				
				<div class="list-group">
					<a class="list-group-item" href="admin.php">Главная / Все категории</a>
				</div>
				<br />
				
				<h4>Категории</h4>								
				<!-- filter form -->							
				<div class="list-group">
				<?php 
					$arr_length = count($all_categories); // count the number of categories
					for ($x = 0; $x < $arr_length; $x++) {
				?>
					<a href="admin.php?filter_category=<?php echo $all_categories[$x]; ?>" class="list-group-item"><?php echo $all_categories[$x]; ?></a>
				<?php 
				} 
				?>				
				</div>
				<br />
				
				<h4>Поиск статьи</h4>				
				<!-- search form -->
				<form class="search-form form-inline" action="admin.php" method="GET" role="form">	
					<div class="input-group mb-3">
						<input class="form-control" type="text" name="entry_search" placeholder="Поиск..." />
						<div class="input-group-append">				
							<button class="btn btn-primary" type="submit">Искать</button>
						</div>
					</div>							
				</form>
				
				
				<br /><br />	
				<h4>Статистика сайта</h4>
				  <a class="list-group-item">
               <?php include ("st/stata.php"); ?>
               </a>
               <form method='post'> 
               <button class="btn btn-danger w-100 my-4" type='submit' name='clear' onClick="alert">Очистить статистику</button>
               </form>
               
               <a href="backup/backup.php" class="btn btn-danger w-100 my-4">Сделать бекап сайта</a>
               <?php
               if(isset($_POST['clear']))  
               {    
                $f = fopen('st/baz.dat','w'); 
                  ftruncate($f, 0); 
                 fclose($f);         
               }
                ?>
                

                <br />
               <a class="btn btn-primary w-100" href="add.php">Добавить статью</a>
              
				<!-- add aricle -->
				
															
			</div> <!-- end col 3-->
			
			<div class="col-lg-9 my-4"> 
		
									
						<?php include "entries.php"; ?>
						<div class="clearfix"></div><br />											
				
				<?php include 'includes/pagination.php'; ?>
								
			</div> <!-- end col 9 -->
						
		</div> <!-- end row -->
	</div> <!-- end container -->
	
</section>


<script>
   //tiny texteditor
   tinyMCE.init({
	selector : ".tinymce",
	plugins: "emoticons link preview wordcount ImgPen Translator lists media image link",
	elementpath: false,
	language: "ru",
	
	menubar: true,
	toolbar: 'styles | undo redo | bold italic underline | fontsizeselect | link | emoticons | preview | wordcount | fontselect fontsizeselect formatselect | bold italic strikethrough forecolor backcolor | link image ImgPen media | alignleft aligncenter alignright alignjustify | numlist bullist | outdent indent removeformat | Translator TranslatorConf TranslatorReverse',
	// toolbar: 'undo redo | blocks | bold italic | alignleft aligncenter alignright alignjustify | outdent indent',
	// toolbar: 'undo redo | styleselect | bold italic | alignleft aligncenter alignright alignjustify | outdent indent',   
	// toolbar: 'numlist bullist | outdent indent removeformat | Translator TranslatorConf TranslatorReverse',
	// toolbar: 'fontselect fontsizeselect formatselect | bold italic strikethrough forecolor backcolor',
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


function equal_height() {   
	// Equal Card Height, Text Height and Title Height
	$('.equalheight').jQueryEqualHeight('.card .card-body .card-title');
	$('.equalheight').jQueryEqualHeight('.card .card-body .card-text');
	$('.equalheight').jQueryEqualHeight('.card');
}
$(window).on('load', function(event) {
	equal_height();
});

$(window).resize(function(event) {
	equal_height();
});


// ajax rating stars
$('.click-trigger').on('click', function(e) {
	e.preventDefault();
	
	let $star = $(this);
    let $container = $star.closest('.rating-stars');
	$.ajax({
		url: 'counter.php',
		type: 'POST',
		data: {	
				value: $star.data('value'),
				file_id: $container.data('id')		
			},
		success: function(data){
			$container.closest('.card-footer').html(data);
			
			//$('.toast').toast('show');			
		}
	});

});


</script>
</body>
</html>

