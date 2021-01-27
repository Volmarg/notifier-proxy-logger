<?php


namespace App\Controller;


use App\DTO\Modules\Mailing\SendTestMailDTO;
use App\Entity\Modules\Discord\DiscordWebhook;
use App\Entity\Modules\Mailing\MailAccount;
use App\Entity\User;
use App\Form\Modules\Discord\AddDiscordWebhookForm;
use App\Form\Modules\Discord\SendTestDiscordMessageForm;
use App\Form\Modules\Mailing\MailAccountForm;
use App\Form\Modules\Mailing\SendTestMailForm;
use App\Form\User\UserLoginForm;
use App\Form\User\UserRegisterForm;
use Symfony\Bundle\FrameworkBundle\Controller\AbstractController;
use Symfony\Component\Form\FormInterface;

class Forms extends AbstractController
{
    /**
     * @param User|null $user
     * @param array $options
     * @return FormInterface
     */
    public function getUserLoginForm(?User $user = null, array $options = []): FormInterface
    {
        return $this->createForm(UserLoginForm::class, $user, $options);
    }

    /**
     * @param User|null $user
     * @param array $options
     * @return FormInterface
     */
    public function getUserRegisterForm(?User $user = null, array $options = []): FormInterface
    {
        return $this->createForm(UserRegisterForm::class, $user, $options);
    }

    /**
     * @param SendTestMailDTO|null $sendTestMailDTO
     * @param array $options
     * @return FormInterface
     */
    public function getSendTestMailForm(?SendTestMailDTO $sendTestMailDTO = null, array $options = []): FormInterface
    {
        return $this->createForm(SendTestMailForm::class, $sendTestMailDTO, $options);
    }

    /**
     * @param DiscordWebhook|null $discordWebhook
     * @param array $options
     * @return FormInterface
     */
    public function getSendTestDiscordMessageForm(?DiscordWebhook $discordWebhook = null, array $options = []): FormInterface
    {
        return $this->createForm(SendTestDiscordMessageForm::class, $discordWebhook, $options);
    }

    /**
     * @param DiscordWebhook|null $discordWebhook
     * @param array $options
     * @return FormInterface
     */
    public function getAddDiscordWebhookForm(?DiscordWebhook $discordWebhook = null, array $options = []): FormInterface
    {
        return $this->createForm(AddDiscordWebhookForm::class, $discordWebhook, $options);
    }

    /**
     * @param MailAccount|null $mailAccount
     * @param array $options
     * @return FormInterface
     */
    public function getMailAccount(?MailAccount $mailAccount = null, array $options = []): FormInterface
    {
        return $this->createForm(MailAccountForm::class, $mailAccount, $options);
    }

}