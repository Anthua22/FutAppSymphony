<?php


namespace App\Controller;


use App\Entity\Chat;
use App\Entity\Usuario;
use Symfony\Bundle\FrameworkBundle\Controller\AbstractController;
use Symfony\Component\HttpFoundation\Request;
use Symfony\Component\Routing\Annotation\Route;

class MesajesController extends AbstractController
{

    /**
     * @Route("/mensajes", name="fut_app_mensajes",
     *     methods={"GET","POST"})
     */
    public function showChats()
    {
        return $this->render('mensajes/mismensajes.twig');
    }


    /**
     * @Route("/chat/{id}", name="fut_app_chat",
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
            return $this->redirectToRoute('fut_app_chat',['id'=>$otrousuario->getId()]);
        }

        $mensajes = $this->getDoctrine()->getRepository(Chat::class)->getMessageOneChat($otrousuario, $usuario);


        return $this->render('mensajes/chat.html.twig', [
            'mensajes' => $mensajes,
            'userchat' => $otrousuario
        ]);
    }
}