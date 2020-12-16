<?php
ini_set("default_charset", 'utf-8');
require 'include/config.php';

// durationLimit is set in config.php
$rawurl = isset($_GET['url']) ? $_GET['url'] : '';
$url = isset($_GET['url']) ? $_GET['url'] : ''; // the youtube video ID

// ######### SQL CONNECTION
$conn = new mysqli($servername, $username, $password, $dbname);

function timeToInt($str)
{
	$arr = array_reverse(explode(":", $str));
	$len = 0;
	for ($i = 0; $i < count($arr); $i++) {
		$len += $arr[$i] * pow(60, $i);
	}
	return $len;
}

function sanitizeURL($url)
{
	// found at http://stackoverflow.com/questions/13476060/validating-youtube-url-using-regex
	$rx = '~^(?:https?://)?' .										# Optional protocol
		'(?:www[.])?' .																# Optional sub-domain
		'(?:youtube[.]com/watch[?]v=|youtu[.]be/)' .	# Mandatory domain name (w/ query string in .com)
		'([^&]{11})~';																# Video id of 11 characters as capture group 1

	$has_match = preg_match($rx, $url, $matches);

	// if matching succeeded, $matches[1] would contain the video ID
	return (isset($matches[1])) ? 'https://www.youtube.com/watch?v=' . $matches[1] : '';
}


?>

<!DOCTYPE html>
<html>
<!-- $$$$$$$$$$$$$$$$$$$$$$$$$$$$ HTML STAT-->

<head>
	<meta name="viewport" content="width=device-width, initial-scale=1.0">
	<meta charset="UTF-8">
	<title>YouTube Audio Downloader</title>
	<link rel="icon" type="image/png" href="favicon.png" />
	<link href="https://getbootstrap.com/docs/4.2/dist/css/bootstrap.min.css" rel="stylesheet" integrity="sha384-GJzZqFGwb1QTTN6wy59ffF1BuGJpLSa9DkKMp0DgiMDm4iYMj70gZWKYbI706tWS" crossorigin="anonymous">
	<link href="css/styles.css" rel="stylesheet">
	<script>
		url = "<?php echo $url ?>";
	</script>
	<script src="js/jquery.js"></script>
	<script src="js/script.js" async></script>
</head>

<body class="preload">

	<nav class='navbar navbar-inverse navbar-static-top'>
		<div class='container'>
			<div class='col-md-12'>
				<form class="navbar-form">
					<div class="input-group">
						<input name='url' type='text' class='form-control' placeholder='Video URL' value='' autocomplete="off">
						<span class="input-group-btn">
							<button class="btn btn-light" type="submit">Go!</button>
						</span>
					</div>
					<!--/.input-group -->
				</form>
				<!--/.navbar-form -->
			</div>
		</div><!-- /.container -->
	</nav><!-- /.navbar -->

	<div style="width: 100%;" class='container text-center'>

		<?php
		if (!empty($url)) { ?>
			<br>
			<div class='card justify-center'>
				<br>

				<img id='thumbnail' src="" class='card-img-top' alt="">

				<div class="spinner-border" id='status' role="status">
					<span class="sr-only">Loading...</span>
				</div>
				<div class="card-body">
					<h5 class="card-title" id='title'>Loading</h5>
				</div>

				<div class="container-fluid">
					<div class="row" style="width: 100%; margin:auto">
						<div class="progress" style="width: 100%;">
							<div class="progress-bar transition-slow progress-bar-striped progress-bar-animated active" style="display: none;" role="progressbar" aria-valuenow="25" aria-valuemin="0" aria-valuemax="100" id="progress">
								<div class="text-animation" id="progress-text">
								</div>
							</div>
						</div>
					</div>
					<div class="row">
						<div class="col-sm-6">
							<a onclick="downloadM4A(url)" class='btn btn-info' style="display: none;">Convert to .m4a</a>
						</div>
						<div class="col-sm-6">
							<a onclick="downloadMP4(url)" class='btn btn-info' style="display: none;">Convert to .mp4</a>
						</div>
					</div>
					<div class="row">
						<div class="col-lg-12">
							<a href="" class='btn btn-success' style="display: none;" id='download' download>Download</a>
						</div>
					</div>
				</div>
			</div>

	</div>



	<br>
	<?php
		} else {
			if (empty($url)) {

	?>
		<br>
		<div class="card">
			<div class="card-body">
				<h5 class="card-title" id='title'>Please enter a video URL.</h5>
			</div>
		</div>
		</div>
<?php
			} else if (isset($durationError)) {
				echo "<h3>Video exceeds $durationMinutes minute limit.</h3>";
			} else {
				echo "<h3>Something went wrong...please enter a valid URL.</h3>";
			}
		}
?>
</div><!-- /.container -->
</body>

</html>