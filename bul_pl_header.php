<?php

include('bul_pl_header.html') ;

/* ----- some important variables ------- */
$bul_site_name = $_REQUEST['z'] ; // there may or may not be a z value appended to the URL
$config_file_location = "" ;
$config_rules_source = "" ;
/* ------------ */

if(isset($bul_site_name)){

	switch($bul_site_name){
		case "bdr":
			$config_file_location = "https://library.brown.edu/common/includes/configs/bdr.cfg" ;
			break ;
	
		case "ocra":
			$config_file_location = "https://library.brown.edu/common/includes/configs/ocra.cfg" ;
			break ;
	
		case "josiah":
			$config_file_location = "https://library.brown.edu/common/includes/configs/josiah.cfg" ;		
			break ;
	}

	$config_rules_source = file_get_contents($config_file_location) ;
	$config_rules = json_decode($config_rules_source, true) ;
	
	foreach($config_rules as $key => $value) {
		if($key == 'background_color'){
			$background_color = $value ;
		}
		elseif($key == 'link_color'){
			$link_color = $value ;
		}

		elseif($key == 'site_title'){
			$site_title = $value ;
			$site_title_nospaces = preg_replace('/\s+/', '_', $site_title) ;
		}
		elseif($key == 'site_url'){
			$site_url = $value ; 
		}
		elseif($key == 'links') {
	    	$subnav_links = "<nav id='" . $site_title_nospaces . "_menu'><ul id='bul_pl_subheader_links'>" ;
	    	foreach($value as $link) {			
				if (array_key_exists('links', $link)) {
					 $subnav_links .= "<li class='bul_pl_subheader_links_trigger'><a href='$link[url]'>$link[label]&nbsp;&nbsp;&dtrif;</a>" ;
					// is there a url for the subnav link above? Should we check? Or just leave it?
					$subnav_links .= "<div class='bul_pl_subheader_sublinks_container'><ul class='bul_pl_subheader_sublinks'>" ;
					foreach($link[links] as $sublink){
					    $subnav_links .= "<li><a href='$sublink[url]'>$sublink[label]</a></li>" ;
					}
					$subnav_links .= "</ul></div>" ;
				}else{
					$subnav_links .= "<li><a href='$link[url]'>$link[label]</a></li>" ;
				}
	    	}
	    	$subnav_links .= "</ul></nav>" ;
	    }
	  }
	  
	echo "	
		<style>
		
		div, ul {
			border : 0px solid ; 
		}
		
		#bul_pl_subheader {
			background-color : $background_color ; 
			padding: 0px 0px 0px 25px ; 
			font-family : CircularStd ; 
			font-size : 13px ;		
			box-sizing : border-box ;
			width : 100% ; 
			line-height : 1 ; 
			display : grid ; 
			grid-template-columns: auto auto ;
			grid-template-rows: auto;
			position: -webkit-sticky; /* Safari */
			position: sticky;
			top: 60px;
		}

		#bul_pl_subheader a:focus {
			outline : none ;
			border-bottom : 1px solid ; 
		}
		
		#bul_pl_subheader a {
			text-decoration : none ;
			color : $link_color ;
		}

		#bul_pl_subheader a:hover {
			color : var(--brown_red) ; 
		}
		
		#bul_pl_subheader_site_title {
			display : inline-block ; 
			padding : 13px 0px ;
		}		
		
		#bul_pl_subheader_site_links {
			display : inline-block ;
			text-align : right ; 
			padding-right : 25px ;
		}

		#bul_pl_subheader_links {
			width : 100% ; 
			box-sizing : border-box ;
			display : inline-block ; 
		}
		
		#bul_pl_subheader_links>li {
			padding : 0px 15px ; 
			box-sizing : border-box ;
			display : inline-block ;
		}
		
		#bul_pl_subheader_links>li a {
			color : var(--brown_brown) ;
			text-decoration : none ; 
		}
		
		.bul_pl_subheader_sublinks_container {
			display : none ;
			position : absolute ;
			box-sizing : border-box ;
		}
		
		.bul_pl_subheader_sublinks {
			min-width : 160px ;
			box-shadow : 0px 8px 16px 0px rgba(0,0,0,0.2) ;
			padding : 0px 10px 0px 10px ;
			z-index : 0 ;
			box-sizing : border-box ;
			margin-left : -10px ; 
			margin-top : 10px ; 
			text-align : left ; 
			background-color : #fff ; 
			list-style-type : none ;
		}
		
		.bul_pl_subheader_sublinks li {
			border-bottom : 1px solid #F0F2F3 ;
			padding : 20px 0px ;
		}
		
		.bul_pl_subheader_sublinks li a {
			text-decoration : none ; 
			color : var(--brown_brown) ; 
		}
		
		.bul_pl_subheader_links_trigger:hover .bul_pl_subheader_sublinks_container, 
		.bul_pl_subheader_links_trigger:focus-within .bul_pl_subheader_sublinks_container {
		  display : block ;
		  box-sizing : border-box ;
		}

		</style>
	
		<div id='bul_pl_subheader'>
			<div id='bul_pl_subheader_site_title'>
				<a href='$site_url'>$site_title</a>
			</div>
			<div id='bul_pl_subheader_site_links'>
				$subnav_links	
			</div>
		</div>
	" ;
}


?>
