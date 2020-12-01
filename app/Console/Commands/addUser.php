<?php

namespace App\Console\Commands;

use Illuminate\Console\Command;
use App\Models\User;
use Exception;
use Illuminate\Support\Facades\Hash;
use Illuminate\Support\Facades\Validator;
use Illuminate\Support\Facades\Log;



class addUser extends Command
{
    /**
     * The name and signature of the console command.
     *
     * @var string
     */
    protected $signature = 'command:createUser';

    /**
     * The console command description.
     *
     * @var string
     */
    protected $description = 'Used for create user';

    /**
     * Create a new command instance.
     *
     * @return void
     */
    public function __construct()
    {
        parent::__construct();
    }

    /**
     * Execute the console command.
     *
     * @return int
     */
    public function handle()
    {
        try {
            //Ask user name, password and collect data
            $name = $this->ask('What is your name?');
            $password = $this->secret('What is the password?');

            //Validate name and password
            $data = ['name' => $name, 'password' => $password];
            $validator =  Validator::make($data, [
                'name' => ['required', 'string', 'max:255', 'unique:users'],
                'password' => ['required', 'string', 'min:4'],
            ]);

            //Return errors. If validation failed
            if ($validator->fails()) {
                $errors = '';
                foreach ($validator->messages()->getMessages() as $field_name => $messages) {
                    foreach ($messages as $key => $message) {
                        $this->error($field_name . ': ' . $message);
                    }
                }
                return true;
            }

            //Save user data
            $user = new User();
            $user->password = Hash::make($password);
            $user->name = $name;
            $user->save();
            $this->info("User $name registered successfully.");
            //Save log
            Log::info("User $name registered successfully.");
        } catch (Exception $e) {
            //Return error
            $this->error('Error:' . $e->getMessage);
            Log::error('User insertion error: ' . json_encode($e));
            return;
        }
    }
}
