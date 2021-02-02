<?php

namespace App\Controller;

use App\Entity\Partido;
use App\Entity\Usuario;
use App\Forms\PartidoForm;
use Symfony\Bundle\FrameworkBundle\Controller\AbstractController;
use Symfony\Component\HttpFoundation\Exception\BadRequestException;
use Symfony\Component\HttpFoundation\Response;
use Symfony\Component\HttpFoundation\Request;
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

        $em= $this->getDoctrine()->getRepository(Partido::class);
       //ç $em->persist($usuario);
        //$em->flush();
        return $this->render('fut_app/index.html.twig', [
            'usuario' => $usuario,
        ]);
    }

    /**
     * @Route("/partidos/nuevo", name="fut_app_partidos_nuevo",
     *     methods={"GET","POST"})
     */
    public function nuevoPartido(Request  $request):Response{
        $error = null;

        try{
            $partido = new Partido();
            $form = $this->createForm(PartidoForm::class,$partido);

            $form->handleRequest($request);

            if($form->isSubmitted() && $form->isValid()){
                $partido = $form->getData();

                $entityManager = $this->getDoctrine()->getManager();
                $entityManager->persist($partido);
                $entityManager->flush();
                return $this->redirectToRoute('fut_app_inicio');
            }

        }catch (BadRequestException $ex){
            $error = $ex->getMessage();
        }

        return $this->render('fut_app/form-partido.html.twig',[
            'form'=>$form->createView(),
            'error'=>$error
        ]);

    }
}
