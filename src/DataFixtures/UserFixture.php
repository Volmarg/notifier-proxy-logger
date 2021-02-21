<?php

namespace App\DataFixtures;

use App\Controller\System\SecurityController;
use App\Entity\User;
use Doctrine\Bundle\FixturesBundle\Fixture;
use Doctrine\Persistence\ObjectManager;
use Exception;
use Faker\Factory;
use Faker\Generator;

class UserFixture extends Fixture
{
    const USER_LOGIN        = "admin";
    const USER_RAW_PASSWORD = "admin";

    /**
     * @var Generator $faker
     */
    private Generator $faker;

    /**
     * @var SecurityController $securityController
     */
    private SecurityController $securityController;

    public function __construct(SecurityController $securityController)
    {
        $this->faker              = Factory::create("en");
        $this->securityController = $securityController;
    }

    /**
     * @param ObjectManager $manager
     * @throws Exception
     */
    public function load(ObjectManager $manager)
    {
        $user        = new User();
        $securityDto = $this->securityController->hashPassword(self::USER_RAW_PASSWORD);

        $user->setPassword($securityDto->getHashedPassword());
        $user->setUsername(self::USER_LOGIN);
        $user->setRoles([User::ROLE_SUPER_ADMIN]);
        $user->setDisplayedUsername(self::USER_LOGIN);

        $manager->persist($user);
        $manager->flush();
    }
}
