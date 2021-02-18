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
     *     methods={"GET","POST"}
     * )
     */
    public function getUsuarios(Request $request): Response
    {
        if ($request->getMethod() === 'POST') {

            $usuarios = $this->getDoctrine()->getRepository(Usuario::class)->getUsersByBusqueda($request->get('nombre'));
            return $this->render('usuarios/index.html.twig', [
                'arbitros' => $usuarios]);
        }
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
     *     "/mi_perfil",
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
     *     "/usuarios/editar_perfil/",
     *     name="futapp_usuario_edit_perfil",
     *     methods={"GET"}
     * )
     */
    public function showEditMyProfile()
    {
        $usuario = $this->getDoctrine()->getRepository(Usuario::class)->findOneBy(['email'=>$this->getUser()->getUsername()]);
        return $this->render('usuarios/editar-perfil.html.twig',
            [
                'usuario' => $usuario
            ]);
    }

    /**
     * @Route(
     *     "/admin/usuarios/editar_perfil/{id}",
     *     name="futapp_usuario_edit_perfil_admin",
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
     *     "/usuarios/editar_perfil/datos_basicos/{id}",
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
            $this->addFlash('perfil_ok', 'Perfil cambiado correctamente');
            if($usuario->getEmail() !== $this->getUser()->getUsername()){
                return $this->redirectToRoute('futapp_usuario_edit_perfil_admin',['id'=>$usuario->getId()]);
            }
            return $this->redirectToRoute('futapp_usuario_edit_perfil');
        }
        $this->addFlash('error_basico', 'El email ya está registrado ');
        if($usuario->getEmail() !== $this->getUser()->getUsername()){
            return $this->redirectToRoute('futapp_usuario_edit_perfil_admin',['id'=>$usuario->getId()]);
        }
        return $this->redirectToRoute('futapp_usuario_edit_perfil');


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
     *     "/usuarios/editar_perfil/password/{id}",
     *     name="futapp_usuario_edit_password",
     *      requirements={"id"="\d+"},
     *     methods={"POST"}
     * )
     */
    public function editPassword(Request $request, UserPasswordEncoderInterface $encoder, Usuario $usuario)
    {

        if ($encoder->isPasswordValid($usuario, $request->get('passantigua'))) {
            if ($request->get('passconfirm') === $request->get('passnueva')) {
                $usuario->setPassword($encoder->encodePassword($usuario, $request->get('passnueva')))
                    ->setUpdateAT(new \DateTime());
                $entityManager = $this->getDoctrine()->getManager();
                $entityManager->persist($usuario);
                $entityManager->flush();
                $this->addFlash('pass_ok', 'Contraseña cambiada correctamente');
                return $this->redirectToRoute('futapp_usuario_edit_perfil');
            } else {
                $this->addFlash('error_pass', 'La contraseña nueva introducida no coincide con la confirmada');
                return $this->redirectToRoute('futapp_usuario_edit_perfil');

            }
        } else {
            $this->addFlash('error_pass', 'La contraseña introducida no coincide con la actual');
            return $this->redirectToRoute('futapp_usuario_edit_perfil');

        }

    }


    /**
     * @Route(
     *     "/admin/usuarios/editar_perfil/password/{id}",
     *     name="futapp_usuario_edit_password_admin",
     *      requirements={"id"="\d+"},
     *     methods={"POST"}
     * )
     */
    public function editPasswordByAdmin(Request $request, UserPasswordEncoderInterface $encoder, Usuario $usuario)
    {

        if ($request->get('passconfirm') === $request->get('passnueva')) {
            $usuario->setPassword($encoder->encodePassword($usuario, $request->get('passnueva')))
                ->setUpdateAT(new \DateTime());
            $entityManager = $this->getDoctrine()->getManager();
            $entityManager->persist($usuario);
            $entityManager->flush();
            $this->addFlash('pass_ok_admin', 'Contraseña cambiada correctamente');
            return $this->redirectToRoute('futapp_usuario_edit_perfil_admin', ['id' => $usuario->getId()]);
        } else {
            $this->addFlash('error_pass_admin', 'La contraseña nueva introducida no coincide con la confirmada');
            return $this->redirectToRoute('futapp_usuario_edit_perfil_admin', ['id' => $usuario->getId()]);

        }


    }

    /**
     * @Route(
     *     "/usuarios/editar_perfil/foto/{id}",
     *     name="futapp_usuario_foto",
     *      requirements={"id"="\d+"},
     *     methods={"POST"}
     * )
     */
    public function editFoto(Request $request, Usuario $usuario)
    {
        $fotoFile = $request->files->get('foto');
        if(!empty($usuario->getFoto())){
            $usuario->setFotoAntigua($usuario->getFoto());
        }
        $usuario->setFotoFile($fotoFile);
        $usuario->setUpdateAT(new \DateTime());
        $entityManager = $this->getDoctrine()->getManager();
        $entityManager->persist($usuario);
        $entityManager->flush();
        $this->addFlash('foto_ok', 'Foto cambiada correctamente');

        if($usuario->getEmail() !== $this->getUser()->getUsername()){
            return $this->redirectToRoute('futapp_usuario_edit_perfil_admin',['id'=>$usuario->getId()]);
        }
        return $this->redirectToRoute('futapp_usuario_edit_perfil');
    }

    /**
     * @Route(
     *     "/admin/usuarios/editar_perfil/rol/{id}",
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
        $this->addFlash('rol_ok', 'Rol cambiado correctamente');
        return $this->redirectToRoute('futapp_usuario_edit_perfil_admin', ['id' => $usuario->getId()]);


    }

    /**
     * @Route(
     *     "/usuarios/{id}/borrar",
     *     name="futapp_borrar_usuario",
     *     requirements={"id"="\d+"},
     *     methods={"GET"}
     * )
     */
    public function borrar(Usuario $usuario)
    {
        try {
            $fileName = $usuario->getFoto();
            if (isset($fileName)) {
                unlink(__DIR__ . '/../../public/uploads/fotos/' . $fileName);
            }
            $manager = $this->getDoctrine()->getManager();
            $manager->remove($usuario);
            $manager->flush();
            return $this->redirectToRoute('futapp_usuarios');
        } catch (BadRequestException $exception) {
            $this->addFlash('error', $exception);
        }

    }

    /**
     * @Route("/usuarios/arbitros", name="fut_app_usuarios_arbitros")
     */
    public function getOnlyArbitros()
    {
        $usuariosfilter = $this->getDoctrine()->getRepository(Usuario::class)->findBy([
            'role' => 'ROLE_USER'
        ]);

        return $this->render('usuarios/index.html.twig', [
            'arbitros' => $usuariosfilter
        ]);
    }

    /**
     * @Route("/usuarios/admins", name="fut_app_usuarios_admins")
     */
    public function getOnlyAdmins()
    {
        $usuariosfilter = $this->getDoctrine()->getRepository(Usuario::class)->findBy([
            'role' => 'ROLE_ADMIN'
        ]);

        return $this->render('usuarios/index.html.twig', [
            'arbitros' => $usuariosfilter
        ]);
    }


}