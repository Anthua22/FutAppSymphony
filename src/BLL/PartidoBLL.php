<?php


namespace App\BLL;


use App\Entity\Ciudad;
use App\Entity\Partido;
use Symfony\Component\Config\Definition\Exception\Exception;

class PartidoBLL extends BaseBLL
{
    public function nuevo(array $data)
    {
        $partido = new Partido();
        $partido->setDisputado(false)
            ->setArbitro($data['arbitro'])
            ->setDireccionEncuentro($data['direccion'])
            ->setEquipoLocal($data['equipolocal'])
            ->setEquipoVisitante($data['equipovisitante'])
            ->setFechaAsignacion($data['fecha_asignacion'])
            ->setFechaEncuentro($data['fecha_encuentro']);

        return $this->guardaValidando($partido);
    }


    public function toArray($partido)
    {
        if (is_null($partido))
            return null;
        if (!($partido instanceof Partido))
            throw new Exception("La entidad no es un Partido");

        return [
            'id' => $partido->getId(),
            'arbitro' => $partido->getArbitro(),
            'equipolocal'=>$partido->getEquipoLocal(),
            'equipovisitante'=>$partido->getEquipoVisitante(),
            'fecha_asignacion'=>$partido->getFechaAsignacion(),
            'fecha_encuentro'=>$partido->getFechaEncuentro()
        ];
    }
}