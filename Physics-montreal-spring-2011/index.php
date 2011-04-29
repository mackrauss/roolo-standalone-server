<?php 
require_once './src/php/RooloClient.php';

$roolo = new RooloClient();
$runConfigs = $roolo->search('type:RunConfig AND runpublished: 1', 'metadata', 'latest');


?>
<!DOCTYPE html PUBLIC "-//W3C//DTD HTML 4.01 Transitional//EN" "http://www.w3.org/TR/html4/loose.dtd">
<html>
<head>
	<meta http-equiv="Content-Type" content="text/html; charset=UTF-8">
	<title>Welcome to Dawson</title>
	<link rel="stylesheet" type="text/css" href="/src/css/style-michelle.css" />
	
	<script type="text/javascript" src="/src/js/jquery-1.3.2.min.js"></script>
	<script type="text/javascript" src="/src/js/jquery-ui-1.7.2.custom.min.js"></script>
	<script type="text/javascript">
		function startRun(){
			var runId = $('#runId').val(); 
			
			if (runId == ''){
				alert('Please choose a run first');
				return;
			}

			window.location.href = 'http://rollcall.proto.encorelab.org/login?destination=http://dawson/src/php/ajaxServices/securityCheck.php?runId='+runId; 
		}
	</script>
</head>

<style type='text/css'>
	#projectTable {
		float: left;
		width: 100%;
		border-collapse: collapse;
	}
	
	#projectTable tr{
		
	}
	
	#projectTable td.logo {
		width: 200px;
		margin: 0 auto;
		border-bottom: 1px solid #CCCCCC;
		padding-bottom: 10px;
	}
	
	#projectTable td.desc {
		border-bottom: 1px solid #CCCCCC;
		padding-bottom: 10px;
		text-align: left;
		padding-left: 30px;
		font-size: 16px;
		line-height: 32px;
	}
	
</style>


<body>
	<div id="mainPageHeader"><span style='width: 100%; background-color: black; color: white; float: left; padding-left: 20px; padding-top: 25px; font-size: 24px; text-align: left; 
height: 60px;'> 
Welcome to Dawson 
</span></div>
	
	<div id='projects' style='margin: 0 auto; width: 976px'>
	
		<h2 style='float: left; margin-top: 30px; width: 100%; text-align: left; padding-bottom: 5px; border-bottom: 1.5px dotted #CCCCCC'> Projects </h2>
		
		<table id='projectTable'>
			<tr>
				<td class='logo'>
					<form action="/src/php/ajaxServices/securityCheck.php" method="post">
						Choose a run:
						<select name='runId' id='runId'>
							<option value=''>Select a run</option>
						<?php 
						foreach ($runConfigs as $curRunConfig){
							$curRunName = $curRunConfig->runName;
							$curRunId = $curRunConfig->runid;
							echo "<option value='$curRunId'>$curRunName</option>";
						}
						?>
						</select>
						
						<br/>
						
						<img src='/resources/images/physics-button.png' style='border: none; cursor: pointer; margin-top: 10px; margin-bottom: 10px;'
							onclick="startRun()"/>
							
						<a href="http://rollcall.proto.encorelab.org/login?destination=http://dawson/src/php/ajaxServices/securityCheck.php">I'm a teacher</a>
					</form>
				</td>
				<td class='desc'> Learn the relationship between physics problems and their underlying concepts through tagging, answering and reflecting on your reasoning. </td>
			</tr>
		</table>
		 
	</div>

</body>
</html>