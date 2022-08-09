<?php

namespace App\Luz\Domain\Processor;

use App\Luz\Domain\Reader\Reader;
use App\Luz\Domain\Reader\ReaderCsv;
use App\Luz\Domain\Reader\ReaderXml;

class Processor
{
    private $filename;
    private $data;

    public function __construct(string $filename)
    {
        $this->filename = $filename;
    }

    public function do()
    {
        $ext = pathinfo($this->filename)['extension'];
        if ($ext == 'csv') {
            $this->parse(new ReaderCsv($this->filename));
        } elseif ($ext == 'xml'){
            $this->parse(new ReaderXml($this->filename));
        } else {

        }

        return $this->check();
    }

    public function parse(Reader $readingSource)
    {
        $readingSource->read();
        $this->data = $readingSource->prepare();
    }

    public function check()
    {
        $customers = [];
        
        // prepare clients data
        foreach ($this->data as $row) {
            if(!isset($customers[$row['client_id']])){
                $customers[$row['client_id']] = [
                    'months' => [],
                    'median' => 0,
                ];
            }
            $customers[$row['client_id']]['months'][$row['period']] = [
                'reading' => $row['reading'],
                'diff_prev_month' => 0,
                'diff_prev_month_percent' => 0,
            ];
        }

        foreach ($customers as &$customer) {
            foreach ($customer['months'] as $key => $month) {

                $keyPrev = \DateTime::createFromFormat('Y-m-d', $key . '-1')
                    ->modify('-1 month')
                    ->format('Y-m');

                if(isset($customer['months'][$keyPrev])){
                    $prevMonth = $customer['months'][$keyPrev];
                    $customer['months'][$key]['diff_prev_month'] = $month['reading'] - $prevMonth['reading'];
                    $customer['months'][$key]['diff_prev_month_percent'] =
                        round((($month['reading'] - $prevMonth['reading']) / $prevMonth['reading']) * 100, 2);
                }
            }

            // calculate median
            $cnt = 0;
            $total = 0;
            foreach ($customer['months'] as $month) {
                if($month['diff_prev_month_percent']>0 && $month['diff_prev_month_percent']<100) {
                    $total += $month['diff_prev_month'];
                    $cnt++;
                }
            }
            $customer['median'] = floor($total / $cnt);
        }
        unset($customer);

        $strangeReading = [];
        foreach ($customers as $clientId => $customer) {
            foreach ($customer['months'] as $key => $month) {
                $keyMonth = \DateTime::createFromFormat('Y-m-d', $key . '-1')->format('m');
                if($keyMonth=='01') continue;
                $diff = floor(($customer['median'] - $month['diff_prev_month']) / $customer['median'] * 100) * -1;
                if($diff>=50 || $diff<=-50){
                    $strangeReading[] = [
                        'client_id' => $clientId,
                        'month' => $key,
                        'reading' => $month['reading'],
                        'median' => $diff,
                    ];
                }
            }
        }

        return $strangeReading;
    }

}