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
<html>
    <head>
        <title>Lista Filtrada</title>

        <link rel="stylesheet" type="text/css" href="../css/inicio.css">
        <link rel="stylesheet" type="text/css" href="../css/listar.css">
        <link rel="stylesheet" href="../css/bootstrap/bootstrap.min.css">

        <script defer src="../fontawesome/js/solid.js"></script>
        <script defer src="../fontawesome/js/fontawesome.js"></script>
        <script src="../js/jquery-3.4.1.min.js"></script> 
        <script src="../js/bootstrap.min.js"></script> 
        <script src="../js/datatables.min.js"></script> 
        <script src="../DataTables-1.10.20/js/dataTables.bootstrap4.min.js"></script> 

        <style>
            body{
                margin: 0;
                padding: 0;
            }
            .dataTables_filter{
                display: inline;
                margin-bottom: 10px;
                float: right !important;
            }  
            
            .content{
                margin: 0 !important;
            }
        </style>
    </head>
    <body>

        <div class="content">
            <?php
            include '../class/UsuarioService.class.php';
            include '../class/EntradaService.class.php';
            $usuarioSer = new UsuarioService();
            $entradaSer = new EntradaService();

            $id_usuario = $_POST['user'];
            $desde = $_POST['desde'];
            $hasta = $_POST['hasta'];
            ?>
            <?php
            if ($id_usuario != 0) {
                ?>
                <div class="listado">
                    <h1>Búsqueda avanzada</h1>
                    <?php
                    $filtrado = $entradaSer->findFiltrado($id_usuario, $desde, $hasta);
                    ?>
                    <table id="paginar" class="table table-hover display">
                        <thead class="thead-dark">
                            <tr>
                                <th>Nombre</th>
                                <th>ID</th>
                                <th>Fecha</th>
                                <th>Entrada</th>
                                <th>Descanso</th>
                                <th>Diario</th>
                            </tr>
                        </thead>
                        <tbody>
                            <?php
                            foreach ($filtrado as $value) {
                                echo '<tr>';
                                echo '<td>' . $value->nombre . '</td>';
                                echo '<td>' . $value->id . '</td>';
                                echo '<td>' . $value->fechaa . '</td>';
                                echo '<td>' . $value->entrada . '</td>';
                                echo '<td>' . $value->descanso . '</td>';
                                echo '<td>' . $value->diario . '</td>';
                                echo '</tr>';
                            }
                            ?>
                        </tbody>
                        <tfoot>
                            <?php
                            /*
                              if (!empty($total)) {
                              echo '<table  border="1" style="border-collapse: collapse;text-align:center;margin:auto" width="500">';
                              echo '<tr>';
                              echo '<td colspan="3">Tiempo Trabajado:</td>';
                              echo '<td>' . $this->sumarHoras($total) . '</td>';
                              echo '</tr>';
                              } */
                        } else if ($id_usuario == 0) {
                            ?>
                        <script>
                            window.alert("Debes seleccionar un usuario!");
                            window.close();
                        </script>
                        <?php
                    }
                    ?>
                </table>
            </div>
        </div>
        <script>
            $(document).ready(function () {
                $('#paginar').DataTable({
                    "pageLength": 7,
                    "pagingType": "simple_numbers",
                    "bLengthChange": false,
                    "searching": false,
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