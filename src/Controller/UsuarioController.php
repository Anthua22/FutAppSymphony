<?php


namespace App\Controller;


use App\Entity\Usuario;

use Symfony\Bundle\FrameworkBundle\Controller\AbstractController;
use Symfony\Component\HttpFoundation\Response;
use Symfony\Component\Routing\Annotation\Route;

class UsuarioController extends AbstractController
{
    /**
     * @Route(
     *     "/arbitros/",
     *     name="futapp_arbitros",
     *     methods={"GET"}
     * )
     */
    public function getArbitros(): Response
    {
        $arbitros = $this->getDoctrine()->getRepository(Usuario::class)->findAll();

        return $this->render('usuarios/index.html.twig', [
            'arbitros' => $arbitros]);

    }
}