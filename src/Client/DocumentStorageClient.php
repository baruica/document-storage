<?php

namespace ETS\DocumentStorage\Client;

interface DocumentStorageClient
{
    /**
     * @param resource|string $pathOrBody
     * @param string          $docName  the name of the file once uploaded
     * @param string          $oldDocName
     *
     * @return string the document key
     *
     * @throws ETS\DocumentStorage\Exception\DocumentNotUploadedException If upload failed
     */
    public function upload($pathOrBody, $docName, $oldDocName = null);

    /**
     * @param string $docName
     *
     * @throws ETS\DocumentStorage\Exception\DocumentNotFoundException If no document is found
     */
    public function download($docName);

    /**
     * @param string $docName
     *
     * @return string the link to download the document
     */
    public function getDownloadLink($docName);
}
