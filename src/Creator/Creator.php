<?php namespace Creator;

use ReflectionClass, ReflectionParameter, ReflectionException;

class Creator {

    /**
     * Static alias to the resolve method.
     *
     * @param dynamic
     * @return mixed
     */
    public static function create()
    {
        return call_user_func_array([new static, 'resolve'], func_get_args());
    }

    /**
     * Attempt to instantiate a given class by resolving its dependencies.
     *
     * @throws ReflectionException
     * @param string $class
     * @return mixed
     */
    public function resolve($class)
    {
        $reflector = new ReflectionClass($class);

        if ( ! $reflector->isInstantiable())
        {
            throw new ReflectionException("Class {$class} is not instantiable.");
        }

        if (is_null($constructor = $reflector->getConstructor()))
        {
            return new $class;
        }

        return $reflector->newInstanceArgs(
            $this->resolveDependencies($constructor->getParameters())
        );
    }

    /**
     * Attempt to resolve all constructor's dependencies.
     *
     * @param array $dependencies
     * @return array
     */
    protected function resolveDependencies(array $dependencies)
    {
        $arguments = [];

        foreach ($dependencies as $dependency)
        {
            if ($this->isClassTypeHint($dependency))
            {
                $arguments[] = $this->resolveClassTypeHint($dependency);

                continue;
            }

            $arguments[] = $this->resolvePrimitive($dependency);
        }

        return $arguments;
    }

    /**
     * Resolve a given dependency of a primitive type.
     *
     * @throws ReflectionException
     * @param ReflectionParameter $dependency
     * @return mixed
     */
    protected function resolvePrimitive(ReflectionParameter $dependency)
    {
        if ( ! $dependency->isDefaultValueAvailable())
        {
            $dependency = $dependency->getName();

            throw new ReflectionException("Unable to resolve {$dependency}.");
        }

        return $dependency->getDefaultValue();
    }

    /**
     * Determine whether a dependency has a non-primitive type hint.
     *
     * @param ReflectionParameter $dependency
     * @return boolean
     */
    protected function isClassTypeHint(ReflectionParameter $dependency)
    {
        try
        {
            return $dependency->getClass() instanceof ReflectionClass;
        }
        catch (ReflectionException $exception)
        {
            return false;
        }
    }

    /**
     * Resolve a given dependecy of a non-primitive type.
     *
     * @param ReflectionParameter $dependency
     * @return mixed
     */
    protected function resolveClassTypeHint(ReflectionParameter $dependency)
    {
        try
        {
            return $this->resolve($dependency->getClass()->getName());
        }
        catch (ReflectionException $exception)
        {
            return $this->resolvePrimitive($dependency);
        }
    }

}
