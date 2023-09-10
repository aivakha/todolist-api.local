<?php

namespace App\Console\Commands;

use App\Models\User;
use Illuminate\Console\Command;

use Illuminate\Support\Facades\Hash;

use function Laravel\Prompts\password;
use function Laravel\Prompts\text;
use function Laravel\Prompts\info;

class CreateUser extends Command
{
    /**
     * The name and signature of the console command.
     *
     * @var string
     */
    protected $signature = 'user:create';

    /**
     * The console command description.
     *
     * @var string
     */
    protected $description = 'Create a new user';

    /**
     * Execute the console command.
     */
    public function handle()
    {
        $name = text(
            label: 'What is your name?',
            required: 'Your name is required.'
        );

        $email = text(
            label: 'What is your email?',
            required: 'Your email is required.',
            validate: fn(string $value) => match (true) {
                !filter_var($value, FILTER_VALIDATE_EMAIL) => 'Invalid email format.',
                User::where('email', $value)->exists() => 'User with the same email already exists.',
                default => null
            }
        );

        $password = password(
            label: 'What is your password?',
            required: 'Your password is required.',
            validate: fn(string $value) => match (true) {
                strlen($value) < 8 => 'The password must be at least 8 characters.',
                default => null
            }
        );

        // Create the user
        $user = User::create([
            'name' => $name,
            'email' => $email,
            'password' => Hash::make($password),
        ]);

        $apiToken = $user->createToken('api-token')->plainTextToken;

        info('User created. API TOKEN: ' . $apiToken);
    }
}
