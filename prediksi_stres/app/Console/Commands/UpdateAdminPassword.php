<?php

namespace App\Console\Commands;

use App\Models\User;
use Illuminate\Console\Command;
use Illuminate\Support\Facades\Hash;

class UpdateAdminPassword extends Command
{
    protected $signature = 'admin:update-password';
    protected $description = 'Update admin password';

    public function handle()
    {
        $admin = User::where('email', 'admin@example.com')->first();
        
        if ($admin) {
            $admin->password = Hash::make('password');
            $admin->save();
            $this->info('Admin password updated successfully!');
        } else {
            $this->error('Admin user not found!');
        }
    }
}

