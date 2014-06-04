<?php namespace specs\Creator;

use PhpSpec\ObjectBehavior;

class CreatorSpec extends ObjectBehavior {

    function it_is_initializable()
    {
        $this->shouldHaveType('Creator\Creator');
    }

    function it_throws_an_exception_if_class_is_not_instantiable()
    {
        $this->shouldThrow('ReflectionException')
             ->duringResolve('Creator\Testing\NotInstantiable');
    }

    function it_resolves_a_class_without_any_dependencies()
    {
        $this->resolve($class = 'Creator\Testing\NoDependencies')
             ->shouldBeAnInstanceOf($class);
    }

    function it_resolves_dependencies_of_primitive_types()
    {
        $this->resolve($class = 'Creator\Testing\PrimitivesWithDefaults')
             ->shouldBeAnInstanceOf($class);
    }

    function it_throws_an_exception_if_default_value_is_not_available()
    {
        $this->shouldThrow('ReflectionException')
             ->duringResolve('Creator\Testing\Primitives');
    }

    function it_resolves_class_type_hints()
    {
        $this->resolve($class = 'Creator\Testing\ClassTypeHint')
             ->shouldBeAnInstanceOf($class);
    }

    function it_resolves_class_type_hints_with_defaults()
    {
        $this->resolve($class = 'Creator\Testing\ClassTypeHintWithDefault')
             ->shouldBeAnInstanceOf($class);
    }

    function it_resolves_nested_dependencies()
    {
        $this->resolve($class = 'Creator\Testing\NestedDependency')
             ->shouldBeAnInstanceOf($class);
    }

}
