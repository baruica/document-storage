<?php

namespace ETS\DocumentStorage\Client;

interface DocumentStorage
{
    /**
     * @param string $filePath
     * @param string $docName  the name of the file once uploaded
     * @param string $docKey
     *
     * @return string           the document key
     */
    public function upload($filePath, $docName = null, $docKey = null);

    /**
     * @param  string $docKey
     */
    public function download($docKey);

    /**
     * @param  string $docKey
     * @param  string $saveAs the full path to use to save the downloaded file to
     */
    public function saveAs($docKey, $saveAs);

    /**
     * @param  string $docKey
     *
     * @return string
     */
    public function getDownloadLink($docKey);
}
