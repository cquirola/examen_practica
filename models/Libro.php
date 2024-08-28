<?php

class Libro extends DB {

    public $id;
    public $titulo;
    public $autor;
    public $anio_publicacion;
    public $genero;


    public static function all() {
        $db = new DB();
        $prepare = $db->prepare( 'SELECT * FROM libros' );
        $prepare->execute();

        return $prepare->fetchAll( PDO::FETCH_CLASS, Libro::class );
    }

    public static function find( $id ) {
        $db = new DB();
        $query = $db->prepare( 'SELECT * FROM libros WHERE id=:id' );
        $query->execute( [ ':id'=>$id ] );

        return $query->fetchObject( Libro::class );
    }


    public function save() {
        $params = [
            ':titulo' => $this->titulo,
            ':autor' => $this->autor,
            ':anio_publicacion' => $this->anio_publicacion,
            ':genero' => $this->genero
        ];
        try {
            if (empty($this->id)) {
                $prepare = $this->prepare('INSERT INTO libros (titulo, autor, anio_publicacion, genero) VALUES (:titulo, :autor, :anio_publicacion, :genero)');
                $prepare->execute($params);
                $this->id = $this->lastInsertId();
            } else {
                $params[':id'] = $this->id;
                $prepare = $this->prepare('UPDATE libros SET titulo = :titulo, autor = :autor, anio_publicacion = :anio_publicacion, genero = :genero WHERE id = :id');
                $prepare->execute($params);
            }
        } catch (PDOException $e) {
            throw new Exception("Error al guardar el libro: " . $e->getMessage());
        }
    }

    public function remove() {
        $query = $this->prepare( 'DELETE FROM libros WHERE id=:id' );
        $query->execute( [ ':id'=>$this->id ] );
    }


}

?>