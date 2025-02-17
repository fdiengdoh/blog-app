<?php
namespace Fdiengdoh\BlogApp;

use Dotenv\Dotenv;

class Config {
    private static $config = [];

    public static function load() {
        $dotenv = Dotenv::createImmutable(__DIR__ . '/../');
        $dotenv->load();

        self::$config = [
            'DB_HOST' => $_ENV['DB_HOST'],
            'DB_NAME' => $_ENV['DB_NAME'],
            'DB_USER' => $_ENV['DB_USER'],
            'DB_PASS' => $_ENV['DB_PASS'],
        ];
    }

    public static function get($key) {
        return self::$config[$key] ?? null;
    }
}
