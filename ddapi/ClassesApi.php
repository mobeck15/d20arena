<?php
require_once 'ApiProcessor.php';

class ClassesApi extends ApiProcessor
{
    protected function modifyApiNode($node)
    {
        //Override this method to modify the data before returning it in the API.
        return $node;
    }
}
