<?php

namespace App\Console\Commands;

use Illuminate\Console\Command;

class deleteoldusers extends Command
{
    /**
     * The name and signature of the console command.
     *
     * @var string
     */
    protected $signature = 'app:deleteoldusers';

    /**
     * The console command description.
     *
     * @var string
     */
    protected $description = 'Command description';

    /**
     * Execute the console command.
     */
    public function handle()
    {
        $date = \Carbon\Carbon::today()->subDays(30);
        $users = User::where('created_at','>=',$date)->get();
        foreach ($users as $user) {
            User::where('id',$user->id)->delete();
        }
    }
}
