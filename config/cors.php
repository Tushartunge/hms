<?php

return [

    /*
    |--------------------------------------------------------------------------
    | Allowed CORS Paths
    |--------------------------------------------------------------------------
    |
    | These are the paths that should be CORS-enabled. You may adjust this
    | list to allow certain paths to accept cross-origin requests.
    |
    */
    'paths' => [
        'api/*',  // This applies CORS to all API routes. You can modify this pattern to suit your needs.
    ],

    /*
    |--------------------------------------------------------------------------
    | Allowed Methods
    |--------------------------------------------------------------------------
    |
    | The allowed HTTP methods for cross-origin requests. You can specify
    | individual methods or use '*' to allow all methods.
    |
    */
    'allowed_methods' => [
        '*',  // Allows all HTTP methods (GET, POST, PUT, DELETE, etc.)
    ],

    /*
    |--------------------------------------------------------------------------
    | Allowed Origins
    |--------------------------------------------------------------------------
    |
    | These are the allowed origins for CORS requests. You can specify
    | a single domain or use '*' to allow all domains. It's better to
    | specify specific domains in production for security reasons.
    |
    */
    'allowed_origins' => [
        '*',  // Allows all origins. Replace with specific domains like ['https://example.com'] if needed.
    ],

    /*
    |--------------------------------------------------------------------------
    | Allowed Origins Patterns
    |--------------------------------------------------------------------------
    |
    | These are the patterns that can be used for matching allowed origins.
    | For example, you can allow all subdomains of a specific domain using a pattern.
    |
    */
    'allowed_origins_patterns' => [
        // You can use regular expressions to define patterns for allowed origins.
    ],

    /*
    |--------------------------------------------------------------------------
    | Allowed Headers
    |--------------------------------------------------------------------------
    |
    | These are the allowed headers for cross-origin requests. You can specify
    | individual headers or use '*' to allow all headers.
    |
    */
    'allowed_headers' => [
        '*',  // Allows all headers
    ],

    /*
    |--------------------------------------------------------------------------
    | Exposed Headers
    |--------------------------------------------------------------------------
    |
    | These are the headers that should be exposed to the browser.
    | You can specify individual headers or use '*' to expose all headers.
    |
    */
    'exposed_headers' => [
        // You can list specific headers here if you need to expose custom headers.
    ],

    /*
    |--------------------------------------------------------------------------
    | Max Age
    |--------------------------------------------------------------------------
    |
    | This option defines the maximum time (in seconds) that the preflight
    | request can be cached by the browser.
    |
    */
    'max_age' => 0,  // Set to 0 to disable caching

    /*
    |--------------------------------------------------------------------------
    | Supports Credentials
    |--------------------------------------------------------------------------
    |
    | This option indicates whether or not the browser should include cookies
    | or other credentials with the CORS requests. Set to true if needed.
    |
    */
    'supports_credentials' => true,  // Set to true if you need to allow credentials (e.g., cookies, authorization headers)

];
