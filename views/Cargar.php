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
        <title>Usuarios y Registros</title>
        
        <link rel="stylesheet" type="text/css" href="../css/inicio.css">
        <link rel="stylesheet" href="../css/bootstrap/bootstrap.min.css">
        <script src="../js/jquery-3.4.1.min.js"></script> 
        <script src="../js/datatables.min.js"></script> 
        <script src="../DataTables-1.10.20/js/dataTables.bootstrap4.min.js"></script> 
        <script defer src="../fontawesome/js/solid.js"></script>
        <script defer src="../fontawesome/js/fontawesome.js"></script>
    </head>
    <body>
        <div class="wrapper">
            <!-- Sidebar  -->
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

                <?php
                include '../class/UsuarioService.class.php';
                include '../class/EntradaService.class.php';

                $usuarioSer = new UsuarioService();
                $entradaSer = new EntradaService();
                $fileTmpPath = $_FILES['fichero']['tmp_name']; //se accede al archivo mediante la ruta temporal
                $fileName = $_FILES['fichero']['name'];
                $info = new SplFileInfo($fileName);

                if (!file_exists($fileTmpPath)) {
                    ?>
                    <script>
                        window.alert('Error al cargar. No ha seleccionado ningún archivo.')
                        window.location.href = "FormFichero.php";
                    </script>
                    <?php
                } else if ($info->getExtension() != "csv") {
                    ?>
                    <script>
                        window.alert('Error. La extensión del archivo seleccionado no es .csv');
                        window.location.href = "FormFichero.php";
                    </script>
                    <?php
                } else if ($fileTmpPath != "") {
                    $usuarioSer->saveFromCSV($fileTmpPath);
                    $entradaSer->saveFromCSV($fileTmpPath);
                }
                ?>
                <div class="entradas">
                    <h2>Entradas</h2>
                    <table id="paginar" class="table table-hover">
                        <thead>
                            <tr>
                                <th scope="col"><b>Nombre</b></th>
                                <th scope="col"><b>id_usuario</b></th>
                                <th scope="col"><b>Fecha</b></th>
                                <th scope="col"<b>Entrada</b></th>
                                <th scope="col"><b>Descanso</b></th>
                                <th scope="col"><b>Diario</b></th>
                            </tr>
                        </thead>
                        <tbody>
                            <?php
                            $entrada = $entradaSer->findAll();
                            foreach ($entrada as $value) {
                                echo '<tr>';

                                echo '<td>';
                                echo $value->nombre;
                                echo '</td>';

                                echo '<td>';
                                echo $value->id;
                                echo '</td>';

                                echo '<td>';
                                echo$value->fechaa;
                                echo '</td>';

                                echo '<td>';
                                echo $value->entrada;
                                echo '</td>';

                                echo '<td>';
                                echo $value->descanso;
                                echo '</td>';

                                echo '<td>';
                                echo $value->diario;
                                echo '</td>';

                                echo '</tr>';
                            }
                            ?>
                        </tbody>
                    </table>
                </div>
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