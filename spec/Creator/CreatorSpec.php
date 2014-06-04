<?php namespace spec\Creator;

use PhpSpec\ObjectBehavior;

class CreatorSpec extends ObjectBehavior {

    function it_is_initializable()
    {
        $this->shouldHaveType('Creator\Creator');
    }

    function it_throws_an_exception_if_class_is_not_instantiable()
    {
        $this->shouldThrow('ReflectionException')
             ->duringResolve('Testing\NotInstantiable');
    }

    function it_resolves_a_class_without_any_dependencies()
    {
        $this->resolve($class = 'Testing\NoDependencies')
             ->shouldBeAnInstanceOf($class);
    }

    function it_resolves_dependencies_of_primitive_types()
    {
        $this->resolve($class = 'Testing\PrimitivesWithDefaults')
             ->shouldBeAnInstanceOf($class);
    }

    function it_throws_an_exception_if_default_value_is_not_available()
    {
        $this->shouldThrow('ReflectionException')
             ->duringResolve('Testing\Primitives');
    }

    function it_resolves_class_type_hints()
    {
        $this->resolve($class = 'Testing\ClassTypeHints')
             ->shouldBeAnInstanceOf($class);
    }

    function it_resolves_class_type_hints_with_defaults()
    {
        $this->resolve($class = 'Testing\ClassTypeHintsDefaults')
             ->shouldBeAnInstanceOf($class);
    }

    function it_resolves_nested_dependencies()
    {
        $this->resolve($class = 'Testing\NestedDependencies')
             ->shouldBeAnInstanceOf($class);
    }

}
