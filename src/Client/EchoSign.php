<?php

namespace ETS\DocumentStorage\Client;

use ETS\DocumentStorage\Exception\DocumentNotUploadedException;
use ETS\EchoSignBundle\Api\Client;
use ETS\EchoSignBundle\Api\Parameter\DocumentCreationInfo;
use ETS\EchoSignBundle\Api\Parameter\FileInfo;
use ETS\EchoSignBundle\Api\Parameter\FileInfoCollection;
use ETS\EchoSignBundle\Api\Parameter\RecipientInfoCollection;

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
    public function upload($pathOrBody, $docName = null, $oldDocKey = null)
    {
        if (!file_exists($pathOrBody)) {
            throw new DocumentNotUploadedException(sprintf('Cannot read file for upload [%s]', $pathOrBody));
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
                    $fileInfo->getRealPath()
                )
            )),
            DocumentCreationInfo::SIGNATURE_TYPE_ESIGN,
            DocumentCreationInfo::SIGNATURE_FLOW_SENDER_SIGNATURE_NOT_REQUIRED
        );

        try {
            $docKey = $this->echoSignClient->sendDocument($documentInfo);
        } catch (\Exception $e) {
            throw new DocumentNotUploadedException(sprintf('Failed uploading [%s]: %s', $docName, $e->getMessage()));
        }

        if (null !== $oldDocKey) {
            // call echosign and check if document alredy exist, if this is the case, we delete the document.
            if (null !== $info = $this->echoSignClient->getDocumentInfo($oldDocKey)) {
                try {
                    $this->echoSignClient->removeDocument($oldDocKey);
                } catch (\Exception $e) {}
            }
        }

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
