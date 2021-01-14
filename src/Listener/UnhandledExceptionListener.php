<?php


namespace App\Listener;


use App\Attributes\IsApiRoute;
use App\Controller\Application;
use App\DTO\API\BaseApiResponseDto;
use App\Services\Internal\AttributeReaderService;
use ReflectionException;
use Symfony\Component\EventDispatcher\EventSubscriberInterface;
use Symfony\Component\HttpKernel\Event\ExceptionEvent;
use Symfony\Component\HttpKernel\KernelEvents;

/**
 * The last line to handle the unhandled Exception (also the one thrown in the API logic)
 * In case of API logic - proper API response is being returned
 *
 * Class UnhandledExceptionListener
 * @package App\Listener
 */
class UnhandledExceptionListener implements EventSubscriberInterface
{
    /**
     * @var Application $app
     */
    private Application $app;

    /**
     * @var AttributeReaderService $attributeReaderService
     */
    private AttributeReaderService $attributeReaderService;

    public function __construct(Application $app, AttributeReaderService $attributeReaderService)
    {
        $this->attributeReaderService = $attributeReaderService;
        $this->app                    = $app;
    }

    /**
     * @param ExceptionEvent $event
     * @throws ReflectionException
     */
    public function onException(ExceptionEvent $event)
    {
        $exception                  = $event->getThrowable();
        $calledControllerWithMethod = $event->getRequest()->attributes->get('_controller');

        if( empty($calledControllerWithMethod) ){
            $this->app->getLoggerService()->logThrowable($exception);
            return;
        }

        $isApiMethod = $this->attributeReaderService->hasMethodAttribute($calledControllerWithMethod, IsApiRoute::class);
        if($isApiMethod){
            $baseApiResponse = BaseApiResponseDto::buildInternalServerErrorResponse()->toJsonResponse();
            $event->setResponse($baseApiResponse);
        }

        $this->app->getLoggerService()->logThrowable($exception);
    }

    public static function getSubscribedEvents(): array
    {
        return [
            KernelEvents::EXCEPTION => 'onException'
        ];
    }
}