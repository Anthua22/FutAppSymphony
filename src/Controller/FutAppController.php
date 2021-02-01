<?php

namespace App\Controller;

use App\Entity\Usuario;
use Symfony\Bundle\FrameworkBundle\Controller\AbstractController;
use Symfony\Component\HttpFoundation\Response;
use Symfony\Component\Routing\Annotation\Route;

class FutAppController extends AbstractController
{
    /**
     * @Route("/", name="fut_app_inicio")
     */
    public function index(): Response
    {
        $usuario = new Usuario();
        $usuario->setNombre('Anthony')
            ->setApellidos('Ubillus')
            ->setEmail('anthony@gmail.com')
            ->setPassword('1234')
            ->setRole('arbitro');

        $em= $this->getDoctrine()->getManager();
       // $em->persist($usuario);
        //$em->flush();
        return $this->render('fut_app/index.html.twig', [
            'usuario' => $usuario,
        ]);
    }
}
