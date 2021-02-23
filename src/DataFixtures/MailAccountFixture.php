<?php

namespace App\DataFixtures;

use App\Entity\Modules\Mailing\MailAccount;
use Doctrine\Bundle\FixturesBundle\Fixture;
use Doctrine\Persistence\ObjectManager;
use Faker\Factory;
use Faker\Generator;

class MailAccountFixture extends Fixture
{
    const RECORDS_COUNT = 7;

    /**
     * @var Generator $faker
     */
    private Generator $faker;

    public function __construct()
    {
        $this->faker = Factory::create("en");
    }

    public function load(ObjectManager $manager)
    {
        for( $x = 0 ; $x < self::RECORDS_COUNT; $x++ ){
            $mailAccount = new MailAccount();
            $mailAccount->setPassword($this->faker->password);
            $mailAccount->setLogin($this->faker->userName);
            $mailAccount->setClient($this->faker->company);
            $mailAccount->setName($this->faker->word);

            $manager->persist($mailAccount);
        }

        $this->addDefaultAccountForDemo($manager);
        $manager->flush();
    }

    /**
     * Will add the default mail account for the demo. Demo uses localhost sendmail,
     * this is only added to show the record markup in the table
     *
     * @param ObjectManager $manager
     */
    private function addDefaultAccountForDemo(ObjectManager $manager)
    {
        $mailAccount = new MailAccount();
        $mailAccount->setPassword($this->faker->password);
        $mailAccount->setLogin($this->faker->userName);
        $mailAccount->setClient($this->faker->company);
        $mailAccount->setName(MailAccount::DEFAULT_CONNECTION_NAME);

        $manager->persist($mailAccount);
    }
}
