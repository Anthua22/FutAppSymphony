<?php

namespace App\Entity;

use App\Repository\UsuarioRepository;
use Doctrine\ORM\Mapping as ORM;
use Exception;
use Symfony\Component\Security\Core\User\UserInterface;

/**
 * @ORM\Entity(repositoryClass=UsuarioRepository::class)
 */
class Usuario implements UserInterface, \Serializable
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
    private $password;

    /**
     * @ORM\Column(type="string", length=255)
     */
    private $nombre;

    /**
     * @ORM\Column(type="boolean")
     */
    private $activo;

    /**
     * @ORM\Column(type="string", length=255, nullable=true)
     */
    private $apellidos;

    /**
     * @ORM\Column(type="string", length=255, nullable=true)
     */
    private $foto;

    /**
     * @ORM\Column(type="string", length=255)
     */
    private $role;

    /**
     * @ORM\Column(type="string", length=255)
     */
    private $email;

    /**
     * @ORM\Column(type="string", length=255, nullable=true)
     */
    private $telefono;

    private $fotoFile;

    /**
     * @ORM\Column(type="datetime", nullable=true)
     */
    private $updateAT;

    private $foto_antigua;

    /**
     * @return mixed
     */
    public function getFotoAntigua()
    {
        return $this->foto_antigua;
    }

    /**
     * @param mixed $foto_antigua
     */
    public function setFotoAntigua($foto_antigua): void
    {
        $this->foto_antigua = $foto_antigua;
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
     */
    public function setFotoFile($fotoFile): void
    {
        $this->fotoFile = $fotoFile;
    }



    /**
     * @return mixed
     */
    public function getActivo()
    {
        return $this->activo;
    }

    /**
     * @param mixed $activo
     * @return Usuario
     */
    public function setActivo($activo)
    {
        $this->activo = $activo;
        return $this;
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

    public function getApellidos(): ?string
    {
        return $this->apellidos;
    }

    public function setApellidos(?string $apellidos): self
    {
        $this->apellidos = $apellidos;

        return $this;
    }

    public function getFoto(): ?string
    {
        return $this->foto;
    }

    public function setFoto(?string $foto): self
    {
        $this->foto = $foto;

        return $this;
    }

    public function getRole(): ?string
    {
        return $this->role;
    }

    public function setRole(string $role): self
    {
        $this->role = $role;

        return $this;
    }

    public function getEmail(): ?string
    {
        return $this->email;
    }

    public function setEmail(string $email): self
    {
        $this->email = $email;

        return $this;
    }

    public function getId(): ?int
    {
        return $this->id;
    }

    public function getPassword(): ?string
    {
        return $this->password;
    }

    public function setPassword(string $password): self
    {
        $this->password = $password;

        return $this;
    }


    public function getTelefono(): ?string
    {
        return $this->telefono;
    }

    public function setTelefono(?string $telefono): self
    {
        $this->telefono = $telefono;

        return $this;
    }

    public function getRoles()
    {
        return [$this->getRole()];
    }

    public function getSalt()
    {
        return null;
    }

    public function getUsername()
    {
        return $this->getEmail();
    }

    public function eraseCredentials()
    {

    }



    public function serialize()
    {
        return serialize([
            $this->id,
            $this->email,
            $this->password,
            $this->role
        ]);
    }

    public function unserialize($serialized)
    {
        list(
            $this->id,
            $this->email,
            $this->password,
            $this->role
            )=unserialize($serialized);
    }

    public function __toString()
    {
        if($this->role === 'ROLE_ADMIN')
            return $this->nombre.' '. $this->apellidos.' - Administrador';
        return $this->nombre.' '. $this->apellidos.' - Árbitro';
    }

    public function getUpdateAT(): ?\DateTimeInterface
    {
        return $this->updateAT;
    }

    public function setUpdateAT(?\DateTimeInterface $updateAT): self
    {
        $this->updateAT = $updateAT;

        return $this;
    }


}
