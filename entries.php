<?php
include('settings.php');

	
/* DELETE ENTRY */
if(isset($_POST['delete_file'])) {	
			
	$filename = 'data/entries/'.$_POST['delete_file'].'.txt';
	$filename_rating = 'data/rating/'.$_POST['delete_file'].'.txt';
	$filename_ip = 'data/ip/'.$_POST['delete_file'].'.txt';
	
	// delete main image
	$delete_main_image = $_POST['delete_main_image'];
	if($delete_main_image != 'uploads/default-image.jpg') {
		unlink($delete_main_image);
	}
	// delete other images	
	foreach($_POST['delete_other_image'] as $delete_other_image) {
		unlink($delete_other_image);
	}
	
	// delete article
	if(file_exists($filename)) { 
		unlink($filename);						 					 
	}
	// delete rating of article
	if(file_exists($filename_rating)) { 
		unlink($filename_rating);						 					 
	}
	// delete stored ip's of article
	if(file_exists($filename_ip)) { 
		unlink($filename_ip);						 					 
	}
	
	echo '<div class="alert alert-success w-100"><b>&check;</b>&nbsp;<b>'.$_POST['delete_title'].'</b> удален!</div>';
}

/* DELETE REVIEW */
if(isset($_POST['delete_review'])) {
	$review_dir = $_POST['review_dir'];
	$review_id = $_POST['review_id'];
	$review_name = $_POST['name'];
	unlink('data/reviews/'.$review_dir.'/'.$review_id.'.txt');
	echo '<div class="alert alert-success w-100"><b>&check;</b>&nbsp;Review <b>'.$review_name.'</b> deleted</div>';

}

/* EDIT REVIEW */
if(isset($_POST['edit_review'])) { 
	$review_dir = $_POST['review_dir'];	
	$review_id = $_POST['review_id'];
	$date = $_POST['date'];
	$name= $_POST['name'];
	$message = $_POST['message'];
	
	$input = $review_id.PHP_EOL;
	$input .= $date.PHP_EOL;
	$input .= $name.PHP_EOL;
	$input .= $message.PHP_EOL;
	
	$reviewfile = 'data/reviews/'.$review_dir.'/'.$review_id.'.txt';
	
	// write data back to file
	file_put_contents($reviewfile, $input);
	echo '<div class="alert alert-success w-100"><b>&check;</b>&nbsp;Отзыв <b>'.$name.'</b> отредактирован!</div>';
}

/* EDIT ARTICLES */
if (isset($_POST['edit_file'])) {
		
	// reset votes
	if( !empty($_POST["reset_votes"]) ) {

		// set values to 0
		$input_rating = 'star-1||0'.PHP_EOL;
		$input_rating .= 'star-2||0'.PHP_EOL;
		$input_rating .= 'star-3||0'.PHP_EOL;
		$input_rating .= 'star-4||0'.PHP_EOL;
		$input_rating .= 'star-5||0'.PHP_EOL;
											
		$ratingfile = 'data/rating/' . $_POST['file_id'] . '.txt'; 

		// overwrite ratingdata to file
		file_put_contents($ratingfile, $input_rating);
		
		
	}
	// reset voted ip's
	if( !empty($_POST["reset_ip"]) ) {

		// empty the ip file
		$input_ip = '';											
		$ipfile = 'data/ip/' . $_POST['file_id'] . '.txt'; 
		// overwrite ip-data to file
		file_put_contents($ipfile, $input_ip);			
	}


    // main image
	if( !empty($_FILES['new_main_image']['name']) ) {
		
		// unlink old main image
		$current_main_image = $_POST['current_main_image'];
		if($current_main_image != 'uploads/default-image.jpg') {
			unlink($current_main_image);
		}
		
		$filename = $_FILES['new_main_image']['name'];
		$rename_image = end(explode('.', $filename )); // strip name of the image
	 
		$image_destination = 'uploads/image_'  .uniqid(). '.' . $rename_image; // rename image with unique_id
		// Upload file
		move_uploaded_file($_FILES['new_main_image']['tmp_name'] , $image_destination);
		$new_main_image = $image_destination;
	}
	else {
		$new_main_image = $_POST['current_main_image'];
	}

	// input plain text values
	$input = $_POST['file_id'].PHP_EOL;
	$input .= $_POST['set_category'].PHP_EOL;
	$input .= $_POST['title'].PHP_EOL;
	$input .= $_POST['price'].PHP_EOL;
	$input .= $_POST['description'].PHP_EOL;				
	$input .= $new_main_image.PHP_EOL;
		
	$entryfile = 'data/entries/' . $_POST['file_id'] . '.txt'; 			
	file_put_contents($entryfile, $input);
	
	
	// multiple images	
	foreach ($_FILES["new_image"]['error'] as $key => $error) { // check if array of files is empty
		if($error === 0) {
			$array_value = true;
			break;			
		}		
	}
							
	if ($array_value) { // if files array not empty
	
		foreach($_POST['current_image'] as $current_image) {
			// unlink current images if is not default image
			if($current_image != 'uploads/default-image.jpg') {
				unlink($current_image);
			}
		}
	
		$countfiles = count($_FILES['new_image']['name']);
		for($i=0; $i<$countfiles; $i++) {
			
			$filename = $_FILES['new_image']['name'][$i];
	 
			$rename_image = end(explode('.', $filename )); // strip name of the image
	 
			$image_destination = 'uploads/image_'  .uniqid(). '.' . $rename_image; // rename image with unique_id
			
			// Upload file
			move_uploaded_file($_FILES['new_image']['tmp_name'][$i] , $image_destination);
			
			$input_image = $image_destination.'||';
								
			// write images to txt file		
			file_put_contents($entryfile, $input_image, FILE_APPEND);
			
		}
		
	}
	else { // replace old images
		foreach($_POST['current_image'] as $current_image) {
			$input_image = $current_image.'||';
			file_put_contents($entryfile, $input_image, FILE_APPEND);
		}
	}
	
			
	echo '<div class="alert alert-success w-100"><b>&check;</b>&nbsp;<b>'.$_POST['title'].'</b> обновлен!</div>';	
	
}
// END EDIT ARTICLES


// Filter category
$filter_category = $_GET['filter_category']; // get string from url for filter
if (isset($filter_category)) {
	$filterthis = strtolower($filter_category);
	$matches = array();
	
	$files = glob("data/entries/*.txt"); // Specify the file directory by extension (.txt)

	foreach($files as $file) {// Loop through the files in the directory	
			
		$handle = @fopen($file, "r");
								
		if ($handle) {
			
			$lines = file($file); //file into an array
			$buffer = $lines[1];
			
			if(strpos(strtolower($buffer), $filterthis) !== FALSE) {  // strtolower; search word not case sensitive	
					// for sorting files by title
					$lines = file($file);
					$grab_title = strtolower($lines[2]); // grab the title line	
					
					$matches[$file] = $grab_title; // put all lines in array indexed by file name	
					 										
			}
			fclose($handle);
		}
	}
}

// Filter title
$filter_title = $_GET['filter_title']; // get string from url for filter
if (isset($filter_title)) {
	$filterthis = strtolower($filter_title);
	$matches = array();
	
	$files = glob("data/entries/*.txt"); // Specify the file directory by extension (.txt)

	foreach($files as $file) {// Loop the files in the directory	
			
		$handle = @fopen($file, "r");
								
		if ($handle) {
			
			$lines = file($file); //file into an array
			$buffer = substr($lines[2],0,1); // grab the first letter from title
			
			if(strpos(strtolower($buffer), $filterthis) !== FALSE) {// strtolower; search word not case sensitive	
					// for sorting files by title
					$lines = file($file);
					$grab_title = strtolower($lines[2]); // grab the title line	
					
					$matches[$file] = $grab_title; // put all lines in array indexed by file name	
					 										
			}
			fclose($handle);
		}
	}
}

// Entry search
$entry_search = $_GET['entry_search']; // get string from url for search
if (isset($entry_search)) {
	$searchthis = strtolower($entry_search);
	$matches = array();
		
	$files = glob("data/entries/*.txt"); // Specify the file directory by extension (.txt)

	foreach($files as $file) { // Loop the files in the directory	{
			
		$handle = @fopen($file, "r");
								
		if ($handle) {
			
			// for sorting files by title
			$lines = file($file);
			$grab_title = strtolower($lines[4]); // grab the title line	
																	
			while (!feof($handle)) {
				$buffer = fgets($handle);
				
				if(strpos(strtolower($buffer), $searchthis) !== FALSE) // strtolower; search word not case sensitive	
													
					$matches[$file] = $grab_title; // put all lines in array indexed by file name	$matches[] = $file; 						
			}
			
			fclose($handle);
		}
	}
	
}



// if found matches for search
if (isset($matches)) {
	// sort array in ascending order
	asort($matches); 

	// PAGINATION code by Crayon Violent, PHP Freaks - http://www.phpfreaks.com/tutorial/basic-pagination
	$numrows = count($matches);
	$totalpages = ceil($numrows/$entriesperpage);

	// get the current page or set a default
	if (isset($_GET['currentpage']) && is_numeric($_GET['currentpage'])) {
		// cast var as int
		$currentpage = (int) $_GET['currentpage'];
	} else {
		// default page num
		$currentpage = 1;
	} // end if

	// if current page is greater than total pages...
	if ($currentpage > $totalpages) {
		// set current page to last page
		$currentpage = $totalpages;
	} // end if

	// if current page is less than first page...
	if ($currentpage < 1) {
		// set current page to first page
		$currentpage = 1;
	} // end if

	// the offset of the list, based on current page 
	$offset = ($currentpage - 1) * $entriesperpage;

	$entry_matches = array_slice($matches, $offset, $entriesperpage);
}


//show filter and search matches:
if($entry_matches != NULL) { // found a match 
	
	$total_matches = count($matches); // count the number of matches; need for pagination
	$totalpages_match = ceil($total_matches/$entriesperpage);
	//Output results below
	?>
	
	<div class="results">
		<?php 
		if($filter_title != NULl ) { ?>		
			<h4 class="float-left">Titles which start with: <?php echo $filter_title; ?></h4>
			<span class="float-right">Итого: <b><?php echo $total_matches; ?></b></span>						
		<?php 
		}
		
		if($filter_category != NULl ) { ?>		
			<h4 class="float-left">Категория: <?php echo $filter_category; ?></h4>
			<span class="float-right">Итого: <b><?php echo $total_matches; ?></b></span>			
		<?php 
		}
			
		if($entry_search != NULl ) { ?>					
			<h4 class="float-left">Результаты поиска для: <?php echo $entry_search; ?></h4>
			<span class="float-right">Итого: <b><?php echo $total_matches; ?></b></span>	
		<?php
		}
		?>
	</div>
	<div class="clearfix"></div>

	
	<div class="row">
	<?php
		
	foreach($entry_matches as $match => $title) {
								
		$lines = file($match, FILE_IGNORE_NEW_LINES); // filedata into an array
		
		include('includes/read.php');	
				
		include('includes/output.php');
				
	}
	?>
	</div>
	<?php
		
}

// No matches found for filters or search
if(isset($filter_category) || isset($filter_title) || isset($entry_search)) {
	if($matches == NULL) {
		?>
		<div class="nomatch">			
			<script type="text/javascript">location="./pages/404.html";</script>
		</div>
		<?php
	}
}
// read all files in entries folder
$dir = 'data/entries/';
if ($dh = opendir($dir)) {
    while(($file = readdir($dh))!== false){
        if ($file != "." && $file != "..") { // This line strips out . & ..
			// for sorting files by title
            $lines = file($dir.$file);
            $grab_title = strtolower($lines[2]); // grab the title line; for sorting only
			$grab_images = strtolower($lines[5]); // grab the image line; for the image slider
			
            $entrylist[$file] = $grab_title;   // put all lines in array indexed by file name
			$carousellist[$file] = $grab_images;
        }

    }
    
}
closedir($dh);


$total_entries = count($entrylist); // count total entries

if (isset($entrylist)) {
	// sort array in ascending order
	asort($entrylist);
		
	// PAGINATION code by Crayon Violent, PHP Freaks - http://www.phpfreaks.com/tutorial/basic-pagination
	$numrows = count($entrylist);
	$totalpages = ceil($numrows/$entriesperpage);
		
	// get the current page or set a default
	if (isset($_GET['currentpage']) && is_numeric($_GET['currentpage'])) {
		// cast var as int
		$currentpage = (int) $_GET['currentpage'];
	} else {
		// default page num
		$currentpage = 1;
	} // end if

	// if current page is greater than total pages...
	if ($currentpage > $totalpages) {
		// set current page to last page
		$currentpage = $totalpages;
	} // end if

	// if current page is less than first page...
	if ($currentpage < 1) {
		// set current page to first page
		$currentpage = 1;
	} // end if

	// the offset of the list, based on current page 
	$offset = ($currentpage - 1) * $entriesperpage;	
	$entries = array_slice($entrylist, $offset, $entriesperpage);
}	



$page = $_GET['page'];
if( !isset($page) && !isset($entry_search) && !isset($filter_category) && !isset($filter_title) ) { // do not list all articles when viewing single article, search results or filter category
	
	/*** ALL ENTRIES (Articles) ***/
	
	// Main slider on homepage
	include'includes/mainslider.php';
	?>
	
	<div class="row">
	<?php
		
	// all articles
	foreach($entries as $file => $title){ // sort each entry by title
									
		// open and prepare entries
		$entryfiles = 'data/entries/'.$file;						
				
		// get data out of txt file		
		$lines = file($entryfiles, FILE_IGNORE_NEW_LINES);// filedata into an array
		
		include 'includes/read.php';		
										
		include 'includes/output.php'; 
		
	} // end foreach
	?>
	</div>
	<?php
	
}
elseif( !isset($entry_search) && !isset($filter_category) && !isset($filter_title)) { // ignore when request is filter_category or entry_search

	/*** SINGLE ENTRY (Article) ***/	
	
	$entryfile = 'data/entries/'.$page.'.txt';	// open and prepare message	
	
	if(file_exists($entryfile)) {	
		// get data out of txt file		
		$lines = file($entryfile, FILE_IGNORE_NEW_LINES);// filedata into an array
		
		include 'includes/read.php';	
												
		include 'includes/output-single.php'; 
				
		include 'includes/output-review.php'; 
		
			
	}
	else {
		echo '<script type="text/javascript">location="./pages/404.html";</script>';
	}
}

	
?>
