<?php

namespace Core;

use PDO;

final class Database {
    public static function makePDO(array $configDb): PDO {

        $dsn = sprintf(
            'mysql:host=%s;port=%d;dbname=%s;charset=%s',
            $configDb['host'],
            $configDb['port'],
            $configDb['db'],
            $configDb['charset']
        );

        //$dsnSprintF = sprintf('martin est le plus %s et le plus %s', 'fort', 'gentil');

        return new PDO($dsn, $configDb['user'], $configDb['pass'], [
            PDO::ATTR_ERRMODE => PDO::ERRMODE_EXCEPTION,
            PDO::ATTR_DEFAULT_FETCH_MODE => PDO::FETCH_ASSOC,
            PDO::ATTR_EMULATE_PREPARES => false
        ]);
    }
}
