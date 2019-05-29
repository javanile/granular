# WP Granular

[![StyleCI](https://github.styleci.io/repos/133355435/shield?branch=master)](https://github.styleci.io/repos/133355435)
[![Build Status](https://travis-ci.org/javanile/granular.svg?branch=master)](https://travis-ci.org/javanile/granular)
[![codecov](https://codecov.io/gh/javanile/granular/branch/master/graph/badge.svg)](https://codecov.io/gh/javanile/granular)
[![Codacy Badge](https://api.codacy.com/project/badge/Grade/d37299ab3e874e94b758ffe11438ac7f)](https://www.codacy.com/app/francescobianco/granular?utm_source=github.com&amp;utm_medium=referral&amp;utm_content=javanile/granular&amp;utm_campaign=Badge_Grade)
[![License: MIT](https://img.shields.io/badge/License-MIT-yellow.svg)](https://opensource.org/licenses/MIT)

WordPress extension framework based on object-oriented paradigm. 
Usign Granular you can write PSR compliant code increasing the general code quality 
performing a better project organization. Organize your code in feature 
or group wordpres actions and filters in the same scope.

```php
namespace Acme\Plugin;

use Javanile\Granular\Bindable;

class MyFirstPluginFeature extends Bindable
{
  static $bindings = [
    'action:init',
    'filter:the_content' => 'theContent',
  ];

  public function init()
  {
    // Init code
  }
  
  public function theContent($content)
  {
    return $content;
  }
}

```


```php

use Javanile\Granular\Autoload;

$app = new Autoload();

// add MyPlugin::init() method to WordPress init action  
$app->register(MyPlugin::class, 'init');


```


## Testing

```bash
$ docker-compose run --rm phpunit --stop-on-failure tests
```


