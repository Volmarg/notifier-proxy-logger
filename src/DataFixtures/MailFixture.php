<?php

namespace App\DataFixtures;

use App\Entity\Modules\Mailing\Mail;
use Doctrine\Bundle\FixturesBundle\Fixture;
use Doctrine\Persistence\ObjectManager;
use Faker\Factory;
use Faker\Generator;

class MailFixture extends Fixture
{
    const RECORDS_COUNT = 20;

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

            $toEmails = [
              $this->faker->email,
              $this->faker->email,
            ];

            $statusIndex = array_rand(Mail::ALL_STATUSES);
            $status      = Mail::ALL_STATUSES[$statusIndex];

            $mail = new Mail();
            $mail->setBody($this->faker->text);
            $mail->setSubject($this->faker->word);
            $mail->setSource($this->faker->word);
            $mail->setCreated($this->faker->dateTime);
            $mail->setFromEmail($this->faker->email);
            $mail->setStatus($status);
            $mail->setToEmails($toEmails);

            $manager->persist($mail);
        }

        $manager->flush();
    }
}
