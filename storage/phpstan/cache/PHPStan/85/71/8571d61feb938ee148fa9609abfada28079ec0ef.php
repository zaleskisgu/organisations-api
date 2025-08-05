<?php declare(strict_types = 1);

// odsl-/var/www/app
return \PHPStan\Cache\CacheItem::__set_state(array(
   'variableKey' => 'v1',
   'data' => 
  array (
    '/var/www/app/Http/Controllers/Api/OrganizationController.php' => 
    array (
      0 => '87dc28ec927c279c1bbfabb5dfbffafb531335b1',
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
        7 => 'app\\http\\controllers\\api\\getchildactivityids',
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
    '/var/www/app/Exceptions/Handler.php' => 
    array (
      0 => '0d6abd7e5b62c8e46d96d04605e65fe4e2e451c7',
      1 => 
      array (
        0 => 'app\\exceptions\\handler',
      ),
      2 => 
      array (
        0 => 'app\\exceptions\\register',
      ),
      3 => 
      array (
      ),
    ),
    '/var/www/app/Http/Controllers/Api/BuildingController.php' => 
    array (
      0 => '40ff364c49df39e46df0fdf770ce6e68259cbeae',
      1 => 
      array (
        0 => 'app\\http\\controllers\\api\\buildingcontroller',
      ),
      2 => 
      array (
        0 => 'app\\http\\controllers\\api\\index',
      ),
      3 => 
      array (
      ),
    ),
    '/var/www/app/Http/Controllers/Api/OpenApiSpec.php' => 
    array (
      0 => '746cdf020690268423003d83584c8f732f507e1f',
      1 => 
      array (
        0 => 'app\\http\\controllers\\api\\openapispec',
      ),
      2 => 
      array (
      ),
      3 => 
      array (
      ),
    ),
    '/var/www/app/Http/Requests/SearchAreaRequest.php' => 
    array (
      0 => '1cc9845e23a02f29d2bfd3c5e10ca1fd16382daf',
      1 => 
      array (
        0 => 'app\\http\\requests\\searcharearequest',
      ),
      2 => 
      array (
        0 => 'app\\http\\requests\\authorize',
        1 => 'app\\http\\requests\\rules',
        2 => 'app\\http\\requests\\messages',
        3 => 'app\\http\\requests\\failedvalidation',
      ),
      3 => 
      array (
      ),
    ),
    '/var/www/app/Http/Requests/SearchNameRequest.php' => 
    array (
      0 => '5eb92e2b3cc76b8e058e14598880c93ac4c9da88',
      1 => 
      array (
        0 => 'app\\http\\requests\\searchnamerequest',
      ),
      2 => 
      array (
        0 => 'app\\http\\requests\\authorize',
        1 => 'app\\http\\requests\\rules',
        2 => 'app\\http\\requests\\messages',
        3 => 'app\\http\\requests\\failedvalidation',
      ),
      3 => 
      array (
      ),
    ),
    '/var/www/app/Http/Requests/SearchRadiusRequest.php' => 
    array (
      0 => '331a3007486c257370ebd82d519ec92791026468',
      1 => 
      array (
        0 => 'app\\http\\requests\\searchradiusrequest',
      ),
      2 => 
      array (
        0 => 'app\\http\\requests\\authorize',
        1 => 'app\\http\\requests\\rules',
        2 => 'app\\http\\requests\\messages',
        3 => 'app\\http\\requests\\failedvalidation',
      ),
      3 => 
      array (
      ),
    ),
    '/var/www/app/Http/Resources/ActivityResource.php' => 
    array (
      0 => '7470560d59cc64c025899d8d6b62f141f29d34e2',
      1 => 
      array (
        0 => 'app\\http\\resources\\activityresource',
      ),
      2 => 
      array (
        0 => 'app\\http\\resources\\toarray',
      ),
      3 => 
      array (
      ),
    ),
    '/var/www/app/Http/Resources/ApiResponse.php' => 
    array (
      0 => '22668f88e3103661a524f9eb3f9eb80e7325c798',
      1 => 
      array (
        0 => 'app\\http\\resources\\apiresponse',
      ),
      2 => 
      array (
        0 => 'app\\http\\resources\\success',
        1 => 'app\\http\\resources\\collection',
        2 => 'app\\http\\resources\\error',
        3 => 'app\\http\\resources\\notfound',
        4 => 'app\\http\\resources\\validationerror',
      ),
      3 => 
      array (
      ),
    ),
    '/var/www/app/Http/Resources/BuildingResource.php' => 
    array (
      0 => '2030defa806dee1ea42e186ef34cc7d623df374e',
      1 => 
      array (
        0 => 'app\\http\\resources\\buildingresource',
      ),
      2 => 
      array (
        0 => 'app\\http\\resources\\toarray',
      ),
      3 => 
      array (
      ),
    ),
    '/var/www/app/Http/Resources/OrganizationPhoneResource.php' => 
    array (
      0 => 'ca5f499e934e0c8878076935739a3dd9d2119cc2',
      1 => 
      array (
        0 => 'app\\http\\resources\\organizationphoneresource',
      ),
      2 => 
      array (
        0 => 'app\\http\\resources\\toarray',
      ),
      3 => 
      array (
      ),
    ),
    '/var/www/app/Http/Resources/OrganizationResource.php' => 
    array (
      0 => '8f3c532487e52c820ac9f815276013e2d60d5c71',
      1 => 
      array (
        0 => 'app\\http\\resources\\organizationresource',
      ),
      2 => 
      array (
        0 => 'app\\http\\resources\\toarray',
      ),
      3 => 
      array (
      ),
    ),
  ),
));