

function createTimeline(timestamps) {
	const timeline = document.getElementById('timeline');
	const startTime = new Date(`1970-01-01T${timestamps[0].time}Z`).getTime();
	const endTime = new Date(`1970-01-01T${timestamps[timestamps.length - 1].time}Z`).getTime();

	timestamps.forEach((timestamp) => {
		const currentTime = new Date(`1970-01-01T${timestamp.time}Z`).getTime();
		const position = ((currentTime - startTime) / (endTime - startTime)) * 100;
		const size = Math.min(15,timestamp.value);
		const dot = document.createElement('div');
		dot.className = 'dot';
		dot.style.left = `${position}%`;
		dot.style.height = `${size}px`;
		dot.title = `Time: ${timestamp.time}, windows: ${timestamp.value}`;
		//dot.addEventListener('click', () => alert(`Timestamp: ${timestamp.time}, Value: ${timestamp.value}`));
		dot.addEventListener('click', function(event) {
			showtimestamp(event, timestamp);
		});

		timeline.appendChild(dot);
	});

	const marker = document.createElement('div');
	marker.className = 'marker';
	timeline.appendChild(marker);
}

document.addEventListener('DOMContentLoaded', () => createTimeline(timestamps));

function showtimestamp(event, element) {

	const marker = document.querySelector('.marker');
	const dot = event.target;

	marker.style.left = dot.style.left;
	marker.style.top = '10px';
	marker.style.display = 'block';

	$.ajax({
		"url": "index.php",
		"type": "post",
		"data": {"action": "screens", "folder": element.folder},
		"dataType": "html",
		"success": function(html) {
			$('.screens').html(html);
		}
	});
}

function openModal(element) {
	var modal = document.getElementById("imageModal");
	var modalImg = document.getElementById("modalImage");
	modal.style.display = "block";
	modalImg.src = element.src;
	$('#modalImage').css("background-image", "url(" + element.src+")");
}

function closeModal() {
	var modal = document.getElementById("imageModal");
	modal.style.display = "none";
}

$(function() {

	var lastsearch = "";
	var searchhandle = null;
	$(".recallsearch").on("keyup", function() {
		var q = $(this).val();
		if(lastsearch!=q) {
			if(searchhandle!=null) clearTimeout(searchhandle);
			searchhandle = setTimeout(function() {
				$.ajax({
					"url": "index.php?action=search",
					"type": "post",
					"data": {"q": lastsearch},
					"dataType": "html",
					"success": function(data) {
						$('.screens').html(data);
					}
				});
			}, 200);
			lastsearch = q;
		}
	});

});