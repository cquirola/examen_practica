<?php

class LibrosController {

    public function index() {
        $libros = Libro::all();        
        view( 'libros.index', [ 'libros'=>$libros, 'user'=>'Juanito Perez' ] );
    }

    public function crear(){
        echo "estamos en crear";
    }

    public function create()
    {
        $data = json_decode(file_get_contents('php://input'));
        $libro = new Libro();
        $libro->titulo = $data->titulo;
        $libro->autor = $data->autor;
        $libro->anio_publicacion = $data->anio_publicacion;
        $libro->genero = $data->genero;
        $libro->save();

        echo json_encode($libro);
    }

    public function update()
    {
        $data = json_decode(file_get_contents('php://input')); 
        $libro = Libro::find($data->id);
        if (!$libro) {
            echo json_encode(['status' => false, 'message' => 'Libro no encontrado.']);
            return;
        }
        $libro->titulo = $data->titulo;
        $libro->autor = $data->autor;
        $libro->anio_publicacion = $data->anio_publicacion;
        $libro->genero = $data->genero;
        $libro->save();

        echo json_encode(['status' => true, 'libro' => $libro]);
    }

    public function delete($id)
    {
        $data = json_decode(file_get_contents('php://input'));
        $id = $data->id;
        try {
        $libro = Libro::find($id);
        $libro->remove();

        echo json_encode(['status' => true]);
        } catch (\Exception $e) {
        echo json_encode(['status' => false]);
        }
        
    }
    public function find($id)
    {
        $libro = Libro::find($id);

        echo json_encode($libro);
    }

}
?>