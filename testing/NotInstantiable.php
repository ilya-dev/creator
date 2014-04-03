<?php namespace Testing;

class NotInstantiable {

    // a class which constructor is declared as non-public cannot be instantiated
    private function __construct() {}

}

