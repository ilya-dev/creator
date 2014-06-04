Creator
=======

[![Build Status](https://travis-ci.org/ilya-dev/creator.svg?branch=master)](https://travis-ci.org/ilya-dev/creator)

Creator is a thin Reflection API wrapper that allows you to easily instantiate classes.


It will automatically read the constructor's dependencies and inject them into a new instance. 


```php
use Creator\Creator;

class Foo {

    public function __construct(AnotherClass $foo, $bar = 42)
    {
       # something here
    }
  
}

# do not want to instantiate it manually? here is what you can do
$foo = Creator::make('Foo'); # => new instance of Foo 
```

# Additional information

Creator is licensed under the MIT license.

