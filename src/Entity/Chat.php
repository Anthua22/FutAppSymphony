<?php

namespace App\Entity;

use App\Repository\ChatRepository;
use Doctrine\ORM\Mapping as ORM;

/**
 * @ORM\Entity(repositoryClass=ChatRepository::class)
 */
class Chat
{
    /**
     * @ORM\Id
     * @ORM\GeneratedValue
     * @ORM\Column(type="integer")
     */
    private $id;

    /**
     * @ORM\Column(type="datetime")
     */
    private $fecha;

    /**
     * @ORM\Column(type="string", length=255)
     */
    private $mensaje;

    /**
     * @ORM\ManyToOne(targetEntity=Usuario::class)
     * @ORM\JoinColumn(onDelete="CASCADE")
     */
    private $emisor;

    /**
     * @ORM\ManyToOne(targetEntity=Usuario::class)
     * @ORM\JoinColumn(onDelete="CASCADE")
     */
    private $receptor;

    public function getId(): ?int
    {
        return $this->id;
    }

    public function getFecha(): ?\DateTimeInterface
    {
        return $this->fecha;
    }

    public function setFecha(\DateTimeInterface $fecha): self
    {
        $this->fecha = $fecha;

        return $this;
    }

    public function getMensaje(): ?string
    {
        return $this->mensaje;
    }

    public function setMensaje(string $mensaje): self
    {
        $this->mensaje = $mensaje;

        return $this;
    }

    public function getEmisor(): ?Usuario
    {
        return $this->emisor;
    }

    public function setEmisor(Usuario $emisor): self
    {
        $this->emisor = $emisor;

        return $this;
    }

    public function getReceptor(): ?Usuario
    {
        return $this->receptor;
    }

    public function setReceptor(Usuario $receptor): self
    {
        $this->receptor = $receptor;

        return $this;
    }
}
