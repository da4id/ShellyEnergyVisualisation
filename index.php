<?php

$Diesel = 0.00;
$Bleifrei = 0.00;

?>

<html>

<html lang="en">
<head>

    <!-- Standard Meta -->
  <meta charset="utf-8" />
  <meta http-equiv="X-UA-Compatible" content="IE=edge,chrome=1" />
  <meta name="viewport" content="width=device-width, initial-scale=1.0, maximum-scale=1.0">

  <!-- Site Properties -->
  <title>Shelly Energiedaten</title>

    <link rel="stylesheet" type="text/css" href="semantic/dist/semantic.min.css">
    <script
      src="https://code.jquery.com/jquery-3.1.1.min.js"
      integrity="sha256-hVVnYaiADRTO2PzUGmuLJr8BLUSjGIZsDYGmIJLv2b8="
      crossorigin="anonymous"></script>
    <script src="semantic/dist/semantic.min.js"></script>

    <script src="jquery.min.js"></script>

    <!-- Styles -->
    <style>
    #chartdiv {
      width: 100%;
      height: 500px;
    }

    </style>

    <!-- Resources -->
    <script src="https://cdn.amcharts.com/lib/4/core.js"></script>
    <script src="https://cdn.amcharts.com/lib/4/charts.js"></script>
    <script src="https://cdn.amcharts.com/lib/4/themes/animated.js"></script>

</head>
<body>


    <h1 class="ui center aligned header">Shelly Energiedaten</h1>

    <div class="ui container">
      <div class="ui segments">
		<div class="ui segment">
            <div class="ui selection dropdown" id="shellySelection">
			  <div class="text"></div>
			  <i class="dropdown icon"></i>
			</div>
			<div class="ui selection dropdown" id="seriesSelection">
			  <div class="text"></div>
			  <i class="dropdown icon"></i>
			</div>
			<div class="ui selection dropdown" id="channelSelection">
			  <div class="text"></div>
			  <i class="dropdown icon"></i>
			</div>
      	</div>  
        <div class="ui segment">
            <div class="ui horizontal statistics">
              <div class="statistic">
                <div id="energyValue" class="value">
                  <?php echo $Diesel; ?>
                </div>
                <div class="label">
                  Energieverbrauch
                </div>
              </div>
            </div>
        </div>
        <div class="ui segment">
            <div id="chartdiv"></div> </div>
      </div>
    </div>

    <div class="ui vertical footer segment">
        <div class="ui container">
            <p>&copy; 2021 David Zingg</p>
        </div>
    </div>

    <!-- Content JS HERE !-->
    <style>
      .last.container {
        margin-bottom: 300px !important;
      }
      h1.ui.center.header {
        margin-top: 3em;
      }
      h2.ui.center.header {
        margin: 4em 0em 2em;
      }
      h3.ui.center.header {
        margin-top: 2em;
        padding: 2em 0em;
      }
    </style>

    <script type="text/javascript">
        $(document).ready(function() {

          var
            $headers     = $('body > h3'),
            $header      = $headers.first(),
            ignoreScroll = false,
            timer
          ;

          // Preserve example in viewport when resizing browser
          $(window)
            .on('resize', function() {
              // ignore callbacks from scroll change
              clearTimeout(timer);
              $headers.visibility('disable callbacks');

              // preserve position
              $(document).scrollTop( $header.offset().top );

              // allow callbacks in 500ms
              timer = setTimeout(function() {
                $headers.visibility('enable callbacks');
              }, 500);
            })
          ;
          $headers
            .visibility({
              // fire once each time passed
              once: false,

              // don't refresh position on resize
              checkOnRefresh: true,

              // lock to this element on resize
              onTopPassed: function() {
                $header = $(this);
              },
              onTopPassedReverse: function() {
                $header = $(this);
              }
            });	
        });
    </script> 

    <!-- Chart code -->
    <script>
        am4core.ready(function() {

        // Themes begin
        am4core.useTheme(am4themes_animated);
        // Themes end

        // Create chart instance
        var chart = am4core.create("chartdiv", am4charts.XYChart);

        // Add data
        //chart.data = generateChartData();

        // Create axes
        var dateAxis = chart.xAxes.push(new am4charts.DateAxis());
        dateAxis.renderer.minGridDistance = 50;
		//dateAxis.dateFormats.setKey("day", "MMMM dt");
		//dateAxis.periodChangeDateFormats.setKey("day", "MMMM dt");

        var valueAxis = chart.yAxes.push(new am4charts.ValueAxis());

        // Create series
        var series = chart.series.push(new am4charts.LineSeries());
        series.dataFields.valueY = "Value";
        series.dataFields.dateX = "date";
        series.strokeWidth = 2;
        series.minBulletDistance = 10;
        series.tooltipText = "{Value}W";
        series.tooltip.pointerOrientation = "vertical";

        // Add scrollbar
        chart.scrollbarX = new am4charts.XYChartScrollbar();
        chart.scrollbarX.series.push(series);

        // Add cursor
        chart.cursor = new am4charts.XYCursor();
        chart.cursor.xAxis = dateAxis;
        chart.cursor.snapToSeries = series;
			
		$.get("shellys.php", data => {
            $('#shellySelection').dropdown({
				values: data,
			  	onChange: function(value, text, $selectedItem) {
      				$.get("series.php?id="+value, data => {
						$('#seriesSelection').dropdown({
							values: data,
							onChange: function(value, text, $selectedItem) {
								$.get("channel.php?id="+value, data => {
									$('#channelSelection').dropdown({
										values: data,
										onChange: function(value, text, $selectedItem) {
											for (let i = 0; i < data.length; i++) {
											  	if(data[i].value == value){
												 	$('#energyValue').text(data[i].energy.toFixed(3)+"kWh");
													$.get("data.php?id="+value, data => {
														for (var d of data) {
															d.date = new Date(d.date)
														}
														chart.data = data;

														chart.events.on("ready", function () {
														  dateAxis.zoomToDates(new Date(data[data.length-1].date-30*24*60*60),new Date( data[data.length-1].date + 1000));
														});

													});
													//Todo daten laden und in Diagramm darstellen
											  	}
											}
										}
									});
								});
							}
						});
					});
    			}
		  	});

		  });

        }); // end am4core.ready()
    </script>  

</bdoy>

</html>
