<?php
class GlobalClass{
	protected $mysql;

	public function __construct($mysql){
		$this->m = $mysql;

		$this->ffmpeg = "ffmpeg";
        $this->ffprobe = "ffprobe";
	}

	public function formatDate($date) {
	   	$ru_month = array( 'Январь', 'Февраль', 'Март', 'Апрель', 'Май', 'Июнь', 'Июль', 'Август', 'Сентябрь', 'Октябрь', 'Ноябрь', 'Декабрь' );
		$en_month = array( 'January', 'February', 'March', 'April', 'May', 'June', 'July', 'August', 'September', 'October', 'November', 'December' );

	    return str_replace($en_month, $ru_month, $date);
	}
    
    public function newNotificationsCount($userID){
        $notifies = mysqli_num_rows(mysqli_query($this->m, "SELECT * FROM notifications WHERE notificationTo = $userID AND notificationNew = 1"));
        return $notifies;
    }
    
	public function formatDateKor($date) {
	   	$ru_month = array( 'Янв', 'Фев', 'Март', 'Апр', 'Май', 'Июнь', 'Июль', 'Авг', 'Сен', 'Окт', 'Ноя', 'Дек' );
		$en_month = array( 'January', 'February', 'March', 'April', 'May', 'June', 'July', 'August', 'September', 'October', 'November', 'December' );

	    return str_replace($en_month, $ru_month, $date);
	}

	public function randText($len){
	    $str = 'abcdefghijklmnopqrs01265892tuvwxyzABCDEFGHIJKLMNOPQRSTUVWXYZ_-';
	    for($i=0;$i<$len;$i++){
	        $txt .= substr($str, rand(0, strlen($str)), 1);   
	    }
        return $txt;
	}

	public function getPlaylist($getID){
		$playlist = mysqli_fetch_assoc(mysqli_query($this->m, "SELECT * FROM playlists LEFT JOIN users ON userID = playlistBy WHERE playlistGetID = '$getID'"));
		return $playlist;
	}

	public function text($value){
		$value = htmlspecialchars($value);
		$value = trim($value);
		$value = stripcslashes($value);
		return $value;
	}

	public function updateDuration($duration) {
        
        $hours = floor($duration / 3600);
        $mins = floor(($duration - ($hours*3600)) / 60);
        $secs = floor($duration % 60);
        
        $hours = ($hours < 1) ? "" : $hours . ":";
        $mins = ($mins < 10) ? "0" . $mins . ":" : $mins . ":";
        $secs = ($secs < 10) ? "0" . $secs : $secs;
        $duration = $hours.$mins.$secs;

        return $duration;
    }

    function getMetubeID($url){
        $longUrlRegex = '/metubee.xyz\/((?:embed)|(?:watch))((?:\?v\=)|(?:\/))([a-zA-Z0-9_-]+)/i';

        if (preg_match($longUrlRegex, $url, $matches)) {
            $metube = $matches[count($matches) - 1];
            return $metube;
        }
    }

    public function count($how, $table, $condition, $result){
    	if($how == 'int'){
			$counted = mysqli_fetch_assoc(mysqli_query($this->m, "SELECT COUNT(*) AS total FROM $table WHERE $condition = $result"));
    	}else{
    		$counted = mysqli_fetch_assoc(mysqli_query($this->m, "SELECT COUNT(*) AS total FROM $table WHERE $condition = '$result'"));
    	}
    	return $counted['total'];
    }

    public function getVideoDuration($filePath) {
        return shell_exec("$this->ffprobe -v error -show_entries format=duration -of default=noprint_wrappers=1:nokey=1 $filePath");
    }

    public function addView($videoID){
    	$query = mysqli_query($this->m, "UPDATE videos SET videoViews = videoViews + 1 WHERE videoID = $videoID");
    	$v = mysqli_fetch_assoc(mysqli_query($this->m, "SELECT * FROM videos WHERE videoID = $videoID"));
    	$vidBy = $v['videoBy'];
    	$queryUser = mysqli_query($this->m, "UPDATE users SET views = views + 1 WHERE userID = $vidBy");
    }

	public function uploadImage($file){
	$filename   = $file['name'];
	$fileTmp    = $file['tmp_name'];
	$fileSize   = $file['size'];
	$errors     = $file['error'];

	$ext = explode('.', $filename);
	$ext = strtolower(end($ext));

	$allowed_extensions  = array('jpg','png','jpeg');

	if(in_array($ext, $allowed_extensions)){
	  
	  if($errors ===0){
	      
	      if($fileSize <= 3793747637236){
	          $file_basename = substr($filename, 0, strripos($filename, '.'));
	          $file_ext = substr($filename, strripos($filename, '.')); 
	          $newfilename = md5($file_basename) . $file_ext;

	       $root = 'uploads/' .md5(md5(date('DMjGsTY'))).md5(md5(date('ljSofFYhisA'))).uniqid().md5(md5(date('DMjGsTY'))).md5(md5(date('ljSofFYhisA'))).md5(uniqid()).$newfilename;
	             move_uploaded_file($fileTmp,$_SERVER['DOCUMENT_ROOT'].'/'.$root);
	           return $root;

	      }else{
              
          }
	  }
	}else{
          
       }
	}

    public function generateThumbnails($filePath, $outputPath) {

        $numThumbnails = 1;
        $pathToThumbnail = $_SERVER['DOCUMENT_ROOT']."/uploads/";
        
        $duration = $this->getVideoDuration($filePath);
        $outputPath = $_SERVER['DOCUMENT_ROOT'].'/'.$outputPath;
        for($num = 1; $num <= $numThumbnails; $num++) {
            if($duration > 5){
                $interval = ($duration * 0.8) / $numThumbnails * $num;
            }else{
                $inteval = 0;
            }
            $interval = round($interval);
            $cmd = "$this->ffmpeg -i $filePath -ss $interval -vf scale=320:-1 -vframes 1 $outputPath 2>&1";
            $outputLog = array();
            exec($cmd, $outputLog, $returnCode);
            if($returnCode != 0) {  // command failed
                foreach($outputLog as $line) {
                    return 'err';
                }
            }
        }
    }

    public function videoData($videoGetID){
    	$video = mysqli_fetch_assoc(mysqli_query($this->m, "SELECT * FROM videos LEFT JOIN users ON userID = videoBy WHERE videoGetID = '$videoGetID'"));
    	return $video;
    }

	public function convertVideoToMp4($tempFilePath, $finalFilePath) {
        $cmd = "$this->ffmpeg -i $tempFilePath $finalFilePath 2>&1";
        $outputLog = array();
        exec($cmd, $outputLog, $returnCode);
        
        if($returnCode != 0) {  // command failed
            foreach($outputLog as $line) {
                return 'err';
            }
        }
    }

	public function check($table, $condition, $result){
		$i = mysqli_num_rows(mysqli_query($this->m, "SELECT * FROM $table WHERE $condition = '$result'"));
		if($i == 0){
			return false;
		}else{
			return true;
		}
	}

	public function checkSubscribe($to, $from){
		$i = mysqli_num_rows(mysqli_query($this->m, "SELECT * FROM subscriptions WHERE subscribeTo = $to AND subscribeBy = $from"));
		if($i == 0){
			return false;
		}else{
			return true;
		}
	}

	public function userData($how, $data){
		if($how == 1){
			$user = mysqli_query($this->m, "SELECT * FROM users WHERE userID = $data");
			$data = mysqli_fetch_assoc($user);
			return $data;
		}else if($how == 2){
			$user = mysqli_query($this->m, "SELECT * FROM users WHERE username = '$data'");
			$data = mysqli_fetch_assoc($user);
			return $data;
		}else if($how == 3){
			$user = mysqli_query($this->m, "SELECT * FROM users WHERE email = '$data'");
			$data = mysqli_fetch_assoc($user);
			return $data;
		}
	}

	public function checkCommentLike($comment, $user){
		$i = mysqli_num_rows(mysqli_query($this->m, "SELECT * FROM likes_comments WHERE likeTo = $comment AND likeFrom = $user"));
		if($i == 0){
			return false;
		}else{
			return true;
		}
	}

	public function checkLike($video, $user){
		$i = mysqli_num_rows(mysqli_query($this->m, "SELECT * FROM likes WHERE likeVid = $video AND likeFrom = $user"));
		if($i == 0){
			return false;
		}else{
			return true;
		}
	}

	public function checkCommentDislike($comment, $user){
		$i = mysqli_num_rows(mysqli_query($this->m, "SELECT * FROM dislikes_comments WHERE dislikeTo = $comment AND dislikeFrom = $user"));
		if($i == 0){
			return false;
		}else{
			return true;
		}
	}

	public function checkDislike($video, $user){
		$i = mysqli_num_rows(mysqli_query($this->m, "SELECT * FROM dislikes WHERE dislikeVid = $video AND dislikeFrom = $user"));
		if($i == 0){
			return false;
		}else{
			return true;
		}
	}

	public function getMention($text){
        $text = preg_replace("/@([\w]+)/", "<a href='/$0'>$0</a>", $text);
        return $text;		
    }

    public function watchLaterCheck($userID, $videoID){
    	$i = mysqli_num_rows(mysqli_query($this->m, "SELECT * FROM watch_later WHERE wlTo = $userID AND wlVideo = $videoID"));
    	if($i != 0){
    		return 'add-to-button-video-success';
    	}
    }
}
?>