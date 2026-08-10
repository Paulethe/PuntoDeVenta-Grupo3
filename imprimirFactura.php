<?php

require "config/db.php";


if (!isset($_GET['id'])) {

    header("Location: facturas.php");
    exit;
}


$id_venta = intval($_GET['id']);



// Datos de la empresa

$query = "SELECT nombre, razon_social, nombre_comercial, rtn, telefono,
                 correo, departamento, municipio, direccion, logo
          FROM empresa
          WHERE id_empresa = 1
          LIMIT 1";

$resultado_empresa = $conn->query($query);
$empresa = $resultado_empresa->fetch_assoc();




// Encabezado factura

$query = "

SELECT

v.*,

u.nombre AS usuario,

c.nombre AS cliente,

fp.forma_pago


FROM ventas v


INNER JOIN usuarios u

ON v.id_usuario=u.id_usuario


INNER JOIN formas_pago fp

ON v.id_forma_pago=fp.id_forma_pago


LEFT JOIN clientes c

ON v.id_cliente=c.id_cliente


WHERE v.id_venta=?


";


$stmt = $conn->prepare($query);


$stmt->bind_param(
    "i",
    $id_venta
);


$stmt->execute();


$factura = $stmt->get_result()->fetch_assoc();




// Detalle

$query = "

SELECT

d.*,

p.nombre


FROM detalle_ventas d


INNER JOIN productos p

ON d.id_producto=p.id_producto


WHERE d.id_venta=?


";


$stmt = $conn->prepare($query);


$stmt->bind_param(
    "i",
    $id_venta
);


$stmt->execute();


$detalle = $stmt->get_result();


?>



<!DOCTYPE html>

<html lang="es">


<head>


    <meta charset="UTF-8">


    <title>

        Factura

    </title>


    <style>
        body {

            font-family: Arial, sans-serif;

            width: 300px;

            margin: auto;

            font-size: 12px;

        }



        .text-center {

            text-align: center;

        }



        .text-right {

            text-align: right;

        }



        table {

            width: 100%;

            border-collapse: collapse;

        }



        td,
        th {

            padding: 5px;

        }



        hr {

            border: 0;

            border-top: 1px dashed #000;

        }



        @media print {


            .no-print {

                display: none;

            }


        }
    </style>


</head>


<body>



    <div class="text-center">

        <?php if (!empty($empresa['logo'] ?? '')) { ?>
            <img src="<?php echo htmlspecialchars($empresa['logo']); ?>"
                 alt="Logo"
                 style="max-width: 100px; max-height: 70px;">
        <?php } ?>


        <h3>

            <?php echo htmlspecialchars(
                !empty($empresa['nombre_comercial'] ?? '')
                    ? $empresa['nombre_comercial']
                    : ($empresa['nombre'] ?? '')
            ); ?>

        </h3>


        <?php if (!empty($empresa['razon_social'] ?? '')) { ?>
            <p><?php echo htmlspecialchars($empresa['razon_social'] ?? ''); ?></p>
        <?php } ?>

        <p>

            <?php echo htmlspecialchars($empresa['direccion'] ?? ''); ?>

            <?php if (!empty($empresa['municipio'] ?? '')) { ?>
                <br><?php echo htmlspecialchars($empresa['municipio']); ?>
            <?php } ?>

            <?php if (!empty($empresa['departamento'] ?? '')) { ?>
                , <?php echo htmlspecialchars($empresa['departamento']); ?>
            <?php } ?>

            <br>

            Tel:
            <?php echo htmlspecialchars($empresa['telefono'] ?? ''); ?>

            <br>

            Correo:
            <?php echo htmlspecialchars($empresa['correo'] ?? ''); ?>

            <br>

            RTN:
            <?php echo htmlspecialchars($empresa['rtn'] ?? ''); ?>


        </p>


    </div>



    <hr>




    <div>


        Factura:

        <?php echo $factura['numero_factura']; ?>


        <br>


        Fecha:

        <?php echo $factura['fecha']; ?>


        <br>


        Cliente:

        <?php

        echo $factura['cliente'] ??
            "Consumidor Final";

        ?>


        <br>


        Vendedor:

        <?php echo $factura['usuario']; ?>


    </div>



    <hr>




    <table>


        <thead>


            <tr>


                <th>

                    Cant

                </th>


                <th>

                    Producto

                </th>


                <th>

                    Total

                </th>


            </tr>


        </thead>



        <tbody>



            <?php while ($item = $detalle->fetch_assoc()) { ?>


                <tr>


                    <td>

                        <?php echo $item['cantidad']; ?>

                    </td>



                    <td>

                        <?php echo $item['nombre']; ?>


                    </td>



                    <td>

                        <?php echo number_format($item['subtotal'], 2); ?>


                    </td>



                </tr>



            <?php } ?>



        </tbody>


    </table>




    <hr>



    <div class="text-right">


        <p>

            Subtotal:

            L.
            <?php echo number_format($factura['subtotal'], 2); ?>


        </p>



        <p>

            Impuesto:

            L.
            <?php echo number_format($factura['impuesto'], 2); ?>


        </p>



        <h3>


            TOTAL:

            L.
            <?php echo number_format($factura['total'], 2); ?>


        </h3>



    </div>



    <hr>



    <div class="text-center">


        <p>

            Gracias por su compra

        </p>


    </div>



    <br>



    <button class="no-print"
        onclick="window.print()">


        Imprimir


    </button>




</body>


</html>
