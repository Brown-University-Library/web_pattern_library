<?php

include('bul_pl_header.html') ;

/* ----- some important variables ------- */
$bul_site_name = $_REQUEST['z'] ; // there may or may not be a z value appended to the URL
$configs_raw = "" ;
$config_file_location = "" ;
$config_rules_source = "" ;
$subnav_links = "" ;
$link_count = 0 ;
$trigram_display = "" ;
/* ------------ */
if(isset($bul_site_name)){

	// the f_g_c function below has an offset because the file is stored on disk with a "<?php" declaration at the beginning, so it won't be accessible by URL in a browser. The offset of 5 skips that PHP declaration when grabbing this file
	$configs_raw = file_get_contents('/var/www/html/common/includes/configs/bul_site_names.php', FALSE, NULL, 5) ;
	$configs_json_all = json_decode($configs_raw, true) ; 
	$configs_json = $configs_json_all[cfg_map] ;

	$config_file_location = $configs_json[$bul_site_name] ;

	// get the config file as a string and turn it into a JSON object represented as an array
	$config_rules_source = file_get_contents($config_file_location) ;
	$config_rules = json_decode($config_rules_source, true) ;
	
	// evaluate each JSON rule and assign values to reusable strings
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
				// used as a unique CSS id, but CSS ids can't have spaces
				$site_title_nospaces = preg_replace('/\s+/', '_', $site_title) ;
				break ;

			case "site_short_title":
				// used if needed between the words 'my' and 'account' (so, 'My BDR account' where short title for the Brown Digital Repository is 'bdr') 
				$site_short_title = $value ; 
				break ;

			case "site_url":
				$site_url = $value ; 
				break ;

			case "shib_site":
				// either 'yes' or not yes
				$shib_site = $value ; 
				break ;

			case "links":

			
				// evaluate each item in the 'links' JSON object
				foreach($value as $link) {	
					
					// this is a special case to toggle the a login/my account link for shib-enabled visitors...need to write JS to hide or show by ID if logged in
					if($shib_site == "yes" && $link[label] == "My Account") {
						$link[label] = "My $site_short_title Account" ;
						
						$subnav_links .= "<li class='bul_pl_subheader_links_trigger' id='bul_pl_subheader_myaccount'><a href='$link[url]' style='text-decoration : none ; color : $link_color ;'>$link[label]&nbsp;&nbsp;&dtrif;</a>" ;
						$subnav_links .= "<div class='bul_pl_subheader_sublinks_container'><ul class='bul_pl_subheader_sublinks'>" ;
						
						++$count ; 
						
						foreach($link[links] as $sublink){
							$subnav_links .= "<li><a href='$sublink[url]'>$sublink[label]</a></li>" ;
							
							++$count ; 
							
						}
						$subnav_links .= "</ul></div></li>" ;
					}					
					elseif($shib_site == "yes"  && $link[label] == "Login") {
						$subnav_links .= "<li id='login' class='bul_pl_subheader_single_link'><a href='$link[url]' style='text-decoration : none ; color : $link_color ;'>$link[label]</a></li>" ;
						
						++$count ; 
						
					}
					// end special case	
					
					// if the 'links' item contains another 'links' item, to make a submenu
					elseif(array_key_exists('links', $link)) {
						// grab the top level label (which will be a dead link, but will be the header for the submenu)
						$subnav_links .= "<li class='bul_pl_subheader_links_trigger'><a href='javascript:;' onclick='javascript:void(0)' style='text-decoration : none ; color : $link_color ;'>$link[label]&nbsp;&nbsp;&dtrif;</a>" ;
						//  $trigram_menu_links .= "<li class='bul_pl_subheader_trigram_links'><a href='$link[url]' style='text-decoration : none ; color : $link_color ;'>$link[label]</a>" ;
						
						//create the submenu as a list
						$subnav_links .= "<div class='bul_pl_subheader_sublinks_container'><ul class='bul_pl_subheader_sublinks'>" ;
						foreach($link[links] as $sublink){
							$subnav_links .= "<li><a href='$sublink[url]'>$sublink[label]</a></li>" ;
							
							++$count ; 
							
							// $trigram_menu_links .= "<li class='bul_pl_subheader_trigram_links'><a href='$sublink[url]'>$sublink[label]</a></li>" ;
						}
						$subnav_links .= "</ul></div></li>" ;	
					}
					
					// if there's no submenu, and it's just a single link
					else{
						$subnav_links .= "<li class='bul_pl_subheader_single_link'><a href='$link[url]' style='text-decoration : none ; color : $link_color ;'>$link[label]</a></li>" ;
						
						++$count ; 
						
						// $trigram_menu_links .= "<li class='bul_pl_subheader_trigram_links'><a href='$link[url]'>$link[label]</a></li>" ;
					}
				}
				
				$trigram_display = "<div id='bul_pl_subheader_trigram'>
						  <label for='bul_pl_subheader_trigram_checkbox' class='bul_pl_subheader_trigram_toggle'>
							&#9776;
						  </label>
						  <input type='checkbox' id='bul_pl_subheader_trigram_checkbox' class='bul_pl_subheader_trigram_toggle'>
						  <ul class='bul_pl_subheader_trigram_menu_contents'>$subnav_links</ul>
				</div>" ;
		
				
				break ;							
			}		
	  }
	  
	  	
	  
echo "
<!-- begin bul_pl_subheader -->
<div id='bul_pl_subheader' style='background-color : $background_color ; ' >
	<div id='bul_pl_subheader_site_title'>
		<a href='$site_url' style='color : $link_color ;'>$site_title</a>
		<a href='subheader_end' id='bul_pl_skip_subheader'>Skip $count subheader links</a>
	</div>
	<nav id='bul_pl_subheader_" . $site_title_nospaces . "_menu' aria-label='" . $site_title . " menu' class='bul_pl_subheader_nav_menu'>
		<div id='bul_pl_subheader_site_links'>
			$trigram_display
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
