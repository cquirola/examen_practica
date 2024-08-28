<!DOCTYPE html>
<html lang="en">

<head>
    <meta charset="UTF-8">
    <meta http-equiv="X-UA-Compatible" content="IE=edge">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title>Gestion de libros</title>
    <link href="https://cdn.jsdelivr.net/npm/bootstrap@5.3.3/dist/css/bootstrap.min.css" rel="stylesheet" integrity="sha384-QWTKZyjpPEjISv5WaRU9OFeRpok6YctnYmDr5pNlyT2bRjXh0JMhjY6hW+ALEwIH" crossorigin="anonymous">
    <script src="https://cdn.jsdelivr.net/npm/bootstrap@5.3.3/dist/js/bootstrap.bundle.min.js" integrity="sha384-YvpcrYf0tY3lHB60NNkmXc5s9fDVZLESaAA55NDzOxhy9GkcIdslK1eN7N6jIeHz" crossorigin="anonymous"></script>
    <script src="https://cdn.jsdelivr.net/npm/axios/dist/axios.min.js"></script>
</head>

<body>
    <div class="container">
        <h1 class="mt-5">Gestión de libros</h1>
        <h2>Examen final parte practica</h2>
        <h2>NRC 17707</h2>
        <h3>Camila Quirola</h3>
        
        <table class="table table-striped mt-4" id="table">
            <thead>
                <tr>
                    <th>ID</th>
                    <th>Titulo</th>
                    <th>Autor</th>
                    <th>Año de publicación</th>
                    <th>Genero</th>
                    <th>Acciones</th>
                </tr>
            </thead>
            <tbody id="tablaLibros">
                <?php foreach ($libros as $libro) : ?>
                    <tr data-id="<?php echo $libro->id; ?>">
                        <td><?php echo $libro->id; ?></td>
                        <td><?php echo $libro->titulo; ?></td>
                        <td><?php echo $libro->autor; ?></td>
                        <td><?php echo $libro->anio_publicacion; ?></td>
                        <td><?php echo $libro->genero; ?></td>
                        <td>
                            <button class="btn btn-warning btnEditarLibro" data-id="<?php echo htmlspecialchars($libro->id); ?>">Editar</button>
                            <button class="btn btn-danger btnEliminarLibro" data-id="<?php echo htmlspecialchars($libro->id); ?>">Eliminar</button>
                        </td>
                    </tr>
                <?php endforeach; ?>
            </tbody>
        </table>
        <button class="btn btn-success" id="btnAgregarLibro">Agregar Libro</button>
    </div>

    <!-- Modal para agregar/editar libro -->
    <div class="modal fade" id="librosModal" tabindex="-1" aria-labelledby="exampleModalLabel" aria-hidden="true">
        <div class="modal-dialog">
            <div class="modal-content">
                <div class="modal-header">
                    <h5 class="modal-title" id="modalLibroTitulo">Agregar libro</h5>
                    <button type="button" class="btn-close" data-bs-dismiss="modal" aria-label="Close"></button>
                </div>
                <div class="modal-body">
                    <form id="formLibro">
                        <input type="hidden" id="libroId">
                        <div class="form-floating mb-3">
                            <input type="text" name="titulo" id="titulo" class="form-control" placeholder="*">
                            <label for="titulo">Titulo</label>
                        </div>
                        <div class="form-floating mb-3">
                            <input type="text" name="autor" id="autor" class="form-control" placeholder="*">
                            <label for="autor">Autor</label>
                        </div>
                        <div class="form-floating mb-3">
                            <input type="text" name="anio_publicacion" id="anio_publicacion" class="form-control" placeholder="*">
                            <label for="anio_publicacion">Año publicación</label>
                        </div>
                        <div class="form-floating mb-3">
                            <input type="text" name="genero" id="genero" class="form-control" placeholder="*">
                            <label for="genero">Genero</label>
                        </div>
                    </form>
                </div>
                <div class="modal-footer">
                    <button type="button" class="btn btn-secondary" data-bs-dismiss="modal">Cancelar</button>
                    <button type="button" class="btn btn-primary" id="btnGuardarLibro">Guardar</button>
                </div>
            </div>
        </div>
    </div>

    <script>
        const librosModal = new bootstrap.Modal(document.getElementById('librosModal'));
        const formLibro = document.getElementById('formLibro');
        const btnGuardarLibro = document.getElementById('btnGuardarLibro');
        const btnAgregarLibro = document.getElementById('btnAgregarLibro');
        const tablaLibros = document.getElementById('tablaLibros');

        btnAgregarLibro.addEventListener('click', () => {
            document.getElementById('modalLibroTitulo').textContent = 'Agregar Libro';
            formLibro.reset();
            document.getElementById('libroId').value = ''; // Restablecer el valor del ID del libro
            librosModal.show();
        });

        btnGuardarLibro.addEventListener('click', () => {
            const libro = {
                id: document.getElementById('libroId').value,
                titulo: document.getElementById('titulo').value,
                autor: document.getElementById('autor').value,
                anio_publicacion: document.getElementById('anio_publicacion').value,
                genero: document.getElementById('genero').value
            };

            const url = libro.id ? '/examen_practico/libros/update' : '/examen_practico/libros/create';

            axios.post(url, libro)
                .then(response => {
                    librosModal.hide();
                    location.reload();
                })
                .catch(error => console.error('Error:', error));
        });

        tablaLibros.addEventListener('click', (e) => {
            if (e.target.classList.contains('btnEditarLibro')) {
                const id = e.target.dataset.id;
                document.getElementById('modalLibroTitulo').textContent = 'Editar Libro';
                axios.get(`/examen_practico/libros/find/${id}`)
                    .then(response => {
                        const libro = response.data;
                        document.getElementById('libroId').value = libro.id;
                        document.getElementById('titulo').value = libro.titulo;
                        document.getElementById('autor').value = libro.autor;
                        document.getElementById('anio_publicacion').value = libro.anio_publicacion;
                        document.getElementById('genero').value = libro.genero;
                        librosModal.show();
                    })
                    .catch(error => console.error('Error:', error));
            } else if (e.target.classList.contains('btnEliminarLibro')) {
                const id = e.target.dataset.id;
                if (confirm('¿Estás seguro de que quieres eliminar este libro?')) {
                    axios.post('/examen_practico/libros/delete', {
                            id
                        })
                        .then(response => location.reload())
                        .catch(error => console.error('Error:', error));

                }
            }
        });
    </script>
</body>

</html>