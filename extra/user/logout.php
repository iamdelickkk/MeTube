<?php
if(isset($_COOKIE['MeTubeUID'])):
    setcookie('MeTubeUID','',time()-7000000,'/');
    setcookie('MeTubeUID','',time()-7000000,'/user');
    setcookie('MeTubeUPassword','',time()-7000000,'/');
endif;
header('Location: ../../');
?>