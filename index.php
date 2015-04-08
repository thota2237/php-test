<?php
/**
 * @since 1.0
 * @link $URL$
 * @author $Author$
 * @version $Revision$
 * @Last Modified$
 */
?>
<html>
<head>
<title>PHP Interview Test</title>
<link rel="stylesheet" type="text/css" href="css/style.css">
<script src="https://ajax.googleapis.com/ajax/libs/jquery/1.11.2/jquery.min.js"></script>
<script src="js/js.js"></script>
</head>
<body>
	<h3>
		<u>HTML Table implemented as per the below instructions given:</u>
	</h3>
	<button id="sortByLastname" value="ASC">Sort by Last Name</button>
	<button id="sortByHeight" value="ASC">Sort by Height</button>
	<button id="sortByGender" value="ASC">Sort by Gender</button>
	<button id="sortByDOB" value="ASC">Sort by Birth Date</button>
	<br />
	<br />

	<h2>
		Table Sorted by Column : <label id='displayOrder'> Name (ASC) </label>
	</h2>

	<table id="t01">
		<thead>
			<tr>
				<th>#</th>
				<th>Name</th>
				<th>Height</th>
				<th>Gender</th>
				<th>Birthdate</th>
			</tr>
		</thead>
		<tbody id='datBind'>

		</tbody>
	</table>
	<br />
	<br />
	<h1>PHP Test</h1>
	<p>This test's purpose is to demonstrate the understanding and
		relationship between HTML, Javascript, PHP and HTTP. There are 3 files
		associated with this test, index.php, request.php, and people.csv. The
		index.php will serve as the as the front end webpage that will be
		opened in the web browser. Upon loading, it will send an AJAX to the
		request.php and get all of the people from the csv. It should load
		them into the table of index.php. Buttons on the webpage should allow
		the user to sort the table. Sorting can be done either server side or
		client side in javascript.</p>

	<h3>Instructions</h3>
	<ol>
		<li>Parse csv with PHP in request.php</li>
		<li>Create object structure using classes, people and person.</li>
		<li>Create method in which takes these objects from the previous step
			and return them via HTTP</li>
		<li>Use Jquery to request the return from the previous step</li>
		<li>Now these objects are in Java script, fill the HTML with the
			results</li>
		<li>Add sorting functionality to buttons, serverside or
			clientside(hint: think this through before choosing)</li>
	</ol>
</body>
</html>
