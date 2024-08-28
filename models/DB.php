<?php

class DB extends PDO {

    public function __construct() {
        $dsn = 'mysql:host=localhost:8889;dbname=libros-mvc';
        parent::__construct( $dsn, 'root', 'root' );
    }
}

?>