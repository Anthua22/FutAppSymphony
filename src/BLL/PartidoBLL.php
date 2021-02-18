<?php


namespace App\BLL;



use App\Entity\Equipo;
use App\Entity\Partido;
use App\Entity\Usuario;
use Symfony\Component\Config\Definition\Exception\Exception;

class PartidoBLL extends BaseBLL
{
    public function nuevo(array $data,Usuario $usuario, Equipo $equipolocal, Equipo $equipovisitante)
    {
        $partido = new Partido();
        $fecha_encuentro = new \DateTime($data['fecha_encuentro']);
        $fecha_asignacion = new \DateTime();
        $partido->setDisputado(false)
            ->setDireccionEncuentro($data['direccion'])
            ->setFechaEncuentro($fecha_encuentro)
            ->setFechaAsignacion($fecha_asignacion)
            ->setArbitro($usuario)
            ->setEquipoVisitante($equipovisitante)
            ->setEquipoLocal($equipolocal);

        return $this->guardaValidando($partido);
    }

    public function editar(array $data, Partido $partido)
    {
        $fecha_encuentro = new \DateTime($data['fecha_encuentro']);
        $fecha_asignacion = new \DateTime();
        $partido->setFechaAsignacion($fecha_asignacion)
            ->setFechaEncuentro($fecha_encuentro)
            ->setDisputado($data['disputado'])
            ->setDireccionEncuentro($data['direccion']);

        return $this->guardaValidando($partido);
    }

    public function disputados():array{
        $partidosfilter = $this->em->getRepository(Partido::class)->findBy([
            'disputado'=>true
        ]);

        return $this->entitiesToArray($partidosfilter);
    }

    public function nodisputados():array
    {
        $partidosfilter = $this->em->getRepository(Partido::class)->findBy([
            'disputado'=>false
        ]);

        return $this->entitiesToArray($partidosfilter);

    }

    public function getByNombre($data):array
    {
        $partidosfilter = $this->em->getRepository(Partido::class)->getEquipoByPartido($data['nombre']);
        return $this->entitiesToArray($partidosfilter);
    }

    public function actaPartido(array $data, Partido $partido)
    {
        $partido->setObservaciones($data['observaciones']);
        $partido->setResultado($data['resultado']);

        return $this->guardaValidando($partido);
    }

    public function getAll(): array
    {
        $partidos = $this->em->getRepository(Partido:: class)->findAll();

        return $this->entitiesToArray($partidos);
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
            'fecha_encuentro'=>$partido->getFechaEncuentro(),
            'observaciones'=>$partido->getObservaciones(),
            'disputado'=>$partido->getDisputado(),
            'direccion'=>$partido->getDireccionEncuentro(),
            'resultado'=>$partido->getResultado()
        ];
    }
}