<?php


namespace App\Controller\System;


use App\Controller\Core\Controllers;
use App\DTO\System\SecurityDTO;
use App\Entity\User;
use Exception;
use Symfony\Component\Security\Core\Encoder\NativePasswordEncoder;
use Symfony\Component\Security\Core\Encoder\EncoderFactoryInterface;
use Symfony\Component\Security\Core\Encoder\SelfSaltingEncoderInterface;

class SecurityController {

    /**
     * @var EncoderFactoryInterface $encoderFactory
     */
    private $encoderFactory;

    /**
     * @var Controllers $controllers
     */
    private Controllers $controllers;

    /**
     * SecurityController constructor.
     * @param EncoderFactoryInterface $encoderFactory
     * @param Controllers $controllers
     */
    public function __construct(EncoderFactoryInterface $encoderFactory, Controllers $controllers)
    {
        $this->encoderFactory = $encoderFactory;
        $this->controllers    = $controllers;
    }

    /**
     * @param string $plainPassword
     * @return SecurityDTO|null
     * @throws Exception
     * @see PasswordUpdater::hashPassword() - taken and turned to reusable logic
     */
    public function hashPassword(string $plainPassword): ?SecurityDTO
    {
        $user = new User();

        if (0 === strlen($plainPassword)) {
            return null;
        }

        $encoder = $this->encoderFactory->getEncoder($user);

        if ($encoder instanceof NativePasswordEncoder || $encoder instanceof SelfSaltingEncoderInterface) {
            $salt = null;
        } else {
            $salt = rtrim(str_replace('+', '.', base64_encode(random_bytes(32))), '=');
        }

        $hashedPassword = $encoder->encodePassword($plainPassword, $user->getSalt());

        $security_dto = new SecurityDTO();
        $security_dto->setSalt($salt);
        $security_dto->setPlainPassword($plainPassword);
        $security_dto->setHashedPassword($hashedPassword);

        return $security_dto;
    }

    /**
     * @param User $user
     * @param string $user_password
     * @param string $used_password
     * @param string|null $salt_for_used_password
     * @return bool
     */
    public function isPasswordValid(User $user, string $user_password, string $used_password, ?string $salt_for_used_password = null): bool
    {
        $encoder         = $this->encoderFactory->getEncoder($user);
        $isPasswordValid = $encoder->isPasswordValid($user_password, $used_password, $salt_for_used_password);
        return $isPasswordValid;
    }

}