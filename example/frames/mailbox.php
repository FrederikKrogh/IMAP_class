<?php
	
	# Including class and config
	include '../../imap.class.php';
	
	if(isset($_GET['mailbox'])) {
		$server = $_GET['mailbox'];
	} else {
		$server = '{mail.test.com:143/imap}';
	}

	$username = 'user@test.com';
	$password = '12345';

	$imap = new imap($server, $username, $password);

	echo "<table>";
	echo "<tr>";
	echo "<td>Dato</td><td>Fra</td><td>Emne</td>";
	echo "</tr>";

	foreach ($imap->getEmails() as $email) {
		echo "<tr>";
		echo "<td>" . $email['date'] . "</td>";
		echo "<td>" . $email['fromaddress'] . "</td>";
		echo "<td><a href=msg.php?uid=".$email['uid']."&id=".$email['id']." target=msg>" . $email['subject'] . "</a></td>";
		echo "</tr>";
	}

	echo "</table>";

?>