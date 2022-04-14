<?php
namespace Classes;

use PHPMailer\PHPMailer\PHPMailer;

class Email {
    protected $email;
    protected $nombre;
    protected $token;


    public function __construct($email , $nombre , $token)
    {
        $this->email = $email;        
        $this->nombre = $nombre;        
        $this->token = $token;        
    }

    public function enviarConfimacion()
    {
        $mail = new PHPMailer();
        $mail->isSMTP();
        $mail->Host = 'smtp.mailtrap.io';
        $mail->SMTPAuth = true;
        $mail->Port = 2525;
        $mail->Username = '59c26c4f59247d';
        $mail->Password = '611bc6d955bcfc';

        $mail->setFrom('cuentas@uptask.com');
        $mail->addAddress('cuentas@uptask.com', 'uptask.com');
        $mail->Subject = 'confirma tu cuenta';
        $mail->isHTML(true);
        $mail->CharSet = 'UTF-8';

        $contenido = " <html>";
        $contenido .= "<p><strong>hola ".$this->nombre."</strong>has creado tu cuenta en uptask, solo debes confimarla en el siguiente enlace </p>";
        $contenido .= "<p>Presione aqui: <a href='http://localhost:3400/confirmar?token=".$this->token."'>Confinmar Cuenta</a> </p>";
        $contenido .= "<p>si tu no creaste esta cuenta ignora este mensaje</p>";
        $contenido .= "</html>";

        $mail->Body = $contenido;
        $mail->send();
    }


    public function enviarInstruciones()
    {
        $mail = new PHPMailer();
        $mail->isSMTP();
        $mail->Host = 'smtp.mailtrap.io';
        $mail->SMTPAuth = true;
        $mail->Port = 2525;
        $mail->Username = '59c26c4f59247d';
        $mail->Password = '611bc6d955bcfc';

        $mail->setFrom('cuentas@uptask.com');
        $mail->addAddress('cuentas@uptask.com', 'uptask.com');
        $mail->Subject = 'reestablece tu password';
        $mail->isHTML(true);
        $mail->CharSet = 'UTF-8';

        $contenido = " <html>";
        $contenido .= "<p><strong>hola ".$this->nombre."</strong> parece que has olvidado tu password sigue el siguiente enlace para reecuperar tu acceso </p>";
        $contenido .= "<p>Presione aqui: <a href='http://localhost:3400/reestablecer?token=".$this->token."'>reestablecer password</a> </p>";
        $contenido .= "<p>si tu no solicitastes reestablecer tu password ignora este mensaje</p>";
        $contenido .= "</html>";

        $mail->Body = $contenido;
        $mail->send();
    }
}