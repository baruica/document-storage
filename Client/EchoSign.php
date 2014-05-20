<?php

namespace ETS\DocumentStorage\Client;

use ETS\EchoSignBundle\Api\Client;
use ETS\EchoSignBundle\Api\Parameter\DocumentCreationInfo;
use ETS\EchoSignBundle\Api\Parameter\FileInfo;
use ETS\EchoSignBundle\Api\Parameter\FileInfoCollection;
use ETS\EchoSignBundle\Api\Parameter\RecipientInfoCollection;
use ETS\EchoSignBundle\Exception\DocumentNotFoundException;

class EchoSign implements DocumentStorage
{
    /**
     * @var ETS\EchoSignBundle\Api\Client
     */
    private $echoSign;

    private $recipients;

    /**
     * @param ETS\EchoSignBundle\Api\Client                            $echoSign
     * @param ETS\EchoSignBundle\Api\Parameter\RecipientInfoCollection $recipients
     */
    public function __construct(Client $echoSign, RecipientInfoCollection $recipients)
    {
        $this->echoSign = $echoSign;
        $this->recipients = $recipients;
    }

    public function upload($filePath, $docName = null, $docKey = null)
    {
        if (!file_exists($filePath)) {
            throw new \InvalidArgumentException(sprintf('Cannot read file for upload [%s]', $filePath));
        }

        if (null !== $docKey) {
            // call echosign and check if document alredy exist, if this is the case, we delete the document.
            $info = $this->echoSign->getDocumentInfo($docKey);
            if ($info) {
                try {
                    $this->echoSign->removeDocument($docKey);
                } catch (DocumentNotFoundException $e) {}
            }
        }

        $fileInfo = new \SplFileInfo($filePath);

        if (null === $docName) {
            $docName = basename($filePath);
        }

        $documentInfo = new DocumentCreationInfo(
            $this->recipients,
            $docName,
            new FileInfoCollection(array(
                new FileInfo(
                    $docName.'.'.$fileInfo->getExtension(),
                    $fileInfo->getRealPath())
            )),
            DocumentCreationInfo::SIGNATURE_TYPE_ESIGN,
            DocumentCreationInfo::SIGNATURE_FLOW_SENDER_SIGNATURE_NOT_REQUIRED
        );

        $docKey = $this->echoSign->sendDocument($documentInfo);

        return $docKey;
    }

    public function download($docKey)
    {}

    public function saveAs($docKey, $saveAs)
    {}

    public function getDownloadLink($docKey)
    {}
}
