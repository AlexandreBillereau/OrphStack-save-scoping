<?php

declare(strict_types=1);

return [
  // Le préfixe unique qui sera ajouté devant les namespaces des packages de prod
  'prefix' => 'Test_Vendor\\Vendor',

  // On dit à php-scoper de ne pas toucher aux fonctions globales de WordPress
  'exclude-functions' => [
    'add_action',
    'add_filter',
    'wp_send_json',
    // Tu pourras en ajouter d'autres si besoin
  ],
];
