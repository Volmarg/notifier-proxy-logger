<?php


namespace App\Action\API\Internal;


use App\Controller\Application;
use App\Controller\Core\Env;
use App\DTO\API\BaseApiResponseDto;
use App\DTO\API\Internal\DotenvIsDemoResponseDto;
use Exception;
use Symfony\Bundle\FrameworkBundle\Controller\AbstractController;
use Symfony\Component\HttpFoundation\JsonResponse;
use Symfony\Component\Routing\Annotation\Route;

#[Route("/api-internal", name: "api_internal_")]
class DotenvInternalApiAction extends AbstractController
{
    /**
     * @var Application $app
     */
    private Application $app;

    /**
     * DotenvInternalApiAction constructor.
     * @param Application $app
     */
    public function __construct(Application $app)
    {
        $this->app = $app;
    }

    /**
     * Returns the information if the project DEMO mode is on
     *
     * @return JsonResponse
     */
    #[Route(path: "/env/is-demo", name: "env_is_demo", methods: ["GET"])]
    public function isDemo(): JsonResponse
    {
        try{
            $dto = new DotenvIsDemoResponseDto();
            $dto->prefillBaseFieldsForSuccessResponse();
            $dto->setDemo(Env::isDemo());

            return $dto->toJsonResponse();
        }catch(Exception $e){
            $this->app->getLoggerService()->logThrowable($e);
            return BaseApiResponseDto::buildInternalServerErrorResponse()->toJsonResponse();
        }
    }

}