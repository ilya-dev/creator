Creator
=======

[![Build Status](https://travis-ci.org/ilya-dev/creator.svg?branch=master)](https://travis-ci.org/ilya-dev/creator)

Thin Reflection API wrapper that allows you to easily instantiate new objects.

```php
use Creator\Creator;

class Foo {

    public function __construct(AnotherClass $foo, $bar = 42)
    {
       // ....
    }
  
}

// do not want to instantiate it manually? here is what you can do

Creator::make('Foo') // => new instance of Foo 
```

# Additional information

The code is licensed under the MIT license, you can talk to me on twitter [@ilya_s_dev](https://twitter.com/ilya_s_dev)
