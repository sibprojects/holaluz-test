<?php

namespace App\Luz\Domain\Reader;

class ReaderCsv extends Reader
{
    public function prepare()
    {
        $result = [];

        $data = explode("\n", $this->content);

        foreach ($data as $row){
            $row = str_getcsv($row);
            if((string)$row[0]=='client') continue;
            $result[] = [
                'client_id' => (string)$row[0],
                'period' => (string)$row[1],
                'reading' => (int)$row[2],
            ];
        }

        return $result;
    }
}