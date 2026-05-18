# Spike Strauss (branche `try/strauss`)

Comparaison avec la stack php-scoper + `.orph/` sur `main`.

## Setup

```bash
composer install
```

- Télécharge `bin/strauss.phar` si absent
- Préfixe les paquets `require` dans `composer-build/`
- Les paquets `require-dev` ne sont **pas** copiés (Strauss lit uniquement `require`)

## Autoload WordPress

```php
require_once __DIR__ . '/composer-build/autoload.php';
```

## Classes

Utiliser le préfixe configuré : `Test_Vendor\Vendor\...` (ex. `Test_Vendor\Vendor\Carbon\Carbon`).

## Fichiers php-scoper (non utilisés sur cette branche)

- `scoper.inc.php`
- `.orph/scripts/clean-scope.php`
- `.orph/scripts/rebuild-scoped-autoload.php`
