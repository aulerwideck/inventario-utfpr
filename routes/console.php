<?php

use Illuminate\Foundation\Inspiring;

/*
|--------------------------------------------------------------------------
| Console Routes
|--------------------------------------------------------------------------
|
| This file is where you may define all of your Closure based console
| commands. Each Closure is bound to a command instance allowing a
| simple approach to interacting with each command's IO methods.
|
*/

Artisan::command('inspire', function () {
    $this->comment(Inspiring::quote());
})->describe('Display an inspiring quote');

Artisan::command('install', function () {
    Artisan::call('key:generate');

    if ($this->confirm('Deseja criar as tabelas?')) {
        Artisan::call('migrate');
        $this->info('As tabelas foram criadas.');
    }
    if ($this->confirm('Deseja inserir o conteúdo predefinido?')) {
        $bar = $this->output->createProgressBar(1);

        $bar->start();

        Artisan::call('db:seed --class=RolesPermissionsSeeder');
        $bar->advance();

        $bar->finish();
        $this->line('Os dados foram inseridos.');
    }

    while($this->confirm('Deseja adicionar um novo usuário?')){
        $name = $this->ask('Qual o nome do usuário?');
        $email = $this->ask('Qual o email do usuário?');
        $password = $this->ask('Qual será a senha do usuário?');

        $user = DB::table('users')->insert([
            'name' => $name,
            'email' => $email,
            'email_verified_at' => now(),
            'password' => Hash::make($password),
            'created_at' => now(),
            'updated_at' => now()
        ]);
        $user = \App\User::where('email', $email)->first();
        $role = $this->anticipate(
            'Qual nivel de acesso? [Super Admin, Admin, President, Reader]',
            ['Super Admin', 'Admin', 'President', 'Reader']);
        $user->assignRole($role);
    }
    $this->comment(Inspiring::quote());
})->describe('Migrate, Seed and create users');
