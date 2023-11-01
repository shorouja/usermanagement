<?php 
	require_once 'classes/connect.php';
?>

<!DOCTYPE html>
<head>
	<meta charset="utf-8">
	<title>Benutzerverwaltung</title>
	<script src="https://ajax.googleapis.com/ajax/libs/jquery/3.7.1/jquery.min.js"></script>
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
			$("#new_user_form").toggle('display');
		}
	</script>
</head>
<body>
<!-- This is gonna be display:none / block depending on state -->
	<form action="classes/functions.php" id ="new_user_form" style="display:none;position:absolute;" method="post">
		<input type="text" name="first_name" placeholder="Vorname" required>
		<input type="text" name="last_name" placeholder="Nachname" required>
		<input type="email" name="email" placeholder="Email" required>
		<input type="password" name="password" placeholder="Passwort" required>
		<button type="submit">Abschicken</button>
	</form>

<div>

<button type="button" onclick="read_all_users()">Get all</button>
<button type="button" onclick="new_user()">Neuer Benutzer</button>
<div class="msg" style="display:none;position:absolute;">LOADING...</div>
<div class="result"></div>


</div>
<script>

	var oForm = $("#new_user_form");
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
				$("#new_user_form").toggle('display');
			}

		})
	});
	$(document).ready(function(){
		$(document).on("click",".delete_user",(function(obj){
			$.ajax({
				url: this.action,
				type: this.method,
				dataType: 'json',
				beforeSend: function() {
					$('.msg').toggle('display');
				},
				data: {user_id: obj.target.id}, 
				complete: function (result) {
					// obj.target.parentElement.parentElement.id remove row
					document.getElementById(obj.target.parentElement.parentElement.id).remove();
					$('.msg').toggle('display');

				}
			})
		}))
	})
</script>
</body>


</html>

