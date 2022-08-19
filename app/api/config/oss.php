<?php
return [
    'access_key_id' => env('OSS.OSS_ACCESS_KEY_ID'), // Required, YourAccessKeyId
    'access_key_secret' => env('OSS.OSS_ACCESS_KEY_SECRET'), // Required, YourAccessKeySecret
    'bucket' => env('OSS.OSS_BUCKET'), // Required, Bucket
    'endpoint' => env('OSS.OSS_ENDPOINT'), // Required, Endpoint

    'callback_url' => env('OSS.OSS_CALLBACK_URL'), // Required, CallbackUrl

    'max_size' => env('OSS.OSS_POLICY_MAX_SIZE', 1048576000),
    'expire_time' => env('OSS.OSS_POLICY_EXPIRE_TIME', 60),
    'user_dir' => env('OSS.OSS_POLICY_USER_DIR', 'upload/'),
];