<?php
$default_image = 'uploads/default-image.jpg';

shuffle($carousellist);
$number_of_slides = $number_of_slides - 1; // first image is set as active; substract with 1

$first_image = reset($carousellist); // grab he first image from the array

?>
<div id="carouselExampleIndicators" class="carousel mainslider slide my-4" data-ride="carousel">
	<ol class="carousel-indicators">
		<li data-target="#carouselExampleIndicators" data-slide-to="0" class="active"></li> <!-- 1st indicator is active -->
		
		<?php
		for ($x = 1; $x <= $number_of_slides; $x++) { // number of indicators 
			echo '<li data-target="#carouselExampleIndicators" data-slide-to="'.$x.'"></li>';
		}
		?>
		
	   
	</ol>
	<div class="carousel-inner" role="listbox">
		<!-- active image slider -->
		<div class="carousel-item active">
			<img class="mx-auto d-block img-fluid carousel-image" src="<?php if( count($carousellist) < 1 ) { echo $default_image; } else { echo $first_image; }  // first image gets class active ?>" /> 
		</div>
		<?php  

		foreach(array_slice($carousellist, 1, $number_of_slides) as $file => $image) { // skip first image and loop trough the other images
		?>
		<div class="carousel-item">			
		  <img class="mx-auto d-block img-fluid carousel-image" src="<?php echo $image; ?>" />
		</div>
		<?php
		}
		?>
	</div>
  
  <a class="carousel-control-prev" href="#carouselExampleIndicators" role="button" data-slide="prev">
	<span class="carousel-control-prev-icon" aria-hidden="true"></span>
	<span class="sr-only">Previous</span>
  </a>
  <a class="carousel-control-next" href="#carouselExampleIndicators" role="button" data-slide="next">
	<span class="carousel-control-next-icon" aria-hidden="true"></span>
	<span class="sr-only">Next</span>
  </a>
  
</div>
