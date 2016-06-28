<?php

namespace GBarak\ViewCrudGenerator\Console;

use Illuminate\Console\Command;
use Illuminate\Database\Eloquent\Model;
use Illuminate\Filesystem\Filesystem;
use Symfony\Component\Console\Output\OutputInterface;

class ViewCrudGenerate extends Command
{


    public $shit;

    /**
     * The name and signature of the console command.
     *
     * @var string
     */
    protected $signature = 'vcgen:make {model : Model Class Path} {--viewPath=: The path to the view folders for the model}';

    /**
     * The console command description.
     *
     * @var string
     */
    protected $description = 'Create a view for CRUD model';

    /**
     * @var Filesystem $files
     */
    protected $files;

    /**
     * The model associated with the command
     *
     * @var Model
     */
    protected $model;

    /**
     * The Model name as singular
     *
     * @var string
     */
    protected $singular;

    /**
     * The Model name as singular
     *
     * @var string
     */
    protected $ucSingular;

    /**
     * The Model name as plural
     *
     * @var string
     */
    protected $plural;

    /**
     * The model primary key
     *
     * @var string
     */
    protected $primaryKey = false;

    /**
     * Model has auto-increment
     *
     * @var boolean
     */
    protected $incrementing;

    /**
     * Model properties
     *
     * @var array
     */
    protected $properties = [];

    /**
     * Model properties
     *
     * @var array
     */
    protected $propertyRelations = [];

    /**
     * Model has auto-increment
     *
     * @var array
     */
    protected $methods = [];

    /**
     * Create a new command instance.
     *
     * @return void
     */
    public function __construct(Filesystem $files)
    {
        parent::__construct();
        $this->files = $files;
    }

    /**
     * Execute the console command.
     *
     * @return mixed
     */
    public function handle()
    {
        $modelName = $this->argument('model');

        if (class_exists($modelName)) {
            try {
                // handle abstract classes, interfaces, ...
                $reflectionClass = new \ReflectionClass($modelName);
                if (!$reflectionClass->isSubclassOf('Illuminate\Database\Eloquent\Model')) {
                    return false;
                }
                if ($this->output->getVerbosity() >= OutputInterface::VERBOSITY_VERBOSE) {
                    $this->comment("Loading model '$modelName'");
                }
                if (!$reflectionClass->isInstantiable()) {
                    // ignore abstract class or interface
                    return false;
                }

                $phpDoc = $reflectionClass->getDocComment();
                $phpDoc = preg_replace('/\r\n|\n\r|\n|\r/', "\n", $phpDoc);

            } catch (\Exception $e) {
                $this->error("Exception: " . $e->getMessage() . "\nCould not analyze class $modelName.");

                return false;
            }

            $lines = explode("\n", $phpDoc);
            $this->initModel($modelName, $lines);

            $tbodyRowId = '{{ $' . $this->singular . '->' . $this->primaryKey . ' }}';
            $singular = $this->singular;
            $plural = $this->plural;
            $modalName = $this->fromSnakeToNormalCase($this->ucSingular);
            $studlySingular = studly_case($this->singular);

            $viewFolder = base_path('resources/views/' . $this->option('viewPath'));
            $viewPath = $viewFolder . '/index.blade.php';

            if (!$this->files->exists($viewFolder)) {
                $this->files->makeDirectory($viewFolder, 0755, true);
            }

            $data = compact('thead', 'tbodyRowId', 'singular', 'plural', 'modalName', 'studlySingular');

            $view = view('view-crud-generator::view', $data)
                ->with($this->createViewTableHead($lines))
                ->with($this->createViewTableBody($lines))
                ->with($this->createViewModal($lines))
                ->with($this->createViewScript($lines))
                ->render();

            $this->files->put($viewPath, $view);

            return true;
        }

        return false;
    }

    /**
     * @param string $modelName
     * @param array $lines
     */
    protected function initModel($modelName, $lines)
    {
        $this->model = $this->laravel->make($modelName);
        $this->plural = $this->model->getTable();
        $this->singular = $this->fromSnakeToSingular($this->plural);
        $this->ucSingular = ucfirst($this->singular);
        $this->incrementing = $this->model->getIncrementing();

        if ($this->incrementing) {
            $this->primaryKey = $this->model->getKeyName();
        }

        foreach ($lines as $line) {
            if (($pos = strpos($line, '@method')) !== false) {
                $parts = explode(' ', substr($line, $pos));
                $name = substr($parts[3], 0, strpos($parts[3], '('));
                $this->methods[$name] = ['name' => $name, 'return' => explode('|', $parts[2]), 'type' => $parts[1]];
                continue;
            }
            if (($pos = strpos($line, '@property-read')) !== false) {
                $parts = explode(' ', substr($line, $pos));
                $name = substr($parts[2], 1);
                $this->propertyRelations[$name] = ['name' => $name, 'class' => $parts[1]];
                continue;
            }
            if (($pos = strpos($line, '@property')) !== false) {
                $parts = explode(' ', substr($line, $pos));
                $name = substr($parts[2], 1);
                $this->properties[$name] = ['name' => $name, 'type' => $parts[1]];
            }
        }
    }

    /**
     * @param array $lines
     *
     * @return array
     */
    protected function createViewTableHead($lines)
    {
        $thead = [];
        if ($this->model->getIncrementing()) {
            $thead[] = "#";
        }

        $this->comment("Going over " . count($lines) . " lines.");
        foreach ($lines as $key => $line) {
            $isMethod = strpos($line, '@method') !== false;
            $isPropertyRelation = strpos($line, '@property-read') !== false;
            $isProperty = strpos($line, '@property') !== false;

            if ($isPropertyRelation) {
                continue;
            }

            if ($isProperty) {
                $propertyName = array_last(explode(' ', $line));
                if ($this->incrementing && substr($propertyName, 1) === $this->primaryKey) {
                    continue; // for the primary auto-incrementing we add # as the head
                } else {
                    $thead[] = $this->fromSnakeToNormalCase($propertyName);
                }
            }

            if ($isMethod) {
                continue;
            }
        }

        return ['thead' => $thead];
    }

    private function fromSnakeToNormalCase($propertyName)
    {
        // break property name by _ after removing $ and UpperCase each part and then glue with space
        // $property_id = Property Id
        return implode(' ', array_map(function ($part) {
            return ucfirst($part);
        }, explode('_', str_contains($propertyName, '$') ? substr($propertyName, 1) : $propertyName)));
    }

    private function fromSnakeToSingular($propertyName)
    {
        // break property name by _ after removing $ and UpperCase each part and then glue with space
        // $property_id = Property Id
        return implode('_', array_map(function ($part) {
            return str_singular($part);
        }, explode('_', str_contains($propertyName, '$') ? substr($propertyName, 1) : $propertyName)));
    }

    /**
     * @param array $lines
     *
     * @return array
     */
    protected function createViewTableBody($lines)
    {
        $tbody = [];
        foreach ($lines as $line) {

            $isMethod = strpos($line, '@method') !== false;
            $isPropertyRelation = strpos($line, '@property-read') !== false;
            $isProperty = strpos($line, '@property') !== false;

            if ($isPropertyRelation) {
                // TODO
                continue;
            }

            if ($isProperty) {
                $propertyName = array_last(explode(' ', $line));
                $propertyName = substr($propertyName, 1);

                $tbody[$propertyName] = '{{ $' . $this->singular . '->' . $propertyName . ' }}';
            }

            if ($isMethod) {
                // TODO
            }

        }

        return ['tbody' => $tbody];
    }

    /**
     * @param array $lines
     *
     * @return array
     */
    protected function createViewModal($lines)
    {
        $modal = [];
        foreach ($lines as $line) {

            $isMethod = strpos($line, '@method') !== false;
            $isPropertyRelation = strpos($line, '@property-read') !== false;
            $isProperty = strpos($line, '@property') !== false;

            if ($isPropertyRelation) {
                // TODO
                continue;
            }

            if ($isProperty) {
                $propertyName = array_last(explode(' ', $line));
                $propertyName = substr($propertyName, 1);
                $modalProperty = $this->getModalProperty($propertyName);
                if (!empty($modalProperty)) {
                    $modal[] = $modalProperty;
                }
                continue;
            }

            if ($isMethod) {
                // TODO
            }

        }

        return ['modal' => $modal];
    }

    /**
     * @param string $propertyName
     *
     * @return array
     * @throws \Exception
     */
    private function getModalProperty($propertyName)
    {
        $property = $this->properties[$propertyName];
        $type = null;

        switch ($property['type']) {
            case 'integer':
                if ($this->primaryKey == $propertyName && $this->incrementing) {
                    return [];
                }

                foreach ($this->propertyRelations as $key => $relation) {
                    if (!empty($relation['name']) && stripos($propertyName, $relation['name'])) {
                        // TODO
                    }
                }

                $type = 'text';
                break;
            case 'string':
                $type = stripos($propertyName, 'email') !== false ? 'email' : 'text';
                break;
            case '\Carbon\Carbon':
                $type = 'date';
                break;
            default:
                throw new \Exception('Missing type for this property type: ' . $property['type']);
        }

        return ['name' => $propertyName, 'type' => $type, 'normalCase' => $this->fromSnakeToNormalCase($propertyName)];
    }

    /**
     * @param array $lines
     *
     * @return array
     */
    protected function createViewScript($lines)
    {
        $index = 0;
        $successModel = $editModel = [];

        foreach ($lines as $key => $line) {
            $isMethod = strpos($line, '@method') !== false;
            $isPropertyRelation = strpos($line, '@property-read') !== false;
            $isProperty = strpos($line, '@property') !== false;

            if ($isPropertyRelation) {
                $index++;
                continue;
            }

            if ($isProperty) {
                $propertyName = array_last(explode(' ', $line));
                $propertyName = substr($propertyName, 1);

                $successModel[] = ['singular' => $this->singular, 'property' => $propertyName];
                if ($this->incrementing && $propertyName === $this->primaryKey) {
                    $index++;
                    continue;
                }

                $editModel[] = ['property' => $propertyName, 'index' => $index];
                $index++;
                continue;
            }

            if ($isMethod) {
                $index++;
                continue;
            }
        }

        $storeAction = "{{ action('".ucfirst(camel_case($this->plural)) . "Controller@store') }}";
        $updateAction = "{{ action('".ucfirst(camel_case($this->plural)) . "Controller@update', ':id') }}";
        $deleteAction = "{{ action('".ucfirst(camel_case($this->plural)) . "Controller@destroy', ':id') }}";

        return compact('successModel', 'editModel', 'storeAction', 'updateAction', 'deleteAction');
    }
}
