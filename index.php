<?php
include("app/Stats.php");
?>
<!DOCTYPE html>
<html lang="es">
<head>
    <meta charset="utf-8">
    <meta http-equiv="X-UA-Compatible" content="IE=edge">
    <meta name="viewport" content="width=device-width, initial-scale=1">

    <title>kilowametro</title>

    <link rel="shortcut icon" href="favicon.ico"/>
    <link href="css/bootstrap.min.css" rel="stylesheet">
    <link href="css/select2.min.css" rel="stylesheet">
    <link href="css/custom.css" rel="stylesheet">

    <!--[if lt IE 9]>
    <script src="https://oss.maxcdn.com/html5shiv/3.7.2/html5shiv.min.js"></script>
    <script src="https://oss.maxcdn.com/respond/1.4.2/respond.min.js"></script>
    <![endif]-->

    <script src='https://www.google.com/recaptcha/api.js?hl=es'></script>
</head>

<body>

<nav class="navbar navbar-inverse navbar-fixed-top">
    <div class="container">
        <div class="navbar-header">
            <a class="navbar-brand" href="#">kilowametro (beta)</a>
        </div>
    </div>
</nav>

<div class="container main-container">

    <div class="page-header">
        <h1>Ingreso de datos</h1>
    </div>


    <form onsubmit="save(); return false;">
        <div id="alert-ok" class="alert alert-dismissable alert-success" role="alert" style="display: none">
            <button type="button" class="close" data-dismiss="alert" aria-label="Close"><span
                    aria-hidden="true">&times;</span></button>
            <span class="glyphicon glyphicon-info-sign" aria-hidden="true"></span>
            <span class="message">¡Gracias por su colaboración!</span>
        </div>
        <div id="alert-error" class="alert alert-dismissable alert-danger" role="alert" style="display: none">
            <button type="button" class="close" data-dismiss="alert" aria-label="Close"><span
                    aria-hidden="true">&times;</span></button>
            <span class="glyphicon glyphicon-exclamation-sign" aria-hidden="true"></span>
            <span class="message">Se ha producido un error. Por favor intente de nuevo más tarde.</span>
        </div>

        <div class="row">
            <div class="col-md-6">
                <div class="form-group">
                    <label for="province">Provincia</label>
                    <select id="province" class="form-control" required>
                        <option value="" disabled>Seleccione una opción</option>
                    </select>
                </div>
            </div>
            <div class="col-md-6">
                <div class="form-group">
                    <label for="city">Ciudad</label>
                    <select id="city" class="form-control" required>
                        <option value="" disabled>Seleccione una opción</option>
                    </select>
                </div>
            </div>
        </div>

        <div class="row">
            <div class="col-md-3">
                <div class="form-group">
                    <label for="period">Período (AAAA-MM)</label>
                    <input type="text" class="form-control" id="period" placeholder="Ej: 2015-12"
                           data-inputmask="'mask': '9999-99'" required>
                </div>
            </div>
            <div class="col-md-3">
                <div class="form-group">
                    <label for="days">Días</label>
                    <input type="text" class="form-control" id="days" placeholder="Ej: 63"
                           data-inputmask="'mask': '9{2,3}'" required>
                </div>
            </div>
            <div class="col-md-3">
                <div class="form-group">
                    <label for="kWh">kWh</label>
                    <input type="text" class="form-control" id="kWh" placeholder="Ej: 132"
                           data-inputmask="'mask': '9{1,5}'" required>
                </div>
            </div>
            <div class="col-md-3">
                <div class="form-group">
                    <label for="amount">Monto</label>
                    <div class="input-group">
                        <div class="input-group-addon">$</div>
                        <input type="text" class="form-control" id="amount" placeholder="Ej: 12,34"
                               data-inputmask="'alias': 'currency2'" required>
                    </div>
                </div>
            </div>
        </div>
        <div class="row">
            <div class="col-md-12">
                <div class="form-group">
                    <div class="g-recaptcha" data-sitekey="public recaptcha key">
                    </div>
                </div>
            </div>
        </div>
        <div class="row">
            <div class="col-md-12">
                <div class="form-group">
                    <button type="submit" class="btn btn-success"><i class="glyphicon glyphicon-ok"></i> Guardar
                    </button>
                </div>
            </div>
        </div>
    </form>

    <div class="page-header">
        <h1>Estadísticas</h1>
    </div>
    <?php
    $stats = new Stats();
    ?>
    <div class="container-fluid">
        <div class="row">
            <div class="form-group">
                <label for="city">Seleccione un período:</label>
                <select id="statsPeriod" class="form-control">
                    <?php
                    $stats->renderPeriodOptions();
                    ?>
                </select>
            </div>
        </div>
        <div class="row">
        </div>
        <div class="row">
            <h3>Costo del kWh por provincia</h3>
            <div class="col-md-6">
                <div id="map-chart-div"></div>
            </div>
            <div class="col-md-6">
                <div id="bar-chart-div"></div>
            </div>
        </div>
        <div class="row">
            <h3>Detalle de consumo</h3>
            <div id="table-chart-div"></div>
        </div>
    </div>


    <div class="page-header">
        <h1>Acerca de...</h1>
    </div>
    <p>Esta web fue desarrollada con el objetivo de calcular una estadística del costo del kWh en las distintas partes
        del país.</p>
    <p>La idea es que mediante la colaboración de las personas podamos calcular y comparar valores reales, dado que las
        discusiones al respecto en Internet son contradictorias y/o difíciles de contrastar.</p>
    <p>Para ponerse en contacto por sugerencias o dudas escribir a <a href="mailto:hola@kilowametro.pw">hola@kilowametro.pw</a>.
    <p><a href="https://github.com/dgerbaudo/kilowametro">Proyecto en GitHub</a></p>
    </p>

    <footer class="footer">
        <p>© 2016 kilowametro<b></b></p>
    </footer>
</div>

<div class="modal fade" id="waiting-modal" tabindex="-1" role="dialog">
    <div class="modal-dialog" role="document">
        <div class="modal-content">
            <div class="modal-header"><h4 class="modal-title">Aguarde por favor...</h4></div>
            <div class="modal-body">
                <div class="progress progress-striped active">
                    <div class="progress-bar" style="width: 100%"></div>
                </div>
            </div>

        </div>
    </div>
</div>

<script src="https://ajax.googleapis.com/ajax/libs/jquery/1.12.0/jquery.min.js"></script>
<script type="text/javascript" src="https://www.gstatic.com/charts/loader.js"></script>
<script type="text/javascript" src="https://www.google.com/jsapi"></script>
<script src="js/bootstrap.min.js"></script>
<script src="js/select2.full.min.js"></script>
<script src="js/jquery.inputmask.bundle.min.js"></script>
<script src="js/province.js"></script>
<script src="js/custom.js"></script>
</body>
</html>
