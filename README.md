# Functional PHP: Functional primitives for PHP

A set of functional primitives for PHP, heavily inspired by [Scala’s traversable
collectio](http://www.scala-lang.org/archives/downloads/distrib/files/nightly/docs/library/scala/collection/Traversable.html),
[Dojo’s](http://dojotoolkit.org/) [array functions](http://dojotoolkit.org/reference-guide/quickstart/arrays.html) and
[Underscore.js](http://documentcloud.github.com/underscore/)

  - Works with arrays and everything implementing interface Traversable
  - Consistent interface: first parameter is always the collection, than the callback. Callbacks always receives value, key,
    collection
  - Calls 5.3 closures as well as traditional callbacks
  - C implementation for performance but compatible userland implementation is packaged if you can’t install PHP
    extensions
  - All functions reside in namespace `Functional` to not conflict with any other extension or library


## TODO
 - Native implementation of `Functional\partition()`, `Functional\group()`, `Functional\drop_left()`, `Functional\drop_right()`
 - Native implementation of `Functional\pluck()` shows slightly different behavior when dealing with private/protected
   properties (see `Functional\PluckTest::testPluckProtectedProperty()`)
 - Simple math shortcuts: `Functional\sum()`, `Functional\product()`, `Functional\difference()`, `Functional\ratio()`
 - Rename `Functional\detect()` to `Functional\first()` to make room for `Functional\last()`
 - Add something like `Functional\FilterChain` to allow chaining filters and still executing them at low complexity
 - Finish currying implementation
 - Test reference handling


## Installation


### Install native extension
```bash
cd functional-php/extension/
phphize
./configure
make
sudo make install
```


### Use userland extension
```php
<?php
include 'path/to/functional-php/src/Functional/_import.php';
```

Everytime you want to work with Functional PHP and not reference the fully qualified name, add `use Functional as F;` on top of
your PHP file.


## Overview


### Functional\all() & Functional\invoke()

``Functional\all(array|Traversable $collection, callable $callback)``

``bool Functional\invoke(array|Traversable $collection, string $methodName[, array $methodArguments])``

```php
<?php
use Functional as F;

// If all users are active, set them all inactive
if (F\all($users, function($user, $collectionKey, $collection) {return $user->isActive();})) {
    F\invoke($users, 'setActive', array(false));
}
```


### Functional\any()

``bool Functional\any(array|Traversable $collection, callable $callback)``

```php
<?php
use Functional as F;

if (F\any($users, function($user, $collectionKey, $collection) use($me) {return $user->isFriendOf($me);})) {
    // One of those users is a friend of me
}
```


### Functional\none()

``bool Functional\none(array|Traversable $collection, callable $callback)``

```php
<?php
use Functional as F;

if (F\none($users, function($user, $collectionKey, $collection) {return $user->isActive();})) {
    // Do something with a whole list of inactive users
}
```


### Functional\reject() & Functional\select()

``array Functional\select(array|Traversable $collection, callable $callback)``

``array Functional\reject(array|Traversable $collection, callable $callback)``

```php
<?php
use Functional as F;

$fn = function($user, $collectionKey, $collection) {
    return $user->isActive();
};
$activeUsers = F\select($users, $fn);
$inactiveUsers = F\reject($users, $fn);
```

### Functional\drop_left() & Functional\drop_right()

``array Functional\drop_left(array|Traversable $collection, callable $callback)``

``array Functional\drop_right(array|Traversable $collection, callable $callback)``

```php
<?php
use Functional as F;

$fn = function($user, $key, $collection) {
    return $key == 3;
};

// All users except the first three
F\drop_first($users, $fn);
// First three users
F\drop_last($users, $fn);
```

### Functional\pluck()
Fetch a single property from a collection of objects.

``array Functional\pluck(array|Traversable $collection, string $propertyName)``

```php
<?php
use Functional as F;

$names = F\pluck($users, 'name');
```

### Functional\partition()
Splits a collection into two by callback. Thruthy values come first

``array Functional\partition(array|Traversable $collection, callable $callback)``

```php
<?php
use Functional as F;

list($admins, $users) = F\partition($collection, function($user) {
    return $user->isAdmin();
});
```

###Functional\group()
Splits a collection into groups by the key returned by the callback

``array Functional\group(array|Traversable $collection, callable $callback)``

```php
<?php
use Functional as F;

$groupedUser = F\group($collection, $function($user) {
    return $user->getGroup()->getName();
});
```

### Functional\reduce_left() & Functional\reduce_right()
Applies a callback to each element in the collection and reduces the collection to a single scalar value.
`Functional\reduce_left()` starts with the first element in the collection, while `Functional\reduce_right()` starts
with the last element.

``mixed Functional\reduce_left(array|Traversable $collection, callable $callback[, $initial = null])``

``mixed Functional\reduce_right(array|Traversable $collection, callable $callback[, $initial = null])``

```php
<?php
use Functional as F;

// $sum will be 64 (2^2^3)
$sum = F\reduce_left(array(2, 3), function($value, $key, $collection, $reduction) {
    return $reduction ^ $value;
}, 2);

// $sum will be 512 (2^3^2)
$sum = F\reduce_right(array(2, 3), function($value, $key, $collection, $reduction) {
    return $reduction ^ $value;
}, 2);
```

### Additional functions:

 - `void Functional\each(array|Traversable $collection, callable $callback)`
   Applies a callback to each element
 - `array Functional\map(array|Traversable $collection, callable $callback)`
   Applies a callback to each element in the collection and collects the return value

## Running the test suite
To run the test suite with the native implementation use `php -c functional.ini $(which phpunit) tests/`
To run the test suite with the userland implementation use `php -n $(which phpunit) tests/`

## Mailing lists
 - General help and development list: http://groups.google.com/group/functional-php
 - Commit list: http://groups.google.com/group/functional-php-commits
