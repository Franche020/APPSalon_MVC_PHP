<?php
namespace Classes;

use PHPMailer\PHPMailer\PHPMailer;

class Email {

    public $email;
    public $nombre;
    public $token;

    public function __construct($email, $nombre, $token){
        $this->email = $email;
        $this->nombre = $nombre;
        $this->token = $token;
    }
    public function enviarConfirmacion(){
        // Crear el objeto de email
        
        
        $mail = new PHPMailer();
        $mail->isSMTP();                                            //Send using SMTP
        $mail->Host       = 'sandbox.smtp.mailtrap.io';             //Set the SMTP server to send through
        $mail->SMTPAuth   = true;                                   //Enable SMTP authentication
        $mail->Username   = '9ec3d33bf2136f';                       //SMTP username
        $mail->Password   = '8512a1c947dad9';                       //SMTP password
        $mail->Port       = 2525;                                   //TCP port to connect to
        
        
        $mail->setFrom('cuentas@appsalon.com', 'AppSalon.com');                     // Desde
        $mail->addAddress($this->email, $this->nombre);  
        $mail->Subject = 'Confirma tu cuenta';

        // Set HTML
        $mail->isHTML(true);
        $mail->CharSet = "UTF-8";

        $contenido = "<html>";
        $contenido .= "<p>Hola <strong>".$this->nombre."</strong> has creado tu cuenta en appSalon, para confirmarla debes pulsar en el siguiente enlace:</p>";
        $contenido .= "<p>Presiona Aquí <a href='http://localhost:3000/confirmar-cuenta?token=" . $this->token . "'>Confirmar Cuenta</a> </p>";
        $contenido .= "<p>Si tu no solicitaste esta cuenta, puedes ignorar el mensaje</p>";
        $contenido .= "</html>";

        $mail->Body = $contenido;
        $mail->Send();
    }
    public function enviarRecuperacion () {
        $mail = new PHPMailer();
        $mail->isSMTP();                                            //Send using SMTP
        $mail->Host       = 'sandbox.smtp.mailtrap.io';             //Set the SMTP server to send through
        $mail->SMTPAuth   = true;                                   //Enable SMTP authentication
        $mail->Username   = '9ec3d33bf2136f';                       //SMTP username
        $mail->Password   = '8512a1c947dad9';                       //SMTP password
        $mail->Port       = 2525;                                   //TCP port to connect to
        
        
        $mail->setFrom('cuentas@appsalon.com', 'AppSalon.com');                     // Desde
        $mail->addAddress($this->email, $this->nombre);  
        $mail->Subject = 'Confirma tu cuenta';

        // Set HTML
        $mail->isHTML(true);
        $mail->CharSet = "UTF-8";

        $contenido = "<html>";
        $contenido .= "<p>Hola <strong>".$this->nombre."</strong> has solicitado la recuperacion de la contraseña en AppSalon.com, para poder recuperar tu cuenta debes presionar en el siguiente enlace:</p>";
        $contenido .= "<p>Presiona Aquí <br> <a href='http://localhost:3000/recuperar?token=" . $this->token . "'>Recuperar Cuenta</a> </p>";
        $contenido .= "<p>Si tu no solicitaste esta cuenta, puedes ignorar el mensaje</p>";
        $contenido .= "</html>";

        $mail->Body = $contenido;
        $mail->Send();
    }

}