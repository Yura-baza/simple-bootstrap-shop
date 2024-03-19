<?php include 'settings.php'; ?>
<!DOCTYPE html>
<html lang="ru">
<head>
<meta charset="utf-8">

<meta name="viewport" content="width=device-width, initial-scale=1">

<!-- Заголовок сайта  -->
<title>Каталог услуг</title>

<!-- немного базового стиля -->
<link href="css/rating.css" rel="stylesheet">
<!-- Bootstrap css-->
<link rel="stylesheet" href="https://maxcdn.bootstrapcdn.com/bootstrap/4.4.1/css/bootstrap.min.css">
<!-- ядро jQuery -->
<script src="https://ajax.googleapis.com/ajax/libs/jquery/3.4.1/jquery.min.js"></script>
<!-- Бутстрап js; нужен модальный --> 
<script src="https://maxcdn.bootstrapcdn.com/bootstrap/4.4.1/js/bootstrap.min.js"></script>
<!-- Ключивые слова --> 
<meta name="description" content="<?php echo $title; ?>">
<!-- Описание --> 
<meta name="keywords" content="<?php echo $description; ?>">

<!-- API key for TinyMCE -->
<script src="tinymce/tinymce.min.js"></script>
</head>
<body>
<!-- запретить повторную отправку формы после обновления -->
<script>
if ( window.history.replaceState ) {
	window.history.replaceState( null, null, window.location.href );
}


</script>
<section class="section">
    <div class="container">
        <div class="row">
		            			
			<div class="col-lg-3 my-4">
					

				<div class="list-group my-4">
					<a class="list-group-item" href="index.php">Главная / Все категории</a>
				</div>
					
				<h4>Категории</h4>	
			
				<!-- форма фильтра -->							
				<div class="list-group">
				<?php 
					$arr_length = count($all_categories); // подсчитайте количество категорий
					for ($x = 0; $x < $arr_length; $x++) {
				?>
					<a href="index.php?filter_category=<?php echo $all_categories[$x]; ?>" class="list-group-item"><?php echo $all_categories[$x]; ?></a>
				<?php 
				} 
				?>				
				</div>
				<br />			
				
				<h4>Поиск статьи</h4>				
				<!-- search form -->
				<form class="search-form form-inline" action="index.php" method="GET" role="form">	
					<div class="input-group mb-3">
						<input class="form-control" type="text" name="entry_search" placeholder="Поиск..." />
						<div class="input-group-append">				
							<button class="btn btn-primary" type="submit">Искать</button>
						</div>
					</div>							
				</form>
				<br />	
				<h4>Статистика сайта</h4>
				  <a class="list-group-item">
               <?php include ("st/stata.php"); ?>
               </a>
										

			</div> <!-- end col 3-->
			
			<div class="col-lg-9 my-4"> 
			
				<div class="result my-4"></div>	<!-- обратный вызов ajax-->
				
				<?php include "entries.php"; ?>
				<div class="clearfix"></div><br />											

				<?php include 'includes/pagination.php'; ?>
								
			</div> <!-- end col 9 -->
			
						
		</div> <!-- end row -->
	</div> <!-- end container -->
	
</section>



<div class="toast" data-autohide="false">
  <div class="toast-header">
    <strong class="mr-auto text-primary">Спасибо за голосование!</strong>    
    <button type="button" class="ml-2 mb-1 close" data-dismiss="toast">&times;</button>
  </div>
 
</div>

<script>
//tiny текстовый редактор
tinyMCE.init({
	selector : ".tinymce",
	plugins: "emoticons link preview wordcount",
	elementpath: false,
	
	menubar: false,
	toolbar: 'undo redo | bold italic underline | fontsizeselect | link | emoticons | preview | wordcount',
	  
	height: 300,
	force_br_newlines : true,
	force_p_newlines : false,
	forced_root_block : '',
	paste_as_text: true,
	
	mobile: {
		theme: 'silver',
		plugins: 'emoticons link preview wordcount',
		toolbar: 'undo redo | bold italic underline | fontsizeselect | link | emoticons | preview | wordcount'
	}	
	
});


function equal_height() {   
	// Одинаковая высота карточки, высота текста и высота заголовка
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

//Обновить капчу
function refreshCaptcha(){
    var img = document.images['captcha_image'];
    img.src = img.src.substring(0,img.src.lastIndexOf("?"))+"?rand="+Math.random()*1000;
}

// звезды рейтинга ajax
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
		}
	});

});

// ajax написать отзыв		
$('#review').on('submit', function(e){
	e.preventDefault();
	tinyMCE.triggerSave(); // сохраните экземпляры TinyMCE перед отправкой данных
	
	var file_id = $(".file_id").val();
	var name = $(".name").val();
	var message = $(".message").val();
	var captcha_image = $("#captcha_image").val();
	var captcha = $(".captcha").val();
	
	$.ajax({
		url: 'write_review.php',
		type: 'POST',
		data: {
			    file_id:file_id,
				name:name,				
				message:message,
				captcha_image:captcha_image,
				captcha:captcha
				
				},

		success: function(data){
			$('.result').html(data);
			$('.modal').modal('hide'); // закрыть модальное окно
			$(".review-body").load(" .review-body > *"); // перезагрузить тело обзора div из обзоров
			$(".captcha_image").load(" .captcha_image"); // перезагрузить изображение с кодом
			
		},
		complete:function(){
            $('body, html').animate({scrollTop:$('.result').offset().top}, 'slow');		   
        }
	});

});


</script>

<?php if($_GET['filter_category'] != NULL) { ?>
<script>
// добавить класс «активный» в группу списков для выбранной категории
$('.list-group-item:contains("<?php echo $_GET['filter_category']; ?>")').addClass('active');
</script>
<?php } ?>

<!-- Ключивые слова --> 
<meta name="description" content="<?php echo $title; ?>">
<!-- Описание --> 
<meta name="keywords" content="<?php echo $description; ?>">



</body>
</html>

