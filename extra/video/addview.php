<?php
include $_SERVER['DOCUMENT_ROOT'].'/extra/init.php';
if(isset($_POST['videoID']) && !empty($_POST['videoID'])){
	if($global->check('videos', 'videoID', $_POST['videoID']) === true){
		$video = $_POST['videoID'];
		$viewAdd = $global->addView($video);
	}
}

?>