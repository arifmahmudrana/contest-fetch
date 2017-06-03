<?php
/**
 * Created by PhpStorm.
 * User: rana
 * Date: 6/3/17
 * Time: 12:55 PM
 */

namespace App\ContestProcessor;


use Carbon\Carbon;

class TophProcessor extends AbstractContestProcessor
{
    public function process($response)
    {
        $collect = collect();
        if ($response->count())
        {
            $urlParts = parse_url($response->getBaseHref());
            $host = $urlParts['scheme'] . '://' . $urlParts['host'];
            $response->each(function ($node) use ($collect, $host) {
                $tds = $node->filter('td');
                $tdFirst = $tds->first();
                $tdLast = $tds->last();

                $title = trim($tdFirst->filter('a')->html());
                $url = $host . $tdFirst->filter('a')->attr('href');
                $start = Carbon::createFromTimestampUTC(
                    $tdLast->filter('span')->attr('data-time')
                );

                $collect->push([
                    'title' => $title,
                    'url' => $url,
                    'start' => $start,
                ]);
            });
        }

        return $collect;
    }
}