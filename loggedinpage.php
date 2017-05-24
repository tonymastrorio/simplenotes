<?php

	session_start();

	$noteContent = "";

	if (array_key_exists("id", $_COOKIE)) {

		$_SESSION['id'] = $_COOKIE['id'];

	}

	if (array_key_exists("id", $_SESSION)) {

		echo "Logged in";

		include("connection.php");

		$query = "SELECT note FROM users WHERE id = ".mysqli_real_escape_string($link, $_SESSION['id'])." LIMIT 1";

		$row = mysqli_fetch_array(mysqli_query($link, $query));

		$noteContent = $row['note'];

	} else {

		header("Location: index.php");

	}


	include("header.php");
?>

<nav class="navbar navbar-default navbar-fixed-top">
  <div class="container-fluid">
    <!-- Brand and toggle get grouped for better mobile display -->
    <div class="navbar-header">
      <button type="button" class="navbar-toggle collapsed" data-toggle="collapse" data-target="#bs-example-navbar-collapse-1" aria-expanded="false">
        <span class="sr-only">Toggle navigation</span>
        <span class="icon-bar"></span>
        <span class="icon-bar"></span>
        <span class="icon-bar"></span>
      </button>
      <a class="navbar-brand" href="#">Simple Notes</a>
    </div>

    <!-- Collect the nav links, forms, and other content for toggling -->
    <div class="collapse navbar-collapse" id="bs-example-navbar-collapse-1">
      <ul class="nav navbar-nav navbar-right">
        <li><a href='index.php?logout=1'><button class="btn btn-success" type="submit">Log out</button></a></li>
      </ul>
    </div><!-- /.navbar-collapse -->
  </div><!-- /.container-fluid -->
</nav>

	<div class="container-fluid" id="containerLoggedInPage">

		<textarea class="form-control" id ="notes"><?php echo $noteContent; ?></textarea>

	</div>

	<div id="testdiv">
		Testing

	</div>


<?php

	include("footer.php");


?>