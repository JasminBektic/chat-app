<html>
	<head>	
		<title>Websocket playground</title>
		<link rel="stylesheet" href="https://maxcdn.bootstrapcdn.com/bootstrap/4.1.0/css/bootstrap.min.css">
		<link rel="stylesheet" href="style.css">
		<meta name="viewport" content="width=device-width, initial-scale=1">
	</head>
	<body>
		<div class="login">
			<div class="input-group">
				<input type="text" name="user" id="user" class="form-control" placeholder="Name (max 6 characters)" maxlength="6" />
				<button id="join" class="btn btn-success" type="button" disabled>JOIN ></button>
			</div>
		</div>
		<div class="container">
			<form class="col-md-6 center" autocomplete="off">
				<div id="chat-box" class="chatbox bg-light text-dark"></div>
				<div class="input-group">
					<input type="text" name="message" id="message" class="form-control" placeholder="Press enter or send button" />
					<button id="send" class="btn btn-success" type="button">Send</button>
				</div>
			</form>
		</div>
		
		<script src="https://code.jquery.com/jquery-3.3.1.min.js"></script>
		<script src="script.js"></script>
	</body>
</html>