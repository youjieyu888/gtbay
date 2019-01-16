<!DOCTYPE html>  <!-- HTML 5 -->

	<head>
		<link rel="shortcut icon" href="img/gtonline_icon.png">
		<link rel="stylesheet" type="text/css" href="css/gtonline_style.css" />
        <meta http-equiv="Content-Type" content="text/html; charset="UTF-8" />
        <script>
			function CheckPriceWarning(id){
				var chechId = 'Check_'+id;
				document.getElementById(chechId).innerHTML="*Please enter a positive price."; 
				document.getElementById(chechId).style.color="red";
			}
			function CheckPriceOk(id){
				var chechId = 'Check_'+id;
				document.getElementById(chechId).innerHTML=""; 
			}
		</script>
        <link rel="shortcut icon" type="image/png" href="img/gtonline_icon.png"/>
        