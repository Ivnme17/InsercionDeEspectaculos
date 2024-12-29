<?php
require_once './funciones.inc';
$codGrupo = "";
$esRecibido = false;
$esValidado = false;
$conexion = new mysqli();
try {
    $conexion->connect("localhost", "root", "", "espectaculos");
} catch (Exception $ex) {
    echo "Error: " . $ex->getMessage();
}
//Consultas
$consultaGrupos = "SELECT cdgrupo, nombre FROM grupo";
$consultaTipos = "SELECT DISTINCT tipo FROM espectaculo";

try {
    $resultadoConsultaGrupos = $conexion->query($consultaGrupos);
    while ($columna = $resultadoConsultaGrupos->fetch_assoc()) {
        $tablaGrupos[$columna["cdgrupo"]] = $columna["nombre"];
    }

    $resultadoConsultaTipos = $conexion->query($consultaTipos);
    while ($tipos = $resultadoConsultaTipos->fetch_assoc()) {
        $tablaTipos[] = $tipos;
    }
} catch (Exception $ex) {
    echo "ERROR: " . $ex->getMessage();
}
?>


<!DOCTYPE html>
<!--
Click nbfs://nbhost/SystemFileSystem/Templates/Licenses/license-default.txt to change this license
Click nbfs://nbhost/SystemFileSystem/Templates/Project/PHP/PHPProject.php to edit this template
-->
<html>
    <head>
        <meta charset="UTF-8">
        <title></title>
        <link rel="stylesheet" href="estilos.css">
    </head>
    <body>
        <form action="<?= $_SERVER["PHP_SELF"] ?>" method="post">
            <label>Introduce el código:</label><input type="text" name="cdespec" value=""><br><br>
            <label>Introduce el nombre:</label><input type="text" name="nombre" value=""><br><br>
            <label>Selecciona el tipo:</label>
            <select name="tipos">
                <option value="0" selected="true">Selecciona un tipo</option>
                <?php if (isset($tablaTipos)) { ?>
                    <?php foreach ($tablaTipos as $tipo) { ?>
                        <?php foreach ($tipo as $valor) { ?>
                            <option value="<?= $valor ?>"><?php echo $valor ?></option>
                        <?php } ?>
                    <?php } ?>
                <?php } ?>
            </select>
            <br>
            <br>

            <label>Selecciona una calificación:</label><br><br>
            0<input type="radio" name="estrellas" value="0"><br><br> 
            1<input type="radio" name="estrellas" value="1"><br><br>        
            2<input type="radio" name="estrellas" value="2"><br><br>        
            3<input type="radio" name="estrellas" value="3"><br><br>        
            4<input type="radio" name="estrellas" value="4"><br><br>        
            5<input type="radio" name="estrellas" value="5"><br><br> 
            <label>Grupo</label>
            <select name="cdgrupo">
                <option value="0" selected="true">Selecciona un grupo</option>
                <?php
                if (isset($tablaGrupos)) {
                    foreach ($tablaGrupos as $codGrupo => $nombreGrupo) {
                        ?>
                        <option value="<?php echo $codGrupo; ?>" ><?php echo $nombreGrupo; ?></option>
                    <?php } ?>
                <?php } ?>          
            </select>
            <br>
            <br>
            <button type="submit" name="enviar">Enviar</button>
        </form>


        <?php
        if (filter_has_var(INPUT_POST, "enviar")) {
            $esRecibido = true;
            $cdespec = validarCadena(filter_input(INPUT_POST, "cdespec"));
            $nombre = validarCadena(filter_input(INPUT_POST, "nombre"));
            $cdgrupo = validarCadena(filter_input(INPUT_POST, "cdgrupo"));
            
            if(filter_has_var(INPUT_POST, "tipos")){
                $tipos = validarCadena(filter_input(INPUT_POST, "tipos"));
            }
            
            if (filter_has_var(INPUT_POST, "estrellas")) {
                $estrellas = calificacionMarcada(filter_input(INPUT_POST, "estrellas"));
            }

            $esValidado = $cdespec && $nombre && $cdgrupo && $estrellas && $tipos;

            if ($esValidado) {
                $insercionEspectaculo = $conexion->stmt_init();
                $insercionEspectaculo->prepare("INSERT INTO espectaculo (cdespec, nombre, tipo, estrellas, cdgru) VALUES (?, ?, ?, ?, ?)");
                $insercionEspectaculo->bind_param("sssis", $cdespec, $nombre, $tipos, $estrellas, $cdgrupo);
                $insercionEspectaculo->execute();
                $insercionEspectaculo->close();
            }
        }
            if ($esRecibido) {
                if (!$esValidado) {
                    ?>
                    <div class="sinResultado">
                        ERROR: No se han pasado datos sobre espectaculo o no son válidos
                    </div>
                <?php } else { ?>
                    <div class="seInsertan">
                        EL ESPECTACULO SE INSERTO CORRECTAMENTE !!!
                    </div>
                    <?php
                }
            } else { ?>
        <div class="sinResultado">
            No se han recibido datos
        </div>
<?php            }
        $conexion->close();?>
    </body>
</html>
