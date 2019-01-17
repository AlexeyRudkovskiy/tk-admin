<?php
/**
 * Created by PhpStorm.
 * User: alexey
 * Date: 26.08.18
 * Time: 05:03
 */

namespace ARudkovskiy\Admin\Commands;

use ARudkovskiy\Admin\Models\User;
use Symfony\Component\Console\Command\Command;
use Symfony\Component\Console\Input\InputInterface;
use Symfony\Component\Console\Output\OutputInterface;

class CreateAdmin extends Command
{
    /**
     * The name and signature of the console command.
     *
     * @var string
     */
    protected $signature = 'admin:create';

    /**
     * The console command description.
     *
     * @var string
     */
    protected $description = 'Command description';

    /**
     * Create a new command instance.
     *
     * @return void
     */
    public function __construct()
    {
        parent::__construct();

        $this->setName($this->signature);
        $this->setDescription($this->description);
    }

    /**
     * Execute the console command.
     *
     * @return mixed
     */
    public function execute(InputInterface $input, OutputInterface $output)
    {
        $user = User::create([
            'username' => 'test',
            'email' => 'test@test.test',
            'password' => '',
            'full_name' => 'Rudkovskiy Alexey Ruslanovich'
        ]);

        $user->password = \Hash::make(implode('@', [ $user->id, 'password' ]));
        $user->save();

        return 0;
    }

}
