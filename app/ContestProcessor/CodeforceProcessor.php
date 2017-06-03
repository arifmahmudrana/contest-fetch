<?php
/**
 * Created by PhpStorm.
 * User: rana
 * Date: 6/3/17
 * Time: 11:48 AM
 */

namespace App\ContestProcessor;


use Carbon\Carbon;

class CodeforceProcessor extends AbstractContestProcessor
{
    public function process($response)
    {
        $format = 'http://codeforces.com/contest/%s/virtual';
        $collection = collect($response)
            ->map(function($item) use ($format) {
                return [
                    'title' => array_get($item, 'name'),
                    'start' => Carbon::createFromTimestampUTC(
                        array_get($item, 'startTimeSeconds')
                    ),
                    'url' => sprintf(
                        $format, array_get($item, 'id')
                    ),
                ];
            });

        return $collection;
    }
}