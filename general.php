<?php

use \craft\config\GeneralConfig;

require_once 'site.php';

$config = GeneralConfig::create();
$config
    // HeadlessMode
    ->headlessMode(true)

    // Admin
    ->omitScriptNameInUrls(true)

    // Development
    ->allowUpdates(false)
    ->allowAdminChanges(false)
    ->devMode(false)
    ->translationDebugOutput(false)

    // Files
    ->maxUploadFileSize('128M')

    // Queue
    ->runQueueAutomatically(false)

    // Graphql
    ->enableGraphqlCaching(true)
    ->enableGraphqlIntrospection(false)

    // Length of time Craft will store data, RSS feed, and template caches.
    // true, false, PT60M (60 Minutes), P1D (1 day) or check http://www.php.net/manual/en/dateinterval.construct.php.
    // https://docs.craftcms.com/v3/config/config-settings.html#cacheduration
    ->cacheDuration('P1D')

    // Images
    ->brokenImagePath('@app/icons/broken-image.svg')

    // Users
    ->useEmailAsUsername(true)

    // Urls
    ->limitAutoSlugsToAscii(true)

    // Search
    ->defaultSearchTermOptions([
        'subLeft' => true,
    ])

    // File extensions
    ->allowedFileExtensions(
        array_diff($config->allowedFileExtensions,
            ['webp', 'gif', 'mov', 'tif', 'webm', 'heic', 'heif', 'hevc', 'avif'])
    );

siteGlobal($config);

switch (CRAFT_ENVIRONMENT) {
    case 'local':
        siteLocal($config);

        if (file_exists(CRAFT_BASE_PATH . '/config/local_config.php')) {
            require_once 'local_config.php';
            /* @phpstan-ignore-next-line */
            localConfig($config);
        }

        break;
    case 'buildTest':
        siteBuildTest($config);
        break;
    case 'accept':
    case 'staging':
        // servd accept is called staging
        siteAccept($config);
        break;
    case 'production':
        siteProduction($config);
        break;
}

return $config;
