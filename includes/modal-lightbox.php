<!-- code from: https://medium.com/@diegovogel/create-a-lightbox-gallery-with-standard-bootstrap-components-b9de322ddb9e -->

<!-- carousel inside a modal -->
<div class="modal fade" id="<?php echo $file_id; ?>" tabindex="-1" role="dialog" aria-hidden="true">
    <div class="modal-dialog" role="document">
        <div class="modal-content">
		
		    <div class="modal-header">
				<button type="button" class="close" data-dismiss="modal" aria-label="Close">
				  <span aria-hidden="true">&times;</span>
				</button>
		    </div>
			
            <div class="modal-body">
				<div id="carousel_<?php echo $file_id; ?>" class="carousel slide" data-ride="carousel">
				 
				    <div class="carousel-inner">
				  
						<div class="carousel-item active">
							<img class="d-block w-100" src="<?php echo $main_image; ?>" />
						</div>
						
						<?php foreach( array_filter($image_array) as $image ) { ?>
						<div class="carousel-item">
							<img class="d-block w-100" src="<?php echo $image; ?>" />
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
				
            </div>
      
        </div>
    </div>
</div>
