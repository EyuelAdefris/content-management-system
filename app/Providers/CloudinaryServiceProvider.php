<?php

namespace App\Providers;

use Cloudinary\Cloudinary;
use Cloudinary\Configuration\Configuration;
use Illuminate\Support\Facades\Storage;
use Illuminate\Support\ServiceProvider;
use League\Flysystem\Filesystem;
use App\Storage\CloudinaryAdapter;

class CloudinaryServiceProvider extends ServiceProvider
{
    /**
     * Register any application services.
     */
    public function register(): void
    {
        //
    }

    /**
     * Bootstrap any application services.
     */
    public function boot(): void
    {
        Storage::extend('cloudinary', function ($app, $config) {
            $cloudinary = new Cloudinary(
                Configuration::instance([
                    'cloud' => [
                        'cloud_name' => $config['cloud_name'],
                        'api_key'    => $config['api_key'],
                        'api_secret' => $config['api_secret'],
                    ],
                    'url' => [
                        'secure' => $config['secure'] ?? true,
                    ],
                ])
            );

            $adapter = new CloudinaryAdapter($cloudinary, $config['cloud_name']);
            $filesystem = new Filesystem($adapter, $config);

            return new \Illuminate\Filesystem\FilesystemAdapter($filesystem, $adapter, $config);
        });
    }
}
