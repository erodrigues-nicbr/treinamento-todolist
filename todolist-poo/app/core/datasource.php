<?php

class DatasourceMysql {
    protected static $connection;
    protected static $repositories = [];
    public static function setup() {
        self::$connection = self::connect();
    }

    /**
     * @var array
     * @access public
     * @static
     * @comment Função para conectar ao banco de dados
     * @return PDO
     * @throws PDOException
     * @comment Exemplo de uso:
     * $db = DatasourceMysql::connect();
     * $stmt = $db->prepare("SELECT * FROM tasks");
     * $stmt->execute();
     */
    protected static function connect() {
        $conf = Configure::read('App.database');
        $dsn = 'mysql:host=' . $conf['host'] . ';dbname=' . $conf['database'];
        $user = $conf['user'];
        $password = $conf['password'];
        $options = [
            PDO::ATTR_ERRMODE => PDO::ERRMODE_EXCEPTION,
            PDO::ATTR_DEFAULT_FETCH_MODE => PDO::FETCH_ASSOC
        ];
        return new PDO($dsn, $user, $password, $options);
    }

    /**
     * @var PDO
     * @access public
     * @static
     * @comment Função para retornar a conexão com o banco de dados
     * @return PDO
     */
    public static function getConnection() {
        return self::$connection;
    }
}

// Nada de comentários, cansei
class Repository {
    protected $db;
    protected $tableName;

    public function __construct($tableName) {
        $this->db = DatasourceMysql::getConnection();
        $this->tableName = $tableName;
    }

    public function execute($sql, $params = []) {
        $stmt = $this->db->prepare($sql);
        $stmt->execute($params);
        return $stmt;
    }

    public function getLastInsertedId() {
        return $this->execute('SELECT LAST_INSERT_ID()')->fetchColumn();
    }

    public function insert($data) {
        $fields = array_keys($data);
        $values = array_values($data);
        $sql = 'INSERT INTO ' . $this->tableName . ' (' . implode(',', $fields) . ') VALUES (' . implode(',', array_fill(0, count($values), '?')) . ')';
        $this->execute($sql, $values);
        $data['id'] = $this->getLastInsertedId();
        return $data;
    }

    public function delete($id) {
        $sql = 'DELETE FROM ' . $this->tableName . ' WHERE id = ?';
        $this->execute($sql, [$id]);
    }

    public function update($id, $data) {
        $fields = array_keys($data);
        $values = array_values($data);
        $sql = 'UPDATE ' . $this->tableName . ' SET ' . implode(' = ?, ', $fields) . ' = ? WHERE id = ?';
        $values[] = $id;
        $this->execute($sql, $values);
    }
}

// setup do datasource
DatasourceMysql::setup();
