<?php namespace Creator;

use ReflectionClass, ReflectionParameter, ReflectionException;

class Creator {

    /**
     * Makes it easier to use resolve()
     *
     * @param  dynamic
     * @return mixed
     */
    public static function create()
    {
        return \call_user_func_array([new static, 'resolve'], \func_get_args());
    }

    /**
     * Resolve all dependencies and return a new instance
     *
     * @param  string $class
     * @throws ReflectionException
     * @return mixed
     */
    public function resolve($class)
    {
        $reflector = new ReflectionClass($class);

        if ( ! $reflector->isInstantiable())
        {
            $message = "Class {$class} is not instantiable";

            throw new ReflectionException($message);
        }

        $constructor = $reflector->getConstructor();

        if (\is_null($constructor))
        {
            return new $class;
        }

        $dependencies = $this->resolveDependencies($constructor->getParameters());

        return $reflector->newInstanceArgs($dependencies);
    }

    /**
     * Resolve a given array of dependencies
     *
     * @param  array $parameters
     * @return array
     */
    protected function resolveDependencies(array $parameters)
    {
        $dependencies = [];

        foreach ($parameters as $parameter)
        {
            if ($this->isClassTypeHint($parameter))
            {
                $dependencies[] = $this->resolveClassTypeHint($parameter);
            }
            else
            {
                $dependencies[] = $this->resolvePrimitive($parameter);
            }
        }

        return $dependencies;
    }

    /**
     * Resolve a given dependency of a primitive type
     *
     * @param  ReflectionParameter $parameter
     * @throws ReflectionException
     * @return mixed
     */
    protected function resolvePrimitive(ReflectionParameter $parameter)
    {
        if ( ! $parameter->isDefaultValueAvailable())
        {
            $message = 'Unable to resolve '.$parameter->getName();

            throw new ReflectionException($message);
        }

        return $parameter->getDefaultValue();
    }

    /**
     * Determine whether a parameter has a type hint of a non-primitive type
     *
     * @param  ReflectionParameter $parameter
     * @return boolean
     */
    protected function isClassTypeHint(ReflectionParameter $parameter)
    {
        try
        {
            return ($parameter->getClass() instanceof ReflectionClass);
        }
        catch (ReflectionException $exception)
        {
            return false;
        }
    }

    /**
     * Resolve a given dependecy of a non-primitive type
     *
     * @param  ReflectionParameter $parameter
     * @return mixed
     */
    protected function resolveClassTypeHint(ReflectionParameter $parameter)
    {
        try
        {
            return $this->resolve($parameter->getClass()->getName());
        }
        catch (ReflectionException $exception)
        {
            return $this->resolvePrimitive($parameter);
        }
    }

}

