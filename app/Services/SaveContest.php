<?php
/**
 * Created by PhpStorm.
 * User: rana
 * Date: 6/3/17
 * Time: 12:18 PM
 */

namespace App\Services;


use App\Contest;
use Illuminate\Support\Collection;

class SaveContest
{
    public function save(Collection $collection = null)
    {
        $totalRecordSaved = 0;
        if ($collection && $collection->count())
        {
            $collection->each(function($item) use (&$totalRecordSaved) {
                try
                {
                    Contest::create($item);
                    $totalRecordSaved++;
                }
                catch (\Exception $e)
                {

                }
            });
        }

        return $totalRecordSaved;
    }
}