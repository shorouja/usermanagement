<?php 
	require_once 'classes/connect.php';
?>

<!DOCTYPE html>
<head>
	<meta charset="utf-8">
	<title>Benutzerverwaltung</title>
	<script src="https://ajax.googleapis.com/ajax/libs/jquery/3.7.1/jquery.min.js"></script>
	<!-- Fallback if internet goes Haywire -->
	<!-- <script src="libs/jquery/3.7.1/jquery.min.js"></script> -->
	<script>
		function read_all_users(){
			$.ajax({
				url:"classes/user.php",
				type: "POST",
				dataType: 'json',
				data: {operation: "read"},
				beforeSend: function() {
					$('#loadingMessage').toggle('display');
				},
				complete: function(result){
					var resultArray = result.responseText.split('~');
					$("#loadingMessage").toggle('display');
					$('#answerMessage').html(resultArray[0]);
					$('#result').html(resultArray[1]);
			}
			});
		};
		function new_user(){
			if($("#user_form").is(":hidden")){
				$("#user_form").show('display');
				$("#user_form").addClass('create');
				$("#user_form").removeClass('update');
			} else if($("#user_form").is(":visible") && $("#user_form").hasClass('create')){
				$("#user_form").hide('display');
				$("#user_form").removeClass('create');
			} else if ($("#user_form").is(":visible") && $("#user_form").hasClass('update')){
				$("#user_form").addClass('create');
				$("#user_form").removeClass('update');
			}
			$("#form_definition").text('Neu');
			$("#user_form").find("#first_name").val('');
			$("#user_form").find("#last_name").val('');
			$("#user_form").find("#email").val('');
		}
	</script>
</head>
<body>
<!-- This is gonna be display:none / block depending on state -->
	<form action="classes/user.php" id ="user_form" style="display:none;position:absolute;top:40%;left:35%;" method="post">
		<label id="form_definition"></label><br/>
		<input type="hidden" id="user_id" name="user_id">
		<input type="text" id="first_name" name="first_name" placeholder="Vorname" required>
		<input type="text" id="last_name" name="last_name" placeholder="Nachname" required>
		<br/>
		<input type="email" id="email" name="email" placeholder="Email" required>
		<input type="password" id="password" name="password" placeholder="Passwort">
		<button type="submit">Abschicken</button>
	</form>

<div>

<button type="button" onclick="read_all_users()">Aktualisieren</button>
<button type="button" onclick="new_user()">Neuer Benutzer</button>
<div id="loadingMessage" style="display:none;position:absolute;top:50%;left:50%;">LOADING...</div>
<div id="answerMessage"></div>
<div id="result"></div>


</div>
<script>

	var oForm = $("#user_form");
	oForm.submit(function(event){
		event.preventDefault();
		$.ajax({
			url: this.action,
			type: this.method,
			dataType: 'json',
			beforeSend: function() {
				$('#loadingMessage').toggle('display');
			},
			data: $(this).serialize() + "&operation=" + $("#user_form").attr('class'), 
			complete: function (result) {
				var resultArray = result.responseText.split('~');
				$("#loadingMessage").toggle('display');
				$("#user_form").toggle('display');
				$('#answerMessage').html(resultArray[0]);
				$('#result').html(resultArray[1]);
			}

		})
	});
	$(document).ready(function(){
		$(document).on("click",".delete_user",(function(obj){
			$.ajax({
				url:"classes/user.php",
				type: "POST",
				dataType: 'json',
				beforeSend: function() {
					$('#loadingMessage').toggle('display');
				},
				data: {user_id: obj.target.id,operation:"delete"}, 
				complete: function (result) {
					var resultArray = result.responseText.split('~');
					$('#loadingMessage').toggle('display');
					$('#answerMessage').html(resultArray[0]);
					$('#result').html(resultArray[1]);
				}
			})
		}))
		$(document).on("click",".update_user",(function(obj){
			// should only trigger form (prefilled)
			if($("#user_form").is(":hidden")){
				$("#user_form").show('display');
				$("#user_form").removeClass('create');
				$("#user_form").addClass('update');
			} else if($("#user_form").is(":visible") && $("#user_form").hasClass('update')){
				$("#user_form").hide('display');
				$("#user_form").removeClass('update');
			} else if($("#user_form").is(":visible") && $("#user_form").hasClass('create')){
				$("#user_form").removeClass('create');
				$("#user_form").addClass('update');
			} 
			var parent = obj.target.closest("tr");
			var cells = parent.cells;
			$("#user_form").find("#user_id").val(cells[0].textContent);
			$("#user_form").find("#first_name").val(cells[1].textContent);
			$("#user_form").find("#last_name").val(cells[2].textContent);
			$("#user_form").find("#email").val(cells[3].textContent);

			$("#form_definition").text('Bearbeiten');
		}))
	})
</script>
</body>


</html>

