<?php


namespace App\Controller\REST;


use App\BLL\PartidoBLL;
use App\BLL\UsuarioBLL;
use App\Entity\Equipo;
use App\Entity\Partido;
use App\Entity\Usuario;
use Symfony\Component\HttpFoundation\Request;
use Symfony\Component\HttpFoundation\Response;
use Symfony\Component\Routing\Annotation\Route;

class PartidoRestController extends BaseApiController
{
    /**
     * @Route(
     *     "/partidos/{id}.{_format}",
     *     name="get_partido",
     *     requirements={"id": "\d+", "_format": "json"},
     *     defaults={"_format": "json"},
     *     methods={"GET"}
     * )
     */
    public function getOne(Partido $partido, PartidoBLL $partidoBLL)
    {
        return $this->getResponse($partidoBLL->toArray($partido));
    }

    /**
     * @Route(
     *     "/partidos.{_format}",
     *     name="get_partidos",
     *     defaults={"_format": "json"},
     *     requirements={"_format": "json"},
     *     methods={"GET"}
     * )
     */
    public function getAll(PartidoBLL $partidoBLL)
    {
        $users = $partidoBLL->getAll();

        return $this->getResponse($users);
    }

    /**
     * @Route(
     *     "/partidos/disputados_api.{_format}",
     *     name="get_partidos_disputados_api",
     *     defaults={"_format": "json"},
     *     requirements={"_format": "json"},
     *     methods={"GET"}
     * )
     */
    public function getDisputados(PartidoBLL $partidoBLL)
    {
        $partidos = $partidoBLL->disputados();

        return $this->getResponse($partidos);
    }

    /**
     * @Route(
     *     "/partidos/no_disputados_api.{_format}",
     *     name="get_partidos_no_disputados",
     *     defaults={"_format": "json"},
     *     requirements={"_format": "json"},
     *     methods={"GET"}
     * )
     */
    public function getNoDisputados(PartidoBLL $partidoBLL)
    {
        $partidos = $partidoBLL->nodisputados();

        return $this->getResponse($partidos);
    }

    /**
     * @Route(
     *     "/partidos/nombre.{_format}",
     *     name="get_partidos_nombre",
     *     defaults={"_format": "json"},
     *     requirements={"_format": "json"},
     *     methods={"POST"}
     * )
     */
    public function getPartidosByNombre(Request $request,PartidoBLL $partidoBLL)
    {
        $data = $this->getContent($request);
        $partidos = $partidoBLL->getByNombre($data);

        return $this->getResponse($partidos);
    }

    /**
     * @Route(
     *     "/partidos/{id}.{_format}",
     *     name="delete_partido",
     *     requirements={"id": "\d+", "_format": "json"},
     *     defaults={"_format": "json"},
     *     methods={"DELETE"}
     * )
     */
    public function delete(Partido $partido, PartidoBLL $partidoBLL)
    {
        $partidoBLL->delete($partido);

        return $this->getResponse(null, Response::HTTP_NO_CONTENT);
    }

    /**
     * @Route(
     *     "/partidos/nuevo.{_format}",
     *     name="partido_nuevo",
     *     requirements={"_format": "json"},
     *     defaults={"_format": "json"},
     *     methods={"POST"}
     * )
     */
    public function nuevo(Request $request, PartidoBLL $partidoBLL)
    {
        $data = $this->getContent($request);
        $arbitro = $this->getDoctrine()->getRepository(Usuario::class)->findOneBy(['id'=>$data['arbitro']]);
        $equipolocal = $this->getDoctrine()->getRepository(Equipo::class)->findOneBy(['id'=>$data['equipolocal']]);
        $equipovisitante = $this->getDoctrine()->getRepository(Equipo::class)->findOneBy(['id'=>$data['equipovisitante']]);
        $partido = $partidoBLL->nuevo($data,$arbitro,$equipolocal,$equipovisitante);

        return $this->getResponse($partido,Response::HTTP_CREATED);
    }

    /**
     * @Route(
     *     "/partidos/editar/{id}.{_format}",
     *     name="partido_editar",
     *     requirements={"id": "\d+", "_format": "json"},
     *     defaults={"_format": "json"},
     *     methods={"PUT"}
     * )
     */
    public function update(Partido $partido, Request $request, PartidoBLL $partidoBLL)
    {
        $data = $this->getContent($request);
        $partidoupdate = $partidoBLL->editar($data,$partido);
        return $this->getResponse($partidoupdate,Response::HTTP_ACCEPTED);
    }


    /**
     * @Route(
     *     "/partidos/acta/{id}.{_format}",
     *     name="partido_editar",
     *     requirements={"id": "\d+", "_format": "json"},
     *     defaults={"_format": "json"},
     *     methods={"PATCH"}
     * )
     */
    public function subirActa(Partido $partido, Request $request, PartidoBLL $partidoBLL)
    {
        $data = $this->getContent($request);
        $partido->setDisputado(true);
        $partidoacta= $partidoBLL->actaPartido($data,$partido);
        return $this->getResponse($partidoacta,Response::HTTP_ACCEPTED);
    }
}