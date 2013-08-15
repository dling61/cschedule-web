<?php
require_once(Yii::getPathOfAlias('application.extensions.phpmailer').DIRECTORY_SEPARATOR.'class.phpmailer.php');
class CPhpMailer 
{
    
    function __construct()
    {
        $this->_mailer = new PHPMailer();
		$this->_mailer->Port = 25;
		$this->_mailer->SMTPSecure = 'SSL';
        $this->_mailer->CharSet = "utf-8";
        $this->_mailer->IsSMTP();
        $this->_mailer->SMTPAuth = true;
        $this->_mailer->AltBody = "text/html";
        $this->_mailer->IsHTML(true);
    }
    function init()
    {
        $this->_mailer->Host = $this->host;
        $this->_mailer->Port = $this->port;
        $this->_mailer->Username = $this->username;
        $this->_mailer->Password = $this->password;
        $this->_mailer->From = $this->from;
        $this->_mailer->FromName = $this->fromName;
    }
}
?>