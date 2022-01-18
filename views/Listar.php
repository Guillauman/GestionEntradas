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
<?php
include '../class/UsuarioService.class.php';
include '../class/EntradaService.class.php';
$usuarioSer = new UsuarioService();
$entradaSer = new EntradaService();
?>
<?php
/* if (isset($_POST['ejecuta'])) { No avanza hasta que no se cierra la aplicación
  exec("C:\\Program Files (x86)\\Notepad++\\notepad++.exe");
  die();
  } */
?>
<!DOCTYPE html>
<html>
    <head>
        <meta name="viewport" content="width=device-width, initial-scale=1.0">
        <link rel="stylesheet" href="../css/inicio.css">
        <link rel="stylesheet" href="../css/listar.css">
        <link rel="stylesheet" href="../css/bootstrap/bootstrap.min.css">

        <script defer src="../fontawesome/js/solid.js"></script>
        <script defer src="../fontawesome/js/fontawesome.js"></script>
        <script src="../js/jquery-3.4.1.min.js"></script> 
        <script src="../js/bootstrap.min.js"></script> 
        <script src="../js/datatables.min.js"></script> 
        <script src="../DataTables-1.10.20/js/dataTables.bootstrap4.min.js"></script> 
        <style>
            .dataTables_filter{
                display: inline;
                margin-bottom: 10px;
                float: right !important;
            }
            .modal-content{
                border-radius: 20px;
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
            <div class="listado">
                <!-- <button class="btn btn-primary" style="float: left;width: 300px;margin: 0;" name="ejecuta"  onclick="muestraFormulario()">Búsqueda Avanzada</button>
                -->
                <button type="button" class="btn btn-primary" style="float: left;width: 300px;margin: 0;" name="ejecuta" data-toggle="modal" data-target="#myModal">Búsqueda Avanzada <i class="fas fa-search"></i></button>

                <div class="modal fade" id="myModal" role="dialog">
                    <div class="modal-dialog modal-lg">
                        <!-- Modal content-->
                        <div class="modal-content" id="modalform">
                            <div class="modal-header">
                                <h4 class="modal-title">Búsqueda Avanzada</h4>
                                <button type="button" class="close" data-dismiss="modal" onclick="limpiaFormulario()">&times;</button>
                            </div>
                            <div class="modal-body">
                                <div class="formulario">
                                    <form name="frmFiltrar" action="ListaFormulario" method="post" id="frmFiltrar" target="_blank">
                                        <div class="form-group">
                                            <label for="usuario">Usuario:</label>
                                            <select name="user" id="usuario" class="form-control">
                                                <option value="0" >--</option>
                                                <?php
                                                $usuario = $usuarioSer->findAll();
                                                foreach ($usuario as $value) {
                                                    echo '<option value="' . $value->id . '">' . $value->nombre . '</option>';
                                                }
                                                ?>
                                            </select>                      
                                        </div>
                                        <div class="form-group">
                                            <label for="desde">Desde:</label>
                                            <input type="date" class="form-control" id="desde" placeholder="Fecha (dd/mm/yyyy)"  name="desde" required pattern="[0-9]{2}/[0-9]{2}/[0-9]{4}">
                                        </div>
                                        <div class="form-group">
                                            <label for="hasta">Hasta:</label>
                                            <input type="date" class="form-control" id="hasta" placeholder="Fecha (dd/mm/yyyy)" name="hasta" required pattern="[0-9]{2}/[0-9]{2}/[0-9]{4}">
                                        </div>
                                        <button type="submit" id="reset" class="btn btn-primary">Enviar</button>
                                        <button type="reset" class="btn btn-primary">Limpiar</button>
                                    </form>
                                </div>  
                            </div>
                            <div class="modal-footer">
                                <button type="button" class="btn btn-primary" data-dismiss="modal" onclick="limpiaFormulario()">Cerrar</button>
                            </div>
                        </div>
                    </div>
                </div>


                <table id="paginar" class="table table-hover display">
                    <thead class="thead-dark">
                        <tr>
                            <th scope="col">Nombre</th>
                            <th scope="col">id_usuario</th>
                            <th scope="col">Fecha</th>
                            <th scope="col">Entrada</th>
                            <th scope="col">Descanso</th>
                            <th scope="col">Diario</th>
                        </tr> 
                    </thead>
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
            function limpiaFormulario() {
                document.getElementById("usuario").value = 0;
                document.getElementById("desde").value = "";
                document.getElementById("hasta").value = "";
            }


        </script>       
    </body>
</html>
