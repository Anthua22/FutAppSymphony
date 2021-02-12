<?php


namespace App\BLL;


use App\Entity\Equipo;
use Symfony\Component\Config\Definition\Exception\Exception;

class EquipoBLL extends BaseBLL
{
    public function nuevo(array $data)
    {
        $equipo = new Equipo();
        $equipo->setNombre($data['nombre'])
            ->setCorreo($data['correo'])
            ->setDireccionCampo($data['direccion']);

        return $this->guardaValidando($equipo);
    }

    public function toArray(Equipo $equipo){
        if(is_null($equipo))
            return null;

        if (!($equipo instanceof Equipo))
            throw new Exception("La entidad no es un Partido");

        return [
            'id'=>$equipo->getId(),
            'nombre'=>$equipo->getNombre(),
            'direccion'=>$equipo->getDireccionCampo()
        ];
    }
}