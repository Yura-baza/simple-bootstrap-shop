
<div class="card mt-4">	

	<!-- Image slider -->
	<div id="carousel_<?php echo $file_id; ?>" class="carousel slide" data-ride="carousel">
				 
		<div class="carousel-inner">
	  
			<div class="carousel-item active">
				<img class="card-img-top img-fluid slider-image" src="<?php echo $main_image; ?>" />
			</div>
			
			<?php foreach( array_filter($image_array) as $image ) { ?>
			<div class="carousel-item">
				<img class="card-img-top img-fluid slider-image" src="<?php echo $image; ?>" />
			</div>
			<?php } ?>
	   
		</div>
		<a class="carousel-control-prev" href="#carousel_<?php echo $file_id; ?>" role="button" data-slide="prev">
			<span class="carousel-control-prev-icon" aria-hidden="true"></span>
			<span class="sr-only">Previous</span>
		</a>
		<a class="carousel-control-next" href="#carousel_<?php echo $file_id; ?>" role="button" data-slide="next">
			<span class="carousel-control-next-icon" aria-hidden="true"></span>
			<span class="sr-only">Next</span>
		</a>
		
	</div>
	<!-- End image slider--> 	
	
	<div class="card-body">
		<h3 class="card-title"><?php echo $title; ?></h3>	
		<h4><?php echo $valuta.'&nbsp;'.$price; ?></h4>
		<p class="card-text"><?php echo $description; ?></p>		
	
		<?php
		$rating_file = 'data/rating/'.$file_id.'.txt'; // path to text rating_file that stores counts
		$all_lines = file($rating_file, FILE_IGNORE_NEW_LINES);

		foreach($all_lines as $line) {
			list($item, $num) = explode('||', $line);
			$count[$item] = $num;
			}
		if(array_sum($count) != 0){
			$avg = number_format(($count['star-1']*1 + $count['star-2']*2 + $count['star-3']*3 + $count['star-4']*4 + $count['star-5']*5) / array_sum($count),1,'.','');
			$intVal = intval($avg);
			$round = ($avg - $intVal < .5) ? $intVal : round($intVal).'.5';
		} 
		else {
			$avg = $round = 0;
		}
		?>
	</div>
	<div class="card-footer bg-white">
		<!-- rating stars -->
		<div class="rating" data-rating="<?php echo $round;?>">
			<div class="rating-stars float-left" data-id="<?php echo $file_id; ?>">
				<a href="#5" class="click-trigger star-5" data-value="star-5" title="Vote 5 stars">&#x2605;</a>
				<a href="#4" class="click-trigger star-4" data-value="star-4" title="Vote 4 stars">&#x2605;</a>
				<a href="#3" class="click-trigger star-3" data-value="star-3" title="Vote 3 stars">&#x2605;</a>
				<a href="#2" class="click-trigger star-2" data-value="star-2" title="Vote 2 stars">&#x2605;</a>
				<a href="#1" class="click-trigger star-1" data-value="star-1" title="Vote 1 star">&#x2605;</a>
			</div>
			<div class="rating-votes float-left">
				<?php echo $avg;?>&nbsp;звезд Голосов: <?php echo array_sum($count);?>
			</div>				
		</div>
	
	
		<?php if($admin) { ?>
		<!-- admin only -->
		<div class="file-edit">	
		
			<!-- Edit button -->	
			<button type="button" class="btn btn-secondary edit-file float-left" data-toggle="modal" data-target="#<?php echo $file_id.'-edit'; ?>">Редактировать</button>
			
			<!-- Form delete -->
			<form class="float-left" action="<?php $_SERVER['PHP_SELF']; ?>" method="POST" role="form">	
				<input type="hidden" name="delete_file" value="<?php echo $file_id; ?>" />	
				<input type="hidden" name="delete_main_image" value="<?php echo $main_image; ?>" />
				<?php foreach( array_filter($image_array) as $image ) { ?>
				<input type="hidden" name="delete_other_image[]" value="<?php echo $image; ?>" />
				<?php } ?>
				<input type="hidden" name="delete_title" value="<?php echo $title; ?>" />	<!-- for echo only -->		
				<button class="btn btn-danger delete-file" type="submit" name="submit">Удалить</button>				
			</form>
																			
			<?php 			
				include('includes/modal-edit.php'); 				
			?> 
						
			<div class="clearfix"></div>
		</div>
		<!-- end admin only -->
		<?php } ?>
	</div> <!-- end card footer -->
	
</div>  
	
  
