<?php
// Take page with CURL
function get_timetable($group,$week){
	$ch = curl_init();
	curl_setopt($ch, CURLOPT_HEADER, 0);
	curl_setopt($ch, CURLOPT_RETURNTRANSFER, 1);
	curl_setopt($ch, CURLOPT_URL, "https://mai.ru/education/schedule/detail.php?group=".$group."&week=".$week);
	$data = curl_exec($ch);
	curl_close($ch);
	return $data;
}

// input group and week
$timetable = get_timetable("М6О-101Б-17","8");

//include lib for parsing HTML page
include('simple_html_dom.php'); 


$html = new simple_html_dom();
$html->load($timetable);
$chunk = $html->find(".sc-container");
for ($j=0; $j <= 6; $j++) { 
	unset($html);
	$html = new simple_html_dom();
	$html->load($chunk[$j]);
	$day = $html->find(".sc-day-header");
	$day_name = $html->find(".sc-day"); 
	$time = $html->find(".sc-item-time");
	$type = $html->find('.sc-item-type');
	$title = $html->find(".sc-title");
	$lecturer = $html->find(".sc-lecturer");
	$location = $html->find(".sc-item-location");
	echo substr($day[0]->innertext,0,5)." ";
	echo $day_name[0]->innertext."\n";
	for ($i=0; $i < 4; $i++) { 
		if ($time[$i]){
			echo str_replace ("&ndash;","-",$time[$i]->innertext)."\n";
			echo $type[$i]->innertext." ";
			echo $title[$i]->innertext." ";
			if ($lecturer[$i]) {
				echo $lecturer[$i]->innertext."\n";
			}
			echo substr($location[$i]->innertext,58);
			echo "\n\n";
		}
	}
}
?>
