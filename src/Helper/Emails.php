<?php

namespace App\Helper;

use App\Entity\Equipo;
use App\Entity\Partido;
use App\Entity\Usuario;
use Exception;
use PHPMailer\PHPMailer\PHPMailer;
use Symfony\Bundle\FrameworkBundle\Controller\AbstractController;


class Emails extends AbstractController
{

    const CORREOSMTP = 'smtp.gmail.com';
    const USERNAME = 'notificacionesfutapp@gmail.com';
    const PASSUSERNAME = 'futapp123';

    private $server;

    private $author;
    private $partido;

    /**
     * Emails constructor.
     * @param Partido $partido
     * @param string $author
     */
    public function __construct($author)
    {
        $this->author = $author;
        $this->server = new PHPMailer(true);
        $this->partido = new Partido();
    }

    /**
     * @return mixed
     */
    public function getAuthor()
    {
        return $this->author;
    }

    /**
     * @param mixed $author
     */
    public function setAuthor($author): void
    {
        $this->author = $author;
    }

    /**
     * @return mixed
     */
    public function getPartido()
    {
        return $this->partido;
    }

    /**
     * @param mixed $partido
     */
    public function setPartido($partido): void
    {
        $this->partido = $partido;
    }


    public function sendDesignacion()
    {
        try {
            //Server settings
            $this->server->isSMTP();                                            // Send using SMTP
            $this->server->Host = self::CORREOSMTP;                   // Set the SMTP server to send through
            $this->server->SMTPAuth = true;                                   // Enable SMTP authentication
            $this->server->Username = self::USERNAME;                     // SMTP username
            $this->server->Password = self::PASSUSERNAME;                               // SMTP password
            $this->server->SMTPSecure = PHPMailer::ENCRYPTION_STARTTLS;         // Enable TLS encryption; `PHPMailer::ENCRYPTION_SMTPS` encouraged
            $this->server->Port = 587;                                    // TCP port to connect to, use 465 for `PHPMailer::ENCRYPTION_SMTPS` above

            //Recipients
            $this->server->setFrom(self::USERNAME, 'Admin-' . $this->author);


            $this->server->addAddress($this->partido->getArbitro()->getEmail());     // Add a recipient
            $this->server->addAddress($this->partido->getEquipoLocal()->getCorreo());
            $this->server->addAddress($this->partido->getEquipoVisitante()->getCorreo());


            $this->server->isHTML(true);                                  // Set email format to HTML
            $this->server->Subject = 'Notificacion de partido';
            $this->server->Body = $this->body();

            $this->server->send();

        } catch (Exception $e) {
            die("Message could not be sent. Mailer Error: {$this->server->ErrorInfo}");
        }
    }

    /* public function sendDeteAsignados(array $admins){
         try {
             //Server settings
             $this->server->isSMTP();                                            // Send using SMTP
             $this->server->Host       = self::CORREOSMTP;                   // Set the SMTP server to send through
             $this->server->SMTPAuth   = true;                                   // Enable SMTP authentication
             $this->server->Username   = self::USERNAME;                     // SMTP username
             $this->server->Password   = self::PASSUSERNAME;                               // SMTP password
             $this->server->SMTPSecure = PHPMailer::ENCRYPTION_STARTTLS;         // Enable TLS encryption; `PHPMailer::ENCRYPTION_SMTPS` encouraged
             $this->server->Port       = 587;                                    // TCP port to connect to, use 465 for `PHPMailer::ENCRYPTION_SMTPS` above
             $this->server->setFrom(self::USERNAME, 'Admin');

             foreach ($admins as $admin){
                 $this->server->addAddress($admin->getEmail());     // Add a recipient
             }

             $this->server->isHTML(true);                                  // Set email format to HTML
             $this->server->Subject = 'Notificacion de eliminacion partido';


             $this->server->Body    = $this->bodyEliminate();

             $this->server->send();
             //Recipients
             $this->server->setFrom(self::USERNAME, 'Admin');
         }catch (Exception $exception){
             die("Message could not be sent. Mailer Error: {$this->server->ErrorInfo}");
         }
     }

    */

    private function body(): string
    {
        $nombrelocal = $this->getDoctrine()->getRepository(Equipo::class)->findOneBy(['id' => $this->partido->getEquipoLocal()])->getNombre();
        $nombrevisitante = $this->getDoctrine()->getRepository(Equipo::class)->findOneBy(['id' => $this->partido->getEquipoVisitante()])->getNombre();
        $arbitro = $this->getDoctrine()->getRepository(Usuario::class)->findOneBy(['id' => $this->partido->getArbitro()->getId()])->getNombre();
        $fecha = $this->partido->getFechaEncuentro()->format('d-m-Y');
        $hora = $this->partido->getFechaEncuentro()->format('H:i:s');
        $direccion = $this->partido->getDireccionEncuentro();

        $body = "<h1 style='align-content: center;'><b>Designación de partido</b></h1>
                <p><span><b>Equipo Local: </b></span><span>$nombrelocal</span> </p>
                <p><span><b>Equipo Visitante: </b></span><span>$nombrevisitante</span> </p>
                <p><span><b>Arbitro: </b></span><span>$arbitro</span></p>
                <p><span><b>Fecha: </b></span><span>$fecha</span></p>
                <p><span><b>Hora: </b></span><span>$hora</span></p>
                <p><span><b>Direccion: </b></span><span>$direccion</span></p>
        ";
        return $body;
    }

}