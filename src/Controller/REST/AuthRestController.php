<?php


namespace App\Controller\REST;


use App\BLL\UsuarioBLL;
use Symfony\Component\HttpFoundation\Request;
use Symfony\Component\HttpFoundation\Response;
use Symfony\Component\Routing\Annotation\Route;

class AuthRestController extends BaseApiController
{
    /**
     * @Route(
     *     "/auth/login.{_format}",
     *     requirements={"_format": "json"},
     *     defaults={"_format": "json"}
     * )
     */
    public function getTokenAction()
    {
        // The security layer will intercept this request
        return new Response('', Response:: HTTP_UNAUTHORIZED );
    }

    /**
     * @Route(
     *     "/auth/register.{_format}",
     *     name="register",
     *     requirements={"_format": "json"},
     *     defaults={"_format": "json"},
     *     methods={"POST"}
     * )
     */
    public function register(Request $request, UsuarioBLL $userBLL)
    {
        $data = $this->getContent($request);

        $user = $userBLL->nuevo($data);

        return $this->getResponse($user, Response:: HTTP_CREATED );
    }
}