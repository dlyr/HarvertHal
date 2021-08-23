<?php
/**
 * Template Name: Publi from hal
 * @version 0.0.0.1
 */
get_header(); 

echo '<div id="content" class="medium-12 large-12 columns" role="main">';

if ( function_exists('yoast_breadcrumb') ) { yoast_breadcrumb('<nav aria-label="You are here:" role="navigation"> <ul class="breadcrumbs">','</ul>'); } 
  
echo '<div class="entry-content-page">';


function curl_download($Url){
  // is cURL installed yet?
  if (!function_exists('curl_init')){
    die('Sorry cURL is not installed!');
  }
  
  // OK cool - then let's create a new cURL resource handle
  $ch = curl_init();
 
  // Now set some options (most are optional)
  // Set URL to download
  curl_setopt($ch, CURLOPT_URL, $Url);
  // Include header in result? (0 = yes, 1 = no)
  curl_setopt($ch, CURLOPT_HEADER, 0);
  // Should cURL return or print out the data? (true = return, false = print)
  curl_setopt($ch, CURLOPT_RETURNTRANSFER, true);
  // Timeout in seconds
  curl_setopt($ch, CURLOPT_TIMEOUT, 10);
  // Download the given URL, and return output
  $output = curl_exec($ch);
  // Close the cURL resource, and free system resources
  curl_close($ch);
 
  return $output;
}


function checkField($array, $field){
  return(isset($array[$field])&&$array[$field]!="");
}

function printPubli($p){	
		
	echo '<div class="publi">';

  $lowtitle = strtolower($p["title_s"][0]);

  $acmlink["dynamic stylized shading primitives"] = "http://dl.acm.org/authorize?N00392";
  $acmlink["a dynamic drawing algorithm for interactive painterly rendering"] = "http://dl.acm.org/authorize?4297";
  $acmlink["constrained palette-space exploration"] ="http://dl.acm.org/authorize?N42654";
	
  $projectpage["hal-02174327"] = "https://www.irit.fr/STORM/site/global-illumination-shadow-layers/";
  $projectpage["hal-01538733"] = "https://www.irit.fr/STORM/site/constrained-palette-space-exploration/";
  $projectpage["hal-02939477"] = "https://cragl.cs.gmu.edu/sketchbench/";
	
	echo '<div class="image">';
	if(file_exists("./data/thumbnails/".$p["halId_s"].".jpg")) {
		echo '<img src="http://www.dlyr.fr/stuff/data/thumbnails/'.$p["halId_s"].'.jpg" alt="publication icone"/>';
  	}
  	else if(file_exists("./data/thumbnails/".$p["halId_s"].".png")) {
		echo '<img src="http://www.dlyr.fr/stuff/data/thumbnails/'.$p["halId_s"].'.png" alt="publication icone"/>';
  	}
	else if($p["halId_s"] == "hal-02939477"){
		echo '<img src="https://cragl.cs.gmu.edu/sketchbench/teaser.jpg" alt="publication icone"/>';}
  	else if(checkField($p,"thumbId_i")) {
    	echo '<img src="https://thumb.ccsd.cnrs.fr/'.$p["thumbId_i"].'/" alt="publication icone"/>';}
  	else {echo '<img src="http://www.dlyr.fr/stuff/data/dummy.jpg" alt="publication icone"/>';}
	
  	echo '</div> '; //image
          
	
  echo '<div class="notice">';
  echo '<div class="title">';
  if($p["uri_s"] != "") echo '<a href="'.$p["uri_s"].'">'.$p["title_s"][0].'</a>';
  else echo $p["title_s"][0];
  echo '</div> ';
	
  echo '<div class="authors">';
  $c = count($p["authFullName_s"]);
  $i = 1;
  foreach ($p["authFullName_s"] as $key=>$author){
    $p["authFirstNameIni_s"][$key] = substr($p["authFirstName_s"][$key],0,1).".";
    echo $p["authFirstNameIni_s"][$key]." ".$p["authLastName_s"][$key];
    if($i++ < $c) echo ", ";
  }
  echo'</div>';
  
  echo '<div class="infos">';
  if(checkField($p,"source_s")){ echo $p["source_s"];
    $how = "source_s";
  }
  else if(checkField($p,"journalTitleAbbr_s")){ echo $p["journalTitleAbbr_s"];
    $how = "journalTitleAbbr_s";
  }
  else if(checkField($p,"journalTitle_s")) {echo $p["journalTitle_s"];
    $how = "journalTitle_s";
  }
  else if(checkField($p,"conferenceTitle_s")) {echo $p["conferenceTitle_s"];
    $how = "conferenceTitle_s";
  }
  else if(checkField($p,"bookTitle_s")) {echo $p["bookTitle_s"];
    $how = "bookTitle_s";
  }
  else if(checkField($p,"docType_s")){
    if($p["docType_s"] === "THESE"){ echo "PhD. Thesis, " . $p["authorityInstitution_s"][0]; 	$how= "docType_s"; }
    
    if($p["docType_s"] === "REPORT"){ echo "Research Report, " . $p["authorityInstitution_s"][0];	$how= "docType_s"; }
    
    if($p["docType_s"] === "MEM"){ echo "Master Thesis, " . $p["authorityInstitution_s"][0];	$how="docType_s"; }    
  }
  
  $printYear = true;
  if(isset($how)){
    if(!(strpos($p[$how], strval($p["producedDateY_i"]))===false)) $printYear = false;
  }
  if($printYear) {
    if(isset($how)) echo ", ";
    echo $p["producedDateY_i"];
  }

  if(checkField($p, "comment_s")){
    echo '.  <span class="note">'.$p["comment_s"].'</span>';
  }
  if(checkField($p, "description_s")){
    echo '.  <span class="note">'.$p["description_s"].'</span>';
  }
	  	echo '</div>'; //infos

  echo '<div class="links">';
  if(checkField($p, "fileMain_s")){
    echo '<a href="'.$p["fileMain_s"].'"><img src="http://www.dlyr.fr/data/Haltools_pdf.png" width="16" height="16" border="0" alt="pdf download"/></a>';
    if(isset($acmlink[$lowtitle])){
      echo '<a href="'.$acmlink[$lowtitle].'"><img src="http://dl.acm.org/images/oa.gif" width="16" height="16" border="0" alt="ACM DL Author-ize service" style="vertical-align:middle"/></a>';
    }
  }
	
	  if(checkField($p, "seeAlso_s"))
	  {
		  echo ' -';
		  foreach ($p["seeAlso_s"] as &$value) {
			  echo '[<a href="'.$value.'">project page</a>]';
			}
		  echo '-';
 	  }
	
	echo '</div>'; //links
	echo '</div>'; //notice
	echo '</div>'; //publi
echo "\n";
 // echo '<div class="clear_flt"></div>';
}

$json = curl_download(
		      //"https://api.archives-ouvertes.fr/search/?q=%20authIdHal_s:%22vdh%22&wt=json&indent=true&fl=title_s,authFullName_s,docType_s,journalTitle_s,conferenceTitle_s,fileMain_s,authUrl_s,thumbId_i,producedDateY_i");
		      "https://api.archives-ouvertes.fr/search/?q=%20authIdHal_s:%22vdh%22&wt=json&indent=true&fl=halId_s,source_s,description_s,authorityInstitution_s,bookTitle_s,page_s,title_s,authFullName_s,docType_s,journalTitle_s,conferenceTitle_s,fileMain_s,uri_s,authLastName_s,authFirstName_s,thumbId_i,producedDateY_i,comment_s,fileAnnexes_s,seeAlso_s&sort=producedDate_tdate%20desc&rows=1000"
		      );
$publis = json_decode($json, true);

echo '<h1 class="sec_title">Publications</h1>';
echo
'<div class=“sec_content”>My publication list is also available directly on <a href="https://hal.archives-ouvertes.fr/search/index/q/%2A/authIdHal_s/vdh/">HAL</a>.</div>';


echo '<h3>Link to ACM Author-ize</h3>';
echo 'The <img src="http://dl.acm.org/images/oa.gif" width="25" height="25" border="0" alt="ACM DL Author-ize service" style="vertical-align:middle"/> images links to the ACM version, with author-ize allowance, so you can download the published version free of charge from ACM.';
    
$year = $publis["response"]["docs"][0]["producedDateY_i"];
echo '<h2 class="subsec_title">'.$year.'</h2>';
echo '<div class="sec_content">';

foreach($publis["response"]["docs"] as $p){
  if($year != $p["producedDateY_i"]){
    $year = $p["producedDateY_i"];	
    echo '</div>';
    
    echo '<h2 class="subsec_title">'.$year.'</h2>';
    echo '<div class="sec_content">';
  }
  printPubli($p);
}

echo '</div>';
echo '</div>';
echo '</div><!-- #content -->';

get_footer(); ?>
