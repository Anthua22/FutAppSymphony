<?php


namespace App\Controller;


use App\Entity\Chat;
use App\Entity\Usuario;
use phpDocumentor\Reflection\Types\Array_;
use Symfony\Bundle\FrameworkBundle\Controller\AbstractController;
use Symfony\Component\HttpFoundation\Request;
use Symfony\Component\Routing\Annotation\Route;

class MesajesController extends AbstractController
{

    /**
     * @Route("/mensajes", name="fut_app_mensajes",
     *     methods={"GET","POST"})
     */
    public function showBandeja(Request $request)
    {
        $usuario = $this->getDoctrine()->getRepository(Usuario::class)->findOneBy(['email' => $this->getUser()->getUsername()]);
        if($request->getMethod()==='POST'){
            $filtro = $request->get('filtro');
            if(!empty($filtro)){
                $mensajesrecivbidos = $this->getDoctrine()->getRepository(Chat::class)->getUsersRecibidosBusqueda($filtro,$usuario->getId());
                $usuariosrecibidos = $this->getUsersMensajes($mensajesrecivbidos);
                $mensajesenviados = $this->getDoctrine()->getRepository(Chat::class)->getUsersEnviadosBusqueda($filtro,$usuario->getId());
                $usuariosenviados = $this->getUsersMensajes($mensajesenviados);
                return $this->render('mensajes/mismensajes.twig', [
                    'usuariosrecibidos' => $usuariosrecibidos,
                    'usuariosenviados' => $usuariosenviados
                ]);
            }else{

                $mensajesrecivbidos = $this->getDoctrine()->getRepository(Chat::class)->getRecibidos($usuario);
                $usuariosrecibidos = $this->getUsersMensajes($mensajesrecivbidos);
                $mensajesenviados = $this->getDoctrine()->getRepository(Chat::class)->getEnviados($usuario);
                $usuariosenviados = $this->getUsersMensajes($mensajesenviados);

                return $this->render('mensajes/mismensajes.twig', [
                    'usuariosrecibidos' => $usuariosrecibidos,
                    'usuariosenviados' => $usuariosenviados
                ]);
            }


        }

        $mensajesrecivbidos = $this->getDoctrine()->getRepository(Chat::class)->getRecibidos($usuario);
        $usuariosrecibidos = $this->getUsersMensajes($mensajesrecivbidos);
        $mensajesenviados = $this->getDoctrine()->getRepository(Chat::class)->getEnviados($usuario);
        $usuariosenviados = $this->getUsersMensajes($mensajesenviados);

        return $this->render('mensajes/mismensajes.twig', [
            'usuariosrecibidos' => $usuariosrecibidos,
            'usuariosenviados' => $usuariosenviados
        ]);
    }

    private function getUsersMensajes(array $mensajes): array
    {
        $usuarios = [];
        foreach ($mensajes as $id){
            $usuario = $this->getDoctrine()->getRepository(Usuario::class)->findOneBy(['id'=>$id['id']]);
            array_push($usuarios,$usuario);
        }

        return $usuarios;
    }

    /**
     * @Route(
     *     "/mensajes/{id}/borrar",
     *     name="futapp_borrar_mensaje",
     *     requirements={"id"="\d+"},
     *     methods={"GET"}
     * )
     */
    public function deleteMessage(Chat $chat)
    {
        $usuario = $chat->getReceptor();
        $manager = $this->getDoctrine()->getManager();
        $manager->remove($chat);
        $manager->flush();
        return $this->redirectToRoute('fut_app_chat',['id'=>$usuario->getId()]);
    }


    /**
     * @Route("/mensajes/chat/{id}", name="fut_app_chat",
     *      requirements={"id"="\d+"},
     *     methods={"GET","POST"})
     */
    public function showChat(Usuario $otrousuario, Request $request)
    {
        $usuario = $this->getDoctrine()->getRepository(Usuario::class)->findOneBy(['email' => $this->getUser()->getUsername()]);
        if ($request->getMethod() == 'POST') {
            $chat = new Chat();
            $chat->setFecha(new \DateTime())
                ->setEmisor($usuario)
                ->setReceptor($otrousuario)
                ->setMensaje($request->get('mensaje'));
            $entityManager = $this->getDoctrine()->getManager();
            $entityManager->persist($chat);
            $entityManager->flush();
            return $this->redirectToRoute('fut_app_chat', ['id' => $otrousuario->getId()]);
        }

        $mensajes = $this->getDoctrine()->getRepository(Chat::class)->getMessageOneChat($otrousuario, $usuario);


        return $this->render('mensajes/chat.html.twig', [
            'mensajes' => $mensajes,
            'userchat' => $otrousuario
        ]);
    }
}