<?php

namespace App\DataFixtures;

use App\Controller\Application;
use App\Entity\Modules\Discord\DiscordMessage;
use Doctrine\Bundle\FixturesBundle\Fixture;
use Doctrine\Common\DataFixtures\OrderedFixtureInterface;
use Doctrine\Persistence\ObjectManager;
use Faker\Factory;
use Faker\Generator;

class DiscordMessagesFixture extends Fixture implements OrderedFixtureInterface
{
    const RECORDS_COUNT = 20;

    /**
     * @var Generator $faker
     */
    private Generator $faker;

    /**
     * @var Application $app
     */
    private Application $app;

    public function __construct(Application $app)
    {
        $this->app   = $app;
        $this->faker = Factory::create("en");
    }

    public function load(ObjectManager $manager)
    {
        for( $x = 0 ; $x < self::RECORDS_COUNT; $x++ ){

            $statusIndex = array_rand(DiscordMessage::ALL_STATUSES);
            $status      = DiscordMessage::ALL_STATUSES[$statusIndex];

            $allWebhooks  = $this->app->getRepositories()->getDiscordWebhookRepository()->findAll();
            $webhookIndex = array_rand($allWebhooks);
            $webhook      = $allWebhooks[$webhookIndex];

            $discordMessage = new DiscordMessage();
            $discordMessage->setMessageContent($this->faker->text);
            $discordMessage->setMessageTitle($this->faker->word);
            $discordMessage->setSource($this->faker->word);
            $discordMessage->setCreated($this->faker->dateTime);
            $discordMessage->setStatus($status);
            $discordMessage->setDiscordWebhook($webhook);

            $manager->persist($discordMessage);
        }

        $manager->flush();
    }

    public function getOrder()
    {
        return 2;
    }
}
