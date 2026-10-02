<?php

require_once __DIR__ . '/../aws.phar';

use Aws\S3\S3Client;

$accountId = "79927b3decae88183b47549c3a15fa67";
$accessKey = "ce0bedf05f53a618f46c98e3019619b7";
$secretKey = "23d464a7a49a18f1e8d641b8fd4844b835b3285d7c9b8d570291fbb0084a5121";
$bucket = "kurakulas";

$s3 = new S3Client([
  'version' => 'latest',
  'region'  => 'auto',
  'endpoint' => "https://$accountId.r2.cloudflarestorage.com",
  'credentials' => [
    'key' => $accessKey,
    'secret' => $secretKey
  ]
]);







