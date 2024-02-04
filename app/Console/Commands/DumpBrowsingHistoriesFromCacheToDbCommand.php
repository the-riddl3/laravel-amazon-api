<?php

namespace App\Console\Commands;

use App\Models\User;
use Illuminate\Console\Command;
use Illuminate\Support\Collection;
use Illuminate\Support\Facades\Redis;

class DumpBrowsingHistoriesFromCacheToDbCommand extends Command
{
    /**
     * The name and signature of the console command.
     *
     * @var string
     */
    protected $signature = 'app:dump-browsing-histories';

    /**
     * The console command description.
     *
     * @var string
     */
    protected $description = 'Moves all browsing histories from cache to database layer';

    /**
     * Execute the console command.
     */
    public function handle(): void
    {
        /** @var Collection<User> $users */
        $users = User::query()->get();

        foreach($users as $user) {
            $user->getBrowsingHistoryService()
                ->persistCachedEntriesToDb();
        }
    }
}
