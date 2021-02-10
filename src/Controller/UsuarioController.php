<?php


namespace App\Controller;


use App\Entity\Equipo;
use App\Entity\Partido;
use App\Entity\Usuario;


use Symfony\Bundle\FrameworkBundle\Controller\AbstractController;
use Symfony\Component\HttpFoundation\Exception\BadRequestException;
use Symfony\Component\HttpFoundation\Request;
use Symfony\Component\HttpFoundation\Response;
use Symfony\Component\Routing\Annotation\Route;
use Symfony\Component\Security\Core\Encoder\UserPasswordEncoderInterface;

class UsuarioController extends AbstractController
{

    /**
     * @Route(
     *     "/usuarios",
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
     *     "/usuarios/{id}",
     *     name="futapp_usuario_perfil",
     *     requirements={"id"="\d+"},
     *     methods={"GET"}
     * )
     */
    public function showProfile(Usuario $usuario): Response
    {
        $partidosnuevos = $this->getDoctrine()->getRepository(Partido::class)->getPartidosNuevosArbitro($usuario);
        $partidospasados = $this->getDoctrine()->getRepository(Partido::class)->getPartidosPasadosArbitro($usuario);

        return $this->render('usuarios/user-profile.html.twig', [
            'user' => $usuario,
            'partidosnuevos' => $partidosnuevos,
            'partidospasados' => $partidospasados
        ]);
    }


    /**
     * @Route(
     *     "/usuarios/mi_perfil",
     *     name="futapp_usuario_yo",
     *     methods={"GET"}
     * )
     */
    public function showMyprofile()
    {
        $usuario = $this->getDoctrine()->getRepository(Usuario::class)->findOneBy(['email' => $this->getUser()->getUsername()]);
        $partidosnuevos = $this->getDoctrine()->getRepository(Partido::class)->getPartidosNuevosArbitro($usuario);
        $partidospasados = $this->getDoctrine()->getRepository(Partido::class)->getPartidosPasadosArbitro($usuario);


        return $this->render('usuarios/user-profile.html.twig', [
            'user' => $usuario,
            'partidosnuevos' => $partidosnuevos,
            'partidospasados' => $partidospasados
        ]);
    }

    /**
     * @Route(
     *     "/usarios/editar_perfil/{id}",
     *     name="futapp_usuario_edit_perfil",
     *     requirements={"id"="\d+"},
     *     methods={"GET"}
     * )
     */
    public function showEditProfile(Usuario $usuario)
    {

        return $this->render('usuarios/editar-perfil.html.twig',
            [
                'usuario' => $usuario
            ]);
    }

    /**
     * @Route(
     *     "/usarios/editar_perfil/datos_basicos/{id}",
     *     name="futapp_usuario_edit_basico",
     *     requirements={"id"="\d+"},
     *     methods={"POST"}
     * )
     */
    public function editPerfil(Request $request, Usuario $usuario)
    {

        if (!$this->checkEmail($request->get('email'), $usuario->getEmail())) {
            $usuario->setTelefono($request->get('telefono'))
                ->setNombre($request->get('nombre'))
                ->setApellidos($request->get('apellidos'))
                ->setEmail($request->get('email'))
                ->setUpdateAT(new \DateTime());


            $entityManager = $this->getDoctrine()->getManager();
            $entityManager->persist($usuario);
            $entityManager->flush();
            return $this->redirectToRoute('futapp_usuario_edit_perfil', ['id' => $usuario->getId()]);
        }
        $this->addFlash('error_basico', 'El email ya está registrado ');
        return $this->redirectToRoute('futapp_usuario_edit_perfil', ['id' => $usuario->getId()]);


    }

    private function checkEmail(string $correo, string $correoantiguo)
    {
        $usuarios = $this->getDoctrine()->getRepository(Usuario::class)->findBy(['email' => $correo]);
        if ($correo === $correoantiguo) {
            return false;
        }
        return count($usuarios) > 0 ? true : false;
    }


    /**
     * @Route(
     *     "/usarios/editar_perfil/password/{id}",
     *     name="futapp_usuario_edit_password",
     *      requirements={"id"="\d+"},
     *     methods={"POST"}
     * )
     */
    public function editpassword(Request $request, UserPasswordEncoderInterface $encoder, Usuario $usuario)
    {

        if ($encoder->isPasswordValid($usuario, $request->get('passantigua'))) {
            if ($request->get('passconfirm') === $request->get('passnueva')) {
                $usuario->setPassword($encoder->encodePassword($usuario, $request->get('passnueva')))
                    ->setUpdateAT(new \DateTime());
                $entityManager = $this->getDoctrine()->getManager();
                $entityManager->persist($usuario);
                $entityManager->flush();
                $this->addFlash('pass_ok', 'Contraseña cambiada');
                return $this->redirectToRoute('futapp_usuario_edit_perfil', ['id' => $usuario->getId()]);
            } else {
                $this->addFlash('error_pass', 'La contraseña nueva introducida no coincide con la confirmada');
                return $this->redirectToRoute('futapp_usuario_edit_perfil', ['id' => $usuario->getId()]);

            }
        } else {
            $this->addFlash('error_pass', 'La contraseña introducida no coincide con la actual');
            return $this->redirectToRoute('futapp_usuario_edit_perfil', ['id' => $usuario->getId()]);

        }

    }

    /**
     * @Route(
     *     "/usarios/editar_perfil/foto/{id}",
     *     name="futapp_usuario_foto",
     *      requirements={"id"="\d+"},
     *     methods={"POST"}
     * )
     */
    public function editFoto(Request $request, Usuario $usuario)
    {

        $fotoFile = $request->files->get('foto');


        $usuario->setFotoFile($fotoFile);
        $usuario->setUpdateAT(new \DateTime());

        $entityManager = $this->getDoctrine()->getManager();
        $entityManager->persist($usuario);
        $entityManager->flush();
        return $this->redirectToRoute('futapp_usuario_edit_perfil', ['id' => $usuario->getId()]);


    }

    /**
     * @Route(
     *     "/usarios/editar_perfil/rol/{id}",
     *     name="futapp_usuario_rol",
     *      requirements={"id"="\d+"},
     *     methods={"POST"}
     * )
     */
    public function editRol(Request $request, Usuario $usuario)
    {
        $usuario->setRole($request->get('rol'));
        $usuario->setUpdateAT(new \DateTime());
        $entityManager = $this->getDoctrine()->getManager();
        $entityManager->persist($usuario);
        $entityManager->flush();
        return $this->redirectToRoute('futapp_usuario_edit_perfil', ['id' => $usuario->getId()]);


    }

    /**
     * @Route(
     *     "/usuarios/{id}/borrar",
     *     name="futapp_borrar_usuario",
     *     requirements={"id"="\d+"},
     *     methods={"GET"}
     * )
     */
    public function borrar(Usuario $usuario){
        try{
            $fileName = $usuario->getFoto();
            if(isset($fileName)){
                unlink(__DIR__.'/../../public/uploads/fotos/'.$fileName);
            }
            $manager = $this->getDoctrine()->getManager();
            $manager->remove($usuario);
            $manager->flush();
            return $this->redirectToRoute('futapp_usuarios');
        }catch (BadRequestException $exception){
            $this->addFlash('error',$exception );
        }

    }

}