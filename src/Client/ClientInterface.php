<?php

namespace ETS\DocumentStorage\Client;

interface ClientInterface
{
    /**
     * @param resource|string $pathOrBody
     * @param string          $docName  the name of the file once uploaded
     * @param string          $oldDocKey
     *
     * @return string the document key
     *
     * @throws ETS\DocumentStorage\Exception\DocumentNotUploadedException If upload failed
     */
    public function upload($pathOrBody, $docName = null, $oldDocKey = null);

    /**
     * @param string $docKey
     *
     * @throws ETS\DocumentStorage\Exception\DocumentNotFoundException If no document is found
     */
    public function download($docKey);

    /**
     * @param string $docKey
     *
     * @return string the link to download the document
     */
    public function getDownloadLink($docKey);
}
