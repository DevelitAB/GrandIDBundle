Grand ID Symfony Bundle
===============================

[![Latest Stable Version](https://poser.pugx.org/bsadnu/grand-id-bundle/v/stable)](https://packagist.org/packages/bsadnu/grand-id-bundle) 
[![Total Downloads](https://poser.pugx.org/bsadnu/grand-id-bundle/downloads)](https://packagist.org/packages/bsadnu/grand-id-bundle) 
[![License](https://poser.pugx.org/bsadnu/grand-id-bundle/license)](https://packagist.org/packages/bsadnu/grand-id-bundle)

This extension provides a number of service methods necessary for working with [Grand ID API](https://www.grandid.com/documentation/). All sessions data are stored in database.

There are so-called mock system which is helpful for testing purposes. Mock-methods do not call any Grand ID API endpoints. They just simulate sessions: create, store & update them in DB table.


## Installation


#### Applications that use Symfony Flex


Open a command console, enter your project directory and execute:

```console
$ composer require bsadnu/grand-id-bundle
```

#### Applications that don't use Symfony Flex


Step 1: Download the Bundle

Open a command console, enter your project directory and execute the
following command to download the latest stable version of this bundle:

```console
$ composer require bsadnu/grand-id-bundle
```

This command requires you to have Composer installed globally, as explained
in the [installation chapter](https://getcomposer.org/doc/00-intro.md)
of the Composer documentation.

Step 2: Enable the Bundle

Then, enable the bundle by adding it to the list of registered bundles
in the `app/AppKernel.php` file of your project:

```php
<?php
// app/AppKernel.php

// ...
class AppKernel extends Kernel
{
    public function registerBundles()
    {
        $bundles = array(
            // ...
            new Bsadnu\GrandIDBundle\GrandIDBundle(),
        );

        // ...
    }

    // ...
}
```

## Configuration

Add the `config/packages/grand_id.yaml` file consists of settings as follows:

```yml
grand_id:
    base_url: '%env(GRAND_ID_BASE_URL)%' #(e.g. https://client-test.grandid.com/json1.1/)
    api_key: '%env(GRAND_ID_API_KEY)%'
    authenticate_service_key: '%env(GRAND_ID_AUTH_SERVICE_KEY)%'
```

Perform `bin/console doctrine:migrations:diff` and `bin/console doctrine:migrations:migrate` commands in order to create Grand ID sessions DB table.

## Usage

Somewhere in your controller:

```php
<?php

...

use Symfony\Component\DependencyInjection\ContainerInterface;
use Symfony\Bundle\FrameworkBundle\Controller\Controller;

...

class SomeController extends Controller
{
    ...
    
    private $bankIdProvider;

    public function __construct(ContainerInterface $container)
    {
        $this->bankIdProvider = $container->get('bsadnu.grand_id');
    }
    
    public function doSomething()
    {
        ...
        
        $callBackUrl = 'https://domain.com/my/action'

        $loginObject = $this->bankIdProvider->federatedLogin($callBackUrl);

        ...
    }   
    
    ...
}
```


## Available methods

* `federatedLogin(string $callbackUrl)` - performs real FederatedLogin API call. Store real session params.
* `federatedLoginMock(string $callbackUrl, string $host, string $protocol)` - does not perform any API call. Just store mock session params.
* `federatedDirectLogin(string $username, string $password)`- performs real FederatedDirectLogin API call. Store real session params.
* `logout(string $sessionId)` - performs real API Logout. Update related DB record.
* `logoutMock(string $sessionId)` - does not perform any API call. Just update certain DB record.
* `getSession(string $sessionId)` - fetch session params by calling real API GetSession.
* `getSessionMock(string $sessionId)` - fetch mock session params by from DB.
* `enableMockSession(string $sessionId, string $username)` - update mock session DB record by adding username and making is_logged_in equals to true.

## Acknowledgments
* [Develit Software Development](https://www.develit.se)
