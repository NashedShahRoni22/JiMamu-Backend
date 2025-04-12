<?php

declare(strict_types=1);

return [
    /*
     * ------------------------------------------------------------------------
     * Default Firebase project
     * ------------------------------------------------------------------------
     */

    'default' => env('FIREBASE_PROJECT', 'app'),

    /*
     * ------------------------------------------------------------------------
     * Firebase project configurations
     * ------------------------------------------------------------------------
     */

    'projects' => [
        'app' => [

            /*
             * ------------------------------------------------------------------------
             * Credentials / Service Account
             * ------------------------------------------------------------------------
             *
             * In order to access a Firebase project and its related services using a
             * server SDK, requests must be authenticated. For server-to-server
             * communication this is done with a Service Account.
             *
             * If you don't already have generated a Service Account, you can do so by
             * following the instructions from the official documentation pages at
             *
             * https://firebase.google.com/docs/admin/setup#initialize_the_sdk
             *
             * Once you have downloaded the Service Account JSON file, you can use it
             * to configure the package.
             *
             * If you don't provide credentials, the Firebase Admin SDK will try to
             * auto-discover them
             *
             * - by checking the environment variable FIREBASE_CREDENTIALS
             * - by checking the environment variable GOOGLE_APPLICATION_CREDENTIALS
             * - by trying to find Google's well known file
             * - by checking if the application is running on GCE/GCP
             *
             * If no credentials file can be found, an exception will be thrown the
             * first time you try to access a component of the Firebase Admin SDK.
             *
             */

            'credentials' => [
                                    'type' => 'service_account',
                                    'project_id' => 'jimamu-548f1',
                                    'private_key_id' => '867beadf3d073b172be358aaebbf0737fc5e9235',
                                    'private_key' =>  "-----BEGIN PRIVATE KEY-----\nMIIEvgIBADANBgkqhkiG9w0BAQEFAASCBKgwggSkAgEAAoIBAQDHOxMCJsaG9QQl\nXjthlOcppNPp00hLMEx4ZyczzoVP2sST1AvCDbUYrPyOlKGRk1qgbfaDfLR+gV0C\nQVgaJc8hW3wLWiyAY8YaDQbGvtDLDTqNUjjxhtWIL9LvmsNgb5XNBqyDIPKMk2Or\n7WbnxOtjkXh6renQSSgXptaqerIr+aJDbjEAIntTa2mnuT5cAuItljgOiQUfMtse\nepEYPKUAu4cETjwnnlKkxHTGHLedvtBUTa8JpqGNkdYD3qp47Y4GxBBgll8DXXen\ntvZkAyT4pEszN12xyB6bGqrjkED8y/sv1hcVA88pQ/WFXoDI6m8ChZOMYudrrAP1\nOVgHnuLBAgMBAAECggEAJTQE0qwG+oCaa5fzIr+5gu9CmzFXGKNGRF2O1n/EKStC\n9g/suinxHVCAQOfEW/jWPYsLM3aABfeGNLubBhb1XNdGjKAkGz2GaqrwDwDm4GPy\nAR3PgFqbWTkE/LU7srBjmsuaP0pRwKptPFeylakSA5/QjagYN0rR78i54U/UIZ3B\ntN8fVXwARYdqHaa53jGmqD6lM7Y+kvDav0is4ycxnYc3/6PQGSESXVnbm/EoLbpE\nQNzzY5ntKWY28OIB7eIwchTvQQAdLxkyQcZfSDNgpas5xQkIW/KI1tvt0OOE/8p1\ne7jYN0XkMhpBY+GK9o9Mejfb7Eo8aUoLnn/v3DQJoQKBgQDiP02T7ZsbaGni1Fhj\nASh2y0zPY7oJwr9EfmhvrRQoV0mNv4s0ykuNqDdupCkSJfNNUku/KUQ/opCfbQLw\npivVij12WKGVg9QbNkPtGGCIlChFRbnGJMOLYZqOwunPzGEIWeE+m+J9k60Cq0fk\ncVTmmq5eow/6KSIwqnACQCoqnQKBgQDhbkASE9b0HTeA8jyCuZ+0WYP9+cQuligU\nTOlAlzXxPwlZebs1835KWo3BxnrxwoxWoximMK8DARDaZk7CpzO4JWR6BvcgSM5S\nYcoI8uKlPXQLRwE5Ot+TJ1O/pqA19ma8/OZUWYqB/WCnqwtMDhI6GNh1dm+83VNK\n+eIYUVo9dQKBgQCoNXJjGhPow6990mxIZ3MDJ1FbG4UUfrMfpG/XH7JN79+iwJcp\nz4Fut3NHEkvqz0Zd5WKlpHO2cBHgRfcaniEW6Wma1HiGJfYLqUPFQgt7X9O09QsS\nswCjXxS/MVEOiqBVyiU6HEG/JY1K37das5vwu24KtGdLkUt3LTCLq9LtzQKBgAuP\nnOeQbuisCumpAqNto5wbLyK6SW5eMwX/f8LZPmH16qPVE13L9AEaLcTvSeTWJl5X\nFO+I9aDA0D1zG7zt2EAsYr/DVa8pLLOa4kKdOjRhN29Qn+Zwd4DTdn8Qq4y460Hr\n8mAHC4xpEyjmI7kty4+BMLCtd0OHUjs5H3hBy8/1AoGBAJ+aocNqfE3XaGfDUdRr\nxLdxAalhgz0SXx8dS4FKO+CEh3uhP4YtJLdmRIVk8JNWzEcB2stx3I6bXpIGk9DV\n+sIAIki8lkv/IKLWDjtpd94283xAlXcT6SXE7aVieLd+bBuYN1jIPBkHlGZUas71\nhTAf/CgSo5WbH+bzczEgv6tG\n-----END PRIVATE KEY-----\n",

                                    'client_email' => 'firebase-adminsdk-fbsvc@jimamu-548f1.iam.gserviceaccount.com',
                                    'client_id' => '115721389881027713772',
                                    'auth_uri' => 'https://accounts.google.com/o/oauth2/auth',
                                    'token_uri' => 'https://oauth2.googleapis.com/token',
                                    'auth_provider_x509_cert_url' => 'https://www.googleapis.com/oauth2/v1/certs',
                                    "client_x509_cert_url" => "https://www.googleapis.com/robot/v1/metadata/x509/firebase-adminsdk-fbsvc%40jimamu-548f1.iam.gserviceaccount.com",
                                    'universe_domain' => 'googleapis.com',
                                ],

            /*
             * ------------------------------------------------------------------------
             * Firebase Auth Component
             * ------------------------------------------------------------------------
             */

            'auth' => [
                'tenant_id' => env('FIREBASE_AUTH_TENANT_ID'),
            ],

            /*
             * ------------------------------------------------------------------------
             * Firestore Component
             * ------------------------------------------------------------------------
             */

            'firestore' => [

                /*
                 * If you want to access a Firestore database other than the default database,
                 * enter its name here.
                 *
                 * By default, the Firestore client will connect to the `(default)` database.
                 *
                 * https://firebase.google.com/docs/firestore/manage-databases
                 */

                // 'database' => env('FIREBASE_FIRESTORE_DATABASE'),
            ],

            /*
             * ------------------------------------------------------------------------
             * Firebase Realtime Database
             * ------------------------------------------------------------------------
             */

            'database' => [

                /*
                 * In most of the cases the project ID defined in the credentials file
                 * determines the URL of your project's Realtime Database. If the
                 * connection to the Realtime Database fails, you can override
                 * its URL with the value you see at
                 *
                 * https://console.firebase.google.com/u/1/project/_/database
                 *
                 * Please make sure that you use a full URL like, for example,
                 * https://my-project-id.firebaseio.com
                 */

                'url' => env('FIREBASE_DATABASE_URL'),

                /*
                 * As a best practice, a service should have access to only the resources it needs.
                 * To get more fine-grained control over the resources a Firebase app instance can access,
                 * use a unique identifier in your Security Rules to represent your service.
                 *
                 * https://firebase.google.com/docs/database/admin/start#authenticate-with-limited-privileges
                 */

                // 'auth_variable_override' => [
                //     'uid' => 'my-service-worker'
                // ],

            ],

            'dynamic_links' => [

                /*
                 * Dynamic links can be built with any URL prefix registered on
                 *
                 * https://console.firebase.google.com/u/1/project/_/durablelinks/links/
                 *
                 * You can define one of those domains as the default for new Dynamic
                 * Links created within your project.
                 *
                 * The value must be a valid domain, for example,
                 * https://example.page.link
                 */

                'default_domain' => env('FIREBASE_DYNAMIC_LINKS_DEFAULT_DOMAIN'),
            ],

            /*
             * ------------------------------------------------------------------------
             * Firebase Cloud Storage
             * ------------------------------------------------------------------------
             */

            'storage' => [

                /*
                 * Your project's default storage bucket usually uses the project ID
                 * as its name. If you have multiple storage buckets and want to
                 * use another one as the default for your application, you can
                 * override it here.
                 */

                'default_bucket' => env('FIREBASE_STORAGE_DEFAULT_BUCKET'),

            ],

            /*
             * ------------------------------------------------------------------------
             * Caching
             * ------------------------------------------------------------------------
             *
             * The Firebase Admin SDK can cache some data returned from the Firebase
             * API, for example Google's public keys used to verify ID tokens.
             *
             */

            'cache_store' => env('FIREBASE_CACHE_STORE', 'file'),

            /*
             * ------------------------------------------------------------------------
             * Logging
             * ------------------------------------------------------------------------
             *
             * Enable logging of HTTP interaction for insights and/or debugging.
             *
             * Log channels are defined in config/logging.php
             *
             * Successful HTTP messages are logged with the log level 'info'.
             * Failed HTTP messages are logged with the log level 'notice'.
             *
             * Note: Using the same channel for simple and debug logs will result in
             * two entries per request and response.
             */

            'logging' => [
                'http_log_channel' => env('FIREBASE_HTTP_LOG_CHANNEL'),
                'http_debug_log_channel' => env('FIREBASE_HTTP_DEBUG_LOG_CHANNEL'),
            ],

            /*
             * ------------------------------------------------------------------------
             * HTTP Client Options
             * ------------------------------------------------------------------------
             *
             * Behavior of the HTTP Client performing the API requests
             */

            'http_client_options' => [

                /*
                 * Use a proxy that all API requests should be passed through.
                 * (default: none)
                 */

                'proxy' => env('FIREBASE_HTTP_CLIENT_PROXY'),

                /*
                 * Set the maximum amount of seconds (float) that can pass before
                 * a request is considered timed out
                 *
                 * The default time out can be reviewed at
                 * https://github.com/kreait/firebase-php/blob/6.x/src/Firebase/Http/HttpClientOptions.php
                 */

                'timeout' => env('FIREBASE_HTTP_CLIENT_TIMEOUT'),

                'guzzle_middlewares' => [],
            ],
        ],
    ],
];
