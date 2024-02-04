<?php

namespace App\Services;

use App\Dtos\BrowsingHistoryEntryDto;
use App\Models\User;
use Illuminate\Support\Facades\DB;
use Illuminate\Support\Facades\Redis;

class BrowsingHistoryService
{
    private User $user;
    private array $history;

    public function __construct(User $user)
    {
        $this->user = $user;
        $this->history = $user->browsing_history['entries'];
    }

    public function getAllHistory(): array
    {
        $user = $this->user;
        return [...Redis::lrange("browsing_histories:$user->id"), ...$this->history];
    }

    public function addHistoryToCache(BrowsingHistoryEntryDto $entryDto)
    {
        $user = $this->user;
        return Redis::lpush("browsing_histories:$user->id", $entryDto->toArray());
    }

    public function persistCachedEntriesToDb(): void
    {
        $user = $this->user;
        // push cached part of browsing history to the database layer
        $this->user->update([
            'browsing_history' => ['entries' => $this->getAllHistory()]
        ]);
        // clear cache to avoid history duplication
        Redis::del("browsing_histories:$user->id");
    }
}
