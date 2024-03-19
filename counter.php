<?php 
// code from: http://fofwebdesign.co.uk/template/_testing/5star/

// create dir ip if not exists
if (!is_dir('./data/ip/')) {
    mkdir('./data/ip/', 0777, true);
}

$file_id = $_POST['file_id']; // posted from index.php
$value = $_POST['value']; // posted from index.php
$ip = $_SERVER['REMOTE_ADDR']; // grab ip address

$ip_file = 'data/ip/'.$file_id.'.txt'; // path to text file that stores ip addresses
$ip_lines = file($ip_file, FILE_IGNORE_NEW_LINES); // filedata into an array

// check if ip address already stored
if (in_array($ip, $ip_lines)) {
	$error = true;
}
else {
	// Open the file to get existing content
	$current = file_get_contents($ip_file);
	// Append a new ip address to the file
	$current .= $ip.PHP_EOL;
	// Write the contents back to the file
	file_put_contents($ip_file, $current);
}

$file = 'data/rating/'.$file_id.'.txt'; // path to text file that stores counts
$fh = fopen($file, 'r+');
$value = $_REQUEST['value']; // posted from page

$lines = '';
while(!feof($fh)){
	$line = explode('||', fgets($fh));
	$item = trim($line[0]);
	$num = trim($line[1]);
	
	if( !empty($item) ) {
		if($item == $value && !$error) {
			$num++; // increment count by 1
			}
		$lines .= "$item||$num\r\n";
		}
	} 
file_put_contents($file, $lines);
fclose($fh);


$all_lines = file($file, FILE_IGNORE_NEW_LINES);
foreach($all_lines as $line){
	list($item, $num) = explode('||', $line);
	$count[$item] = $num;
	}
$avg = number_format(($count['star-1']*1 + $count['star-2']*2 + $count['star-3']*3 + $count['star-4']*4 + $count['star-5']*5) / array_sum($count),1,'.','');
$intVal = intval($avg);
$round = ($avg - $intVal < .5) ? $intVal : round($intVal).'.5';


?>	
<!-- rating stars; send back to page index.php -->
<div class="rating" data-rating="<?php echo $round; ?>">
	<div class="rating-stars float-left" data-id="<?php echo $file_id; ?>">
		<a href="#5" class="click-trigger star-5" data-value="star-5" title="Vote 5 stars">&#x2605;</a>
		<a href="#4" class="click-trigger star-4" data-value="star-4" title="Vote 4 stars">&#x2605;</a>
		<a href="#3" class="click-trigger star-3" data-value="star-3" title="Vote 3 stars">&#x2605;</a>
		<a href="#2" class="click-trigger star-2" data-value="star-2" title="Vote 2 stars">&#x2605;</a>
		<a href="#1" class="click-trigger star-1" data-value="star-1" title="Vote 1 star">&#x2605;</a>
	</div>
	<div class="rating-votes float-left">
		<?php echo $avg; ?>&nbsp;звезд Голосов: <?php echo array_sum($count); ?>
	</div>				
</div>
<?php if (!$error) {
	echo '<div class="result-vote text-success">Спасибо за голосование!</div>';
}
else {
	echo '<div class="result-vote text-danger">Вы уже проголосовали!</div>';
}
?>


