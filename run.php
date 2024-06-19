<?php
chdir(__DIR__);
$fn = __DIR__ . '/settings.json';
if(!file_exists($fn)) {
	die("please copy settings.json.dist to settings.json and configure.\n");
}
$settings = json_decode(file_get_contents($fn), true);

$neccessary = [
	"import",
	"compare",
	"wmctrl",
	"awk",
	"tesseract",
];
foreach($neccessary as $one) {
	$E = trim(exec("which ".$one));
	if($E=="") die("please install ".$one."\n");
}

$res = exec('ps aux | grep -v grep | grep "screenshotscreens.sh"');
if($res!="") {
	die("already running! \n");
}

$pid = exec(__DIR__ . '/screenshotscreens.sh '.$settings["screenshots"]." ".$settings["interval"].' run "'.implode(",", $settings["exclude"]).'" > /dev/null 2>&1 & echo $!;');

exec("php -S localhost:".$settings["port"]." -t ".__DIR__."/app > /dev/null 2>&1 &");

echo "ScreenRecall running on port ".$settings["port"]."\nhttp://localhost:".$settings["port"]."\n";
?>