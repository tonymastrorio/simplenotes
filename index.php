<?php

	//starts a new session
	session_start();

	$error = "";

	if(array_key_exists("logout", $_GET)) {

		unset($_SESSION['id']);
		setcookie("id", "", time() - 60*60);
		$_COOKIE["id"] = "";

	} else if ((array_key_exists("id", $_SESSION) AND $_SESSION['id']) OR (array_key_exists("id", $_COOKIE) AND $_COOKIE['id'])) {

		header("Location: loggedinpage.php");

	}

	if(array_key_exists("submit", $_POST)) {

		include("connection.php");

		if(!$_POST['email']) {
			$error .= "<p>Email Address is required</p>";
		}

		if(!$_POST['password']) {
			$error .= "<p>Password is required</p>";
		}

		if($error) {

			$error = "<p>There were errors in your form:</p>".$error;

		} else {

			if ($_POST['signUp'] == '1') {

				$query = "SELECT id FROM users WHERE email ='".mysqli_real_escape_string($link, $_POST['email'])."' LIMIT 1";

				$result = mysqli_query($link, $query);

				if(mysqli_num_rows($result) > 0) {

					$error = "That email address is already taken";

				} else {

					$query = "INSERT INTO users (email, password) VALUES ('".mysqli_real_escape_string($link, $_POST['email'])."', '".mysqli_real_escape_string($link, $_POST['password'])."')";

					if (!mysqli_query($link, $query)) {

						$error = "<p>Could not sign you up. Please try again later.</p>";

					} else {

						$query = "UPDATE users SET password = '".md5(md5(mysqli_insert_id($link)).$_POST['password'])."' WHERE id = ".mysqli_insert_id($link)." LIMIT 1";

						mysqli_query($link, $query);

						$_SESSION['id'] = mysqli_insert_id($link);

						if($_POST['stayLoggedIn'] == '1') {

							setcookie("id", mysqli_insert_id($link), time() + 60*60*24);

						}

						header("Location: loggedinpage.php");

					}

				}

			} else {

				$query = "SELECT * FROM users WHERE email = '".mysqli_real_escape_string($link, $_POST['email'])."'";

				$result = mysqli_query($link, $query);

				$row = mysqli_fetch_array($result);

				if(isset($row)) {

					$hashedPassword = md5(md5($row['id']).$_POST['password']);

					if($hashedPassword == $row['password']) {

						$_SESSION['id'] = $row['id'];

						if($_POST['stayLoggedIn'] == '1') {

							setcookie("id", $row['id'], time() + 60*60*24);

						}

						header("Location: loggedinpage.php");

					} else {

						$error = "Your email/password information is incorrect.";

					}

				} else {

					$error = "Your email/password information is incorrect.";

				}

			}

		}

	}
?>

<?php include("header.php"); ?>
    
  	<div class="container" id="homePageContainer">

  		<h1>Simple Notes</h1>

  		<p>Store your thoughts permanently and securely.</p>

  		<div id="error"><?php if ($error != "") {

  			echo '<div class="alert alert-danger" role="alert">'.$error.'</div>';

  			} ?></div>

			<form method="POST" id="signUpForm">

				<p class="explainer">Interested? Sign Up now.</p>

				<fieldset class="form-group">
					<input type="email" class="form-control" name="email" id="email" placeholder="Email Address">
				</fieldset>

				<fieldset class="form-group">
					<input type="password" class="form-control" name="password" id="password" placeholder="Password">
				</fieldset>

				<div class="checkbox">
					<label>
						<input type="checkbox" name="stayLoggedIn" value=1>Stay Logged In
					</label>
				</div>

				<fieldset class="form-group">
					<input type="hidden" name="signUp" value="1">
					<input type="submit" class="btn btn-success" name="submit" value="Sign Up">
				</fieldset>

				<p><a class="toggleForms">Log In</a></p>

			</form>

			<form method="POST" id="logInForm">

				<p class="explainer">Log in with your username and password.</p>

				<fieldset class="form-group">
					<input type="email" class="form-control" name="email" id="email" placeholder="Email Address">
				</fieldset>

				<fieldset class="form-group">
					<input type="password" class="form-control" name="password" id="password" placeholder="Password">
				</fieldset>

				<div class="checkbox">
					<label>
						<input type="checkbox" name="stayLoggedIn" value=1>Stay Logged In
					</label>
				</div>

				<fieldset class="form-group">
					<input type="hidden" name="signUp" value="0">
					<input type="submit" class="btn btn-success" name="submit" value="Log In">
				</fieldset>

				<p><a class="toggleForms">Sign Up</a></p>

			</form>

		</div>

<?php include("footer.php"); ?>

