# Genkgo.Cache
PHP Cache library using mechanisms as originally proposed by Anthony Ferrara.

### Credit to Anthony Ferrara.

All the credit for the cache mechanisms go to [@ircmaxell](http://blog.ircmaxell.com). Please go and read his
[blog post explaining why cache should be implemented this way](http://blog.ircmaxell.com/2014/10/a-followup-to-open-letter-to-php-fig.html).

### Installation

Requires PHP 5.5 or later. There are no plans to support PHP 5.4 or PHP 5.3. In case this is an obstacle for you,
conversion should be no problem. The library is very small.

It is installable and autoloadable via Composer as [genkgo/cache](https://packagist.org/packages/genkgo/cache).

### Quality

[![Scrutinizer Code Quality](https://scrutinizer-ci.com/g/genkgo/cache/badges/quality-score.png?b=master)](https://scrutinizer-ci.com/g/genkgo/cache/)
[![Code Coverage](https://scrutinizer-ci.com/g/genkgo/cache/badges/coverage.png?b=master)](https://scrutinizer-ci.com/g/genkgo/cache/)
[![Build Status](https://travis-ci.org/genkgo/cache.png?branch=master)](https://travis-ci.org/genkgo/cache)

To run the unit tests at the command line, issue `phpunit -c tests/`. [PHPUnit](http://phpunit.de/manual/) is required.

This library attempts to comply with [PSR-1][], [PSR-2][], and [PSR-4][]. If
you notice compliance oversights, please send a patch via pull request.

[PSR-1]: https://github.com/php-fig/fig-standards/blob/master/accepted/PSR-1-basic-coding-standard.md
[PSR-2]: https://github.com/php-fig/fig-standards/blob/master/accepted/PSR-2-coding-style-guide.md
[PSR-4]: https://github.com/php-fig/fig-standards/blob/master/accepted/PSR-4-autoloader.md

## Getting Started

### Create your own adapter

Create an adapter that implements the CacheAdapterInterface. A simple array adapter would look as follows. The array
adapter is also shipped with this library.

```php
<?php
namespace My\Namespace;

use Genkgo\Cache\CacheAdapterInterface;

class ArrayAdapter implements CacheAdapterInterface
{
    private $data = [];

    public function set($key, $value)
    {
        $this->data[$key] = $value;
    }

    public function get($key)
    {
        if ($this->exists($key)) {
            return $this->data[$key];
        }
    }

    public function delete($key)
    {
        if ($this->exists($key)) {
            unset($this->data[$key]);
        }
    }

    private function exists($key)
    {
        return isset($this->data[$key]) || array_key_exists($key, $this->data);
    }
}
```

### Inject the adapter

To use your adapter, inject it into another object and start using the api. If you do not want any cache,
but your class relies on a cache adapter being there, inject the NullAdapter.

## Contributing

- Found a bug? Please try to solve it yourself first and issue a pull request. If you are not able to fix it, at least
  give a clear description what goes wrong. We will have a look when there is time.
- Want to see a feature added, issue a pull request and see what happens. You could also file a bug of the missing
  feature and we can discuss how to implement it.