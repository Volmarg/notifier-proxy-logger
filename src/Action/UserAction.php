<?php

namespace App\Action;

use App\Controller\Application;
use App\Controller\Core\Controllers;
use App\Controller\Core\Env;
use App\Entity\User;
use Exception;
use Symfony\Bundle\FrameworkBundle\Controller\AbstractController;
use Symfony\Component\HttpFoundation\Request;
use Symfony\Component\HttpFoundation\Response;
use Symfony\Component\Security\Http\Authentication\AuthenticationUtils;

//php8 attribute = do note remove
use Symfony\Component\Routing\Annotation\Route;

/**
 * Actions related to registering / login etc, all user based actions
 *
 * Class UserAction
 * @package App\Action
 */
class UserAction extends AbstractController
{
    /**
     * @var Application $application
     */
    private Application $application;

    /**
     * @var Controllers $controllers
     */
    private Controllers $controllers;

    /**
     * UserAction constructor.
     * @param Application $application
     * @param Controllers $controllers
     */
    public function __construct(Application $application, Controllers $controllers)
    {
        $this->application = $application;
        $this->controllers = $controllers;
    }

    /**
     * @param AuthenticationUtils $authenticationUtils
     * @return Response
     */
    #[Route("/login", name: "login", methods: ["GET", "POST"])]
    public function login(AuthenticationUtils $authenticationUtils): Response
    {
        $userLoginForm = $this->application->getForms()->getUserLoginForm();
        $error         = $authenticationUtils->getLastAuthenticationError();
        $errorMessage  = "";

        if( !empty($error) ){
            $errorMessage = $error->getMessage();
        }

        $templateData = [
            'errorMessage'  => $errorMessage,
            'userLoginForm' => $userLoginForm->createView(),
        ];

        return $this->render("user/login/login.twig", $templateData);
    }

    /**
     * @param Request $request
     * @return Response
     * @throws Exception
     */
    #[Route("/register", name: "register", methods: ["GET", "POST"])]
    public function register(Request $request): Response
    {
        if( Env::isDemo() ){
            return $this->redirectToRoute("login");
        }

        $userRegisterForm = $this->application->getForms()->getUserRegisterForm();
        $userRegisterForm->handleRequest($request);

        if( $userRegisterForm->isSubmitted() && $userRegisterForm->isValid() ){
            /**
             * @var User $userFromForm
             */
            $userFromForm    = $userRegisterForm->getData();
            $plainPassword   = $userFromForm->getPassword();

            $securityDto = $this->controllers->getSecurityController()->hashPassword($plainPassword);

            $newUser = clone $userFromForm;
            $newUser->setPassword($securityDto->getHashedPassword());
            $newUser->setRoles([User::ROLE_SUPER_ADMIN]);

            $this->controllers->getUserController()->saveUser($newUser);;

            return $this->redirectToRoute("login");
        }

        $templateData = [
            'userRegisterForm' => $userRegisterForm->createView(),
        ];

        return $this->render('user/register/register.twig', $templateData);
    }

    #[Route("/logout", name: "logout", methods: ["GET"])]
    public function logout()
    {
        throw new \LogicException('This method can be blank - it will be intercepted by the logout key on your firewall.');
    }
}