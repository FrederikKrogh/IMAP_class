<?php
	
	include '../../imap.class.php';

	$server = '{mail.test.com:143/imap}';
	$username = 'user@test.com';
	$password = '12345';

	$imap = new imap($server, $username, $password);

	foreach ($imap->getMailboxList() as $mailbox) {
		echo '<li><a href="mailbox.php?mailbox='.$mailbox.'" target=mailbox>' . $mailbox . '</a></li>';
	}

?>