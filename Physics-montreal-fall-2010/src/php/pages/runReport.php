<?php

$runId = $_REQUEST['runId'];
$scope = $_REQUEST['scope'];


?>
<html>
	<head>
		<script type="text/javascript" src="/src/js/jquery-1.3.2.min.js"></script>
		<script type="text/javascript" src="/src/js/jquery-ui-1.7.2.custom.min.js"></script>
		<link rel="stylesheet" type="text/css" href="/src/css/style-michelle.css" />
		
		<script type="text/javascript" src="/rep/swfobject.js"></script>
		<script type="text/javascript">
			// For version detection, set to min. required Flash Player version, or 0 (or 0.0.0), for no version detection. --> 
			var swfVersionStr = "10.0.0";
			// To use express install, set to playerProductInstall.swf, otherwise the empty string. -->
			var xiSwfUrlStr = "/rep/playerProductInstall.swf";
			var flashvars = {};
			var params = {};
			params.quality = "high";
			params.bgcolor = "#ffffff";
			params.allowscriptaccess = "sameDomain";
			params.allowfullscreen = "true";
			params.FlashVars = "graphml_url=http://<?= $_SERVER['HTTP_HOST']?>&run_id=<?= $runId ?>&scope=<?= $scope ?>";
			var attributes = {};
			attributes.id = "PhysicsVis";
			attributes.name = "PhysicsVis";
			attributes.align = "middle";
			swfobject.embedSWF(
				"/rep/PhysicsVis.swf", "flashContent", 
				"1024", "1024", 
				swfVersionStr, xiSwfUrlStr, 
				flashvars, params, attributes
			);
			swfobject.createCSS("#flashContent", "display:block; text-align:left; float: left: width: 100%;");
		</script>
		
	</head>

	<body>
		<div style='float: left; width: 100%; font-size: 14px'>
			<a href='/src/php/pages/runAuthoring.php'>&lt; Back to main page</a>
		</div>
		<div id="flashContent">
		     	<p>
		      	To view this page ensure that Adobe Flash Player version 
				10.0.0 or greater is installed. 
				</p>
				<script type="text/javascript"> 
				var pageHost = ((document.location.protocol == "https:") ? "https://" :	"http://"); 
				document.write("<a href='http://www.adobe.com/go/getflashplayer'><img src='" 
								+ pageHost + "www.adobe.com/images/shared/download_buttons/get_flash_player.gif' alt='Get Adobe Flash player' /></a>" ); 
				</script> 
		</div>
    	<noscript>
		         <object classid="clsid:D27CDB6E-AE6D-11cf-96B8-444553540000" width="1024" height="1024" id="PhysicsVis">
		             <param name="movie" value="PhysicsVis.swf" />
		             <param name="quality" value="high" />
		             <param name="bgcolor" value="#ffffff" />
		             <param name="allowScriptAccess" value="sameDomain" />
		             <param name="allowFullScreen" value="true" />
		             <!--[if !IE]>-->
		        <object type="application/x-shockwave-flash" data="PhysicsVis.swf" width="1024" height="1024">
		            <param name="quality" value="high" />
		            <param name="bgcolor" value="#ffffff" />
		            <param name="allowScriptAccess" value="sameDomain" />
		            <param name="allowFullScreen" value="true" />
		        <!--<![endif]-->
		        <!--[if gte IE 6]>-->
		        	<p> 
		        		Either scripts and active content are not permitted to run or Adobe Flash Player version
		        		10.0.0 or greater is not installed.
		        	</p>
		        <!--<![endif]-->
		            <a href="http://www.adobe.com/go/getflashplayer">
		                <img src="http://www.adobe.com/images/shared/download_buttons/get_flash_player.gif" alt="Get Adobe Flash Player" />
		            </a>
		        <!--[if !IE]>-->
		        </object>
		        <!--<![endif]-->
		       </object>
		</noscript>
	</body>
</html>
