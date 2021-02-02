<?php

namespace App\Controller;

use App\Entity\Equipo;
use App\Forms\EquipoForm;

use App\Helper\FileUploader;
use Symfony\Bundle\FrameworkBundle\Controller\AbstractController;
use Symfony\Component\HttpFoundation\Exception\BadRequestException;
use Symfony\Component\HttpFoundation\Request;
use Symfony\Component\HttpFoundation\Response;
use Symfony\Component\Routing\Annotation\Route;

class EquipoController extends AbstractController
{
    /**
     * @Route("/equipos/", name="futapp_equipos",
     *     methods={"GET"})
     */
    public function getEquipos(): Response
    {
        $equipos = $this->getDoctrine()->getRepository(Equipo::class)->findAll();
        return $this->render('equipo/index.html.twig', [
            'equipos' => $equipos,
        ]);
    }

    /**
     * @Route("/equipos/nuevo", name="futapp_equipos_form",
     *     methods={"GET","POST"})
     */
    public function newEquipo(Request $request): Response
    {
        $error = null;
        try{
            $equipo = new Equipo();
            $form = $this->createForm(EquipoForm::class,$equipo);

            $form->handleRequest($request);
            if($form->isSubmitted() && $form->isValid()){
                $equipo = $form->getData();
                $fotoFile = $form->get('fotoFile')->getData();


                if($fotoFile){
                    $equipo->setFotoFile($fotoFile);

                }

                $entityManager = $this->getDoctrine()->getManager();
                $entityManager->persist($equipo);
                $entityManager->flush();
                return $this->redirectToRoute('futapp_equipos');
            }

        }catch (BadRequestException $exception){

        }
        return $this->render('equipo/form-equipo.html.twig', [
            'form' => $form->createView(),
            'error'=>$error
        ]);
    }
}
