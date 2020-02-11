<?php

include('bul_pl_header.html') ;

/* ----- all our variables ------- */
$bul_site_name = $_REQUEST['z'] ; // there may or may not be a z value appended to the URL
$config_file_location = "" ;
$config_rules_source = "" ;
$config_rules = "" ;
$subheader_content = "" ;
/* ------------ */

if(isset($bul_site_name)){

	switch($bul_site_name){
		case "bdr":
			$config_file_location = "https://library.brown.edu/common/includes/configs/bdr.cfg" ;
// 			echo "BDR" ;
		break ;
	
		case "ocra":
			//rules
		break ;
	
		case "josiah":
			//rules
		break ;
	}

	$config_rules_source = file_get_contents($config_file_location) ;
	$config_rules = json_decode($config_rules_source, true) ;
	
	foreach($config_rules as $key => $value) {
		if($key == 'custom_color'){
			$custom_color = $value ;
		}
		if($key == 'site_title'){
			$site_title = $value ;
		}
		if($key == 'site_url'){
			$site_url = $value ; 
		}

	    if($key == 'subheader_links') {
	    	foreach($value as $subkey => $subvalue) {
	    		$links_list .= "<dd style='display : inline-block ; '><a href='$subvalue' style='color : var(--brown-brown) ; text-decoration : none ; ' >$subkey</a></dd>" ;
	    	}
	    }
	  }
	  
	echo "
		<div id='bul_pl_subheader' style='background-color : $custom_color ; padding: 0px 0px 0px 25px ; font-family : CircularStd ; font-size : 13px ; '>
			<div id='bul_pl_subheader_site_title' style='display : inline-block ; '>
				<a href='$site_url' style='text-decoration : none ; color : var(--brown-brown) ; '>$site_title</a>
			</div>
			<div id='bul_pl_subheader_site_links' style='display : inline-block ; '>
				<dl style='display : inline-block ; '>$links_list</dl>
			</div>
		</div>
	" ;
}


?>
