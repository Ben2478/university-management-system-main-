<?php

namespace App\Console\Commands;

use App\Models\User;
use Illuminate\Console\Command;

class CheckUsers extends Command
{
    protected $signature = 'check:users';

    public function handle()
    {
        $users = User::all();
        
        foreach($users as $u) {
            $this->info($u->email . ' => ' . $u->role);
        }
    }
}
