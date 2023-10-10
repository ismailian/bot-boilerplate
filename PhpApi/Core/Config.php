<?php

namespace PhpApi\Core;

use Exception;

/**
 * this is the config class
 */
class Config
{

    /**
     * @var string $env the env file path.
     */
    private string $env = __DIR__ . '/env.json';

    /**
     * @var bool $save_on_change whether to save to env file when a property changes.
     */
    private bool $save_on_change = false;
    private mixed $content;

    /**
     * default constructor
     * @param bool $save_on_change Whether to save to env file on changes.
     * @throws Exception
     */
    public function __construct(bool $save_on_change = false)
    {
        /** set save-on-change property */
        $this->save_on_change = $save_on_change;

        if (!file_exists($this->env)) {
            throw new Exception('Env File Not Found');
        }

        /** get content */
        $this->content = json_decode(file_get_contents($this->env));
    }

    /**
     * get env content
     *
     * @return object
     */
    public function read(): object
    {
        return $this->content;
    }

    /**
     * set env property value.
     * @param string $name the property name.
     * @param mixed $value the property value.
     */
    public function setProperty(string $name, mixed $value): void
    {
        $this->content->$name = $value;

        /** save if set to true */
        if ($this->save_on_change) {
            $this->save();
        }
    }

    /**
     * the magic call function to directly get env properties
     * @param string $name the name of env property.
     * @param array $arguments extra arguments to value changing purposes.
     * @return mixed returns data for the corresponding property.
     * @throws Exception
     */
    public function __call(string $name, array $arguments)
    {
        if (empty($this->content))
            throw new Exception('Env Not Loaded');

        /** return property value */
        return $this->content->$name ?? null;
    }

    /**
     * save env data to env file
     */
    private function save(): void
    {
        file_put_contents(
            $this->env,
            json_encode($this->content, JSON_UNESCAPED_SLASHES)
        );
    }
}
