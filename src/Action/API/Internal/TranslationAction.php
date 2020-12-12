<?php


namespace App\Action\API\Internal;


use App\Controller\Application;
use App\DTO\API\Internal\BaseInternalApiResponseDto;
use App\DTO\API\Internal\GetTranslationsForIdsResponseDto;
use Symfony\Bundle\FrameworkBundle\Controller\AbstractController;
use Symfony\Component\HttpFoundation\JsonResponse;
use Symfony\Component\HttpFoundation\Request;

use Symfony\Component\HttpFoundation\Response;
use Symfony\Component\Routing\Annotation\Route;
use function Symfony\Component\Translation\t;

#[Route("/api-internal", name: "api_internal_")]
class TranslationAction extends AbstractController
{
    const KEY_NAME_TRANSLATIONS_IDS = "translationsIds";

    /**
     * @var Application $app
     */
    private Application $app;

    public function __construct(Application $app)
    {
        $this->app = $app;
    }

    /**
     * @param Request $request
     * @return JsonResponse
     */
    #[Route("/get-translations-for-ids", name:"get_translations_for_strings", methods: ["POST"])]
    public function getTranslationForIds(Request $request): JsonResponse
    {
        $translationsStrings = [];
        $translationIds      = $request->query->get(self::KEY_NAME_TRANSLATIONS_IDS, null);

        if(
                empty($translationIds)
            ||  !is_array($translationIds)
        ){
            return BaseInternalApiResponseDto::buildInternalServerErrorResponse()->toJsonResponse();
        }

        foreach($translationIds as $translationId){
            $translationsStrings[$translationId] = $this->app->trans($translationId);
        }

        $translationsJson = json_encode($translationsStrings);
        $responseDto      = new GetTranslationsForIdsResponseDto($translationsJson);
        $responseDto->setCode(Response::HTTP_OK);
        $responseDto->setSuccess(true);

        return $responseDto->toJsonResponse();
    }

}