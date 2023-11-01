<?php 
	require_once 'classes/connect.php';
?>

<!DOCTYPE html>
<head>
	<meta charset="utf-8">
	<title>Benutzerverwaltung</title>
	<!-- <script src="https://ajax.googleapis.com/ajax/libs/jquery/3.7.1/jquery.min.js"></script> -->
	<script src="jquery-3.5.1.min.js"></script>
	<script>
		function read_all_users(){
			$.ajax({
				url:"classes/functions.php",
				type: "POST",
				dataType: 'json',
				data: {operation: "read"},
				beforeSend: function() {
					$('.msg').toggle('display');
				},
				complete: function(result){
					$('.result').html(result.responseText);
					$('.msg').toggle('display');
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
	<form action="classes/functions.php" id ="user_form" style="display:none;position:absolute;top:40%;left:35%;" method="post">
		<label id="form_definition"></label><br/>
		<input type="text" id="first_name" name="first_name" placeholder="Vorname" required>
		<input type="text" id="last_name" name="last_name" placeholder="Nachname" required>
		<br/>
		<input type="email" id="email" name="email" placeholder="Email" required>
		<input type="password" id="password" name="password" placeholder="Passwort" required>
		<button type="submit">Abschicken</button>
	</form>

<div>

<button type="button" onclick="read_all_users()">Get all</button>
<button type="button" onclick="new_user()">Neuer Benutzer</button>
<div class="msg" style="display:none;position:absolute;top:50%;left:50%;">LOADING...</div>
<div class="result"></div>


</div>
<script>

	var oForm = $("#user_form");
	// oForm.on('submit',function(event){
	oForm.submit(function(event){
		event.preventDefault();
		$.ajax({
			url: this.action,
			type: this.method,
			dataType: 'json',
			beforeSend: function() {
				$('.msg').toggle('display');
			},
			data: $(this).serialize() + "&operation=create", 
			complete: function (result) {
				$('.result').html(result.responseText);
				$("#user_form").toggle('display');
			}

		})
	});
	$(document).ready(function(){
		$(document).on("click",".delete_user",(function(obj){
			$.ajax({
				url:"classes/functions.php",
				type: "POST",
				dataType: 'json',
				beforeSend: function() {
					$('.msg').toggle('display');
				},
				data: {user_id: obj.target.id,operation:"delete"}, 
				complete: function (result) {
					// obj.target.parentElement.parentElement.id remove row
					if (result.responseText == "gelöscht"){
						document.getElementById(obj.target.parentElement.parentElement.id).remove();
						alert("gelöscht");
					} else {
						alert(result.responseText);
					}
					$('.msg').toggle('display');
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
			$("#user_form").find("#first_name").val(cells[1].textContent);
			$("#user_form").find("#last_name").val(cells[2].textContent);
			$("#user_form").find("#email").val(cells[3].textContent);

			$("#form_definition").text('Bearbeiten');

			// $.ajax({
			// 	url:"classes/functions.php",
			// 	type: "POST",
			// 	dataType: 'json',
			// 	beforeSend: function() {
			// 		$('.msg').toggle('display');
			// 	},
			// 	data: $(this).serialize() + "&operation=update&user_id=" + obj.target.id}, 
			// 	complete: function (result) {
			// 		// obj.target.parentElement.parentElement.id remove row
			// 		if (result.responseText == "updated"){
			// 			// update element? document.getElementById(obj.target.parentElement.parentElement.id).remove();
			// 			alert("updated");
			// 		} else {
			// 			alert(result.responseText);
			// 		}
			// 		$('.msg').toggle('display');
			// 	}
			// })
		}))
	})
</script>
</body>


</html>

