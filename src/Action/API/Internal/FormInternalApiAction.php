<?php


namespace App\Action\API\Internal;


use App\Controller\Application;
use App\DTO\API\BaseApiResponseDto;
use App\DTO\API\Internal\CsrfTokenResponseDto;
use Exception;
use Symfony\Bundle\FrameworkBundle\Controller\AbstractController;
use Symfony\Component\HttpFoundation\JsonResponse;
use Symfony\Component\Routing\Annotation\Route;
use Symfony\Component\Security\Csrf\CsrfTokenManagerInterface;

#[Route("/api-internal", name: "api_internal_")]
class FormInternalApiAction extends AbstractController
{

    /**
     * @var CsrfTokenManagerInterface $csrfTokenManager
     */
    private CsrfTokenManagerInterface $csrfTokenManager;

    /**
     * @var Application $app
     */
    private Application $app;

    public function __construct(CsrfTokenManagerInterface $csrfTokenManager, Application $app)
    {
        $this->csrfTokenManager = $csrfTokenManager;
        $this->app              = $app;
    }

    /**
     * Will return the @see CsrfTokenResponseDto containing the csrf token for form submission
     *
     * @param string $formName
     * @return JsonResponse
     */
    #[Route("/get-csrf-token/{formName}", name: "get-csrf-token", methods: ["GET"])]
    public function getCsrfToken(string $formName): JsonResponse
    {
        try{
            $token = $this->csrfTokenManager->getToken($formName);

            $dto = new CsrfTokenResponseDto();
            $dto->prefillBaseFieldsForSuccessResponse();
            $dto->setCsrfToken($token);

            return $dto->toJsonResponse();
        }catch(Exception $e){
            $this->app->getLoggerService()->logThrowable($e);
            return BaseApiResponseDto::buildInternalServerErrorResponse()->toJsonResponse();
        }

    }


}