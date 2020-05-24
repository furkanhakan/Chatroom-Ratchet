<link href="//netdna.bootstrapcdn.com/bootstrap/3.0.0/css/bootstrap.min.css" rel="stylesheet" id="bootstrap-css">
<!DOCTYPE html>
<html>

<head>
	<meta charset='UTF-8'>
	<meta name="robots" content="noindex">
	<link rel='stylesheet prefetch' href='https://cdnjs.cloudflare.com/ajax/libs/meyer-reset/2.0/reset.min.css'>
	<link rel='stylesheet prefetch' href='https://cdnjs.cloudflare.com/ajax/libs/font-awesome/4.6.2/css/font-awesome.min.css'>
	<link rel='stylesheet prefetch' href='css/chatroom.css'>
</head>

<body>
	<?php
	session_start();
	if (!isset($_SESSION['user'])) {
		header("location: index.php");
	}
	require("./db/users.php");
	require("./db/chatrooms.php");

	$objChatroom = new chatrooms;
	$chatrooms   = $objChatroom->getAllChatRooms();

	$objUser = new users;
	$users   = $objUser->getAllUsers();
	?>

	<div id="frame">
		<div id="sidepanel">
			<div id="profile">
				<div class="wrap">
					<img id="profile-img" src="images/user-picture.png" class="online" alt="" />
					<?php
					foreach ($_SESSION['user'] as $key => $user) {
						$userId = $key;
						echo '<input type="hidden" name="userId" id="userId" value="' . $key . '">';
						echo "<p> <span id='uname'>" . $user['name'] . "</span></p>";
					}
					?>
					<input type="button" class="btn btn-warning" id="log-out" name="log-out" value="Log Out">
				</div>
			</div>
			<div id="search">
				<label for=""><i class="fa fa-search" aria-hidden="true"></i></label>
				<input type="text" placeholder="Search contacts..." />
			</div>
			<!-- Users -->
			<div id="contacts">
				<ul>
					<?php
					foreach ($users as $key => $user) {
						$last_login = $user['last_login'];
						if ($user['login_status'] == 1) {
							$last_login = "Online";
						}
						if (!isset($_SESSION['user'][$user['id']])) {
							echo "<li class='contact'>
							<div class='wrap'>
								<span id={$user['id']} class='contact-status online'></span>
								<img src='images/user-picture.png'  />
								<div class='meta'>
									<p class='name'>{$user['name']}</p>
									<p class='preview {$user['id']}'>{$last_login}</p>
								</div>
							</div>
						</li>";
						}
					}
					?>
				</ul>
			</div>
			<!-- END Users -->
			<div id="bottom-bar">
				<button id="addcontact"><i class="fa fa-user-plus fa-fw" aria-hidden="true"></i> <span>Add contact</span></button>
				<button id="settings"><i class="fa fa-cog fa-fw" aria-hidden="true"></i> <span>Settings</span></button>
			</div>
		</div>
		<div class="content">
			<div class="contact-profile">
				<img src="images/team.png" alt="" />
				<p>Chat Room</p>
				<div class="social-media">
					<i class="fa fa-facebook" aria-hidden="true"></i>
					<i class="fa fa-twitter" aria-hidden="true"></i>
					<i class="fa fa-instagram" aria-hidden="true"></i>
				</div>
			</div>
			<!-- Message -->
			<div class="messages">
				<ul id="chats">
					<?php
					foreach ($chatrooms as $key => $chatroom) {
						if ($userId == $chatroom['userid']) {
							echo "
									<li class='replies'>
										<p>{$chatroom['msg']}<span class='msg_date'>". date("H:i", strtotime($chatroom['created_on']))."</span></p>
									</li>
								";
						} else {
							echo "
									<li class='sent'>
										<p><span>{$chatroom['name']}</span><br>{$chatroom['msg']}<span class='msg_date'>". date("H:i", strtotime($chatroom['created_on']))."</span></p>
									</li>
								";
						}
					}
					?>
				</ul>
			</div>
			<!-- END Message -->
			<form method="post" >
				<div class="message-input" >
					<div class="wrap" >
						<textarea id="msg" name="msg" type="text" placeholder="Write your message..."></textarea>
						<i class="fa fa-paperclip attachment" aria-hidden="true"></i>
						<button id="send" name="send" class="submit"><i class="fa fa-paper-plane" aria-hidden="true"></i></button>
					</div>
				</div>
			</form>
		</div>
	</div>

	<script src='https://code.jquery.com/jquery-2.2.4.min.js'></script>
	<script>
		$(".messages").animate({
			scrollTop: $(document).height() + 500
		}, "fast");
		$(document).ready(function() {
			var conn = new WebSocket('ws://localhost:8080');
			conn.onopen = function(e) {
				console.log("Connection established!");

				var type = "connect";
				var msg = $("#userId").val();
				var data = {
					type: type,
					msg: msg
				};
				conn.send(JSON.stringify(data));
			};

			conn.onmessage = function(e) {
				var data = JSON.parse(e.data);
				var row;
				if (data.type == "message") {
					if (data.from == "Me") {
						row = "<li class='replies'><p>" + data.msg +"<span class='msg_date'>" + data.dt + "</span></p></li>"
					} else {
						row = "<li class='sent'><p><span>" + data.from + "</span><br>" + data.msg + "<span class='msg_date'>" + data.dt + "</span></p></li>"
					}
					$('#chats').append(row);
				} else if (data.type == "connect") {
					$("p." + data.msg).text("Online");
				} else {
					$("p." + data.msg).text(data.date);
				}
				$(".messages").animate({
						scrollTop: $(document).height() + 500
					}, "fast");

			};

			conn.onclose = function(e) {
				console.log("Connection Closed!");
			}

			$('#msg').on('keyup', function(e) {
				if (e.keyCode == 13 && !e.shiftKey && $("#msg").val().trim() != "") {
					var type = "message";
					var userId = $("#userId").val();
					var msg = $("#msg").val();
					var data = {
						type: type,
						userId: userId,
						msg: msg
					};
					conn.send(JSON.stringify(data));
					$(this).val('');
				}
			});

			$("#send").click(function() {
				if ($("#msg").val().trim() != "") {
					var type = "message";
					var userId = $("#userId").val();
					var msg = $("#msg").val();
					var data = {
						type: type,
						userId: userId,
						msg: msg
					};
					conn.send(JSON.stringify(data));
					$("#msg").val("");
					
				}
				else{
					// alert();
				}
			});

			$("#log-out").click(function() {
				var userId = $("#userId").val();
				$.ajax({
					url: "action.php",
					method: "post",
					data: "userId=" + userId + "&action=log-out"
				}).done(function(result) {
					var data = JSON.parse(result);
					if (data.status == 1) {
						conn.close();
						location = "index.php";
					} else {
						console.log(data.msg);
					}

				});
			})
		})
	</script>
</body>

</html>