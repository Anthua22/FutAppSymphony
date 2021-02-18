<?php

namespace App\Controller;

use App\Entity\Equipo;
use App\Entity\Partido;
use App\Forms\EquipoForm;

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
     * @Route("/admin/equipo_nuevo", name="futapp_equipos_form",
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
            $error = $exception->getMessage();
        }
        return $this->render('equipo/form-equipo.html.twig', [
            'form' => $form->createView(),
            'error'=>$error
        ]);
    }

    /**
     * @Route(
     *     "/admin/equipos/{id}/editar",
     *     name="futapp_editar_equipo",
     *     requirements={"id"="\d+"},
     *     methods={"GET", "POST"}
     * )
     */
    public function editarEquipo(Request $request, Equipo $equipo){
        $form = $this->createForm(EquipoForm::class,$equipo);

        $form->handleRequest($request);

        if($form->isSubmitted() && $form->isValid()){
            $equipo = $form->getData();
            $fotoFile = $form['fotoFile']->getData();

            if($fotoFile){
                $equipo->setFotoAntigua($equipo->getFoto());
                $equipo->setFotoFile($fotoFile);
                $equipo->setUpdateAt(new \DateTime());
            }

            $entityManager = $this->getDoctrine()->getManager();
            $entityManager->persist($equipo);
            $entityManager->flush();

            return $this->redirectToRoute('futapp_equipos');

        }

        return $this->render('equipo/form-equipo.html.twig',[
            'form'=>$form->createView()
        ]);
    }


    /**
     * @Route(
     *     "/admin/equipos/{id}/borrar",
     *     name="futapp_borrar_equipo",
     *     requirements={"id"="\d+"},
     *     methods={"GET"}
     * )
     */
    public function borrar(Equipo $equipo){
        try{
            $fileName = $equipo->getFoto();
            if(isset($fileName)){
                unlink(__DIR__.'/../../public/uploads/fotos/'.$fileName);
            }
            $manager = $this->getDoctrine()->getManager();
            $manager->remove($equipo);
            $manager->flush();
            return $this->redirectToRoute('futapp_equipos');
        }catch (BadRequestException $exception){
            $this->addFlash('error',$exception );
        }

    }

    /**
     * @Route(
     *     "/equipos/{id}/detalle",
     *     name="futapp_detalle_equipo",
     *     requirements={"id"="\d+"},
     *     methods={"GET", "POST"}
     * )
     */
    public function detalle(Equipo $equipo):Response{
        $partidosNuevos = $this->getDoctrine()->getRepository(Partido::class)->getPartidosNuevosEquipo($equipo);
        $partidosPasados = $this->getDoctrine()->getRepository(Partido::class)->getPartidosPasadosEquipo($equipo);

        return $this->render('equipo/equipo-detail.html.twig',[
            'equipo'=>$equipo,
            'partidosnuevos'=>$partidosNuevos,
            'partidospasados'=>$partidosPasados
        ]);
    }

}
