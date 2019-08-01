<?php

class DB{

    private $host;
    private $user;
    private $password;
    private $dbName;

    private $conn;
    private $error;
    public $qError;

    private $stmt;

    public function __construct() {
        // Credentials to connect to MySQl Database
        $this->host = 'localhost';
        $this->user = 'root';
        $this->password = '';
	    $this->dbName = 'gointern_db';
		
        // PDO for mysql
        $pdo = "mysql:host=" . $this->host . ";dbname=" . $this->dbName;
        $options = array(
            PDO::ATTR_PERSISTENT => false,
            PDO::ATTR_ERRMODE => PDO::ERRMODE_EXCEPTION,
        );

        try {
            $this->conn = new PDO($pdo, $this->user, $this->password, $options);
        }
         catch (PDOException $e) {
            $this->error = $e->getMessage();
        }

    }

    // Prepare the query
    public function query($query){
        $this->stmt = $this->conn->prepare($query);
    }

    // Bind the related values
    public function bind($param, $value, $type = null){
        if (is_null($type)) {
            switch (true) {
                case is_int($value):
                    $type = PDO::PARAM_INT;
                    break;
                case is_bool($value):
                    $type = PDO::PARAM_BOOL;
                    break;
                case is_null($value):
                    $type = PDO::PARAM_NULL;
                    break;
                default:
                    $type = PDO::PARAM_STR;
            }
        }
        $this->stmt->bindValue($param, $value, $type);
    }

    // Run the query
    public function execute(){
        return $this->stmt->execute();

        $this->qError = $this->conn->errorInfo();
        if (!is_null($this->qError[2])) {
            echo $this->qError[2];
        }
       
    }

    // Fetch all the result of the query
    public function resultset(){
        $this->execute();
        return $this->stmt->fetchAll(PDO::FETCH_ASSOC);
    }
    
    // Fetch only single result of the query
    public function single(){
        $this->execute();
        return $this->stmt->fetch(PDO::FETCH_ASSOC);
    }
    
    // Get the row count of the query
    public function rowCount(){
        return $this->stmt->rowCount();
    }

    // Check if an error has occured
    public function queryError(){
        $this->qError = $this->conn->errorInfo();
        return !is_null($this->qError[2]);
    }

    // Terminate the connection
    public function terminate(){
        $this->conn = null;
    }
}
