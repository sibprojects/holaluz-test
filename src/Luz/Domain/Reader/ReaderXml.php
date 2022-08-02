<?php

namespace App\Luz\Domain\Reader;

class ReaderXml extends Reader
{
    public function prepare()
    {
        $result = [];

        $xml = simplexml_load_string($this->content);

        foreach ($xml->xpath('//reading') as $row){
            $result[] = [
                'client_id' => (string)$row->attributes()->clientID,
                'period' => (string)$row->attributes()->period,
                'reading' => (int)$row,
            ];
        }

        return $result;
    }
}