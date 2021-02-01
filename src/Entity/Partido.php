<?php

namespace App\Entity;

use App\Repository\PartidoRepository;
use Doctrine\ORM\Mapping as ORM;

/**
 * @ORM\Entity(repositoryClass=PartidoRepository::class)
 */
class Partido
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
    private $direccion_encuentro;

    /**
     * @ORM\Column(type="datetime", length=255)
     */
    private $fecha_encuentro;

    /**
     * @ORM\ManyToOne(targetEntity=Usuario::class, inversedBy="usuarios")
     */
    private $arbitro;

    /**
     * @ORM\ManyToOne(targetEntity=Equipo::class, inversedBy="equipos")
     */
    private $equipoLocal;

    /**
     * @ORM\ManyToOne(targetEntity=Equipo::class, inversedBy="equipos")
     */
    private $EquipoVisitante;

    /**
     * @ORM\Column(type="string", length=6, nullable=true)
     */
    private $resultado;

    /**
     * @ORM\Column(type="string", length=255, nullable=true)
     */
    private $observaciones;

    public function getId(): ?int
    {
        return $this->id;
    }

    public function getDireccionEncuentro(): ?string
    {
        return $this->direccion_encuentro;
    }

    public function setDireccionEncuentro(string $direccion_encuentro): self
    {
        $this->direccion_encuentro = $direccion_encuentro;

        return $this;
    }

    public function getFechaEncuentro(): ?string
    {
        return $this->fecha_encuentro;
    }

    public function setFechaEncuentro(string $fecha_encuentro): self
    {
        $this->fecha_encuentro = $fecha_encuentro;

        return $this;
    }

    public function getArbitro(): ?string
    {
        return $this->arbitro;
    }

    public function setArbitro(string $arbitro): self
    {
        $this->arbitro = $arbitro;

        return $this;
    }

    public function getEquipoLocal(): ?Equipo
    {
        return $this->equipoLocal;
    }

    public function setEquipoLocal(Equipo $equipoLocal): self
    {
        $this->equipoLocal = $equipoLocal;

        return $this;
    }

    public function getEquipoVisitante(): ?Equipo
    {
        return $this->EquipoVisitante;
    }

    public function setEquipoVisitante(Equipo $EquipoVisitante): self
    {
        $this->EquipoVisitante = $EquipoVisitante;

        return $this;
    }

    public function getResultado(): ?string
    {
        return $this->resultado;
    }

    public function setResultado(?string $resultado): self
    {
        $this->resultado = $resultado;

        return $this;
    }

    public function getObservaciones(): ?string
    {
        return $this->observaciones;
    }

    public function setObservaciones(?string $observaciones): self
    {
        $this->observaciones = $observaciones;

        return $this;
    }
}
