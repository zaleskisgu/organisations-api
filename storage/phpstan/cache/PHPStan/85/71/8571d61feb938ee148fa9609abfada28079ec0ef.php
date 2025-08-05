<?php declare(strict_types = 1);

// odsl-/var/www/app
return \PHPStan\Cache\CacheItem::__set_state(array(
   'variableKey' => 'v1',
   'data' => 
  array (
    '/var/www/app/Http/Controllers/Api/OrganizationController.php' => 
    array (
      0 => '956fcc53118c28ebd534e8eb57edc873a7fde8a8',
      1 => 
      array (
        0 => 'app\\http\\controllers\\api\\organizationcontroller',
      ),
      2 => 
      array (
        0 => 'app\\http\\controllers\\api\\getbybuilding',
        1 => 'app\\http\\controllers\\api\\getbyactivity',
        2 => 'app\\http\\controllers\\api\\searchbyradius',
        3 => 'app\\http\\controllers\\api\\searchbyarea',
        4 => 'app\\http\\controllers\\api\\show',
        5 => 'app\\http\\controllers\\api\\searchbyactivitytree',
        6 => 'app\\http\\controllers\\api\\searchbyname',
        7 => 'app\\http\\controllers\\api\\getbuildings',
        8 => 'app\\http\\controllers\\api\\getalldescendantids',
      ),
      3 => 
      array (
      ),
    ),
    '/var/www/app/Http/Controllers/Controller.php' => 
    array (
      0 => 'a33a5105f92c73a309c9f8a549905dcdf6dccbae',
      1 => 
      array (
        0 => 'app\\http\\controllers\\controller',
      ),
      2 => 
      array (
      ),
      3 => 
      array (
      ),
    ),
    '/var/www/app/Http/Middleware/ApiKeyMiddleware.php' => 
    array (
      0 => '7fdc194367836085b1734d94d6b7656b07653ea9',
      1 => 
      array (
        0 => 'app\\http\\middleware\\apikeymiddleware',
      ),
      2 => 
      array (
        0 => 'app\\http\\middleware\\handle',
      ),
      3 => 
      array (
      ),
    ),
    '/var/www/app/Models/Activity.php' => 
    array (
      0 => 'f8ba3a6b605eb837ff7c4b578d72dcd4ece8ab9e',
      1 => 
      array (
        0 => 'app\\models\\activity',
      ),
      2 => 
      array (
        0 => 'app\\models\\parent',
        1 => 'app\\models\\children',
        2 => 'app\\models\\organizations',
        3 => 'app\\models\\getallchildren',
        4 => 'app\\models\\getalldescendants',
      ),
      3 => 
      array (
      ),
    ),
    '/var/www/app/Models/Building.php' => 
    array (
      0 => 'c8b0befddce8752c20bdc5f3c8b177fafc1bebaa',
      1 => 
      array (
        0 => 'app\\models\\building',
      ),
      2 => 
      array (
        0 => 'app\\models\\organizations',
      ),
      3 => 
      array (
      ),
    ),
    '/var/www/app/Models/Organization.php' => 
    array (
      0 => 'ffeac1c37d42e8cb5d25cea0df59c16fbd069f2c',
      1 => 
      array (
        0 => 'app\\models\\organization',
      ),
      2 => 
      array (
        0 => 'app\\models\\building',
        1 => 'app\\models\\activities',
        2 => 'app\\models\\phones',
      ),
      3 => 
      array (
      ),
    ),
    '/var/www/app/Models/OrganizationPhone.php' => 
    array (
      0 => '4858d8ef4ff794ece4f518a302ae770668e58542',
      1 => 
      array (
        0 => 'app\\models\\organizationphone',
      ),
      2 => 
      array (
        0 => 'app\\models\\organization',
      ),
      3 => 
      array (
      ),
    ),
    '/var/www/app/Models/User.php' => 
    array (
      0 => '0de556dcaed737c421ea5b9b029f7cc1445768cf',
      1 => 
      array (
        0 => 'app\\models\\user',
      ),
      2 => 
      array (
        0 => 'app\\models\\casts',
      ),
      3 => 
      array (
      ),
    ),
    '/var/www/app/Providers/AppServiceProvider.php' => 
    array (
      0 => '01bf9e5cf5bb666446625056b618445ae4749675',
      1 => 
      array (
        0 => 'app\\providers\\appserviceprovider',
      ),
      2 => 
      array (
        0 => 'app\\providers\\register',
        1 => 'app\\providers\\boot',
      ),
      3 => 
      array (
      ),
    ),
    '/var/www/app/Providers/RouteServiceProvider.php' => 
    array (
      0 => 'ccf5f77777cc81aedea32cae2950597a09549d7e',
      1 => 
      array (
        0 => 'app\\providers\\routeserviceprovider',
      ),
      2 => 
      array (
        0 => 'app\\providers\\boot',
      ),
      3 => 
      array (
      ),
    ),
  ),
));