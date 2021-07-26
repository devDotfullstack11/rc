<?php 


// require 'path/to/PHPMailer/src/Exception.php';
// require 'path/to/PHPMailer/src/PHPMailer.php';
// require 'path/to/PHPMailer/src/SMTP.php';



require_once(APPPATH.'/third_party/phpmailer/src/Exception.php');
require_once(APPPATH.'/third_party/phpmailer/src/PHPMailer.php');
//require_once(APPPATH.'/third_party/phpmailer/src/PHPMailer.php');
require_once(APPPATH.'/third_party/phpmailer/src/SMTP.php');

use PHPMailer\PHPMailer\PHPMailer;
use PHPMailer\PHPMailer\Exception;


class PHPMailerLib extends PHPMailer {

}



;?>