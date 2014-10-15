<?php
require_once('macaroni.php');
?>
<html>
<head>
	<title>RU Cheesy</title>
	<style type="text/css">

	.dhall {
		margin: 0px 50px 20px;
		list-style-type: none;
	}

	.dhall .header {
		background-color: #F3933A;
		color: #fff;
		font-size: 18px;
		padding: 2px 10px;
		border-top-right-radius: 10px;
		border-top-left-radius: 10px;
	}

	.macs {
		width: 100%;
		background-color: #eee;
	}

	.mac:hover {
		background-color: #e1e1e1;
	}

	.macs .meal {
		width: 1%;
		text-align: center;
		border: 1px solid #F3933A;
		border-top: 0px;
		box-shadow: inset 1px 1px 1px #fff;
		padding: 5px 15px;
		white-space: nowrap;
		font-size: 14px;
	}

	.macs .type {
		border-bottom:  1px solid #F3933A;
		border-right: 1px solid #F3933A;
		box-shadow: inset 1px 1px 1px #fff;
		padding: 5px 10px;
		font-size: 14px;
	}

	</style>

	<script type="text/javascript">
		function decSeconds() {
			var el = document.getElementById("countdown_seconds");
			var seconds = parseInt(el.innerHTML) - 1;
			if(seconds < 0) {
				el.innerHTML = "59";
				decMinutes();
			}
			else {
				el.innerHTML = seconds;
			}
		}

		function decMinutes() {
			var el = document.getElementById("countdown_minutes");
			var minutes = parseInt(el.innerHTML) - 1;
			if(minutes < 0) {
				el.innerHTML = "59";
				decHours();
			}
			else {
				el.innerHTML = minutes;
			}
		}

		function decHours() {
			var el = document.getElementById("countdown_hours");
			var hours = parseInt(el.innerHTML) - 1;
			if(hours < 0) {
				el.innerHTML = "23";
				decDays();
			}
			else {
				el.innerHTML = hours;
			}
		}

		function decDays() {
			var el = document.getElementById("countdown_days");
			var days = parseInt(el.innerHTML) - 1;
			if(days < 0) {
				document.getElementById("countdown").innerHTML = "NOW!";
			}
			else {
				el.innerHTML = days;
			}
		}

		window.setInterval(function(){
			if(document.getElementById("countdown").getAttribute("data-running") == "yes") {
				decSeconds();
			}
		}, 1000);
	</script>
</head>
<body>


<table cellpadding="0" cellspacing="0" align="center">

	<tr>
		<td></td>
		<td style="text-align: center"><img src="images/3.jpg" width="400" /><br /><img src="images/5.jpg" width="400" /></td>
		<td></td>
	</tr>
	<tr>
		<td><img src="images/2.jpg" /></td>
		<td style="text-align: center; font-family:verdana; font-size: 18px;">
			<?php
			$found = findCheesyArray(getJSONData());
			printMacAndCheese($found);

			$countdownto = getCountdownTo();
			$countdown = getCountdown($countdownto);
			?>
			<br /><br />
			TIME UNTIL MAC AND CHEESE TAKEOUT ON BUSCH:<br />
			
			<span style="font-size: 24px;" id="countdown" data-running="<?=$countdown['running']?>">

				<?php
				if($countdown['running'] == "yes") {
					?>
					<span id="countdown_days"><?=$countdown['days']?></span> days 
					<span id="countdown_hours"><?=$countdown['hours']?></span> hours 
					<span id="countdown_minutes"><?=$countdown['minutes']?></span> minutes 
					<span id="countdown_seconds"><?=$countdown['seconds']?></span> seconds
				<?php
				}
				else {
					echo $countdown['message'];
				}
				?>

			</span>
			<br /><br />
			<img src="images/mac.png" width="400" />
		</td>
		<td><img src="images/1.jpg" /></td>
	</tr>

</table>


</body>
</html>