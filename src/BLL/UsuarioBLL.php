<?php


namespace App\BLL;


use App\Entity\Usuario;
use Symfony\Component\HttpKernel\Exception\BadRequestHttpException;
use Symfony\Component\Security\Core\Encoder\UserPasswordEncoderInterface;

class UsuarioBLL extends BaseBLL
{
    /** @var UserPasswordEncoderInterface $encoder
     */
    private $encoder;

    private function guardaImagen(Usuario $usuario, $data) {
        $arr_foto = explode (',', $data['foto']);
        if ( count ($arr_foto) < 2)
            throw new BadRequestHttpException('formato de imagen incorrecto');

        $imgFoto = base64_decode ($arr_foto[1]);
        if (!is_null($imgFoto))
        {
            $fileName = $data['nombreFoto'] . '-'. time() . '.jpg';
            $usuario->setFoto($fileName);
            $ifp = fopen ($this->fotosDirectory . '/' . $fileName, "wb");
            if ($ifp)
            {
                $ok = fwrite ($ifp, $imgFoto);

                fclose ($ifp);

                if ($ok)
                    return $this->guardaValidando($usuario);
            }
        }

        throw new \Exception('No se ha podido cargar la imagen del contacto');
    }


    public function setEncoder(UserPasswordEncoderInterface $encoder)
    {
        $this->encoder = $encoder;
    }

    public function getAll(): array
    {
        $usuarios = $this->em->getRepository(Usuario:: class)->findAll();

        return $this->entitiesToArray($usuarios);
    }

    public function nuevo(array $data)
    {
        $user = new Usuario();
        $user->setPassword($this->encoder->encodePassword($user, $data['password']));
        $user->setEmail($data['email']);
        $user->setRole($data['role']);
        $user->setNombre($data['nombre']);
        $user->setApellidos($data['apellidos']);
        $user->setActivo(true);

        $this->guardaImagen($user,$data);
        if(!empty($data['telefono'])){
            $user->setTelefono($data['telefono']);
        }
       ;


        return $this->guardaValidando($user);
    }

    public function profile()
    {
        $user = $this->getUser();

        return $this->toArray($user);
    }

    public function cambiaPassword(string $nuevoPassword)
    {
        $user = $this->getUser();
        $user->setPassword($this->encoder->encodePassword($user, $nuevoPassword));
        return $this->guardaValidando($user);
    }

    public function toArray(Usuario $user)
    {
        if ( is_null ($user))
            return null;

        return [
            'id' => $user->getId(),
            'email' => $user->getEmail(),
            'role' => $user->getRole(),
            'nombre'=>$user->getNombre(),
            'apellidos'=>$user->getApellidos()
        ];
    }

}