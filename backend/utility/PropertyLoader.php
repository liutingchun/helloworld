<?php

class PropertyLoader {
	public $hostname;
	public $database;
	public $username;
	public $password;
	
	public $smtpServer;
	public $smtpPort;
	public $smtpUser;
	public $smtpPass;
	
	function __construct() {
		require_once('Connections/cn_onyourmark.php');
		
		$this->hostname = $hostname_cn_onyourmark;
		$this->database = $database_cn_onyourmark;
		$this->username = $username_cn_onyourmark;
		$this->password = $password_cn_onyourmark;
		
		require_once('SMTP/smtp_onyourmark.php');
		
		$this->smtpEnable = $SmtpEnable;
		$this->smtpServer = $SmtpServer;
		$this->smtpPort = $SmtpPort;
		$this->smtpUser = $SmtpUser;
		$this->smtpPass = $SmtpPass;
	}
}

?>