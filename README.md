# Seo admin panel for Laravel framework

## Installation
Install package via composer

```bash
composer require bioforce/seosettings
```

Publishing files

```bash
php artisan vendor:publish --provider="Bioforce\SeoSettings\SeoSettingsServiceProvider"
```

Migrate database

```bash
php artisan migrate
```

Add meta tags group. For example Default group.
In the group add the metatags. For example Title.

Then you can manage metatgs in routes.

In blade just add
```php
@metatags
```

Config in config/seo-settings.php

```php
    /**
     * Root admin path
     */
    'path' => 'admin',

    /**
     * Middleware for admin dashboard
     */
    'middleware' => [
        'web',
        'auth',
    ]
```

Go to your site: site.com{seo-settings.path}/seo and just enjoy