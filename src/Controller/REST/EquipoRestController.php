<?php


namespace App\Controller\REST;


use App\BLL\EquipoBLL;
use Symfony\Component\HttpFoundation\Request;
use Symfony\Component\HttpFoundation\Response;
use Symfony\Component\Routing\Annotation\Route;

class EquipoRestController extends BaseApiController
{

    /**
     * @Route("/equipos.{_format}", name="post_equipos",
     *      defaults={"_format": "json"},
     *      requirements={"_format": "json"},
     *     methods={"POST"}
     * )
     */
    public function post(Request $request, EquipoBLL $equipoBLL){
        $data = $this->getContent($request);
        $equipo = $equipoBLL->nuevo($data);
        return $this->getResponse(
            $equipo, Response:: HTTP_CREATED
        );
    }

}