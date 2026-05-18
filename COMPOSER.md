# Composer + Strauss

Isolation des dépendances pour éviter les conflits entre plugins WordPress.

## Setup dev

```bash
composer install
```

1. Installe les paquets dans `vendor/` (Composer classique)
2. Télécharge `bin/strauss.phar` si besoin
3. Copie et préfixe les paquets `require` dans `composer-build/`
4. Génère `composer-build/autoload.php`

## Structure

```
test-vendor/
├── bin/
│   └── strauss.phar          # ignoré par git, téléchargé au install
├── vendor/                   # ignoré — install Composer
├── composer-build/           # ignoré — libs préfixées (runtime WP)
├── composer.json             # require + extra.strauss
└── test-vendor.php           # require composer-build/autoload.php
```

## Config (`composer.json`)

```json
"extra": {
  "strauss": {
    "target_directory": "composer-build",
    "namespace_prefix": "Test_Vendor\\Vendor\\",
    "classmap_prefix": "Test_Vendor_Vendor_"
  }
}
```

Seuls les paquets listés dans `require` sont préfixés (pas `require-dev`).

## Usage PHP

```php
use Test_Vendor\Vendor\Carbon\Carbon;
```

## Commandes

```bash
composer install          # install + prefix
composer update           # idem
composer prefix-namespaces   # relancer Strauss seul
```
