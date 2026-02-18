<?php
 
namespace App\Services;
 
use Aws\S3\S3Client;
use Illuminate\Http\UploadedFile;
 
 
class CephStorageService
{
    private S3Client $client;
    private string $bucket;
 
    public function __construct()
    {
        $this->client = new S3Client([
            'region' => config('filesystems.disks.ceph.region'),
            'version' => '2006-03-01',
            'endpoint' => config('filesystems.disks.ceph.endpoint'),
            'credentials' => [
                'key' => config('filesystems.disks.ceph.key'),
                'secret' => config('filesystems.disks.ceph.secret'),
            ],
            'use_path_style_endpoint' => true,
        ]);
 
        $this->bucket = config('filesystems.disks.ceph.bucket');
    }
 
    /**
     * Upload file langsung ke Ceph via server (PutObject)
     */
    public function putObject(string $key, UploadedFile $file): string
    {
        $this->client->putObject([
            'Bucket' => $this->bucket,
            'Key' => $key,
            'Body' => fopen($file->getRealPath(), 'r'),
            'ContentType' => $file->getMimeType(),
        ]);
 
        return $key;
    }
 
    /**
     * Pre-signed download URL
     */
    public function presignDownload(string $key, int $minutes = 60): string
    {
        $cmd = $this->client->getCommand('GetObject', [
            'Bucket' => $this->bucket,
            'Key' => $key,
        ]);
 
        $request = $this->client->createPresignedRequest($cmd, "+{$minutes} minutes");
 
        return (string) $request->getUri();
    }
 
    /**
     * Pre-signed upload URL (PUT)
     */
    public function presignUpload(string $key, int $minutes = 10): string
    {
        $cmd = $this->client->getCommand('PutObject', [
            'Bucket' => $this->bucket,
            'Key' => $key,
        ]);
 
        $request = $this->client->createPresignedRequest($cmd, "+{$minutes} minutes");
 
        return (string) $request->getUri();
    }
}
 