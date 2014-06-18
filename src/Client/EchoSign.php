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
    private $echoSignClient;

    /**
     * @var ETS\EchoSignBundle\Api\Parameter\RecipientInfoCollection
     */
    private $recipients;

    /**
     * @param ETS\EchoSignBundle\Api\Client                            $echoSignClient
     * @param ETS\EchoSignBundle\Api\Parameter\RecipientInfoCollection $recipients
     */
    public function __construct(Client $echoSignClient, RecipientInfoCollection $recipients)
    {
        $this->echoSignClient = $echoSignClient;
        $this->recipients = $recipients;
    }

    /**
     * @see DocumentStorage::upload
     */
    public function upload($pathOrBody, $docName = null, $docKey = null)
    {
        if (!file_exists($pathOrBody)) {
            throw new \InvalidArgumentException(sprintf('Cannot read file for upload [%s]', $pathOrBody));
        }

        if (null !== $docKey) {
            // call echosign and check if document alredy exist, if this is the case, we delete the document.
            $info = $this->echoSignClient->getDocumentInfo($docKey);
            if ($info) {
                try {
                    $this->echoSignClient->removeDocument($docKey);
                } catch (DocumentNotFoundException $e) {}
            }
        }

        $fileInfo = new \SplFileInfo($pathOrBody);

        if (null === $docName) {
            $docName = basename($pathOrBody);
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

        $docKey = $this->echoSignClient->sendDocument($documentInfo);

        return $docKey;
    }

    /**
     * @see DocumentStorage::download
     */
    public function download($docKey)
    {}

    /**
     * @see DocumentStorage::getDownloadLink
     */
    public function getDownloadLink($docKey)
    {
        return $this->echoSignClient->getDocumentUrls($docKey);
    }
}