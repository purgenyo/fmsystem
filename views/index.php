<!DOCTYPE html>
<html>
<head>
	<meta charset="utf-8">
	<title>Weather info</title>
<script src="//api-maps.yandex.ru/2.1/?lang=ru_RU" type="text/javascript"></script>
<script src="https://ajax.googleapis.com/ajax/libs/jquery/1.12.0/jquery.min.js"></script>
<script src="assets/modal/dist/remodal.min.js"></script>

<link rel="stylesheet" href="assets/modal/dist/remodal.css">
<link rel="stylesheet" href="assets/modal/dist/remodal-default-theme.css">

<style>
    html, body, #map {
        width: 100%; height: 100%; padding: 0; margin: 0;
    }
</style>
</head>
<body>

<div id="modals" class="remodal" data-remodal-id="modal">
	<button data-remodal-action="close" class="remodal-close"></button>
	<h1 class="modal_content"></h1>
	<p>
		<div class="preloader" style="display:none;">
			<img src="assets/pre.gif">
		</div>

		<div id="weather_chart"></div>
	</p>
	<br>
	<button data-remodal-action="cancel" class="remodal-cancel">Проваливай</button>
	<button data-remodal-action="confirm" class="remodal-confirm">OK</button>
</div>

<div id="map"></div>
<script type="text/javascript">
	
	var city_data = JSON.parse('<?=$data['json_result']?>');
	
	function showModalWeather(city) {
		$('.preloader').show();

		var request = $.ajax({
		  url: "/ngs/?a=stats",
		  method: "GET",
		  data: { city : city },
		});
	
		request.done(function( data ) {
			$('.preloader').hide();
			document.getElementById('weather_chart').innerHTML = '';
			var chart = initChart('weather_chart');
			var chart_2 = initChart('weather_chart');
			chart.setData(data);
		});
		 
		request.fail(function( jqXHR, textStatus ) {
			alert( "Request failed: " + textStatus );
		});
	}

</script>

	<script src="assets/maps.js" type="text/javascript"></script>
	<script src="assets/d3.min.js" type="text/javascript"></script>
	<script src="assets/chart.js" type="text/javascript"></script>
	<style type="text/css">
		path { 
		    stroke: steelblue;
		    stroke-width: 2;
		    fill: none;
		}

		.axis path,
		.axis line {
		    fill: none;
		    stroke: grey;
		    stroke-width: 1;
		    shape-rendering: crispEdges;
		}
	</style>
</body>
</html>