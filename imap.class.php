<?php

	/**
	 * IMAP class
	 * 
	 * For working with mail retrieval on IMAP servers
	 *
	 * @author 		Frederik Krogh <frederikkrogh@me.com>
	 *
	 */
	
	class imap
	{
	
		private $connection;			// Contains the IMAP connection.
		private $mailserver;			
		private $mime = "ISO-8859-1";	// For decoding MIME msg.
		
		/**
		 * Establish connection to the IMAP server.
		 *
		 */
		public function __construct($imap_server, $imap_user, $imap_password)
		{
			
			#{localhost:993/imap/ssl/novalidate-cert}
		
			if ($this->connection = imap_open($imap_server, $imap_user, $imap_password))
			{
				$this->mailserver = $imap_server;
				return true;
			}
			else
			{
				return false;
			}
				
		}
		
		/**
		 * Returns an array of IMAP headers.
		 *
		 */
		public function getEmails()
		{
		
			// Declairs the return output in an array
			$returnOutput = array();

			// Counts the number of msg in the IMAP connection
			$numMessages = imap_num_msg($this->connection);

			// Looping through the IMAP server
			for ($i = $numMessages; $i > ($numMessages - 20); $i--) {
				
				// Pulling IMAP header
				$header = imap_header($this->connection, $i);

				// Aquires the UID of the email msg
				$uid = imap_uid($this->connection, $i);
				
				// Generating an array containing the header information
				$returnOutput[$uid] = array(
					'uid' =>  $uid,
					'id' => $i,
					'date' => $header->date,
					'subject' => iconv_mime_decode($header->subject, 0, $this->mime),
					'toaddress' => iconv_mime_decode($header->toaddress, 0, $this->mime),
					'fromaddress' => iconv_mime_decode($header->fromaddress, 0, $this->mime),
					'reply_toaddress' => iconv_mime_decode($header->reply_toaddress, 0, $this->mime),
					'senderaddress' => iconv_mime_decode($header->senderaddress, 0, $this->mime),
					'ccaddress' => iconv_mime_decode(@$header->ccaddress, 0, $this->mime),
					'Recent' => $header->Recent,
					'Unseen' => $header->Unseen,
					'Flagged' => $header->Flagged,
					'Answered' => $header->Answered,
					'Deleted' => $header->Deleted,
					'Draft' => $header->Draft,
					'Size' => $header->Size,
					'udate' => $header->udate,
				);
							 
			}

			// Returns the header array $returnOutput
			return $returnOutput;
		
		}

		/**
		 * Returns a specifik IMAP header
		 *
		 */
		public function getHead($id)
		{
		
			// Declairs the return output in an array
			$returnOutput = array();

			// Pulling IMAP header
			$header = imap_header($this->connection, $id);		
			
			// Generating an array containing the header information
			$returnOutput = array(
				'date' => $header->date,
				'subject' => iconv_mime_decode($header->subject, 0, $this->mime),
				'toaddress' => iconv_mime_decode($header->toaddress, 0, $this->mime),
				'fromaddress' => iconv_mime_decode($header->fromaddress, 0, $this->mime),
				'reply_toaddress' => iconv_mime_decode($header->reply_toaddress, 0, $this->mime),
				'senderaddress' => iconv_mime_decode($header->senderaddress, 0, $this->mime),
				'ccaddress' => iconv_mime_decode(@$header->ccaddress, 0, $this->mime),
				'Recent' => $header->Recent,
				'Unseen' => $header->Unseen,
				'Flagged' => $header->Flagged,
				'Answered' => $header->Answered,
				'Deleted' => $header->Deleted,
				'Draft' => $header->Draft,
				'Size' => $header->Size,
				'udate' => $header->udate,
			);
			
			// Generating a subarray with the recipients
			foreach ($header->to as $toemail)
			{
				$returnOutput['to'][] = array(
					'personal' => iconv_mime_decode(@$toemail->personal, 0, $this->mime),
					'mailbox' => iconv_mime_decode(@$toemail->mailbox, 0, $this->mime),
					'host' => iconv_mime_decode(@$toemail->host, 0, $this->mime),
				);
			}

			// Generating a subarray with the senders
			foreach ($header->from as $fromemail)
			{
				$returnOutput['from'][] = array(
					'personal' => iconv_mime_decode(@$fromemail->personal, 0, $this->mime),
					'mailbox' => iconv_mime_decode(@$fromemail->mailbox, 0, $this->mime),
					'host' => iconv_mime_decode(@$fromemail->host, 0, $this->mime),
				);
			}

			// Generating a subarray with the reply addresses
			foreach ($header->reply_to as $replyemail)
			{
				$returnOutput['reply_to'][] = array(
					'personal' => iconv_mime_decode(@$replyemail->personal, 0, $this->mime),
					'mailbox' => iconv_mime_decode(@$replyemail->mailbox, 0, $this->mime),
					'host' => iconv_mime_decode(@$replyemail->host, 0, $this->mime),
				);
			}

			// Generating a subarray with the senders
			foreach ($header->sender as $senderemail)
			{
				$returnOutput['sender'][] = array(
					'personal' => iconv_mime_decode(@$senderemail->personal, 0, $this->mime),
					'mailbox' => iconv_mime_decode(@$senderemail->mailbox, 0, $this->mime),
					'host' => iconv_mime_decode(@$senderemail->host, 0, $this->mime),
				);
			}

			// Checks if there is any CC recipients
			if(isset($header->cc))
			{
				// Generating a subarray with the CC's
				foreach ($header->cc as $ccemail)
				{
					$returnOutput['cc'][] = array(
						'personal' => iconv_mime_decode(@$ccemail->personal, 0, $this->mime),
						'mailbox' => iconv_mime_decode(@$ccemail->mailbox, 0, $this->mime),
						'host' => iconv_mime_decode(@$ccemail->host, 0, $this->mime),
					);
				}
			}

			// Checks if there is any BC recipients
			if(isset($header->bc))
			{
				// Generating a subarray with the BC's
				foreach ($header->bc as $bcemail)
				{
					$returnOutput['bc'][] = array(
						'personal' => iconv_mime_decode(@$bcemail->personal, 0, $this->mime),
						'mailbox' => iconv_mime_decode(@$bcemail->mailbox, 0, $this->mime),
						'host' => iconv_mime_decode(@$bcemail->host, 0, $this->mime),
					);
				}
			}

			// Returns the header array $returnOutput
			return $returnOutput;
		
		}

		/**
		 * Returns the email body.
		 *
		 */
		public function getBody($uid) {
			$body = $this->get_part($this->connection, $uid, "TEXT/HTML");
			// if HTML body is empty, try getting text body
			if ($body == "") {
				$body = $this->get_part($this->connection, $uid, "TEXT/PLAIN");
			}
			return $body;
		}

		/**
		 * Returns the parts of an email.
		 *
		 */
		public function get_part($imap, $uid, $mimetype, $structure = false, $partNumber = false) {
			if (!$structure) {
				   $structure = imap_fetchstructure($imap, $uid, FT_UID);
			}
			if ($structure) {
				if ($mimetype == $this->get_mime_type($structure)) {
					if (!$partNumber) {
						$partNumber = 1;
					}
					$text = imap_fetchbody($imap, $uid, $partNumber, FT_UID);
					switch ($structure->encoding) {
						case 3: return imap_base64($text);
						case 4: return imap_qprint($text);
						default: return $text;
				   }
			   }

				// multipart 
				if ($structure->type == 1) {
					foreach ($structure->parts as $index => $subStruct) {
						$prefix = "";
						if ($partNumber) {
							$prefix = $partNumber . ".";
						}
						$data = $this->get_part($imap, $uid, $mimetype, $subStruct, $prefix . ($index + 1));
						if ($data) {
							return $data;
						}
					}
				}
			}
			return false;
		}

		/**
		 * Returns MIME types.
		 *
		 */
		public function get_mime_type($structure) {
			$primaryMimetype = array("TEXT", "MULTIPART", "MESSAGE", "APPLICATION", "AUDIO", "IMAGE", "VIDEO", "OTHER");

			if ($structure->subtype) {
			   return $primaryMimetype[(int)$structure->type] . "/" . $structure->subtype;
			}
			return "TEXT/PLAIN";
		}

		/**
		 * Returns a list of mailboxes.
		 *
		 */
		public function getMailboxList() {

			$boxes = imap_list($this->connection, $this->mailserver, '*');

			return $boxes;

		}

		/**
		 * Closes IMAP connection.
		 *
		 */

		public function __destruct() {

			imap_close($this->connection);

		}

	}
	
?>