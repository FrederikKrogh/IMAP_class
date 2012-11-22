<?php
	
	include '../../imap.class.php';
	
	$server = '{mail.test.com:143/imap}';
	$username = 'user@test.com';
	$password = '12345';

	$imap = new imap($server, $username, $password);

	if($_GET['uid'] & $_GET['id'])
	{

		$head = $imap->getHead(@$_GET['id']);


		echo "<b>Fra: </b>" . $head['fromaddress'] . "<br>";
		echo "<hr>";
		echo "<b>Til: </b>";

		foreach ($head['to'] as $to) {
			echo $to['personal'] . ' ';
			echo '<';
			echo $to['mailbox'];
			echo '&#64;';
			echo $to['host'];
			echo '> ';
		}

		echo "<hr>";
		echo "<b>Dato: </b>" . $head['date'] . "<br>";
		echo "<hr>";
		echo "<b>Emne: </b>" . $head['subject'] . "<br>";
		echo "<hr>";


		echo $imap->getbody(@$_GET['uid']);

		echo '<pre>';
		print_r($head);
	}
?>