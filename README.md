# Oauth setup for ArgentinaHumana login

## Installation

You can install the package via composer:

```bash
composer require frente-patria-grande/ah-auth
```

You need to open the `config/services.php` file and add the following service

```php
//...
'argentina-humana' => [
    'base_url' => rtrim(env('ARGENTINA_HUMANA_BASE_URL', 'https://cuenta.argentinahumana.com.ar'), '/'),
    'client_id' => env('ARGENTINA_HUMANA_CLIENT_ID'),
    'client_secret' => env('ARGENTINA_HUMANA_CLIENT_SECRET'),
    'redirect' => env('ARGENTINA_HUMANA_CALLBACK_URL'),
],
//...
```

Then add the following trait to your user model

```php
use FrentePatriaGrande\AHAuth\Traits\HasSocialiteAuth;

class User ... {
    use HasSocialiteAuth;
}
```

You can publish and run the migrations with:

```php
php artisan vendor:publish --tag=":package_slug-migrations"
php artisan migrate
```

## Usage

```php
// Redirect to login
Socialite::driver('argentina-humana')->redirect();
            
// Login callback
/** @var \Laravel\Socialite\Two\User $socialiteUser */
$socialiteUser = Socialite::driver('argentina-humana')->user();
// $socialiteUser->email;
// $socialiteUser->token;
// $socialiteUser->refreshToken;
```

## Testing

```bash
composer test
```

## Changelog

Please see [CHANGELOG](CHANGELOG.md) for more information on what has changed recently.

## Contributing

Please see [CONTRIBUTING](CONTRIBUTING.md) for details.

## Security Vulnerabilities

Please review [our security policy](../../security/policy) on how to report security vulnerabilities.

## Credits

- [Frente Patria Grande](https://github.com/Frente-Patria-Grande)
- [All Contributors](../../contributors)

## License

The MIT License (MIT). Please see [License File](LICENSE.md) for more information.
