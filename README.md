# Read Me

## Getting Started

Before you can begin working on your PHP code, you'll need to configure your application settings with Box.  To start your application configuration, follow these [instructions](https://developer.box.com/v2.0/docs/configuring-box-platform).

Make sure to select **OAuth 2.0 with JWT (Server Authentication)** for **Authentication Method**.

Also make sure to give your application the permissions to handle files and folders.  For the purpose of the included examples, permission should be given to read and write files and perform actions as users.

After you've created/generated your RSA keypair, download your app configuration JSON file.

## Configure Your Local Setup

Clone the project to your local computer.

Make a copy  of `example.box.config.php` (in the project root directory) and save it as `box.config.php`.  Fill out the missing values from your downloaded configuration JSON file.

`jwtPrivateKey` is the path to your private key you created/generated earlier.  For example, if your private key, `pkey.pem`, is in your local project directory, you'll set it like this:

```php
return array(
    "clientId" => "<clientId>",
    "clientSecret" => "<clientSecret>",
    "enterpriseId" => "<enterpriseId>",
    "jwtPrivateKey" => __DIR__ . "/pkey.pem",
    "jwtPrivateKeyPassword" => "<passphrase>",
    "jwtPublicKeyId" => "<publicKeyID>",
);
```

Install dependencies (assuming you already have [Composer](https://getcomposer.org/) installed).

```bash
composer install
```

## Run an Example

Examples are stored in the `examples` folder in the project root directory.  To run the example to retrieve an access token, execute the following from the project root directory:

```bash
php ./examples/exAuthGetAccessToken.php
```

This will return something like the following:

```
Access Token: BrTUlJQ55EhpAZFXYmJXXfZxuCvM5uq1
```

Examples dealing with files and folders will impersonate a Box user.  You'll need to open the example file and fill in the email for `$userLogin`.

```php
$userLogin = 'johndoe@example.com';
```

## Starting Your Project

Simply include your autoloader and the `helpers/helpers.php` as in `bootstrap/autoload.php`.  Once you've done that, use the examples to help you get started.  A good example to get started with is `examples/exAuthGetAccessToken.php`.

## Run Tests

Make a copy of `example.phpunit.xml` as `phpunit.xml`  Open up `phpunit.xml` and fill out `AS_USER_EMAIL` with the Box user account login email.  This is the account the tests will impersonate.

```xml
    <php>
        <env name="APP_ENV" value="testing"/>
        <env name="CONFIG" value="box.config.php"/>
        <env name="AS_USER_EMAIL" value="johndoe@example.com"/>
    </php>
```

Run the tests.

```php
phpunit
```

You should get an output like this:

```
PHPUnit 7.0.1 by Sebastian Bergmann and contributors.

............                                                      12 / 12 (100%)

Time: 58.06 seconds, Memory: 6.00MB
```