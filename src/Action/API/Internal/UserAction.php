<?php


namespace App\Action\API\Internal;


use App\Controller\Application;
use App\DTO\API\Internal\LoggedInUserDataDto;
use Symfony\Bundle\FrameworkBundle\Controller\AbstractController;
use Symfony\Component\HttpFoundation\JsonResponse;
use Symfony\Component\HttpFoundation\Request;
use Symfony\Component\HttpFoundation\Response;
use Symfony\Component\Routing\Annotation\Route;

#[Route("/api-internal", name: "api_internal_")]
class UserAction extends AbstractController
{
    /**
     * @var Application $app
     */
    private Application $app;

    public function __construct(Application $app)
    {
        $this->app = $app;
    }

    /**
     * Will return the @see LoggedInUserDataDto as the json
     * @return JsonResponse
     */
    #[Route("/get-logged-in-user-data", name:"get_logged_in_user_data", methods: [Request::METHOD_GET])]
    public function getLoggedInUserData(): JsonResponse
    {
        $loggedInUser = $this->app->getLoggedInUser();

        $loggedInUserDataDto = new LoggedInUserDataDto();
        $loggedInUserDataDto->setCode(Response::HTTP_OK);
        $loggedInUserDataDto->setSuccess(true);;
        $loggedInUserDataDto->setAvatar($loggedInUser->getAvatar() ?? "");
        $loggedInUserDataDto->setShownName($loggedInUser->getUsedName());

        return $loggedInUserDataDto->toJsonResponse();
    }
}