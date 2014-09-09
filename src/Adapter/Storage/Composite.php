<?php

namespace ETS\DocumentStorage\Adapter\Storage;

use ETS\DocumentStorage\Storage;

class Composite implements Storage
{
    /** @var Storage[] */
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
    public function upload($pathOrBody, $docName, $oldDocName = null)
    {
        foreach ($this->clients as $client) {
            $client->upload($pathOrBody, $docName, $oldDocName);
        }
    }

    /**
     * @see DocumentStorage::download
     */
    public function download($docName)
    {
        foreach ($this->clients as $client) {
            return $client->download($docName);
        }
    }

    /**
     * @see DocumentStorage::getDownloadLink
     */
    public function getDownloadLink($docName)
    {
        foreach ($this->clients as $client) {
            return $client->getDownloadLink($docName);
        }
    }
}
