<?php

namespace App\Console\Commands;

use App\Repositories\Backend\Newsfeed\NewsFeedRepositoryContract;
use Carbon\Carbon;
use Illuminate\Console\Command;
use DB;

class NewsfeedNotifs extends Command
{
    /**
     * The name and signature of the console command.
     *
     * @var string
     */
    protected $signature = 'newsfeed_notifs';

    /**
     * The console command description.
     *
     * @var string
     */
    protected $description = 'Newsfeed notification';

    /**
     * Create a new command instance.
     *
     * @return void
     */
    private $newsfeedRepository;
    public function __construct(NewsFeedRepositoryContract $newsfeedRepository)
    {
        parent::__construct();
        $this->newsfeedRepository = $newsfeedRepository;
    }

    /**
     * Execute the console command.
     *
     * @return mixed
     */
    public function handle()
    {
        $this->newsfeedRepository->newsfeedNotifications();
    }
}
