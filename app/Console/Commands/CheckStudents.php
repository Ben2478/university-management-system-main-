<?php

namespace App\Console\Commands;

use App\Models\Student;
use Illuminate\Console\Command;

class CheckStudents extends Command
{
    protected $signature = 'check:students';

    public function handle()
    {
        $students = Student::with('user')->limit(10)->get();
        
        if($students->isEmpty()) {
            $this->error('No students found!');
            return;
        }
        
        foreach($students as $s) {
            $this->info('Student: ' . ($s->user->name ?? 'NO USER') . ' (ID: ' . $s->id . ')');
        }
    }
}
