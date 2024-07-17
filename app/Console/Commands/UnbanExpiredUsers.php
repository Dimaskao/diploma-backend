<?php

namespace App\Console\Commands;

use App\Models\BannedUser;
use Carbon\Carbon;
use Illuminate\Console\Command;

class UnbanExpiredUsers extends Command
{
    /**
     * The name and signature of the console command.
     *
     * @var string
     */
    protected $signature = 'app:unban-expired-users';

    /**
     * The console command description.
     *
     * @var string
     */
    protected $description = 'Unban users whose ban period has expired';

    public function __construct()
    {
        parent::__construct();
    }

    public function handle()
    {
        $now = Carbon::now();
        $expiredBans = BannedUser::where('valid_until', '<=', $now)->get();

        foreach ($expiredBans as $ban) {
            $ban->delete();
        }

        $this->info('Expired bans have been removed successfully.');
    }
}
