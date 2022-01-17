<?php

namespace App\Controller\Modules\Mailing;

use App\Controller\Core\ConfigLoaders;
use App\Entity\Modules\Mailing\Mail;
use App\Entity\Modules\Mailing\MailAttachment;
use LogicException;
use Symfony\Component\HttpFoundation\File\Exception\FileException;
use Symfony\Component\HttpFoundation\File\File;
use Symfony\Component\HttpKernel\Exception\NotFoundHttpException;
use Symfony\Component\HttpKernel\KernelInterface;
use Symfony\Component\Mailer\Mailer;
use Symfony\Component\Mime\Email;

/**
 * Handles {@see MailAttachment}
 * @link https://symfony.com/doc/5.3/mailer.html#file-attachments
 * @link https://symfony.com/doc/5.3/mailer.html#embedding-images
 */
class MailAttachmentController
{
    private const BASE_64_IMAGE_REGEXP   = '#src="(?<BASE64_STRING>(data:image/[^;]+;base64[^"]+))"#';
    private const BASE_64_CONTENT_REGEXP = '#data:(.*);base64,#';
    private const CID_IMAGE_PREFIX       = "cid_";
    public const CONTENT_TYPE_HTML       = "HTML";
    public const CONTENT_TYPE_TEXT       = "TEXT";

    /**
     * @var KernelInterface $kernel
     */
    private KernelInterface $kernel;

    /**
     * @var ConfigLoaders $configLoaders
     */
    private ConfigLoaders $configLoaders;

    /**
     * @param KernelInterface $kernel
     * @param ConfigLoaders   $configLoaders
     */
    public function __construct(KernelInterface $kernel, ConfigLoaders $configLoaders)
    {
        $this->configLoaders = $configLoaders;
        $this->kernel        = $kernel;
    }

    /**
     * Will turn the <img="base64..."/> into <img="cid:..."/>
     * Some mail clients won't show base64 content so this allows to show it properly.
     *
     * @param Email  $email
     * @param string $contentType - will attach the cid image as attachment if this is equal to {@see MailAttachmentController::CONTENT_TYPE_HTML}
     * @return Email
     */
    public function base64HtmlIntoEmbeddedImage(Email $email, string $contentType): Email
    {
        $content = match($contentType){
            self::CONTENT_TYPE_HTML => $email->getHtmlBody(),
            self::CONTENT_TYPE_TEXT => $email->getTextBody(),
            default                 => throw new LogicException("This content type is not supported: {$contentType}"),
        };

        preg_match_all(self::BASE_64_IMAGE_REGEXP, $content, $matches);
        $base64Matches = $matches['BASE64_STRING'] ?? [];
        foreach($base64Matches as $base64string){
            $uniqueFileName = self::CID_IMAGE_PREFIX . uniqid();
            $base64content  = preg_replace(self::BASE_64_CONTENT_REGEXP, "", $base64string);
            $fileContent    = base64_decode($base64content);
            $content        = str_replace($base64string, "cid:{$uniqueFileName}", $content);

            if($contentType === self::CONTENT_TYPE_HTML){
                // extension must be skipped, else the attachment indeed does work but `cid` image is not shown
                $email->embed($fileContent, $uniqueFileName);
            }
        }

        match($contentType){
            self::CONTENT_TYPE_HTML => $email->html($content),
            self::CONTENT_TYPE_TEXT => $email->text($content),
        };

        return $email;
    }

    /**
     * Will attach files to be sent via {@see Mailer}
     *
     * @param Mail  $mailEntity
     * @param Email $symfonyEmail
     * @return Email
     */
    public function attachFiles(Mail $mailEntity, Email $symfonyEmail): Email
    {
        $allFilesSizeMb = 0;
       foreach($mailEntity->getAttachments() as $attachment){
           $attachmentAbsolutePath = $this->kernel->getProjectDir() . $attachment->getPath();
           if( !file_exists($attachmentAbsolutePath) ){
               throw new NotFoundHttpException("E-Mail Attachment does not exists: {$attachmentAbsolutePath}, E-mail id: {$mailEntity->getId()}");
           }

           $file            = new File($attachmentAbsolutePath);
           $allFilesSizeMb += $file->getSize() / 1024 / 1024;
           if( $allFilesSizeMb > $this->configLoaders->getSystemDataConfigLoader()->getGetAllAttachmentsMaxSizeMb() ){
               $message = "Attachments for E-Mail: {$mailEntity->getId()}, are to big. Max: {$this->configLoaders->getSystemDataConfigLoader()->getGetAllAttachmentsMaxSizeMb()}, got: {$allFilesSizeMb}";
               throw new FileException($message);
           }

           $symfonyEmail->attachFromPath($attachmentAbsolutePath, $attachment->getFileName());
       }

       return $symfonyEmail;
    }

}