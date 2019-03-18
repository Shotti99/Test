<?php 
	if (!empty($_SESSION['theme'])){
		if ($_SESSION['theme']==2) $th='default';
		if ($_SESSION['theme']==1) $th='2';
    }
    else{
    	$th='2';
    }
define('CSS_URL', 'style/'.$th.'.css');
define('TOPC_STYLE','q');
define('MIDDLEC_STYLE','a');
define('DOWNC_STYLE','down_c');
?>