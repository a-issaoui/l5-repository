<?php
namespace Prettus\Repository\Generators;

use Illuminate\Support\Str;
use Prettus\Repository\Generators\Commands\EntityCommand ;
/**
 * Class ControllerGenerator
 * @package Prettus\Repository\Generators
 * @author Anderson Andrade <contato@andersonandra.de>
 */
class ControllerGenerator extends Generator
{

    /**
     * Get stub name.
     *
     * @var string
     */
    protected $stub = 'controller/controller';

    /**
     * Get root namespace.
     *
     * @return string
     */
    public function getRootNamespace()
    {
        return str_replace('/', '\\', parent::getRootNamespace() . parent::getConfigGeneratorClassPath($this->getPathConfigNode()));
    }

    /**
     * Get generator path config node.
     *
     * @return string
     */
    public function getPathConfigNode()
    {
        return 'controllers';
    }

    /**
     * Get destination path for generated file.
     *
     * @return string
     */
    public function getPath()
    {
        /***Controlller Folder****/
        $words = explode('/', $this->name); // Break words into array
        $noofwords = count($words); // Find out how many
        unset($words[$noofwords-1]); // remove the last one (-1 because of zero-index)
        $Cfolder = implode('/', $words); //put back together


        $controller = $this->getBasePath() . '/' . parent::getConfigGeneratorClassPath($this->getPathConfigNode(), true) . '/' . $Cfolder. '/'. $this->getControllerName() . 'Controller.php';


        return $controller;

         }

    /**
     * Get base path of destination file.
     *
     * @return string
     */
    public function getBasePath()
    {
        return config('repository.generator.basePath', app()->path());
    }

    /**
     * Gets controller name based on model
     *
     * @return string
     */
    public function getControllerName()
    {

        return ucfirst($this->getClass());
    }

    /**
     * Gets plural name based on model
     *
     * @return string
     */
    public function getPluralName()
    {

        return Str::plural(lcfirst(ucwords($this->getClass())));
    }

    /**
     * Get array replacements.
     *
     * @return array
     */
    public function getReplacements()
    {



        /***Controlller Folder****/
        $words = explode('/', $this->name); // Break words into array
        $noofwords = count($words); // Find out how many
        unset($words[$noofwords-1]); // remove the last one (-1 because of zero-index)
        $Cfolder = implode('\\', $words); //put back together

        return array_merge(parent::getReplacements(), [
            'controller' => $this->getControllerName(),
            'plural'     => $this->getPluralName(),
            'singular'   => $this->getSingularName(),
            'validator'  => $this->getValidator(),
            'repository' => $this->getRepository(),
            'appname'    => $this->getAppNamespace(),
            'cfolder'    => $Cfolder,
            'connection' => $this->connection,
            'database' => $this->database ,

        ]);
    }

    /**
     * Gets singular name based on model
     *
     * @return string
     */
    public function getSingularName()
    {
        return Str::singular(lcfirst(ucwords($this->getClass())));
    }

    /**
     * Gets validator full class name
     *
     * @return string
     */
    public function getValidator()
    {
        $validatorGenerator = new ValidatorGenerator([
            'name' => $this->name,
        ]);

        $validator = $validatorGenerator->getRootNamespace() . '\\'.str_replace(["\\",'/'], '\\', $this->option('Cfolder')) . '\\'. $validatorGenerator->getName();

        return 'use ' . str_replace([
            "\\",
            '/'
        ], '\\', $validator) . 'Validator;';
    }


    /**
     * Gets repository full class name
     *
     * @return string
     */
    public function getRepository()
    {
        $repositoryGenerator = new RepositoryInterfaceGenerator([
            'name' => $this->name,
        ]);

        $repository = $repositoryGenerator->getRootNamespace() . '\\' .str_replace(["\\",'/'], '\\', $this->option('Cfolder')) . '\\'. $repositoryGenerator->getName();

        return 'use ' . str_replace([
            "\\",
            '/'
        ], '\\', $repository) . 'Repository;';
    }
}
