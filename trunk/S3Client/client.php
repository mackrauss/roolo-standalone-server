<html>
	<head>
		<script type="text/javascript" src="/jquery-1.3.2.js"></script>
		
		<script type="text/javascript">
			username = '<?= $_REQUEST['username']?>';
			groupname = '<?= $_REQUEST['groupname']?>';

			/**
			 * CHANGE DURING THE ACTUAL RUN, TO THE IP OF THE SERVER
			 *
			*/
			serviceUrl = "http://localhost:8080/buffer-server/";
			
			function Post (type, content){
				xml = "<message><from>"+username+"</from><to>agents</to>";
				xml += "<type>"+type+"</type>";
				xml += "<content>"+content+"</content>";
				xml += "</message>";

				xml = escape(xml);
				
//				$("#groupMembers").html("<pre>"+xml+"</pre>"); 

//				$("#groupMembers").load(bufferServerUrl+"Post", {message:xml}, 
//						function(){
//					   		alert("The last 25 entries in the feed have been loaded");
//					 	}
//			 	);

//				url = bufferServerUrl+"Post";
//				alert(url);
//				alert(xml);
				
				$.get("proxy.php", { serviceUrl:serviceUrl, action:"Post", paramName: "message", paramValue: xml},
						  function(data){
						    alert("Data Loaded: " + data);
						  });
			}
		</script>
	</head>
	<body onload="Post('myType', 'myContent')">
		<div class='groupMembers' name='groupMembers' id='groupMembers'>
		
		</div>
		
		<div name='functionalArea' id='functionalArea'>
		
		</div>
		
		<div name='mathProblem' id='mathProblem'>
		
		</div>
		
		<div name='interactionPane' id='interactionPane'>
		
		</div>
	</body>
</html>