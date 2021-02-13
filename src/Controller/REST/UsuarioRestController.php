<?php


namespace App\Controller\REST;



use App\BLL\UserBLL;
use App\BLL\UsuarioBLL;
use App\Entity\Usuario;
use Symfony\Component\HttpFoundation\Request;
use Symfony\Component\HttpFoundation\Response;
use Symfony\Component\HttpKernel\Exception\BadRequestHttpException;
use Symfony\Component\Routing\Annotation\Route;

class UsuarioRestController extends BaseApiController
{



    /**
     * @Route(
     *     "/usuarios/{id}.{_format}",
     *     name="get_usuario",
     *     requirements={"id": "\d+", "_format": "json"},
     *     defaults={"_format": "json"},
     *     methods={"GET"}
     * )
     */
    public function getOne(Usuario $user, UsuarioBLL $userBLL)
    {
        return $this->getResponse($userBLL->toArray($user));
    }


    /**
     * @Route(
     *     "/usuarios.{_format}",
     *     name="get_usuarios",
     *     defaults={"_format": "json"},
     *     requirements={"_format": "json"},
     *     methods={"GET"}
     * )
     */
    public function getAll(UsuarioBLL $userBLL)
    {
        $users = $userBLL->getAll();

        return $this->getResponse($users);
    }


    /**
     * @Route(
     *     "/usuarios/{id}.{_format}",
     *     name="delete_usuario",
     *     requirements={"id": "\d+", "_format": "json"},
     *     defaults={"_format": "json"},
     *     methods={"DELETE"}
     * )
     */
    public function delete(Usuario $user, UsuarioBLL $userBLL)
    {
        $userBLL->delete($user);

        return $this->getResponse(null, Response::HTTP_NO_CONTENT);
    }

    /**
     * @Route(
     *     "/profile.{_format}",
     *     name="profile",
     *     requirements={"_format": "json"},
     *     defaults={"_format": "json"},
     *     methods={"GET"}
     * )
     */
    public function profile(UsuarioBLL $userBLL)
    {
        $user = $userBLL->profile();

        return $this->getResponse($user);
    }

    /**
     * @Route(
     *     "/profile/password.{_format}",
     *     name="cambia_password",
     *     requirements={"_format": "json"},
     *     defaults={"_format": "json"},
     *     methods={"PATCH"}
     * )
     */
    public function cambiaPassword(
        Request $request, UsuarioBLL $userBLL)
    {
        $data = $this->getContent($request);
        if ( is_null ($data['password']) || !isset($data['password']) || empty($data['password']))
            throw new BadRequestHttpException('No se ha recibido el password');

        $user = $userBLL->cambiaPassword($data['password']);

        return $this->getResponse($user);
    }
}