<?php

namespace App\Entity;

use App\Repository\PartidoRepository;
use Doctrine\ORM\Mapping as ORM;
use Symfony\Component\Validator\Constraints as Assert;

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
     * @Assert\NotBlank()
     * @Assert\Type("\DateTime")
     */
    private $fecha_encuentro;

    /**
     * @ORM\ManyToOne(targetEntity=Usuario::class, inversedBy="usuarios",cascade="remove")
     * @ORM\JoinColumn(onDelete="CASCADE")
     */
    private $arbitro;

    /**
     * @ORM\ManyToOne(targetEntity=Equipo::class, inversedBy="equipos", cascade="remove")
     * @ORM\JoinColumn(onDelete="CASCADE")
     */
    private $equipoLocal;

    /**
     * @ORM\ManyToOne(targetEntity=Equipo::class, inversedBy="equipos", cascade="remove")
     * @ORM\JoinColumn(onDelete="CASCADE")
     */
    private $EquipoVisitante;

    /**
     * @ORM\Column(type="string", length=6, nullable=true)
     * @ORM\JoinColumn(onDelete="CASCADE")
     */
    private $resultado;

    /**
     * @ORM\Column(type="string", length=255, nullable=true)
     */
    private $observaciones;

    /**
     * @ORM\Column(type="boolean", nullable=true)
     */
    private $disputado;

    /**
     * @ORM\Column(type="datetime", nullable=true)
     */
    private $fecha_asignacion;




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

    public function getFechaEncuentro()
    {
        return $this->fecha_encuentro;
    }

    public function setFechaEncuentro($fecha_encuentro): self
    {
        $this->fecha_encuentro = $fecha_encuentro;

        return $this;
    }

    public function getArbitro(): ?Usuario
    {
        return $this->arbitro;
    }

    public function setArbitro(Usuario $arbitro): self
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

    public function getDisputado(): ?bool
    {
        return $this->disputado;
    }

    public function setDisputado(?bool $disputado): self
    {
        $this->disputado = $disputado;

        return $this;
    }

    public function getFechaAsignacion()
    {
        return $this->fecha_asignacion;
    }

    public function setFechaAsignacion($fecha_asignacion): self
    {
        $this->fecha_asignacion = $fecha_asignacion;

        return $this;
    }



}
