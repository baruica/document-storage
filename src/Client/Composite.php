<?php

namespace ETS\DocumentStorage\Client;

class Composite implements ClientInterface
{
    /**
     * @var array
     */
    private $clients;

    /**
     * @param array $clients
     */
    public function __construct(array $clients)
    {
        $this->clients = $clients;
    }

    /**
     * @see DocumentStorage::upload
     */
    public function upload($pathOrBody, $docName = null, $oldDocKey = null)
    {
        foreach ($this->clients as $client) {
            $client->upload($pathOrBody, $docName, $oldDocKey);
        }
    }

    /**
     * @see DocumentStorage::download
     */
    public function download($docKey)
    {
        foreach ($this->clients as $client) {
            $client->download($docKey);
        }
    }

    /**
     * @see DocumentStorage::getDownloadLink
     */
    public function getDownloadLink($docKey)
    {
        foreach ($this->clients as $client) {
            $client->getDownloadLink($docKey);
        }
    }
}
