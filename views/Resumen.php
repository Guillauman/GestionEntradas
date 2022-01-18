<?php
/**
 * @author     Guillermo Rodríguez García
 */
?>
<?php
//Reanudamos la sesión
session_start();

//Comprobamos si el usario está logueado
//Si no lo está, se le redirecciona al index
//Si lo está, definimos el botón de cerrar sesión y la duración de la sesión
if (!isset($_SESSION['user']) and $_SESSION['estado'] != 'Autenticado') {
    header('Location: ../index.php');
} else {
    $estado = $_SESSION['user'];
    $salir = '<a href="class/Logout.php" target="_self">Cerrar sesión</a>';
    require('../class/Sesiones.php');
}
?>
<!DOCTYPE html>
<html>
    <head>
        <meta name="viewport" content="width=device-width, initial-scale=1.0">
        <link rel="stylesheet" href="../css/inicio.css">
        <link rel="stylesheet" href="../css/resumen.css">
        <link rel="stylesheet" href="../css/bootstrap/bootstrap.min.css">

        <script defer src="../fontawesome/js/solid.js"></script>
        <script defer src="../fontawesome/js/fontawesome.js"></script>

        <script src="../js/jquery-3.4.1.min.js"></script> 
        <script src="../js/datatables.min.js"></script> 
        <script src="../DataTables-1.10.20/js/dataTables.bootstrap4.min.js"></script> 
        <style>
            .dataTables_filter{
                float: right;
            }
        </style>
    </head>
    <body>

        <div class="sidebar">
            <div class="sidebar-header">
                <a  class="active" href="../index"><i class="fas fa-home"></i> Inicio</a>
            </div>
            <a href="FormFichero"><i class="fas fa-upload"></i> Cargar</a>
            <a href="Listar"><i class="fas fa-list"></i> Listado</a>
            <a href="Resumen"><i class="fas fa-sticky-note"></i> Resumen</a>
            <a href="../class/Logout"><i class="fas fa-sign-out-alt"></i> Salir</a>
        </div>

        <div class="content">
            <div class="resumen">
                <?php
                include '../class/EntradaService.class.php';
                $total_resultados_pagina = 5;
                $entradaSer = new EntradaService();
                $resultado = $entradaSer->resumenMes($total_resultados_pagina);
                ?> 
                <span class="titulo" style="text-decoration: underline;"><h1>Resumen mensual</h1></span>
                <table id="paginar" class="table table-hover">
                    <thead class="thead-dark">
                        <tr>
                            <th scope="col">ID</th>
                            <th scope="col">Nombre</th>
                            <th scope="col">Mes</th>
                            <th scope="col">Horas Trabajadas</th>
                            <th scope="col">Horas Descanso</th>
                        </tr>
                    </thead>
                    <tbody>
                        <?php
                        foreach ($resultado as $fila) {
                            echo '<tr>';
                            echo '<th scope="row">' . $fila->id . '</th>';
                            echo '<td>' . $fila->nombre . '</td>';
                            $fechaEntera = strtotime($fila->fechaa);
                            $anio = date('Y', $fechaEntera); //Se cogen solo el el año y el mes ya que es un resumen mensual.
                            $mes = date('M', $fechaEntera);
                            $fecha = $mes . '-' . $anio;
                            echo '<td>' . $fecha . '</td>';
                            echo '<td>' . $fila->diario . '</td>';
                            echo '<td>' . $fila->descanso . '</td>';
                            echo '</tr>';
                        }
                        ?>
                    </tbody>
                </table>
            </div>
        </div>
        <script>
            $(document).ready(function () {
                $('#paginar').DataTable({
                    "pageLength": 7,
                    "pagingType": "simple_numbers",
                    "bLengthChange": false,
                    "language": {
                        "info": "Mostrando del _START_ al _END_ de _TOTAL_ registros",
                        "paginate": {
                            "first": "Primero",
                            "last": "Ultimo",
                            "next": "Siguiente",
                            "previous": "Anterior"
                        },
                        "emptyTable": "No hay información",
                        "search": "Buscar:",
                    },
                    "order": [[3, "desc"]],
                    "responsive": true,
                });
            });

        </script>
    </body>
</html>