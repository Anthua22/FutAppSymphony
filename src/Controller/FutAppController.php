<?php

namespace App\Controller;

use App\Entity\Partido;
use App\Entity\Usuario;
use App\Forms\PartidoForm;
use App\Helper\Emails;
use Symfony\Bundle\FrameworkBundle\Controller\AbstractController;
use Symfony\Component\HttpFoundation\Exception\BadRequestException;
use Symfony\Component\HttpFoundation\Response;
use Symfony\Component\HttpFoundation\Request;
use Symfony\Component\Routing\Annotation\Route;

class FutAppController extends AbstractController
{
    /**
     * @Route("/", name="fut_app_inicio",
     *     methods={"GET","POST"})
     */
    public function index(Request  $request): Response
    {
        $partidos = $this->getDoctrine()->getRepository(Partido::class)->getUltimosPartidosAsignados();
        if($request->getMethod()==='POST'){

            $equiponombre = $request->get('nombreequipo');
            if(!empty($equiponombre)){
                $partidosfilter = $this->getDoctrine()->getRepository(Partido::class)->getEquipoByPartido($equiponombre);
            }else{
                $partidosfilter = $this->getDoctrine()->getRepository(Partido::class)->findAll();
            }

            return $this->render('fut_app/index.html.twig', [
                'partidos'=>$partidos,
                'partidosfilter'=>$partidosfilter
            ]);

        }else{
            $partidosfilter = $this->getDoctrine()->getRepository(Partido::class)->findAll();

            return $this->render('fut_app/index.html.twig', [
                'partidos'=>$partidos,
                'partidosfilter'=>$partidosfilter
            ]);
        }

    }

    /**
     * @Route("/partidos/nuevo", name="fut_app_partidos_nuevo",
     *     methods={"GET","POST"})
     */
    public function nuevoPartido(Request  $request, Emails $emails):Response{

        try{
            $partido = new Partido();

            $fecha_actual = new \DateTime();
            $form = $this->createForm(PartidoForm::class,$partido);

            $form->handleRequest($request);

            if($form->isSubmitted() && $form->isValid()){

                $partido = $form->getData();

                if($fecha_actual < $partido->getFechaEncuentro())
                {
                    $partido->setFechaAsignacion(new \DateTime());
                    $partido->setDisputado(false);
                    if($partido->getEquipoLocal()!==$partido->getEquipoVisitante()){
                        $emails->sendDesignacion();
                        $entityManager = $this->getDoctrine()->getManager();
                        $entityManager->persist($partido);
                        $entityManager->flush();
                        $emails->setPartido($partido);

                        return $this->redirectToRoute('fut_app_inicio');
                    }

                    $this->addFlash('error','El equipo visitante no puede ser el mismo que el equipo local');


                }
                $this->addFlash('error','La fecha introducida ya ha pasado');

            }

        }catch (BadRequestException $ex){
            $this->addFlash('error',$ex->getMessage());
        }catch (\Exception $exception){
            $this->addFlash('error',$exception->getMessage());
        }

        return $this->render('fut_app/form-partido.html.twig',[
            'form'=>$form->createView()
        ]);

    }

    /**
     * @Route(
     *     "/partidos/{id}/editar",
     *     name="futapp_editar_partido",
     *     requirements={"id"="\d+"},
     *     methods={"GET", "POST"}
     * )
     */
    public function editar(Request $request,Partido $partido)
    {
        $form = $this->createForm(PartidoForm::class, $partido);

        $form->handleRequest($request);

        if($form->isSubmitted() && $form->isValid()){
            $partido = $form->getData();
            $entityManager = $this->getDoctrine()->getManager();
            $entityManager->persist($partido);
            $entityManager->flush();

            return $this->redirectToRoute('fut_app_inicio');
        }

        return $this->render('fut_app/form-partido.html.twig',[
            'form'=>$form->createView()
        ]);
    }

    /**
     * @Route(
     *     "/partidos/{id}/detalle",
     *     name="futapp_detalle_partido",
     *     requirements={"id"="\d+"},
     *     methods={"GET"}
     * )
     */
    public function detalle(Partido $partido):Response{
        return $this->render('fut_app/partido-detail.html.twig',[
            'partido'=>$partido
        ]);
    }

    /**
     * @Route(
     *     "/partidos/{id}/borrar",
     *     name="futapp_borrar_partido",
     *     requirements={"id"="\d+"},
     *     methods={"GET"}
     * )
     */
    public function borrarPartido(Partido $partido){
        $manager = $this->getDoctrine()->getManager();
        $manager->remove($partido);
        $manager->flush();
        return $this->redirectToRoute('fut_app_inicio');

    }

    /**
     * @Route("/partidos/disputados", name="fut_app_partidos_disputados")
     */
    public function partidosDisputados():Response{
        $partidosfilter = $this->getDoctrine()->getRepository(Partido::class)->findBy([
            'disputado'=>true
        ]);

        $partidos = $this->getDoctrine()->getRepository(Partido::class)->getUltimosPartidosAsignados();

        return $this->render('fut_app/index.html.twig',[
            'partidos'=>$partidos,
            'partidosfilter'=>$partidosfilter
        ]);

    }

    /**
     * @Route("/partidos/no_disputados", name="fut_app_partidos_no_disputados")
     */
    public function partidosSinDisputados():Response{
        $partidosfilter = $this->getDoctrine()->getRepository(Partido::class)->findBy([
            'disputado'=>false
        ]);

        $partidos = $this->getDoctrine()->getRepository(Partido::class)->getUltimosPartidosAsignados();

        return $this->render('fut_app/index.html.twig',[
            'partidos'=>$partidos,
            'partidosfilter'=>$partidosfilter
        ]);

    }

    /**
     * @Route(
     *     "/partidos/{id}/observaciones",
     *     name="futapp_observaciones_partido",
     *     requirements={"id"="\d+"},
     *     methods={"POST"}
     * )
     */
    public function postDetallesPartido(Request $request, Partido $partido)
    {
        $partido->setResultado($request->get('resultado'));
        $partido->setDisputado($request->get('acabado'));
        if($request->get('observaciones')){
            $partido->setObservaciones($request->get('observaciones'));
        }

        $entityManager = $this->getDoctrine()->getManager();
        $entityManager->persist($partido);
        $entityManager->flush();

        return $this->redirectToRoute('futapp_detalle_partido',['id'=>$partido->getId()]);

    }


}
