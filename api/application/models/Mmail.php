<?php
defined('BASEPATH') OR exit('No direct script access allowed');

class Mmail extends CI_Model {

	public function kirim_email($to,$to_name,$subjek,$pesan){
		$this->load->library("phpmailer_library");
		$mail = $this->phpmailer_library->load();
		$mail->SMTPDebug = 0;                               
	//Set PHPMailer to use SMTP.
	$mail->isSMTP();            
	//Set SMTP host name                          
	$mail->Host = "ssl://mail.artakita.com"; //host mail server	
	$mail->SMTPAuth = true;                          	
	$mail->Username = "cs@artakita.com";   //nama-email smtp          
	$mail->Password = "cs123arta!@#kita";           //password email smtp		
	$mail->SMTPSecure = "ssl";                           
	//Set TCP port to connect to 
	$mail->Port = 465;                                   

	$mail->From = "cs@artakita.com"; //email pengirim
	$mail->FromName = "Customer Service Artakita"; //nama pengirim

	 $mail->addAddress($to, $to_name); //email penerima

	 $mail->isHTML(true);

	$mail->Subject = $subjek; //subject
    $mail->Body    = $pesan; //isi email
        $mail->AltBody = "Email"; //body email (optional)

        if(!$mail->send()) 
        {        	
        	return $mail->ErrorInfo;        	
        } 
        else 
        {        	
        	return "Message has been sent successfully";
        }
    }


}

/* End of file Mmail.php */
/* Location: ./application/models/Mmail.php */