<?php

namespace ETS\DocumentStorage\Adapter\Storage;

use ETS\DocumentStorage\Exception\DocumentNotFoundException;
use ETS\DocumentStorage\Storage;

class Composite implements Storage
{
    /** @var \ETS\DocumentStorage\Storage[] */
    private $clients;

    /**
     * @param array $clients
     */
    public function __construct(array $clients)
    {
        $this->clients = $clients;
    }

    /**
     * @inheritdoc
     */
    public function store($pathOrBody, $docName, $oldDocName = null)
    {
        foreach ($this->clients as $client) {
            $client->store($pathOrBody, $docName, $oldDocName);
        }
    }

    /**
     * @inheritdoc
     */
    public function retrieve($docName)
    {
        foreach ($this->clients as $client) {
            return $client->retrieve($docName);
        }

        throw new DocumentNotFoundException(sprintf('Could not retrieve [%s]', $docName));
    }

    /**
     * @inheritdoc
     */
    public function getUrl($docName)
    {
        foreach ($this->clients as $client) {
            return $client->getUrl($docName);
        }

        throw new DocumentNotFoundException(sprintf('Could not retrieve [%s]', $docName));
    }
}
