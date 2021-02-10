<?php


namespace App\Controller;


use App\Entity\Usuario;
use Symfony\Bundle\FrameworkBundle\Controller\AbstractController;
use Symfony\Component\HttpFoundation\Request;
use Symfony\Component\Routing\Annotation\Route;
use Symfony\Component\Security\Core\Encoder\UserPasswordEncoderInterface;
use Symfony\Component\Security\Http\Authentication\AuthenticationUtils;


class SecurityController extends AbstractController
{


    /**
     * @Route(
     *     "/login",
     *     name="futapp_login",
     *     methods={"GET","POST"}
     * )
     */
    public function login(Request $request, AuthenticationUtils $authUtils)
    {
        $error = $authUtils->getLastAuthenticationError();
// last username entered by the user
        $lastUsername = $authUtils->getLastUsername();

        return $this->render('security/login.html.twig', [
            'last_username' => $lastUsername,
            'error' => $error,
        ]);


    }

    /**
     * @Route(
     *     "/login/check",
     *     name="futapp_check_login",
     *     methods={"POST"}
     * )
     */
    function checkLogin()
    {

    }

    /**
     * @Route(
     *     "/logout",
     *     name="futapp_logout"
     * )
     */
    function logout()
    {

    }

    /**
     * @Route(
     *     "/registro",
     *     name="futapp_register",
     *     methods={"GET","POST"}
     * )
     */
    public function registro(Request $request, UserPasswordEncoderInterface $encoder)
    {
        if ($request->getMethod() == 'POST') {
            $password = $request->get('password');
            $passwordcheck = $request->get('password2');
            if(!$this->checkEmailRepeat($request->get('email'))){
                if ($password === $passwordcheck) {
                    define("CLAVE_SECRETA", "6Lf94UsaAAAAAAMRHun72PLiRl50ii0yNqST0qHj");
                    if (empty($request->get('g-recaptcha-response'))) {
                        $this->addFlash('error_registro', 'Debes completar el captcha');
                        $this->asignarDatos($request);
                        return $this->redirectToRoute('futapp_register');
                    }
                    $token = $request->get('g-recaptcha-response');
                    $verificado = $this->checkCaptcha($token, CLAVE_SECRETA);
                    if ($verificado) {
                        $usario = new Usuario();
                        $encoded = $encoder->encodePassword($usario, $request->get('password'));
                        $usario->setRole('ROLE_USER')
                            ->setEmail($request->get('email'))
                            ->setPassword($encoded)
                            ->setNombre($request->get('nombre'))
                            ->setActivo(true)
                            ->setTelefono($request->get('telefono'))
                            ->setApellidos($request->get('apellidos'))
                            ->setFotoFile($request->files->get('foto'));
                        $entityManager = $this->getDoctrine()->getManager();
                        $entityManager->persist($usario);
                        $entityManager->flush();
                        return $this->redirectToRoute('futapp_login');

                    } else {
                        $this->addFlash('error_registro', 'Eres un robot!');
                        $this->asignarDatos($request);
                        return $this->redirectToRoute('futapp_register');
                    }


                } else {
                    $this->addFlash('error_registro', 'Las contraseñas no coinciden');
                    $this->asignarDatos($request);
                }
            }else{
                $this->addFlash('error_registro', 'El correo ya existe');
                    $this->asignarDatos($request);
            }



        }

        return $this->render('security/registro.html.twig');
    }

    private function asignarDatos(Request $request){
        $this->addFlash('email', $request->get('email'));
        $this->addFlash('nombre',$request->get('nombre'));
        $this->addFlash('apellidos',$request->get('apellidos'));
        $this->addFlash('telefono',$request->get('telefono'));

    }

    private function checkCaptcha($token, $claveSecreta)
    {
# La API en donde verificamos el token
        $url = "https://www.google.com/recaptcha/api/siteverify";
        # Los datos que enviamos a Google
        $datos = [
            "secret" => $claveSecreta,
            "response" => $token,
        ];
        // Crear opciones de la petición HTTP
        $opciones = array(
            "http" => array(
                "header" => "Content-type: application/x-www-form-urlencoded\r\n",
                "method" => "POST",
                "content" => http_build_query($datos), # Agregar el contenido definido antes
            ),
        );
        # Preparar petición
        $contexto = stream_context_create($opciones);
        # Hacerla
        $resultado = file_get_contents($url, false, $contexto);
        # Si hay problemas con la petición (por ejemplo, que no hay internet o algo así)
        # entonces se regresa false. Este NO es un problema con el captcha, sino con la conexión
        # al servidor de Google
        if ($resultado === false) {
            # Error haciendo petición
            return false;
        }

        # En caso de que no haya regresado false, decodificamos con JSON
        # https://parzibyte.me/blog/2018/12/26/codificar-decodificar-json-php/

        $resultado = json_decode($resultado);
        # La variable que nos interesa para saber si el usuario pasó o no la prueba
        # está en success
        $pruebaPasada = $resultado->success;
        # Regresamos ese valor, y listo (sí, ya sé que se podría regresar $resultado->success)
        return $pruebaPasada;
    }

    private function  checkEmailRepeat(string $correo){
        $usuarios = $this->getDoctrine()->getRepository(Usuario::class)->findBy(['email'=>$correo]);
        return count($usuarios) > 0 ? true:false;
    }
}