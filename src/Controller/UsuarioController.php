<?php


namespace App\Controller;


use App\Entity\Partido;
use App\Entity\Usuario;

use Symfony\Bundle\FrameworkBundle\Controller\AbstractController;
use Symfony\Component\HttpFoundation\Response;
use Symfony\Component\Routing\Annotation\Route;

class UsuarioController extends AbstractController
{
    /**
     * @Route(
     *     "/arbitros/",
     *     name="futapp_usuarios",
     *     methods={"GET"}
     * )
     */
    public function getArbitros(): Response
    {
        $arbitros = $this->getDoctrine()->getRepository(Usuario::class)->findAll();

        return $this->render('usuarios/index.html.twig', [
            'arbitros' => $arbitros]);

    }

    /**
     * @Route(
     *     "/arbitros/{id}",
     *     name="futapp_usuario_perfil",
     *     requirements={"id"="\d+"},
     *     methods={"GET"}
     * )
     */
    public function showProfile(Usuario $usuario):Response{
        $partidosnuevos = $this->getDoctrine()->getRepository(Partido::class)->getPartidosNuevosArbitro($usuario);
        $partidospasados =  $this->getDoctrine()->getRepository(Partido::class)->getPartidosPasadosArbitro($usuario);

        return $this->render('usuarios/user-profile.html.twig',[
            'user'=>$usuario,
            'partidosnuevos'=>$partidosnuevos,
            'partidospasados'=>$partidospasados
        ]);
    }


    /**
     * @Route(
     *     "/mi_perfil",
     *     name="futapp_usuario_yo",
     *     methods={"GET"}
     * )
     */
    public function showMyprofile()
    {
        $usuario=$this->getDoctrine()->getRepository(Usuario::class)->findOneBy(['email'=>$this->getUser()->getUsername()]);
        $partidosnuevos = $this->getDoctrine()->getRepository(Partido::class)->getPartidosNuevosArbitro($usuario);
        $partidospasados =  $this->getDoctrine()->getRepository(Partido::class)->getPartidosPasadosArbitro($usuario);


        return $this->render('usuarios/user-profile.html.twig',[
            'user'=>$usuario,
            'partidosnuevos'=>$partidosnuevos,
            'partidospasados'=>$partidospasados
        ]);
    }
}