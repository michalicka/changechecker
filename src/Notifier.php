<?php

declare(strict_types=1);

use PHPMailer\PHPMailer\PHPMailer;
use PHPMailer\PHPMailer\SMTP;
use PHPMailer\PHPMailer\Exception;

class Notifier {

    /**
     * @var Config
     */
    private $config;

    /**
     * @var array
     */
    private $changes;

    /**
     * @var PHPMailer
     */
    private $mail;


    public function __construct(Config $config, array $changes) {
        $this->config = $config;
        $this->changes = $changes;
        $this->mail = new PHPMailer();
    }

    /**
     * Sends email notification
     */
    public function sendMail(): bool
    {
		try {
		    //Recipients
		    $this->mail->setFrom($this->config->getString('from_email'));
		    $this->mail->addAddress($this->config->getString('to_email'));

		    $filename = $this->config->getString('screen_file');
		    if (file_exists($filename)) $this->mail->addAttachment($filename);

		    $this->mail->Subject = $this->config->getString('subject');
		    $this->mail->Body    = $this->getBody();

		    $this->mail->send();
		    return true;
		} catch (Exception $e) {
			//log
		}
		return false;
	}

    /**
     * Creates email body content
     */
	private function getBody(): string
	{
		$body = date('j.n.Y H:i:s')."\n\n";
		foreach ($this->changes as $change) {
			$body .= $change['from'] . ' => ' . $change['to'] . "\n";
		}

		return $body;
	}

    /**
     * Sends Email to SMS notification
     */
    public function sendSMSMail(): bool
    {
		try {
		    //Recipients
		    $this->mail->setFrom($this->config->getString('from_email'));
		    $this->mail->addAddress($this->config->getString('sms_email'));

		    $this->mail->Subject = $this->config->getString('subject');
		    $this->mail->Body    = $this->getBody();

		    $this->mail->send();
		    return true;
		} catch (Exception $e) {
			//log
		}
		return false;
	}


}