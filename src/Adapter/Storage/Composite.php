<?php

namespace ETS\DocumentStorage\Adapter\Storage;

use ETS\DocumentStorage\Storage;

class Composite implements Storage
{
    /** @var ETS\DocumentStorage\Storage[] */
    private $clients;

    /**
     * @param array $clients
     */
    public function __construct(array $clients)
    {
        $this->clients = $clients;
    }

    /**
     * @see ETS\DocumentStorage\Storage::store
     */
    public function store($pathOrBody, $docName, $oldDocName = null)
    {
        foreach ($this->clients as $client) {
            $client->store($pathOrBody, $docName, $oldDocName);
        }
    }

    /**
     * @see ETS\DocumentStorage\Storage::retrieve
     */
    public function retrieve($docName)
    {
        foreach ($this->clients as $client) {
            return $client->retrieve($docName);
        }
    }

    /**
     * @see ETS\DocumentStorage\Storage::getUrl
     */
    public function getUrl($docName)
    {
        foreach ($this->clients as $client) {
            return $client->getUrl($docName);
        }
    }
}
