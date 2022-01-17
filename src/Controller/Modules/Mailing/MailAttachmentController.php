<?php

namespace App\Controller\Modules\Mailing;

use App\Entity\Modules\Mailing\MailAttachment;
use LogicException;
use Symfony\Component\Mime\Email;

/**
 * Handles {@see MailAttachment}
 */
class MailAttachmentController
{
    private const BASE_64_IMAGE_REGEXP   = '#src="(?<BASE64_STRING>(data:image/[^;]+;base64[^"]+))"#';
    private const BASE_64_CONTENT_REGEXP = '#data:(.*);base64,#';
    private const CID_IMAGE_PREFIX       = "cid_";
    public const CONTENT_TYPE_HTML       = "HTML";
    public const CONTENT_TYPE_TEXT       = "TEXT";


    /**
     * Will turn the <img="base64..."/> into <img="cid:..."/>
     * Some mail clients won't show base64 content so this allows to show it properly.
     *
     * @param Email  $email
     * @param string $contentType - will attach the cid image as attachment if this is equal to {@see MailAttachmentController::CONTENT_TYPE_HTML}
     * @return Email
     */
    public function base64HtmlIntoCidWithAttachment(Email $email, string $contentType): Email
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
            $content       = str_replace($base64string, "cid:{$uniqueFileName}", $content);

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

}