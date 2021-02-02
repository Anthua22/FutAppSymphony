<?php

namespace App\Entity;

use App\Repository\EquipoRepository;
use Doctrine\ORM\Mapping as ORM;

/**
 * @ORM\Entity(repositoryClass=EquipoRepository::class)
 */
class Equipo
{
    /**
     * @ORM\Id
     * @ORM\GeneratedValue
     * @ORM\Column(type="integer")
     */
    private $id;

    /**
     * @ORM\Column(type="string", length=255)
     */
    private $nombre;

    /**
     * @ORM\Column(type="string", length=255, nullable=true)
     */
    private $foto;

    private $fotoFile;

    /**
     * @ORM\Column(type="string", length=255)
     */
    private $correo;

    /**
     * @ORM\Column(type="string", length=255)
     */
    private $direccion_campo;

    public function getId(): ?int
    {
        return $this->id;
    }

    public function getNombre(): ?string
    {
        return $this->nombre;
    }

    public function setNombre(string $nombre): self
    {
        $this->nombre = $nombre;

        return $this;
    }

    public function getFoto()
    {
        return $this->foto;
    }

    /**
     * @return mixed
     */
    public function getFotoFile()
    {
        return $this->fotoFile;
    }

    /**
     * @param mixed $fotoFile
     * @return Equipo
     */
    public function setFotoFile($fotoFile)
    {
        $this->fotoFile = $fotoFile;
        return $this;
    }




    public function setFoto(?string $foto): self
    {
        $this->foto = $foto;

        return $this;
    }

    public function getCorreo(): ?string
    {
        return $this->correo;
    }

    public function setCorreo(string $correo): self
    {
        $this->correo = $correo;

        return $this;
    }

    public function getDireccionCampo(): ?string
    {
        return $this->direccion_campo;
    }

    public function setDireccionCampo(string $direccion_campo): self
    {
        $this->direccion_campo = $direccion_campo;

        return $this;
    }
}
