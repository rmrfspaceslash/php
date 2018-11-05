<?php

//start session if need to
if (!isset($_SESSION)) {
	session_start();
}

require('db.php');

//get database query
$sql3 = "SELECT firstname, lastname, img_url, title, followid FROM fm_users";
$results2 = $conn->query($sql3);
//check to see if post information was submitted
if ($_SERVER['REQUEST_METHOD'] === 'POST') {
	//grab results from sql query
	while ($rowdb3 = $results2->fetch_assoc()) {
		$tmpuser = $rowdb3['firstname'];
		//every dynamically created <input> is named after the first name of the user. userid should be used instead
		if ($_POST["$tmpuser"] == "Yes") { //add people to the database
			//get logged in users credential (userid)
			$userid = $_SESSION['userid'];
			//get the followid of the user to be followed
			$followerid = $rowdb3['followid'];
			//Insert into the follows table the logged in users userid and the following users followid
			$INSERT = "INSERT IGNORE INTO fm_follows (userid, followid) VALUES ('$userid','$followerid')";
			$conn->query($INSERT);
		}else { //remove people form the database
			$userid = $_SESSION['userid']; //refer to comments above
			$followerid = $rowdb3['followid']; //refer to comments above
			//Remove the logged in users userid and the corresponding followid
			$DELETE = "DELETE FROM fm_follows WHERE userid = '$userid' AND followid = '$followerid'";
			$conn->query($DELETE);
		}
	}
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
	            <a class="navbar-brand" href="#">Come with me </a>
			</div>
			<div class="collapse navbar-collapse" id="navbarToggler">
	            <ul class="navbar-nav ml-auto">
	                <li class="nav-item">
	                    <a href="login.php" class="nav-link">And you'll see</a>
	                </li>
	                <li class="nav-item">
	                    <a href="#" target="_blank" class="nav-link">A World of
												<?php echo $_SESSION['username']; ?>
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
        </div>
				<div class="tab-content following">
						<div class="tab-pane active" id="follows" role="tabpanel">
								<div class="row">
										<div class="col-md-6 ml-auto mr-auto">
												<ul class="list-unstyled follows">
													<form action="" method="post">
													<?php $sql = "SELECT firstname, lastname, img_url, title, followid FROM fm_users"; ?>
													<?php $result = $conn->query($sql); ?>
													<?php while ($rowdb = $result->fetch_assoc()) { $olduserid = $rowdb['followid']; $marker = false; ?>
														<li>
																<div class="row">
																		<div class="col-md-2 col-sm-2 ml-auto mr-auto">
																				<img src="<?php echo $rowdb['img_url']; ?>" alt="Circle Image" class="img-circle img-no-padding img-responsive">
																		</div>
																		<div class="col-md-7 col-sm-4  ml-auto mr-auto">
																				<h6><?php echo $rowdb['firstname'] . " " . $rowdb['lastname']; ?><br/><small><?php echo $rowdb['title']; ?></small></h6>
																		</div>
																		<div class="col-md-3 col-sm-2  ml-auto mr-auto">
								<div class="form-check">
																	<label class="form-check-label">
																			<input class="form-check-input" name="<?php echo $rowdb['firstname']; ?>" type="checkbox" value="Yes" <?php
																				$userid = $_SESSION['userid'];
																				$sql2 = "SELECT userid, followid FROM fm_follows WHERE userid = '$userid'";
																				$results = $conn->query($sql2);
																				while ($rowdb2 = $results->fetch_assoc()) {
																					if ($_SESSION['userid'] == $rowdb2['userid'] && $rowdb2['followid'] == $rowdb['followid']) { $marker = true;
																			 ?>checked><?php } } if ($marker == false) { ?> > <?php } ?>
																			<span class="form-check-sign"></span>
																	</label>
															</div>
														</div>
																</div>
														</li>
													<?php
													} ?>
											<input class="btn-primary btn-large btn" type="submit" name="submit" value="Follow Checked users">
											</form>
												<?php


													?>
														<hr />
												</ul>
										</div>
								</div>
						</div>
						<div class="tab-pane text-center" id="following" role="tabpanel">
								<h3 class="text-muted">Not following anyone yet :(</h3>
								<button class="btn btn-warning btn-round">Find artists</button>
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
