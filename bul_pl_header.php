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

		case "ocradata":
			$config_file_location = "https://library.brown.edu/common/includes/configs/ocradata.cfg" ;
			break ;
	
		case "josiah":
			$config_file_location = "https://library.brown.edu/common/includes/configs/josiah.cfg" ;		
			break ;
			
		case "libguides":
			$config_file_location = "https://library.brown.edu/common/includes/configs/libguides.cfg" ;		
			break ;
	}

	$config_rules_source = file_get_contents($config_file_location) ;
	$config_rules = json_decode($config_rules_source, true) ;
	
	foreach($config_rules as $key => $value) {
		switch($key){		
			case "background_color":
				$background_color = $value ;
				break ;

			case "link_color":
				$link_color = $value ;
				break ;
				
			case "site_title":
				$site_title = $value ;
				$site_title_nospaces = preg_replace('/\s+/', '_', $site_title) ;
				break ;

			case "site_short_title":
				$site_short_title = $value ; 
				break ;

			case "site_url":
				$site_url = $value ; 
				break ;

			case "shib_site":
				$shib_site = $value ; 
				break ;

			case "links":
				$subnav_links = "" ;

				foreach($value as $link) {	
					
					// this is a special case to toggle the a login/my account link for shib-enabled visitors...need to write JS to hide or show by ID if logged in
					if($shib_site == "yes" && $link[label] == "My Account") {
						$link[label] = "My $site_short_title Account" ;
						
						$subnav_links .= "<li class='bul_pl_subheader_links_trigger' id='bul_pl_subheader_myaccount'><a href='$link[url]' style='text-decoration : none ; color : $link_color ;'>$link[label]&nbsp;&nbsp;&dtrif;</a>" ;
						$subnav_links .= "<div class='bul_pl_subheader_sublinks_container'><ul class='bul_pl_subheader_sublinks'>" ;
						foreach($link[links] as $sublink){
							$subnav_links .= "<li><a href='$sublink[url]'>$sublink[label]</a></li>" ;
						}
						$subnav_links .= "</ul></div></li>" ;
					}					
					elseif($shib_site == "yes"  && $link[label] == "Login") {
						$subnav_links .= "<li id='login' class='bul_pl_subheader_single_link'><a href='$link[url]' style='text-decoration : none ; color : $link_color ;'>$link[label]</a></li>" ;
					}
					// end special case	
												
					elseif(array_key_exists('links', $link)) {
						 $subnav_links .= "<li class='bul_pl_subheader_links_trigger'><a href='javascript:;' onclick='javascript:void(0)' style='text-decoration : none ; color : $link_color ;'>$link[label]&nbsp;&nbsp;&dtrif;</a>" ;
						//  $trigram_menu_links .= "<li class='bul_pl_subheader_trigram_links'><a href='$link[url]' style='text-decoration : none ; color : $link_color ;'>$link[label]</a>" ;
						$subnav_links .= "<div class='bul_pl_subheader_sublinks_container'><ul class='bul_pl_subheader_sublinks'>" ;
						foreach($link[links] as $sublink){
							$subnav_links .= "<li><a href='$sublink[url]'>$sublink[label]</a></li>" ;
							// $trigram_menu_links .= "<li class='bul_pl_subheader_trigram_links'><a href='$sublink[url]'>$sublink[label]</a></li>" ;
						}
						$subnav_links .= "</ul></div></li>" ;
					}else{
						$subnav_links .= "<li class='bul_pl_subheader_single_link'><a href='$link[url]' style='text-decoration : none ; color : $link_color ;'>$link[label]</a></li>" ;
						// $trigram_menu_links .= "<li class='bul_pl_subheader_trigram_links'><a href='$link[url]'>$link[label]</a></li>" ;
					}
				}
				$subnav_links .= "" ;
				break ;
			}		
	  }
	  
echo "
<!-- begin bul_pl_subheader -->


<div id='bul_pl_subheader' style='
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
	top: 60px;' >
		
	<div id='bul_pl_subheader_site_title'>
		<a href='$site_url' style='text-decoration : none ; color : $link_color ;'>$site_title</a>
	</div>
	<nav id='bul_pl_subheader_" . $site_title_nospaces . "_menu' class='bul_pl_subheader_nav_menu'>
		<div id='bul_pl_subheader_site_links'>

			<ul id='bul_pl_subheader_links'>
				$subnav_links
			</ul>	
		</div>
	</nav>
</div>
<!-- end bul_pl_subheader -->
" ;
}

?>
