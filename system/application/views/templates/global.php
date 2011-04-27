<!doctype html>	 

	<!-- paulirish.com/2008/conditional-stylesheets-vs-css-hacks-answer-neither/ --> 
	<!--[if lt IE 7 ]> <html lang="en" class="no-js ie6"> <![endif]-->
	<!--[if IE 7 ]>	   <html lang="en" class="no-js ie7"> <![endif]-->
	<!--[if IE 8 ]>	   <html lang="en" class="no-js ie8"> <![endif]-->
	<!--[if IE 9 ]>	   <html lang="en" class="no-js ie9"> <![endif]-->
	<!--[if (gt IE 9)|!(IE)]><!--> <html lang="en" class="no-js"> <!--<![endif]-->
	<head>
		<meta charset="utf-8">

		<!-- Always force latest IE rendering engine (even in intranet) & Chrome Frame 
		Remove this if you use the .htaccess -->
		<meta http-equiv="X-UA-Compatible" content="IE=edge,chrome=1">

		<title><?=$meta['pagetitle']?></title>
		<meta name="description" content="">
		<meta name="author" content="">

		<!--	Mobile viewport optimized: j.mp/bplateviewport -->
		<meta name="viewport" content="width=device-width, initial-scale=1.0">

		<!-- Place favicon.ico & apple-touch-icon.png in the root of your domain and delete these references -->
		<link rel="shortcut icon" href="/favicon.ico">

		<!-- CSS : implied media="all" -->
		<link rel="stylesheet" href="/css/style.css?v=2">

		<!-- Uncomment if you are specifically targeting less enabled mobile browsers
		<link rel="stylesheet" media="handheld" href="css/handheld.css?v=2">	-->

		<!-- All JavaScript at the bottom, except for Modernizr which enables HTML5 elements & feature detects -->
		<script src="js/libs/modernizr-1.6.min.js"></script>

	</head>

	<body>

		<div id="container">
			<header class="clearfix">
				<div id="logo"><span class="logoFirst">Empire</span><span class="logoLast">Maps</span></div>
				'Those who cannot remember the past are condemned to repeat it.'
								<br>~ George Santayana
			</header>

			<div id="main">
				<!-- Begin Page Contents -->

								<? $this->load->view($page['page_content']); ?>

				<!-- End Page Contents -->
			</div>

			<footer>

			</footer>
		</div> <!--! end of #container -->


		<!-- Javascript at the bottom for fast page loading -->

		<!-- Grab Google CDN's jQuery. fall back to local if necessary -->
		<script src="//ajax.googleapis.com/ajax/libs/jquery/1.5.1/jquery.js"></script>
		<script>!window.jQuery && document.write(unescape('%3Cscript src="js/libs/jquery-1.5.1.min.js"%3E%3C/script%3E'))</script>

		<!-- scripts concatenated and minified via ant build script-->
		
		<!--Google Maps API JS-->
		<script type="text/javascript"
		    src="http://maps.google.com/maps/api/js?sensor=true">
		</script>
		
		<script src="js/plugins.js"></script>
		<script src="js/script.js"></script>
		<!-- end concatenated and minified scripts-->

		<!--[if lt IE 7 ]>
		<script src="js/libs/dd_belatedpng.js"></script>
		<script> DD_belatedPNG.fix('img, .png_bg'); //fix any <img> or .png_bg background-images </script>
		<![endif]-->

		<!-- asynchronous google analytics: mathiasbynens.be/notes/async-analytics-snippet 
		change the UA-XXXXX-X to be your site's ID -->
		<script>
		var _gaq = [['_setAccount', 'UA-XXXXX-X'], ['_trackPageview']];
		(function(d, t) {
		var g = d.createElement(t),
		s = d.getElementsByTagName(t)[0];
		g.async = true;
		g.src = ('https:' == location.protocol ? 'https://ssl' : 'http://www') + '.google-analytics.com/ga.js';
		s.parentNode.insertBefore(g, s);
		})(document, 'script');
		</script>

	</body>
</html>