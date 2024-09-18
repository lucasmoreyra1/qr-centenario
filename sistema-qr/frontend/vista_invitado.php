<!DOCTYPE html>
<html lang="es">
<head>
    <meta charset="UTF-8">
    <meta http-equiv="X-UA-Compatible" content="IE=edge">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title>Información del Invitado</title>
    <!-- Aquí puedes agregar un link a un framework CSS como Bootstrap para estilos -->
    <link rel="stylesheet" href="resources/boostrap-4.5.2.min.css">
    <!-- html5-qrcode CSS -->
    <!-- <link href="https://unpkg.com/html5-qrcode/minified/html5-qrcode.min.css" rel="stylesheet"> -->

    <script src="resources/jquery-3.5.1.min.js"></script>

    <link rel="stylesheet" href="resources/data-table.css" />
  
    <script src="resources/data-table.js"></script>


    <!-- <script src="https://code.jquery.com/jquery-3.5.1.slim.min.js"></script> -->
    <script src="resources/popper.min.js"></script>
    <script src="resources/boostrap.min.js"></script>

    

</head>
<body>

    <!-- Navbar -->
    <nav class="navbar navbar-expand-lg navbar-light bg-light">
        <!-- Logo del evento -->
        <img src="resources/tc2.svg" alt="Tecnica 2" width="37rem" height="37rem">

        <!-- Botón de toggle para móviles -->
        <button class="navbar-toggler" type="button" data-toggle="collapse" data-target="#navbarNav" aria-controls="navbarNav" aria-expanded="false" aria-label="Toggle navigation">
            <span class="navbar-toggler-icon"></span>
        </button>

        <!-- Contenido colapsable (se oculta en pantallas pequeñas) -->
        <div class="collapse navbar-collapse" id="navbarNav">
            <ul class="navbar-nav ml-auto">
                <li class="nav-item">
                    <a class="btn btn-primary" href="../backend/exit.php">Cerrar</a>
                </li>
            </ul>
        </div>
    </nav>


    <div class="container mt-5">
        <h2 class="text-center">Escanear Código QR</h2>
        <div class="text-center mt-4">
            <button id="startScan" class="btn btn-primary">Iniciar Escaneo</button>
        </div>
        <div style="width: 100%; height: 400px;">
            <div id="reader" class="mt-4" style="width: 100%; height: 200px;"></div>
        </div>
        
    </div>


            <div class="alert alert-primary" role="alert" >
                <h4 id="invitado-nombre" ></h4>
                <h2 id="invitacion-uso" ></h2>
                <h2 id="numero-mesa"></h2>
            </div>


            <div class="container mt-5">
                <h1>Lista de Invitados</h1>
                <table id="example" class="display">
                    <thead>
                        <tr>
                            <th>Nombre</th>
                            <th>Invitación usada</th>
                            <th>Mesa</th>
                            <th>Uso</th>
                        </tr>
                    </thead>
                    <tbody id="tabla-invitados-body">

                    </tbody>
                </table>
            </div>


        <!-- Alerta oculta por defecto -->
        <div id="my-alert" class="alert alert-warning alert-dismissible fade show mt-3 d-none" role="alert">
            ¡Esta es una alerta emergente con Bootstrap!
            <button type="button" class="btn-close" data-bs-dismiss="alert" aria-label="Close"></button>
        </div>


    <script>
    var table = new DataTable('#example', {
        responsive: true,
        data: [],  // Iniciar vacía, cargaremos datos dinámicamente
        columns: [
            { title: "Nombre", data: "nombre" },
            { title: "Invitación Usada", data: "invitacion_uso", render: function(data, type, row) {
                return data == 1 || data === true ? 'Sí' : 'No';
            }},
            { title: "Mesa", data: "id_mesa" },
            { title: "Acción", data: null, render: function(data, type, row) {
                return `<button class="btn btn-primary" onclick="marcarUso(${row.id_invitado})">Marcar Uso</button>`;
            }}
        ]
    });

    </script>

     <!-- html5-qrcode JS -->
    <!-- html5-qrcode JS desde el CDN -->
    <script src="https://unpkg.com/html5-qrcode" type="text/javascript"></script>
    <script>
;
        const startScanButton = document.getElementById('startScan');
        const resultElement = document.getElementById('result');

        function onScanSuccess(decodedText, decodedResult) {
            // Intentar parsear el JSON del QR
            html5QrCode.stop().then((ignore) => {
                try {
                    const invitado = JSON.parse(decodedText);

                    verificarUso(invitado.id);
                    // Mostrar la información en los elementos correspondientes
                    document.getElementById('invitado-nombre').innerText = `Nombre: ${invitado.nombre}`;
                    // document.getElementById('invitacion-uso').innerText = `Invitación usada: ${invitado.uso ? 'Sí' : 'No'}`;
                    document.getElementById('numero-mesa').innerText = `Número de mesa: ${invitado.mesa}`;

/*                     table
                    .search(`${invitado.nombre}  ${invitado.mesa}`)
                    .draw() */

                    marcarUso(invitado.id);
                    actualizarTablaInvitados();

                } catch (e) {
                    console.error('Error al leer el código QR:', e);
                    alert('No se pudo leer el código QR.');
                }
                });
        }

        function onScanError(errorMessage) {
            console.log(`Error de escaneo: ${errorMessage}`);
        }

        startScanButton.addEventListener('click', () => {
            html5QrCode = new Html5Qrcode("reader");

            html5QrCode.start(
                { facingMode: "environment" }, // usa la cámara trasera
                { fps: 10 }, // frame per second
                onScanSuccess,
                onScanError
            ).catch(err => {
                console.error(`Error al iniciar la cámara: ${err}`);
            });
        });


        function marcarUso(id)
        {

            $.ajax({
                url: '../backend/marcar-uso.php',          // El archivo PHP que quieres llamar
                type: 'POST',                // Método POST
                data: { id: id},    // Datos a enviar
                success: function(response) {
                    // $('#response').html(response);  // Mostrar la respuesta del archivo PHP
                    console.log(response);
                    actualizarTablaInvitados();
                },
                error: function(xhr, status, error) {
                    console.error('Error en la petición: ', error);
                }
            });

        }


        function verificarUso(id)
        {
            $.ajax({
                url: '../backend/consultar-uso.php',   // Archivo PHP que consulta el uso
                type: 'POST',              // Tipo de petición (POST o GET)
                data: { id: id },   // Datos a enviar (por ejemplo, el id del invitado)
                success: function(response) {
                    console.log('algo :' + response);
                    // Parsear el JSON que devuelve el PHP
                    var invitado = JSON.parse(response);
/*                     if(response)
                    {
                        $('#my-alert').removeClass('d-none');
                    } */
                    // Mostrar si la invitación fue usada
                    document.getElementById('invitacion-uso').innerText = `Invitación usada: ${response === "1" ? 'Sí' : 'No'}`;

                },
                error: function(xhr, status, error) {
                    console.error('Error en la consulta:', error);
                }
            });
        }
    </script>




    <script>
    // Función para actualizar la tabla de invitados
    function actualizarTablaInvitados() {
        // Hacer una petición AJAX a obtener_invitados.php
        const xhr = new XMLHttpRequest();
        xhr.open('GET', '../backend/consultas.php', true);
        xhr.onload = function () {
            if (xhr.status === 200) {
                // Obtener la respuesta y parsearla como JSON
                const invitados = JSON.parse(xhr.responseText);

                // Limpiar la tabla actual y agregar los nuevos datos
                table.clear(); // Limpia la tabla
                table.rows.add(invitados); // Añade las nuevas filas
                table.draw(); // Dibuja la tabla con los nuevos datos
            } else {
                console.error('Error al cargar los invitados:', xhr.statusText);
            }
        };
        xhr.onerror = function () {
            console.error('Error en la petición AJAX.');
        };
        xhr.send();
    }

    // Llamar a la función para actualizar la tabla al cargar la página
    window.onload = function() {
        actualizarTablaInvitados();
    };
    </script>




   

</body>
</html>
