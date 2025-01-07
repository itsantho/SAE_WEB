<?php

namespace Projet\php;

use Projet\php\Exception\BddConnectException;

class BddConnect {
    public \PDO $pdo;
    protected ?string $host = null;
    protected ?string $login= null;
    protected ?string $password= null;
    protected ?string $dbname= null;

    public function __construct() {
        $this->host = getenv('DB_HOST') ?: '127.0.0.1';
        $this->login = getenv('DB_LOGIN') ?: 'root';
        $this->password = getenv('DB_PASSWORD') ?: '1234';
        $this->dbname = getenv('DB_NAME') ?: 'association';
    }

    /**
     * @throws BddConnectException
     */
    public function connexion() : \PDO {
        try {
            $dsn = "mysql:host=$this->host;dbname=$this->dbname;charset=utf8";
            $this->pdo = new \PDO($dsn, $this->login, $this->password);
            $this->pdo->setAttribute(\PDO::ATTR_ERRMODE, \PDO::ERRMODE_EXCEPTION);
            $this->pdo->setAttribute(\PDO::ATTR_DEFAULT_FETCH_MODE, \PDO::FETCH_ASSOC);
        } catch (\PDOException $e) {
            throw new BddConnectException("Erreur de connexion BDD : " . $e->getMessage());
        }

        return $this->pdo;
    }

    /**
     * Retourne l'instance PDO
     *
     * @return \PDO
     */
    public function getPdo() : \PDO {
        return $this->pdo;
    }
}
