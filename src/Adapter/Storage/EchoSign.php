<?php

namespace ETS\DocumentStorage\Adapter\Storage;

use ETS\EchoSignBundle\Api\Client;
use ETS\EchoSignBundle\Api\Parameter\DocumentCreationInfo;
use ETS\EchoSignBundle\Api\Parameter\FileInfo;
use ETS\EchoSignBundle\Api\Parameter\FileInfoCollection;
use ETS\EchoSignBundle\Api\Parameter\RecipientInfoCollection;

use ETS\DocumentStorage\Storage;
use ETS\DocumentStorage\Exception\DocumentNotStoredException;

class EchoSign implements Storage
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
     * @see ETS\DocumentStorage\Storage::store
     */
    public function store($pathOrBody, $docName, $oldDocName = null)
    {
        if (!file_exists($pathOrBody)) {
            throw new DocumentNotStoredException(sprintf('Cannot read file [%s]', $pathOrBody));
        }

        $fileInfo = new \SplFileInfo($pathOrBody);

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
            throw new DocumentNotStoredException(sprintf('Failed storing [%s]: %s', $docName, $e->getMessage()));
        }

        if (null !== $oldDocName) {
            // call echosign and check if document alredy exist, if this is the case, we delete the document.
            if (null !== $this->echoSignClient->getDocumentInfo($oldDocName)) {
                try {
                    $this->echoSignClient->removeDocument($oldDocName);
                } catch (\Exception $e) {}
            }
        }

        return $docKey;
    }

    /**
     * @see ETS\DocumentStorage\Storage::retrieve
     */
    public function retrieve($docName)
    {}

    /**
     * @see ETS\DocumentStorage\Storage::getUrl
     */
    public function getUrl($docName)
    {
        return $this->echoSignClient->getDocumentUrls($docName);
    }
}
