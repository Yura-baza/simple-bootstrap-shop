<?php
// check login
session_start();
if(!isset($_SESSION['shop_login'])){
	header("Location: /login.php");
	exit();
}
?>
<div class="modal" id="<?php echo $review_id.'-edit'; ?>">
  <div class="modal-dialog">

	<!-- Modal content-->
	<div class="modal-content">
	
		<div class="modal-header">
			<h3 class="modal-title">Изменить: <i><?php echo $name; ?></i></h3>
			<button type="button" class="close" data-dismiss="modal">&times;</button>
		
		</div>
		<div class="modal-body">

			<form id="review" action="" method="POST" role="form">	
						<!-- REVIEW_DIR; ID and DATE-->
						<input type="hidden" name="edit_review" value="edit_review" />
						<input type="hidden" name="review_dir" value="<?php echo $review_dir; ?>" />
						<input type="hidden" name="review_id" value="<?php echo $review_id; ?>" />
						<input type="hidden" name="date" value="<?php echo $date; ?>" />
						<!-- NAME 	-->
						<div class="control-group form-group">
							<div class="controls">
								<input class="form-control name" name="name" type="text" value="<?php echo $name; ?>">
							</div>
						</div>						
						<!-- MESSAGE	-->
						<div class="control-group form-group">	
							<div class="controls">															
								<textarea class="tinymce form-control custom-control message" onkeyup="countChar(this)" name="message"><?php echo $message; ?></textarea>								
							</div>
						</div>
						
					
						<button type="submit" id="cf-submit" name="submit_message" class="btn btn-primary w-100">Обновить</button>							
					</form>	
					
		</div>
		<div class="modal-footer"></div>
	</div>

  </div>
</div>
