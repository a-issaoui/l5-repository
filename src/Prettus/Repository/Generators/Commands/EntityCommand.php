<?php
namespace Prettus\Repository\Generators\Commands;

use Illuminate\Console\Command;
use Illuminate\Support\Collection;
use Symfony\Component\Console\Input\InputArgument;
use Symfony\Component\Console\Input\InputOption;

/**
 * Class EntityCommand
 * @package Prettus\Repository\Generators\Commands
 * @author Anderson Andrade <contato@andersonandra.de>
 */
class EntityCommand extends Command
{





    /**
     * The name of command.
     *
     * @var string
     */
    protected $name = 'make:entity';

    /**
     * The description of command.
     *
     * @var string
     */
    protected $description = 'Create New  Model, Repository, Validator, Observer, Controller .... ';

    /**
     * @var Collection
     */
    protected $generators = null;

    /**
     * Execute the command.
     *
     * @see fire()
     * @return void
     */
    public function handle(){
        
        $this->laravel->call([$this, 'fire'], func_get_args());
    }

    /**
     * Execute the command.
     *
     * @return void
     */
    public function fire()
    {

        $controllername = $this->option('Cfolder'). "/" .ucfirst(substr($this->argument('name'), strrpos($this->argument('name'), '/') + 1));

         $database = explode('/', $this->argument('name'))[0];

   /*     if ($this->confirm('Would you like to create a Presenter? [y|N]')) {
            $this->call('make:presenter', [
                'name'    => $this->argument('name'),
                '--force' => $this->option('force'),
            ]);
        }


        if (is_null($validator) && $this->confirm('Would you like to create a Validator? [y|N]')) {
            $validator = 'yes';
        }

        if ($validator == 'yes') { */
        $validator = $this->option('validator');
            $this->call('make:validator', [
                'name'    => $controllername,
                '--rules' => $this->option('rules'),
                '--force' => $this->option('force'),
                '--Cfolder' => $this->option('Cfolder'),
                '--Connection' =>$this->option('Connection'),
                '--database' =>$database,
            ]);




      //  if ($this->confirm('Would you like to create a Controller? [y|N]')) { //


            $resource_args = [
                'name'    => $controllername,
                '--Cfolder' => $this->option('Cfolder'),
                '--Connection' =>$this->option('Connection'),
                '--database' =>$database,
                  ];

            // Generate a controller resource
            $controller_command = ((float) app()->version() >= 5.5  ? 'make:rest-controller' : 'make:resourcex');

            $this->call($controller_command, $resource_args);



        $this->call('make:repository', [
            'name'        => $controllername,
            '--fillable'  => $this->option('fillable'),
            '--rules'     => $this->option('rules'),
            '--validator' => $validator,
            '--force'     => $this->option('force'),
            '--Cfolder' => $this->option('Cfolder'),
            '--Connection' =>$this->option('Connection'),
            '--database' =>$database,
        ]);

        $this->call('make:bindings', [
            'name'    => $this->argument('name'),
            '--force' => $this->option('force'),
            '--Cfolder' => $this->option('Cfolder'),
            '--Connection' =>$this->option('Connection'),
            '--database' =>$database,

        ]);
    }


    /**
     * The array of command arguments.
     *
     * @return array
     */
    public function getArguments()
    {
        return [
            [
                'name',
                InputArgument::REQUIRED,
                'The name of class being generated.',
                null
            ],
        ];
    }


    /**
     * The array of command options.
     *
     * @return array
     */
    public function getOptions()
    {
        return [

            [
                'Cfolder',
                null,
                InputOption::VALUE_OPTIONAL,
                'The Controller folder attributes.',
                null
            ],
            [
                'Connection',
                null,
                InputOption::VALUE_OPTIONAL,
                'The database connection name.',
                null
            ],
            [
                'database',
                null,
                InputOption::VALUE_OPTIONAL,
                'The database connection name.',
                null
            ],
            [
                'fillable',
                null,
                InputOption::VALUE_OPTIONAL,
                'The fillable attributes.',
                null
            ],
            [
                'rules',
                null,
                InputOption::VALUE_OPTIONAL,
                'The rules of validation attributes.',
                null
            ],
            [
                'validator',
                null,
                InputOption::VALUE_OPTIONAL,
                'Adds validator reference to the repository.',
                null
            ],
            [
                'force',
                'f',
                InputOption::VALUE_NONE,
                'Force the creation if file already exists.',
                null
            ]
        ];
    }
}
