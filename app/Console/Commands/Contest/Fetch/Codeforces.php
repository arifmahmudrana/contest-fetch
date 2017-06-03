<?php

namespace App\Console\Commands\Contest\Fetch;

use App\ContestProcessor\CodeforceProcessor;
use App\Services\SaveContest;
use Carbon\Carbon;
use Illuminate\Console\Command;

class Codeforces extends Command
{
    use DoRequest;
    /**
     * The name and signature of the console command.
     *
     * @var string
     */
    protected $signature = 'contest:fetch:codeforces';

    /**
     * The console command description.
     *
     * @var string
     */
    protected $description = 'Fetches codeforces contest list & saves to db';
    /**
     * @var CodeforceProcessor
     */
    protected $processor;
    /**
     * @var SaveContest
     */
    protected $saveContest;

    /**
     * Codeforces constructor.
     * @param CodeforceProcessor $processor
     * @param SaveContest $saveContest
     */
    public function __construct(
        CodeforceProcessor $processor,
        SaveContest $saveContest
    )
    {
        parent::__construct();
        $this->processor = $processor;
        $this->saveContest = $saveContest;
    }


    public function handle()
    {
        $this->info('Starts: ' . Carbon::now());
        $this->info('Fetching codeforces API');
        $response = json_decode(
            $this->doRequest(
                'http://codeforces.com/api/contest.list'
            ), true
        );
        $this->info('Fetching codeforces API completed');
        $this->info('Codeforces contest found ' . count($response['result']));

        $this->info('Processing codeforces API responses');
        $collection = $this->processor->process($response['result']);

        $this->info('Saving codeforces API responses to DB');
        $this->info('Total records to be saved in DB ' . $collection->count());
        $this->info('Total records saved in DB '
                    . $this->saveContest->save($collection)
        );
        $this->info('Ends: ' . Carbon::now());
    }
}
