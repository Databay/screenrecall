<?php
function callAction($action) {
	$settings = json_decode(file_get_contents(__DIR__ . '/../settings.json'), true);

	$html = "";
	if($action=="search") {
		$txts = glob($settings["screenshots"]."/*/*.ocr.txt" );
		$html = "";
		$q = explode(" ", $_REQUEST["q"]);
		$anz=0;
		foreach(array_reverse($txts) as $txt) {
			if(is_link($txt)) continue;
			$content = file_get_contents($txt);
			$found = true;
			foreach($q as $one) {
				if(!stristr($content, $one)) {
					$found = false;
					break;
				}
			}
			if($found) {
				$anz++;
				$screen = substr($txt,0, -8);
				$html .= "<img src='index.php?action=screen&file=".$screen."' onclick='openModal(this);' class='thumbnail' style='margin:0 20px 20px 0;box-shadow: 0px 0px 10px 1px rgba(0, 0, 0, .1);'>";
				if($anz>100) break;
			}
		}
		$html = '<div class="image-gallery">'.$html.'</div>';
	}
	if($action=="screens") {
		$T = substr($_REQUEST["folder"], -6);
		$time = substr($T,0,2).":".substr($T,2,2).":".substr($T,4,2);
		//$html .= "<h3>".$time."</h3>";
		$screens = glob("{".$_REQUEST["folder"]."/*.png,".$_REQUEST["folder"]."/*.jpg}", GLOB_BRACE);
		#var_dump($screens);
		foreach($screens as $screen) {
			#if(is_link($screen)) continue;
			$html .= "<img src='index.php?action=screen&file=".$screen."' onclick='openModal(this);' class='thumbnail' style='margin:0 20px 20px 0;box-shadow: 0px 0px 10px 1px rgba(0, 0, 0, .1);'>";
		}
		$html = "<h3>".$time."</h3>".'<div class="image-gallery">'.$html.'</div>';

	}
	if($action=="screen") {
		$html = file_get_contents($_REQUEST["file"]);
	}
	return $html;
}
?>