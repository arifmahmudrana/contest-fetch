<?php

namespace App\Console\Commands\Contest\Fetch;

use App\ContestProcessor\TophProcessor;
use App\Services\SaveContest;
use Carbon\Carbon;
use Goutte\Client;
use Illuminate\Console\Command;
use Symfony\Component\DomCrawler\Crawler;

class Toph extends Command
{
    use DoRequest;
    /**
     * The name and signature of the console command.
     *
     * @var string
     */
    protected $signature = 'contest:fetch:toph';

    /**
     * The console command description.
     *
     * @var string
     */
    protected $description = 'Crawls & scraps toph contest list & saves to db';
    /**
     * @var TophProcessor
     */
    protected $processor;
    /**
     * @var SaveContest
     */
    protected $saveContest;
    /**
     * @var Client
     */
    protected $client;

    /**
     * Create a new command instance.
     *
     * @param TophProcessor $processor
     * @param SaveContest $saveContest
     * @param Client $client
     */
    public function __construct(
        TophProcessor $processor,
        SaveContest $saveContest,
        Client $client
    )
    {
        parent::__construct();
        $this->processor = $processor;
        $this->saveContest = $saveContest;
        $this->client = $client;
    }

    /**
     * Execute the console command.
     *
     * @return mixed
     */
    public function handle()
    {
        $this->info('Starts: ' . Carbon::now());
        $crawler = $this->client->request(
            'GET', 'https://toph.co/contests'
        );

        $collection = collect();
        while (true)
        {
            $this->info('Fetched request ' . $crawler->getBaseHref());
            if (!$this->hasNext($crawler))
            {
                break;
            }

            $selector   =
                'div.portlet.light > div.portlet-body > div.table-scrollable.table-scrollable-borderless > table.table.table-hover.table-light > tbody tr';
            $collection = $collection->merge(
                $this->processor
                    ->process($crawler->filter($selector))
                    ->toArray()
            );
            $crawler    = $this->clickNext($crawler);
        }

        $this->info('Toph contest found ' . $collection->count());
        $this->info('Saving Toph Web scraped responses to DB');
        $this->info('Total records to be saved in DB ' . $collection->count());
        $this->info('Total records saved in DB '
                    . $this->saveContest->save($collection)
        );
        $this->info('Ends: ' . Carbon::now());
    }

    /**
     * @param $crawler
     * @return bool
     */
    protected function hasNext(Crawler $crawler)
    {
        return (bool) $this->getNext($crawler)->count();
    }

    protected function clickNext(Crawler $crawler)
    {
        $selector =
            'div.portlet.light > div.portlet-body > div.text-center > div.search-page > div.search-pagination > ul.pagination';
        $link = $crawler->filter($selector)->selectLink('»')->link();

        return $this->client->click($link);
    }

    /**
     * @param Crawler $crawler
     * @return Crawler
     */
    protected function getNext(Crawler $crawler)
    {
        $selector =
            'div.portlet.light > div.portlet-body > div.text-center > div.search-page > div.search-pagination > ul.pagination li > a';

        return $crawler->filter($selector)
        ->reduce(function (Crawler $node)
        {
            return $node->html() == '»';
        });
    }
}
