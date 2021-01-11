<?php

namespace App\DataFixtures;

use App\Entity\Modules\Discord\DiscordWebhook;
use Doctrine\Bundle\FixturesBundle\Fixture;
use Doctrine\Common\DataFixtures\OrderedFixtureInterface;
use Doctrine\Persistence\ObjectManager;
use Faker\Factory;
use Faker\Generator;

class DiscordWebhookFixture extends Fixture implements OrderedFixtureInterface
{
    const RECORDS_COUNT = 5;

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

            $discordWebhook = new DiscordWebhook();
            $discordWebhook->setDescription($this->faker->text);
            $discordWebhook->setUsername($this->faker->word);
            $discordWebhook->setWebhookName($this->faker->word);
            $discordWebhook->setWebhookUrl($this->faker->url);

            $manager->persist($discordWebhook);
        }

        $manager->flush();
    }

    public function getOrder()
    {
        return 1;
    }
}
