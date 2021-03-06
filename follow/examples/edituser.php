<?php

//start session if need to
if (!isset($_SESSION)) {
	session_start();
}

require('db.php');
$userid = $_SESSION['userid'];
//Create session username somewhere (session email)
//modify fm-users table to include first name and last name and img_url
//set session variables for first name and last name and img_url
//modify fm_users to add title -> $session[title]
//modify fm_users to add description-> $session[description]

$email = $_SESSION['users'];

//update session variables
$sql = "SELECT password, firstname, lastname, description, title, img_url FROM fm_users WHERE users = '$email'";

//get results from query
$result = $conn->query($sql);

//extract results from query
while ($row = $result->fetch_assoc()) {
		$_SESSION['firstname'] = $row['firstname'];
		$_SESSION['lastname'] = $row['lastname'];
		$_SESSION['description'] = $row['description'];
		$_SESSION['title'] = $row['title'];
		$_SESSION['img_url'] = $row['img_url'];
}

if ($_SERVER['REQUEST_METHOD'] === 'POST') {
	//get database connection


	if (isset($_POST['edit_firstname'])) {
		$firstname = $_POST['edit_firstname'];
		$sql = "UPDATE fm_users SET firstname = '$firstname' WHERE users='$email'";
		$conn->query($sql);
	}

	if (isset($_POST['edit_lastname'])) {
		$lastname = $_POST['edit_lastname'];
		$sql = "UPDATE fm_users SET lastname = '$lastname' WHERE users='$email'";
		$conn->query($sql);
	}

	if (isset($_POST['edit_title'])) {
		$title = $_POST['edit_title'];
		$sql = "UPDATE fm_users SET title = '$title' WHERE users='$email'";
		$conn->query($sql);
	}

	if (isset($_POST['edit_description'])) {
	$description = $_POST['edit_description'];
	$sql = "UPDATE fm_users SET description = '$description' WHERE users='$email'";
  $conn->query($sql);
	}

	//update session variables
	$sql = "SELECT password, firstname, lastname, description, title FROM fm_users WHERE users = '$email'";

	//get results from query
	$result = $conn->query($sql);

	//if upload is triggered, store the file
	  if ($_FILES['upload'] != null) {

			if (!file_exists("uploads")) {
				mkdir("./uploads");
			}

	    $target_dir = "uploads/".$userid."/";

	    //check to see if directory exists
	    if (file_exists($target_dir)) {
	      $target_file = $target_dir . basename($_FILES['upload']['name']);
	      $uploadver = true;
	    }else {
	      //if uploads directory does not exist, create it
	      mkdir($target_dir, 0777, true);
	      chmod($target_dir, 0777);
	      $target_file = $target_dir . basename($_FILES['upload']['name']);
	      $uploadver = true;
	    }

	  }

	  //check if the file is already submitted
	  if (file_exists($target_file)) {
	    $uploadver = false;
	    $ret = true;
	    $output = "The file you are trying to upload already exists";
	  }

	  //get the uploaded file type
	  $file_type = $_FILES['upload']['type'];

		//if file hasnt been uploaded, upload it
		if ($uploadver) {
			move_uploaded_file($_FILES['upload']['tmp_name'], $target_file);
			$_FILES['upload'] = null;
			$uploadver = false;

			$target_file = "./$target_file";
			$sql = "UPDATE fm_users SET img_url='$target_file' WHERE userid='$userid'";
			$conn->query($sql);
			$_SESSION['img_url'] = $target_file;
		}


	//extract results from query
	while ($row = $result->fetch_assoc()) {
		$_SESSION['firstname'] = $row['firstname'];
		$_SESSION['lastname'] = $row['lastname'];
		$_SESSION['description'] = $row['description'];
		$_SESSION['title'] = $row['title'];
}
	$edited = true;
}

if ($edited == true) {
	$edited = false;
	header("Location: profile.php");
}


?>


<!doctype html>
<html lang="en">
<head>
	<meta charset="utf-8" />
	<link rel="icon" type="image/png" href="../assets/img/favicon.ico">
	<link rel="apple-touch-icon" sizes="76x76" href="../assets/img/apple-icon.png">
	<meta http-equiv="X-UA-Compatible" content="IE=edge,chrome=1" />

	<title>Noah's Website. Completely Noah's Website.</title>

	<meta content='width=device-width, initial-scale=1.0, maximum-scale=1.0, user-scalable=0' name='viewport' />
    <meta name="viewport" content="width=device-width" />

	<!-- Bootstrap core CSS     -->
	<link href="../assets/css/bootstrap.min.css" rel="stylesheet" />
	<link href="../assets/css/paper-kit.css?v=2.1.0" rel="stylesheet"/>

	<!--  CSS for Demo Purpose, don't include it in your project     -->
	<link href="../assets/css/demo.css" rel="stylesheet" />

    <!--     Fonts and icons     -->
	<link href='http://fonts.googleapis.com/css?family=Montserrat:400,300,700' rel='stylesheet' type='text/css'>
	<link href="http://maxcdn.bootstrapcdn.com/font-awesome/latest/css/font-awesome.min.css" rel="stylesheet">
	<link href="../assets/css/nucleo-icons.css" rel="stylesheet">

</head>
<body>
    <nav class="navbar navbar-expand-md fixed-top navbar-transparent" color-on-scroll="150">
        <div class="container">
			<div class="navbar-translate">
	            <button class="navbar-toggler navbar-toggler-right navbar-burger" type="button" data-toggle="collapse" data-target="#navbarToggler" aria-controls="navbarTogglerDemo02" aria-expanded="false" aria-label="Toggle navigation">
					<span class="navbar-toggler-bar"></span>
					<span class="navbar-toggler-bar"></span>
					<span class="navbar-toggler-bar"></span>
	            </button>
	            <a class="navbar-brand" href="edituser.php">Edit Settings</a>
			</div>
			<div class="collapse navbar-collapse" id="navbarToggler">
	            <ul class="navbar-nav ml-auto">
	                <li class="nav-item">
	                    <a href="users.php" class="nav-link">Follow People</a>
	                </li>
	                <li class="nav-item">
	                    <a href="profile.php" target="_self" class="nav-link">
												<?php echo $_SESSION['firstname'] . 's Profile'; ?>
											</a>
	                </li>
									<li>

									</li>
	            </ul>
	        </div>
		</div>
    </nav>

    <div class="wrapper">
        <div class="page-header page-header-xs" data-parallax="true" style="background-image: url('../assets/img/fabio-mangione.jpg');">
			<div class="filter"></div>
		</div>
        <div class="section profile-content">
            <div class="container">
                <div class="owner">
                    <div class="avatar">
                        <img src="<?php echo $_SESSION['img_url'];?>" class="img-circle img-no-padding img-responsive">
                    </div>
                    <div class="name">
                        <h4 class="title"><?php echo $_SESSION['firstname'] . " " . $_SESSION['lastname']; ?><br /></h4>
						<h6 class="description"><?php echo $_SESSION['title']; ?></h6>
                    </div>
                </div>
                <!-- Tab panes -->
                <div class="tab-content following">
									<h1 class="text-dark md-auto ml-auto mx-auto col-md-12">Edit user settings</h1><br/>
                    <div class="tab-pane active" id="follows" role="tabpanel">
                        <div class="row">
                            <div class="col-md-6 ml-auto mr-auto">
                                <ul class="list-unstyled follows">
																			<form class="" action="edituser.php" method="post" enctype="multipart/form-data">
																				<li>
																					<p class="text-dark">Edit your firstname:</p>
																					<input class="form-control" type="text" name="edit_firstname" value="<?php echo $_SESSION['firstname']; ?>"> <br/>
																				</li>
																				<li>
																				<Br/>
																					<p class="text-dark">Edit your lastname:</p>
																					<input class="form-control" type="text" name="edit_lastname" value="<?php echo $_SESSION['lastname']; ?>"> <br/>
																				</li>
																				<li>
																				<br/>
																					<p class="text-dark">Edit your title:</p>
																					<input class="form-control" type="text" name="edit_title" value="<?php echo $_SESSION['title']; ?>"> <br/>
																				</li>
																				<li>
																				<br/>
																					<p class="text-dark">Edit your description:<p/>
																					<textarea class="form-control" rows="4" cols="50" name="edit_description"><?php echo $_SESSION['description']; ?></textarea>
																				</li>
																				<li>
																				<br/>
																					<p>Edit your profile image</p>
																					<input class="btn btn-primary" type="file" name="upload" value="Upload image">
																				</li>
																				<br/>
																				<li>
																				<br/>
																					<input class="btn btn-primary" type="submit" name="submit" value="Confirm">
																				</li>
																			</form>
                                </ul>
                            </div>
                        </div>
                    </div>
                </div>
            </div>
        </div>
				</div>
    </div>
	<footer class="footer section-dark">
        <div class="container">
            <div class="row">
                <nav class="footer-nav">
                    <ul>
                        <li><a href="https://www.creative-tim.com">Creative Tim</a></li>
                        <li><a href="http://blog.creative-tim.com">Blog</a></li>
                        <li><a href="https://www.creative-tim.com/license">Licenses</a></li>
                    </ul>
                </nav>
                <div class="credits ml-auto">
                    <span class="copyright">
                        © <script>document.write(new Date().getFullYear())</script>, made with <i class="fa fa-heart heart"></i> by Creative Tim
                    </span>
                </div>
            </div>
        </div>
    </footer>
</body>

<!-- Core JS Files -->
<script src="../assets/js/jquery-3.2.1.js" type="text/javascript"></script>
<script src="../assets/js/jquery-ui-1.12.1.custom.min.js" type="text/javascript"></script>
<!-- <script src="../assets/js/tether.min.js" type="text/javascript"></script> -->
<script src="https://cdnjs.cloudflare.com/ajax/libs/popper.js/1.11.0/umd/popper.min.js" integrity="sha384-b/U6ypiBEHpOf/4+1nzFpr53nxSS+GLCkfwBdFNTxtclqqenISfwAzpKaMNFNmj4" crossorigin="anonymous"></script>
<script src="../assets/js/bootstrap.min.js" type="text/javascript"></script>


<!--  Paper Kit Initialization snd functons -->
<script src="../assets/js/paper-kit.js?v=2.1.0"></script>

</html>
