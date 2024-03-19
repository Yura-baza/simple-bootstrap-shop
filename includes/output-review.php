<?php
$review_dir = $file_id;
$reviewfiles = glob('data/reviews/'.$review_dir.'/*.txt'); // read all the files in de review dir
// sort array
rsort($reviewfiles);

?>
<div class="card card-outline-secondary my-4">
    <div class="card-header">Обзоры товаров</div>
    <div class="card-body review-body">
<?php
foreach($reviewfiles as $reviewfile) {
	$lines = file($reviewfile, FILE_IGNORE_NEW_LINES);// filedata into an array
		
	$review_id = $lines[0]; // id
	  $date = $lines[1]; // date
	  $name = $lines[2]; // name
	  $message = $lines[3]; // message

    // loop for name, message and date
?>		
		<p><?php echo $message; ?></p>
		<small class="text-muted"><?php echo 'Написал пользователь <b>'. $name .'</b> на '.$date; ?></small>
	
		<?php if($admin) { ?>
		<!-- DELETE BUTTON -->
		<form class="float-right" action="<?php $_SERVER['PHP_SELF']; ?>" method="POST" role="form">
			<input type="hidden" name="delete_review" value="delete_review" />	
			<input type="hidden" class="form-control" name="review_dir" value="<?php echo $review_dir; ?>" />	<!-- review_dir is dir name -->
			<input type="hidden" class="form-control" name="review_id" value="<?php echo $review_id; ?>" />	<!-- id of review file -->
			<input type="hidden" class="form-control" name="name" value="<?php echo $name; ?>" />	<!-- name only for echo -->						
			<button class="guest-delete btn btn-danger m-1" type="submit" name="submit">Удалить</button>				
		</form>
		<!-- EDIT; Trigger the modal with a button -->
		<button type="button" class="guest-edit btn btn-secondary float-right m-1" data-toggle="modal" data-target="#<?php echo $review_id.'-edit'; ?>">Редактировать</button>
		<?php include 'includes/modal-edit-review.php';
		echo '<br /><br />';		
		} // endif admin
		
		echo '<hr>';			
} // end foreach loop
?>
		</div>
		<div class="card-footer">
<!-- Modal button Review-->
<?php if(!$admin) { ?>
		<button type="button" class="btn btn-success review-file float-left" data-toggle="modal" data-target="#<?php echo $file_id.'-review'; ?>">Оставить отзыв</button>
<?php include 'includes/modal-review.php'; } ?>

	</div>
</div>


