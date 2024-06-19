<?php
if(isset($_REQUEST["action"])) {

	include "action.php";
	echo callAction($_REQUEST["action"]);
	return;
}
?>
<!DOCTYPE html>
<html lang="en">
<head>
	<meta charset="utf-8">
	<title>ScreenRecall-View</title>

	<meta http-equiv="Content-Type" content="text/html; charset=UTF-8">
	<meta name="viewport" content="width=device-width, initial-scale=1">
	<meta http-equiv="X-UA-Compatible" content="IE=edge">

	<link rel="stylesheet" href="https://cdn.jsdelivr.net/npm/bootstrap@5.3.2/dist/css/bootstrap.min.css" integrity="sha384-T3c6CoIi6uLrA9TneNEoa7RxnatzjcDSCmG1MXxSR1GAsXEV/Dwwykc2MPK8M2HN" crossorigin="anonymous">

	<script src="https://code.jquery.com/jquery-3.6.0.min.js"></script>
	<link rel="stylesheet" href="https://cdnjs.cloudflare.com/ajax/libs/font-awesome/5.15.4/css/all.min.css"/>

	<link rel="stylesheet" href="styles.css"/>

</head>
<body>

<nav class="navbar navbar-expand-md navbar-dark fixed-top bg-dark">
	<div class="container-fluid">
		<a class="navbar-brand" href="#">ScreenRecall</a>
		<button class="navbar-toggler" type="button" data-bs-toggle="collapse" data-bs-target="#navbarCollapse" aria-controls="navbarCollapse" aria-expanded="false" aria-label="Toggle navigation">
			<span class="navbar-toggler-icon"></span>
		</button>
		<div class="collapse navbar-collapse" id="navbarCollapse">
			<ul class="navbar-nav me-auto mb-2 mb-md-0">
				<!--<li class="nav-item">
					<a class="nav-link active" aria-current="page" href="#">Today</a>
				</li>
				<li class="nav-item">
					<a class="nav-link" href="#">Yesterday</a>
				</li>
				<li class="nav-item">
					<a class="nav-link" href="#" tabindex="-1">Choose day</a>
				</li>-->
			</ul>
			<form class="d-flex">
				<input class="form-control me-2 recallsearch" type="search" placeholder="Search" aria-label="Search">
<!--				<button class="btn btn-outline-success" type="submit">Search</button>-->
			</form>
		</div>
	</div>
</nav>

<main class="container-fluid">

	<?php
	$settings = json_decode(file_get_contents(__DIR__ . '/../settings.json'), true);
	$folders = glob($settings["screenshots"].'/session_'.date("Ymd")."_*", GLOB_ONLYDIR);
	$data = [];
	foreach($folders as $folder) {
		$T = substr($folder, -6);
		$data[] = [
			"folder" => $folder,
			"time" => substr($T,0,2).":".substr($T,2,2).":".substr($T,4,2),
			"value" => count(glob("{".$folder."/*.png,".$folder."/*.jpg}", GLOB_BRACE)),
		];
	}
	#var_dump($data);
	?>

	<div id="timeline-container">
		<div id="timeline"></div>
	</div>

	<div class="bg-light p-5 rounded screens" style="padding-top:5px !important;">



	</div>

	<div id="imageModal" class="modal">
		<span class="close" onclick="closeModal()">&times;</span>
		<div class="modal-content" id="modalImage"></div>
	</div>

</main>

<script>
	const timestamps = <?= json_encode($data);?>;
</script>

<script src="script.js"></script>
<script src="https://cdn.jsdelivr.net/npm/bootstrap@5.3.2/dist/js/bootstrap.min.js" integrity="sha384-BBtl+eGJRgqQAUMxJ7pMwbEyER4l1g+O15P+16Ep7Q9Q+zqX6gSbd85u4mG4QzX+" crossorigin="anonymous"></script>

<script>

</script>

</body>
</html>