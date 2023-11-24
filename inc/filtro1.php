<!DOCTYPE html>
<html lang="en">
<head>
    <meta charset="UTF-8">
    <meta http-equiv="X-UA-Compatible" content="IE=edge">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <link rel="stylesheet" href="css/style.css">
    <title>filtro</title>
</head>
<body>
    
<?php
$servidor = "localhost";
$usuario = "root";
$password = "";
$nombreBD = "inmobiliaria1";

$conexion = new mysqli($servidor, $usuario, $password, $nombreBD);

if ($conexion->connect_error){
    die("La conexión ha fallado " . $conexion->connect_error);
}

if (!isset($_POST['buscar'])) {
    $_POST['buscar'] = '';
}
if (!isset($_POST['tipo'])) {
    $_POST['tipo'] = '';
}
if (!isset($_POST['estado'])) {
    $_POST['estado'] = '';
}
if (!isset($_POST['buscapreciodesde'])) {
    $_POST['buscapreciodesde'] = '';
}
if (!isset($_POST['buscapreciohasta'])) {
    $_POST['buscapreciohasta'] = '';
}
if (!isset($_POST["orden"])) {
    $_POST['orden'] = '';
}

?>
 <div class="container">
        <h1>Buscador de Propiedades</h1>
        <form id="form1" name="form1" method="POST" action="filtro1.php">
            <div class="form-group">
                <label for="buscar">Ingresa ubicación:</label>
                <input type="text" class="form-control" name="buscar" value="<?php echo $_POST["buscar"] ?>">
            </div>

            <h4>Filtro de búsqueda</h4>
            <div class="form-group">
                <label for="tipo">Tipo de propiedad:</label>
                <select id="tipo" class="select-control" name="tipo">
                    <option value="" <?php if ($_POST["tipo"] == ''){echo 'selected';} ?>>Todos</option>
                    <option value="departamento" <?php if ($_POST["tipo"] == 'departamento'){echo 'selected';} ?>>Departamento</option>
                    <option value="casa" <?php if ($_POST["tipo"] == 'casa'){echo 'selected';} ?>>Casa</option>
                </select>
            </div>
            
            <div class="form-group">
                <label for="estado">Estado:</label>
                <select name="estado" class="select-control" id="estado">
                    <option value="" <?php if ($_POST["estado"] == ''){echo 'selected';} ?>>Todos</option>
                    <option value="alquiler" <?php if ($_POST["estado"] == 'alquiler'){echo 'selected';} ?>>Alquiler</option>
                    <option value="venta" <?php if ($_POST["estado"] == 'venta'){echo 'selected';} ?>>Venta</option>
                </select>
            </div>

            <div class="form-group">
                <label for="buscapreciodesde">Precio desde:</label>
                <input type="number" class="form-control" name="buscapreciodesde" value="<?php echo $_POST["buscapreciodesde"] ?>">
            </div>

            <div class="form-group">
                <label for "buscapreciohasta">Precio hasta:</label>
                <input type="number" class="form-control" name="buscapreciohasta" value="<?php echo $_POST["buscapreciohasta"] ?>">
            </div>

            <h4>Ordenar por</h4>
            <div class="form-group">
                <select name="orden" class="select-control">
                    <option value="" <?php if ($_POST["orden"] == ''){echo 'selected';} ?>>-</option>
                    <option value="3" <?php if ($_POST["orden"] == '3'){echo 'selected';} ?>>Ordenar precio de menor a mayor</option>
                    <option value="4" <?php if ($_POST["orden"] == '4'){echo 'selected';} ?>>Ordenar por mayor a menor</option>
                </select>
            </div>

            <input type="submit" class="btn" value="Ver">
        </form>
    </div>
</form>

<?php /* filtro de búsqueda */
if ($_POST['buscar'] == '') {
    $_POST['buscar'] = '';
}
$akeyword = explode(" ", $_POST['buscar']);
if ($_POST['buscar'] == '' && $_POST['tipo'] == '' && $_POST['estado'] == '' && $_POST['buscapreciodesde'] == '' && $_POST['buscapreciohasta'] == '') {
    $query = "SELECT * FROM propiedades";
} else {
    $query = "SELECT * FROM propiedades WHERE 1=1 ";

    if ($_POST["buscar"] != '') {
        $query .= "AND (localidad LIKE '%" . $akeyword[0] . "%') ";
        for ($i = 1; $i < count($akeyword); $i++) {
            if (!empty($akeyword[$i])) {
                $query .= " OR localidad LIKE '%" . $akeyword[$i] . "%' ";
            }
        }
    }

    if ($_POST["tipo"] != '') {
        $query .= "AND tipo = '".$_POST['tipo']."' ";
    }

    if ($_POST["estado"] != '') {
        $query .= "AND estado = '".$_POST['estado']."' ";
    }

    if ($_POST['buscapreciodesde'] != '') {
        $query .= "AND precio >= '".$_POST['buscapreciodesde']."' ";
    }

    if ($_POST['buscapreciohasta'] != '') {
        $query .= "AND precio <= '".$_POST['buscapreciohasta']."' ";
    }

    if ($_POST["orden"] == '1') {
        $query .= "ORDER BY calle ASC ";
    } elseif ($_POST["orden"] == '2') {
        $query .= "ORDER BY departamento ASC ";
    } elseif ($_POST["orden"] == '3') {
        $query .= "ORDER BY precio ASC ";
    } elseif ($_POST["orden"] == '4') {
        $query .= "ORDER BY precio DESC ";
    }
}

$sql = $conexion->query($query);
$numeroSql = mysqli_num_rows($sql);
?>
<p><?php echo $numeroSql; ?> resultados encontrados</p>
<div class="tabla-responsive">
    <table class="table">
        <thead>
            <tr>
                <th>Tipo</th>
                <th>Estado</th>
                <th>Calle</th>
                <th>Dirección</th>
                <th>Localidad</th>
                <th>Ambientes</th>
                <th>Baños</th>
                <th>Metros Cuadrados</th>
                <th>Precio</th>
                <th>Descripción</th>
            </tr>
        </thead>
        <tbody>
            <?php 
            while ($rowSql = $sql->fetch_assoc()) {   
            ?>
            <tr>
                <td><?php echo $rowSql["tipo"]; ?></td>
                <td><?php echo $rowSql["estado"]; ?></td>
                <td><?php echo $rowSql["calle"]; ?></td>
                <td><?php echo $rowSql["direccion"]; ?></td>
                <td><?php echo $rowSql["localidad"]; ?></td>
                <td><?php echo $rowSql["ambientes"]; ?></td>
                <td><?php echo $rowSql["banos"]; ?></td>
                <td><?php echo $rowSql["metro_cuadrado"]; ?></td>
                <td><?php echo $rowSql["precio"]; ?></td>
                <td><?php echo $rowSql["descripcion"]; ?></td>
            </tr>
            <?php }?>
        </tbody>
    </table>
</div>
</body>
</html>