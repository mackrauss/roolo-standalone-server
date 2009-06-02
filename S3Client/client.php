<html>
	<head>
	
		<link rel="stylesheet" type="text/css" href="styles.css" />  
	
		<script type="text/javascript" src="/jquery-1.3.2.js"></script>
		
		<script type="text/javascript">

			var typeToDiv = [];
			typeToDiv['showMessage'] = 'interactionPane';
			typeToDiv['listGroupMembers'] = 'groupMembers';
			typeToDiv['showGroupCategory'] = 'functionalArea';
			typeToDiv['showProblemToTag'] = 'mathProblem';
			typeToDiv['showProblemToSolve'] = 'mathProblem';
			typeToDiv['askForConfirmation'] = 'interactionPane';
			typeToDiv['askForComment'] = 'interactionPane';
			typeToDiv['askForSolution'] = 'interactionPane';
		
			username = '<?= $_REQUEST['username']?>';
			groupname = '<?= $_REQUEST['groupname']?>';

			var continue_loop = true;

			/**
			 * CHANGE DURING THE ACTUAL RUN, TO THE IP OF THE SERVER
			 *
			*/
			serviceUrl = "http://192.168.0.197:8080/buffer-server/";


			function Startup() {
				Post ('userLoggedIn', '');
				request();
			}
 			
			/*
			* Post a msg to proxy.php which consequently will post
			* the message to our buffer-server
			*/
			function Post (type, content){
				xml = "<message><from>"+username+"</from><to>agents</to>";
				xml += "<type>"+type+"</type>";
				xml += "<content>"+content+"</content>";
				xml += "</message>";

				xml = escape(xml);
				
				$.get("proxy.php", { serviceUrl:serviceUrl, action:"Post", paramName: "message", paramValue: xml},
						  function(data){							
						    if (!continue_loop){
							    //clearInteractionPane();
							    //document.getElementById('interactionPane').style.visibility = 'visible';
							    continue_loop = true;
							    request();
						    }
						  });
			}

			/*
			* The request function is run constantly to retrieve and display any messages
			* which are left for the client with the buffer-server 
			*/
			function request () {

				if (continue_loop){
					$.get("proxy.php", { serviceUrl:serviceUrl, action:"GetNext", paramName: "to", paramValue: username},
						  function(data){
								postResponse(data);
								setTimeout(request, 1000);
//								alert ('received data. Set timer');
						  });
				}
			};


			/*
			* Parse the xml returned by the buffer-server and display the "content" part
			* in the appropriate part of the page. 
			*/
			function postResponse(data){
				try{
					typeBegins = data.indexOf("<type>") + 6;
					typeEnds = data.indexOf("</type");

					type = data.substring(typeBegins, typeEnds);
					//alert (type);

					displayDiv = typeToDiv[type];

					// parse content manually
					contentDataBegins = data.indexOf("<content>") + 9;
					contentDataEnds = data.lastIndexOf ("</content>");  
					contentData = data.substring(contentDataBegins, contentDataEnds);

					if (type == "askForConfirmation" || type == "askForComment"){
						continue_loop = false;
					}else {
						continue_loop = true;
					}


					if (type == "showMessage"){
						alert (contentData);
						//document.getElementById('interactionPane').style.visibility = 'visible';
						continue_loop = true;
					}else{
						document.getElementById(displayDiv).innerHTML = contentData;
						// Add javascript effect to show delay

						if (displayDiv == 'interactionPane'){
							document.getElementById('interactionPane').style.visibility = 'visible';
							document.getElementById('waitPane').innerHTML = "";
						} 
					}

					
					//document.getElementById('interactionPane').style.visibility = 'visible';
					
				}catch (e){

				}
				
			}


			/*
			* All the image uploading stuff. 
			*/
			function uploadFile()
			{
			  var uploadForm = document.getElementById("uploadform");
			  if (uploadForm)
			  {
			   uploadForm.target="fileframe";
			   uploadForm.action="upload_image.php";
			  }
			  uploadForm.submit();
			}
			

			/*
			* Sending the image URL back to the Buffer-server
			*/
			function sendImage (){
				if (document.getElementById('fileframe').contentDocument.getElementById('image_url') != null){
					image_url = document.getElementById('fileframe').contentDocument.getElementById('image_url').value;
					if (image_url != null){
						alert("Image URL: "+image_url);
						Post('userUploadedSolution', '<imageUrl>'+image_url+'</imageUrl>');
					}
				}
			}

			// Clear interaction pane
			function clearInteractionPane() {
				document.getElementById('interactionPane').style.visibility = 'hidden';
				document.getElementById('waitPane').innerHTML = "<b>Please wait...</b>";
			}
			
						
		</script>
	</head>
	
<body onLoad="Startup()"> 
<div class='container'>
	

	<table width='100%'>
		<tr>
			<td colspan="3" style='text-align: center'><img src="/S3Logo.png" style='margin-bottom: 10px;' /></td>
		</tr>
		<tr>
			<th> Group Members </th>
			<th> Functional Area </th>
			<th> Problem </th>
		</tr>
		<tr>
			<td width=20%> <div class='groupMembers' name='groupMembers' id='groupMembers'></div> </td>
		
			<td width=20%><div name='functionalArea' id='functionalArea'></div> </td>
		
		    <td width=60%><div name='mathProblem' id='mathProblem'></div> </td>
		</tr>
		<tr>
			<td width=10%></td>
			<td width=10%></td>
			<td width=80%><div id='waitPane' name='waitPane'></div><br/><div name='interactionPane' id='interactionPane'></div> </td>
		</tr>		
	</table>	

</div>	
	</body>
</html>