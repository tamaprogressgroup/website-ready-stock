<?php

return [
    /*
    |--------------------------------------------------------------------------
    | Redis Cache Key Prefix — Paradise Ready Stock
    |--------------------------------------------------------------------------
    | Main prefix applied to all application cache keys in Redis.
    | Format: {redis_key}:{path}[:{lang}][:{slug}]
    |
    | Change these values in .env:
    |   REDIS_KEY=progressgroupreadystock
    |   REDIS_KEY_GROUP=progressgroupreadystock:group
    */
    'redis_key'       => env('REDIS_KEY', 'progressgroupreadystock'),
    'redis_key_group' => env('REDIS_KEY_GROUP', 'progressgroupreadystock:group'),

    /*
    |--------------------------------------------------------------------------
    | HubSpot Township Mapping
    |--------------------------------------------------------------------------
    | Maps township_name (exact match) to HubSpot CRM property values.
    | 'code'    → first_project_website property in HubSpot
    | 'project' → used for internal routing (e.g. WA channel selection)
    |
    | Add/edit entries here to match your HubSpot CRM configuration.
    */
    'hubspot_township_map' => [
        'Paradise Serpong City'   => ['code' => '01-PSC',  'project' => 'psc'],
        'Paradise Resort City'    => ['code' => '02-PR',   'project' => 'pr'],
        'Paradise Serpong City 2' => ['code' => '05-PSC2', 'project' => 'psc2'],
    ],
];
