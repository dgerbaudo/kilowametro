$(document).ready(function () {
    var eProv = $('#province');
    var eCity = $('#city');
    var eStats = $('#statsPeriod');

    eProv.select2({
        data: provinceListJSON
    });

    eCity.select2({
        ajax: {
            url: "app/city.php",
            dataType: 'json',
            delay: 300,
            data: function (params) {
                return {
                    province_id: eProv.val(),
                    q: params.term
                };
            },
            processResults: function (data) {
                return {
                    results: data
                };
            },
            cache: true
        },
        minimumInputLength: 4
    });

    eProv.on('change', function (e) {
        var selectedId = eProv.val();
        eCity.prop("disabled", (selectedId == CABA_ID));
        eCity.val(null).trigger("change");
    });

    eStats.on('change', function (e) {
        plotMap();
    });

    eStats.select2();

    Inputmask.extendAliases({
        "currency2": {
            groupSeparator: ".",
            radixPoint: ",",
            alias: "numeric",
            placeholder: "0",
            autoGroup: true,
            digits: 2,
            digitsOptional: false,
            clearMaskOnLostFocus: false
        }
    });

    $(":input").inputmask();

    $('#waiting-modal').modal({
        backdrop: 'static',
        keyboard: false,
        show: false
    });
    plotMap();
});

function save() {
    hideAlerts();
    var params = {
        "g-recaptcha-response": $("#g-recaptcha-response").val(),
        "province": $("#province").val(),
        "city": $("#city").val(),
        "period": $("#period").val(),
        "days": $("#days").val(),
        "kWh": $("#kWh").val(),
        "amount": $("#amount").val()
    };
    $.ajax({
        data: params,
        url: 'app/save.php',
        type: 'post',
        dataType: "json",
        beforeSend: function () {
            showWaitingModal();
        },
        success: function (response) {
            if (response.status == "success") {
                clearForm();
                $("#alert-ok").show();
            } else {
                showError(response.info);
            }
            hideWaitingModal();
        },
        error: function () {
            showError("Ha ocurrido un error. Intente nuevamente más tarde.");
            hideWaitingModal();
        }
    });
}

function clearForm() {
    $("#period").val("");
    $("#days").val("");
    $("#kWh").val("");
    $("#amount").val("");
}

function hideAlerts() {
    $("form .alert").hide();
}

function showError(message) {
    $("#alert-error .message").text(message);
    $("#alert-error").show();
}

function showWaitingModal() {
    $('#waiting-modal').modal('show');
}

function hideWaitingModal() {
    $('#waiting-modal').modal('hide');
}

function plotMap() {
    $.ajax({
        data: {'period': $('#statsPeriod').val()},
        url: 'app/stats.geochart.php',
        type: 'get',
        dataType: "JSON",
        beforeSend: function () {
            showWaitingModal();
        },
        success: function (response) {
            if (response.status == "success") {
                drawRegionsMap(response.chartData, response.tableData);
            } else {
                showError(response.info);
            }
            hideWaitingModal();
        },
        error: function () {
            showError("Ha ocurrido un error. Intente nuevamente más tarde.");
            hideWaitingModal();
        }
    });
}


function drawRegionsMap(chartData, tableData) {
    google.load("visualization", "1", {
        packages: ["corechart", "table"],
        callback: drawVisualization,
        'language': 'es'
    });


    function drawVisualization() {
        var chartTable = google.visualization.arrayToDataTable(
            chartData
        );
        var table = google.visualization.arrayToDataTable(
            tableData
        );

        var avgCurrencyFormatter = new google.visualization.NumberFormat(
            {decimalSymbol: ',', fractionDigits: 4, groupingSymbol: '.', prefix: '$ '});

        var currencyFormatter = new google.visualization.NumberFormat(
            {decimalSymbol: ',', fractionDigits: 2, groupingSymbol: '.', prefix: '$ '});

        var numberFormatter = new google.visualization.NumberFormat(
            {decimalSymbol: ',', fractionDigits: 2, groupingSymbol: '.'});

        avgCurrencyFormatter.format(table, 6);
        avgCurrencyFormatter.format(table, 7);
        avgCurrencyFormatter.format(table, 8);

        numberFormatter.format(table, 2);
        numberFormatter.format(table, 4);

        currencyFormatter.format(table, 3);
        currencyFormatter.format(table, 5);

        var options = {
            'title': 'Mapa de costo de KW por provincia',
            'region': 'AR', 'resolution': 'provinces'
        };

        var mapChart = new google.visualization.GeoChart(document.getElementById('map-chart-div'));
        var barChart = new google.visualization.BarChart(document.getElementById('bar-chart-div'));
        var tableChart = new google.visualization.Table(document.getElementById('table-chart-div'));

        mapChart.draw(chartTable, options);
        barChart.draw(chartTable, {legend: {position: 'top', maxLines: 3}});
        tableChart.draw(table, {showRowNumber: false, width: '100%', height: '100%'});
    }
}