<?php


namespace App\Twig;


use App\Controller\Core\Env as EnvController;
use Twig\Extension\AbstractExtension;
use Twig\TwigFunction;

class Env extends AbstractExtension
{
    public function getFunctions(): array
    {
        return [
          new TwigFunction('isDemo', [$this, 'isDemo']),
        ];
    }

    /**
     * Will return the information if the system has the demo mode on or not
     *
     * @return bool
     */
    public function isDemo(): bool {
        return EnvController::isDemo();
    }
}