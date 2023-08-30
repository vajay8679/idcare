<script src="<?php echo base_url() . 'backend_asset/admin/js/' ?>app.js"></script> 
<script src="https://cdn.jsdelivr.net/npm/chart.js@2.8.0"></script>
<script src="https://cdnjs.cloudflare.com/ajax/libs/jspdf/1.2.61/jspdf.min.js"></script>
<script>
    $("#date1").datepicker({ dateFormat: 'mm/dd/yy' });
    $("#date2").datepicker({ dateFormat: 'mm/dd/yy' });
    function getReports(){
       var date1= $("#date1").val();
       var date2= $("#date2").val();
       if(date1 !='' && date2 != ''){
            getAntibioticByCareUnit('','',date1,date2);
       }
    }

    getAntibioticByCareUnit();
    function getAntibioticByCareUnit(careUnit,rx,date1,date2) {
        if(careUnit == ""){
            var careUnit = $("#careUnit").val();
        }
        var steward = $("#steward").val();
        var url = "<?php echo base_url() ?>reports/get_antibiotic_by_care_unit";
        $.ajax({
            method: "POST",
            url: url,
            data: {careUnit: careUnit, steward: steward,rx:rx},
            dataType: "json",
            success: function (response) {
                if (response.status == 200) {
                    //console.log(response.antibiotic);
                    var ChartGraphName = [];
                    var ChartGraphPercentage = [];

                    response.antibiotic.forEach(element => {
                        ChartGraphName.push([element.name]);
                        ChartGraphPercentage.push(element.percentage);
                    });

                    $('#canvas').remove(); // this is my <canvas> element
                    $('#Graph-chart').append('<canvas id="canvas"><canvas>');
                    var ctx = document.getElementById("canvas");
                    var data = {
                        labels: ChartGraphName,
                        datasets: [{
                                label: "%",
                                backgroundColor: ["#D68910", "#43A047", "#3cba9f", "#58aed1", "#2E4053", "#129279", "#4A235A", "#0A122A", "#2E3B0B","#5F4C0B","#61210B"],
                                data: ChartGraphPercentage
                            }]
                    }
                    var myChart = new Chart(ctx, {
                        type: 'bar',
                        data: data,
                        options: {
                            "hover": {
                                "animationDuration": 0
                            },
                            "animation": {
                                "onComplete": function () {
                                    var chartInstance = this.chart,
                                            ctx = chartInstance.ctx;

                                    ctx.font = Chart.helpers.fontString(Chart.defaults.global.defaultFontSize, Chart.defaults.global.defaultFontStyle, Chart.defaults.global.defaultFontFamily);
                                    ctx.textAlign = 'center';
                                    ctx.textBaseline = 'bottom';

                                    this.data.datasets.forEach(function (dataset, i) {
                                        var meta = chartInstance.controller.getDatasetMeta(i);
                                        meta.data.forEach(function (bar, index) {
                                            var data = dataset.data[index];
                                            ctx.fillText(data, bar._model.x, bar._model.y - 5);
                                        });
                                    });
                                }
                            },
                            legend: {
                                "display": false
                            },
                            title: {
                                display: true,
                                text: response.care_name
                            },
                            tooltips: {
                                "enabled": false
                            },
                            responsive: true,
                            maintainAspectRatio: false,
                            plugins: [{
                                    beforeDraw: function (c) {
                                        var chartHeight = c.chart.height;
                                        c.scales['x-axis-0'].options.ticks.fontSize = chartHeight * 4 / 100;
                                    }
                                }],
                            scales: {
                                xAxes: [{
                                        display: true,
                                        scaleLabel: {
                                            display: true,
                                            labelString: 'Antibiotics'
                                        }
                                    }],
                                yAxes: [{
                                        display: true,
                                        scaleLabel: {
                                            display: true,
                                            labelString: 'Total Antibiotics Percentage'
                                        }
                                    }]
                            }
                        }
                    });
                } else {
                    $('#canvas').remove();
                    $("#msg").html("<div class='alert alert-danger'>Records not found</div>");
                }
            },
            error: function (error, ror, r) {
                $("#msg").html("<div class='alert alert-danger'>Records not found</div>");
            },
        });

        getDaysSavedByCareUnit(careUnit,rx,date1,date2);
        actual_dot_vs_new_dot_by_care_unit(careUnit,rx,date1,date2);
        actual_dot_vs_new_dot_by_care_unit_provider_doctor(careUnit,rx,date1,date2);
        getAntibioticByCareUnitSteward(careUnit,rx,date1,date2);
        actual_dot_vs_new_dot_by_care_unit_md_steward(careUnit,rx,date1,date2);
        get_rx_by_actual_dots(careUnit,rx,date1,date2);
        get_dx_by_actual_dots(careUnit,rx,date1,date2);
        getAntibioticByCareUnitProvider(careUnit,rx,date1,date2);
        getAntibioticByCareUnitProviderPrice(careUnit,rx,date1,date2);
        getAntibioticByCareUnitStewardPrice(careUnit,rx,date1,date2);
        getAntibioticByCareUnitProviderId(date1,date2);
        provider_md_rest();
    }

    function getAntibioticByCareUnitSteward(careUnit,rx,date1,date2) {
        //var careUnit = $("#careUnit").val();
        var steward = $("#steward").val();
        var url = "<?php echo base_url() ?>reports/get_antibiotic_by_care_unit_md_steward";
        $.ajax({
            method: "POST",
            url: url,
            data: {careUnit: careUnit, steward: steward,rx:rx,date1:date1,date2,date2},
            dataType: "json",
            success: function (response) {
                if (response.status == 200) {
                    //console.log(response.antibiotic);
                    var ChartGraphName = [];
                    var ChartGraphPercentage = [];
                    var rxData = [];
                    var label = [];
                    var backgroundColor = [];
                    response.antibiotic.forEach(element => {
                        ChartGraphName.push([element.md_name]);
                        ChartGraphPercentage.push(element.rx);
                    });
                    response.datasheet.forEach(element => {
                        rxData.push([element.data[0]]);
                    });
                    response.datasheet.forEach(element => {
                        label.push([element.label]);
                    });
                    response.datasheet.forEach(element => {
                        backgroundColor.push([element.backgroundColor]);
                    });
                    $('#canvas1').remove(); // this is my <canvas> element
                    if(ChartGraphName != ""){
                        $('#Graph-chart1').append('<canvas id="canvas1"><canvas>');
                    }
                   
                    var ctx = document.getElementById("canvas1");
                    // var data = {
                    //     labels: ChartGraphName,
                    //     datasets: rxData
                    // };
                    var data = {
                            labels: response.labels,
                            datasets: [{
                              backgroundColor: response.backgroundColor,
                              data: response.data
                            }]
                          };
                    var myChart = new Chart(ctx, {
                        type: 'pie',
                        data: data,
                        options: {
                            "hover": {
                                "animationDuration": 0
                            },
                            tooltips: {
                                "enabled": true
                            },
                            responsive: true,
                            maintainAspectRatio: false,
                            title: {
                                display: true,
                                text: response.md_name
                            }
                        },
                    });
                } else {
                    $('#canvas1').remove();
                    $("#msg").html("<div class='alert alert-danger'>Records not found</div>");
                }
            },
            error: function (error, ror, r) {
                $("#msg").html("<div class='alert alert-danger'>Records not found</div>");
            },
        });

        actual_dot_vs_new_dot_by_care_unit_md_steward();
    }

    function getAntibioticByCareUnitSteward_oldbarcharts(careUnit,rx,date1,date2) {
        //var careUnit = $("#careUnit").val();
        var steward = $("#steward").val();
        var url = "<?php echo base_url() ?>reports/get_antibiotic_by_care_unit_md_steward";
        $.ajax({
            method: "POST",
            url: url,
            data: {careUnit: careUnit, steward: steward,rx:rx,date1:date1,date2,date2},
            dataType: "json",
            success: function (response) {
                if (response.status == 200) {
                    //console.log(response.antibiotic);
                    var ChartGraphName = [];
                    var ChartGraphPercentage = [];
                    var rxData = [];
                    response.antibiotic.forEach(element => {
                        ChartGraphName.push([element.md_name]);
                        ChartGraphPercentage.push(element.rx);
                    });
                    response.datasheet.forEach(element => {
                        rxData.push(element);
                    });
                    $('#canvas1').remove(); // this is my <canvas> element
                    if(ChartGraphName != ""){
                        $('#Graph-chart1').append('<canvas id="canvas1"><canvas>');
                    }
                   
                    var ctx = document.getElementById("canvas1");
                    var data = {
                        labels: ChartGraphName,
                        datasets: rxData
                    };
                    var myChart = new Chart(ctx, {
                        type: 'bar',
                        data: data,
                        options: {
                            "hover": {
                                "animationDuration": 0
                            },
                            legend: {
                                "display": true
                            },
                            tooltips: {
                                "enabled": true
                            },
                            responsive: true,
                            maintainAspectRatio: false,
                            plugins: [{
                                    beforeDraw: function (c) {
                                        var chartHeight = c.chart.height;
                                        c.scales['x-axis-0'].options.ticks.fontSize = chartHeight * 4 / 100;
                                    }
                                }],
                            title: {
                                display: true,
                                text: response.care_name
                            },
                            scales: {
                                xAxes: [{
                                        display: true,
                                        stacked: true,
                                        scaleLabel: {
                                            display: true,
                                            labelString: 'MD Steward'
                                        }
                                    }],
                                yAxes: [{
                                        display: true,
                                        stacked: true,
                                        scaleLabel: {
                                            display: true,
                                            labelString: 'Total Antibiotics Percentage'
                                        }
                                    }]
                            }
                        },
                    });
                } else {
                    $('#canvas1').remove();
                    $("#msg").html("<div class='alert alert-danger'>Records not found</div>");
                }
            },
            error: function (error, ror, r) {
                $("#msg").html("<div class='alert alert-danger'>Records not found</div>");
            },
        });

        actual_dot_vs_new_dot_by_care_unit_md_steward();
    }

    function getDaysSavedByCareUnit(careUnit,rx,date1,date2) {
        //var careUnit = $("#careUnit").val();
        var url = "<?php echo base_url() ?>reports/get_days_saved_by_care_unit";
        $.ajax({
            method: "POST",
            url: url,
            data: {careUnit: careUnit,rx:rx,date1:date1,date2,date2},
            dataType: "json",
            success: function (response) {
                if (response.status == 200) {
                    var ChartGraphName = [];
                    var ChartGraphPercentage = [];

                    response.antibiotic.forEach(element => {
                        ChartGraphName.push([element.name]);
                        ChartGraphPercentage.push(element.percentage);
                    });
                    //console.log(ChartGraphName);
                    //console.log(ChartGraphPercentage);
                    $('#canvas2').remove(); // this is my <canvas> element
                    $('#Graph-chart2').append('<canvas id="canvas2"><canvas>');

                    new Chart(document.getElementById("canvas2"), {
                        type: 'bar',
                        data: {
                            labels: ChartGraphName,
                            datasets: [{
                                    label: "Days Saved",
                                    backgroundColor: ["#008000", "#A52A2A"],
                                    data: ChartGraphPercentage
                                }]
                        },
                        options: {
                            title: {
                                display: true,
                                text: response.care_name
                            },
                            scales: {
                            yAxes: [{
                                ticks: {
                                    beginAtZero: true,
                                    userCallback: function(label, index, labels) {
                                        // when the floored value is the same as the value we have a whole number
                                        if (Math.floor(label) === label) {
                                            return label;
                                        }

                                    },
                                }
                            }],
                        },
                        }
                    });
                } else {
                    $('#canvas2').remove();
                    $("#msg").html("<div class='alert alert-danger'>Records not found</div>");
                }
            },
            error: function (error, ror, r) {
                $("#msg").html("<div class='alert alert-danger'>Records not found</div>");
            },
        });
    }

    function actual_dot_vs_new_dot_by_care_unit(careUnit,rx,date1,date2) {
        //var careUnit = $("#careUnit").val();
        var url = "<?php echo base_url() ?>reports/actual_dot_vs_new_dot_by_care_unit";
        $.ajax({
            method: "POST",
            url: url,
            data: {careUnit: careUnit,rx:rx,date1:date1,date2,date2},
            dataType: "json",
            success: function (response) {
                if (response.status == 200) {
                    var ChartGraphName = [];
                    var ChartGraphPercentage = [];

                    response.antibiotic.forEach(element => {
                        ChartGraphName.push([element.name]);
                        ChartGraphPercentage.push(element.percentage);
                    });
                    //console.log(ChartGraphName);
                    //console.log(ChartGraphPercentage);
                    $('#canvas4').remove(); // this is my <canvas> element
                    $('#Graph-chart4').append('<canvas id="canvas4"><canvas>');

                    new Chart(document.getElementById("canvas4"), {
                        type: 'bar',
                        data: {
                            labels: ChartGraphName,
                            datasets: [{
                                    label: "DOT",
                                    backgroundColor: ["#CB4335", "#148F77"],
                                    data: ChartGraphPercentage
                                }]
                        },
                        options: {
                            title: {
                                display: true,
                                text: response.care_name
                            },
                            scales: {
                            yAxes: [{
                                ticks: {
                                    beginAtZero: true,
                                    userCallback: function(label, index, labels) {
                                        // when the floored value is the same as the value we have a whole number
                                        if (Math.floor(label) === label) {
                                            return label;
                                        }

                                    },
                                }
                            }],
                        },
                        }
                    });
                } else {
                    $('#canvas4').remove();
                    $("#msg").html("<div class='alert alert-danger'>Records not found</div>");
                }
            },
            error: function (error, ror, r) {
                $("#msg").html("<div class='alert alert-danger'>Records not found</div>");
            },
        });
    }

    function actual_dot_vs_new_dot_by_care_unit_md_steward(careUnit,rx,date1,date2) {
        //var careUnit = $("#careUnit").val();
        var steward = $("#steward").val();
        var url = "<?php echo base_url() ?>reports/actual_dot_vs_new_dot_by_care_unit_md_steward";
        $.ajax({
            method: "POST",
            url: url,
            data: {careUnit: careUnit, steward: steward,rx:rx,date1:date1,date2,date2},
            dataType: "json",
            success: function (response) {
                if (response.status == 200) {
                    var ChartGraphName = [];
                    var ChartGraphPercentage = [];
                    var rxData = [];

                    response.antibiotic.forEach(element => {
                        ChartGraphName.push([element.md_name]);
                    });
                    response.datasheet.forEach(element => {
                        rxData.push(element);
                    });
                    //console.log(ChartGraphName);
                    //console.log(ChartGraphPercentage);
                    $('#canvas5').remove(); // this is my <canvas> element
                    $('#Graph-chart5').append('<canvas id="canvas5"><canvas>');

                    var ctx = document.getElementById("canvas5");
                    var data = {
                        labels: ChartGraphName,
                        datasets: rxData
                    };
                    var myChart = new Chart(ctx, {
                        type: 'bar',
                        data: data,
                        options: {
                            "hover": {
                                "animationDuration": 0
                            },
                            
                            responsive: true,
                            maintainAspectRatio: false,
                            plugins: [{
                                    beforeDraw: function (c) {
                                        var chartHeight = c.chart.height;
                                        c.scales['x-axis-0'].options.ticks.fontSize = chartHeight * 4 / 100;
                                    }
                                }],
                            legend: {
                                "display": true
                            },
                            tooltips: {
                                "enabled": true
                            },
                            scales: {
                                xAxes: [{
                                        display: true,
                                        scaleLabel: {
                                            display: true,
                                            labelString: 'MD Steward'
                                        }
                                    }],
                                yAxes: [{
                                        display: true,
                                        scaleLabel: {
                                            display: true,
                                            labelString: 'Days of treatment'
                                        },
                                        ticks: {
                                    beginAtZero: true,
                                    userCallback: function(label, index, labels) {
                                        // when the floored value is the same as the value we have a whole number
                                        if (Math.floor(label) === label) {
                                            return label;
                                        }

                                    },
                                }
                                    }]
                            }
                        }
                    });

                } else {
                    $('#canvas5').remove();
                    $("#msg").html("<div class='alert alert-danger'>Records not found</div>");
                }
            },
            error: function (error, ror, r) {
                $("#msg").html("<div class='alert alert-danger'>Records not found</div>");
            },
        });
    }

    function actual_dot_vs_new_dot_by_care_unit_provider_doctor(careUnit,rx,date1,date2) {
        //var careUnit = $("#careUnit").val();
        var provider_doctor = $("#provider_doctor").val();
        var url = "<?php echo base_url() ?>reports/actual_dot_vs_new_dot_by_care_unit_provider_doctor";
        $.ajax({
            method: "POST",
            url: url,
            data: {careUnit: careUnit, provider_doctor: provider_doctor,rx:rx,date1:date1,date2,date2},
            dataType: "json",
            success: function (response) {
                if (response.status == 200) {
                    var ChartGraphName = [];
                    var ChartGraphPercentage = [];
                    var rxData = [];

                    response.antibiotic.forEach(element => {
                        ChartGraphName.push([element.md_name]);
                    });
                    response.datasheet.forEach(element => {
                        rxData.push(element);
                    });
                    //console.log(ChartGraphName);
                    //console.log(ChartGraphPercentage);
                    $('#canvas6').remove(); // this is my <canvas> element
                    $('#Graph-chart6').append('<canvas id="canvas6"><canvas>');

                    var ctx = document.getElementById("canvas6");
                    var data = {
                        labels: ChartGraphName,
                        datasets: rxData
                    };
                    var myChart = new Chart(ctx, {
                        type: 'bar',
                        data: data,
                        options: {
                            "hover": {
                                "animationDuration": 0
                            },
                            responsive: true,
                            maintainAspectRatio: false,
                            plugins: [{
                                    beforeDraw: function (c) {
                                        var chartHeight = c.chart.height;
                                        c.scales['x-axis-0'].options.ticks.fontSize = chartHeight * 4 / 100;
                                    }
                                }],
                            legend: {
                                "display": true
                            },
                            tooltips: {
                                "enabled": true
                            },
                            scales: {
                                xAxes: [{
                                        display: true,
                                        scaleLabel: {
                                            display: true,
                                            labelString: 'Provider MD'
                                        }
                                    }],
                                yAxes: [{
                                        display: true,
                                        scaleLabel: {
                                            display: true,
                                            labelString: 'Days of treatment'
                                        },
                                        ticks: {
                                    beginAtZero: true,
                                    userCallback: function(label, index, labels) {
                                        // when the floored value is the same as the value we have a whole number
                                        if (Math.floor(label) === label) {
                                            return label;
                                        }

                                    },
                                }
                                    }]
                            }
                        }
                    });
                } else {
                    $('#canvas6').remove();
                    $("#msg").html("<div class='alert alert-danger'>Records not found</div>");
                }
            },
            error: function (error, ror, r) {
                $("#msg").html("<div class='alert alert-danger'>Records not found</div>");
            },
        });
    }

    function get_rx_by_actual_dots(careUnit,rx,date1,date2) {
        var careUnit1 = $("#careUnit").val();
        var url = "<?php echo base_url() ?>reports/get_rx_by_actual_dots";
        $.ajax({
            method: "POST",
            url: url,
            data: {careUnit: careUnit,rx:rx,date1:date1,date2,date2},
            dataType: "json",
            success: function (response) {
                if (response.status == 200) {
                    //console.log(response.antibiotic);
                    var ChartGraphName = [];
                    var ChartGraphPercentage = [];

                    response.antibiotic.forEach(element => {
                        ChartGraphName.push([element.name]);
                        ChartGraphPercentage.push(element.percentage);
                    });

                    $('#canvas31').remove(); // this is my <canvas> element
                    $('#Graph-chart31').append('<canvas id="canvas31"><canvas>');
                    var ctx = document.getElementById("canvas31");
                    var data = {
                        labels: ChartGraphName,
                        datasets: [
                            {
                                label: "%",
                                backgroundColor: ["#3e95cd", "#58aed1", "#374f2f", "#644ab8", "#c45850"],
                                data: ChartGraphPercentage
                            }
                        ]
                    };
                    var myChart = new Chart(ctx, {
                        type: 'bar',
                        data: data,
                        options: {
                            "hover": {
                                "animationDuration": 0
                            },
                            "animation": {
                                "onComplete": function () {
                                    var chartInstance = this.chart,
                                            ctx = chartInstance.ctx;

                                    ctx.font = Chart.helpers.fontString(Chart.defaults.global.defaultFontSize, Chart.defaults.global.defaultFontStyle, Chart.defaults.global.defaultFontFamily);
                                    ctx.textAlign = 'center';
                                    ctx.textBaseline = 'bottom';

                                    this.data.datasets.forEach(function (dataset, i) {
                                        var meta = chartInstance.controller.getDatasetMeta(i);
                                        meta.data.forEach(function (bar, index) {
                                            var data = dataset.data[index];
                                            if (data > 0)
                                                ctx.fillText(data, bar._model.x, bar._model.y - 5);
                                        });
                                    });
                                }
                            },
                            responsive: true,
                            maintainAspectRatio: false,
                            plugins: [{
                                    beforeDraw: function (c) {
                                        var chartHeight = c.chart.height;
                                        c.scales['x-axis-0'].options.ticks.fontSize = chartHeight * 4 / 100;
                                    }
                                }],
                            legend: {
                                "display": false
                            },
                            title: {
                                display: true,
                                text: response.care_name
                            },
                            tooltips: {
                                "enabled": false
                            },
                            scales: {
                                xAxes: [{
                                        display: true,
                                        scaleLabel: {
                                            display: true,
                                            labelString: 'Antibiotics'
                                        }
                                    }],
                                yAxes: [{
                                        display: true,
                                        scaleLabel: {
                                            display: true,
                                            labelString: 'Percentage %'
                                        }
                                    }]
                            }
                        }
                    });
                } else {
                    $('#canvas31').remove();
                    $("#msg").html("<div class='alert alert-danger'>Records not found</div>");
                }
            },
            error: function (error, ror, r) {
                $("#msg").html("<div class='alert alert-danger'>Records not found</div>");
            },
        });
    }

    function get_dx_by_actual_dots(careUnit,rx,date1,date2) {
        var careUnit1 = $("#careUnit").val();
        var url = "<?php echo base_url() ?>reports/get_dx_by_actual_dots";
        $.ajax({
            method: "POST",
            url: url,
            data: {careUnit: careUnit,rx:rx,date1:date1,date2,date2},
            dataType: "json",
            success: function (response) {
                if (response.status == 200) {
                    //console.log(response.antibiotic);
                    var ChartGraphName = [];
                    var ChartGraphPercentage = [];

                    response.antibiotic.forEach(element => {
                        ChartGraphName.push([element.name]);
                        ChartGraphPercentage.push(element.percentage);
                    });

                    $('#canvas32').remove(); // this is my <canvas> element
                    $('#Graph-chart32').append('<canvas id="canvas32"><canvas>');
                    var ctx = document.getElementById("canvas32");
                    var data = {
                        labels: ChartGraphName,
                        datasets: [
                            {
                                label: "%",
                                backgroundColor: ["#644ab8", "#4E1554", "#074001", "#e8c3b9", "#c45850"],
                                data: ChartGraphPercentage
                            }
                        ]
                    };
                    var myChart = new Chart(ctx, {
                        type: 'bar',
                        data: data,
                        options: {
                            "hover": {
                                "animationDuration": 0
                            },
                            "animation": {
                                "onComplete": function () {
                                    var chartInstance = this.chart,
                                            ctx = chartInstance.ctx;

                                    ctx.font = Chart.helpers.fontString(Chart.defaults.global.defaultFontSize, Chart.defaults.global.defaultFontStyle, Chart.defaults.global.defaultFontFamily);
                                    ctx.textAlign = 'center';
                                    ctx.textBaseline = 'bottom';

                                    this.data.datasets.forEach(function (dataset, i) {
                                        var meta = chartInstance.controller.getDatasetMeta(i);
                                        meta.data.forEach(function (bar, index) {
                                            var data = dataset.data[index];
                                            if (data > 0)
                                                ctx.fillText(data, bar._model.x, bar._model.y - 5);
                                        });
                                    });
                                }
                            },
                            legend: {
                                "display": false
                            },
                            title: {
                                display: true,
                                text: response.care_name
                            },
                            tooltips: {
                                "enabled": false
                            },
                            responsive: true,
                            maintainAspectRatio: false,
                            plugins: [{
                                    beforeDraw: function (c) {
                                        var chartHeight = c.chart.height;
                                        c.scales['x-axis-0'].options.ticks.fontSize = chartHeight * 4 / 100;
                                    }
                                }],
                            scales: {
                                xAxes: [{
                                        display: true,
                                        scaleLabel: {
                                            display: true,
                                            labelString: 'Infections'
                                        }
                                    }],
                                yAxes: [{
                                        display: true,
                                        scaleLabel: {
                                            display: true,
                                            labelString: 'Percentage %'
                                        }
                                    }]
                            }
                        }
                    });
                } else {
                    $('#canvas32').remove();
                    $("#msg").html("<div class='alert alert-danger'>Records not found</div>");
                }
            },
            error: function (error, ror, r) {
                $("#msg").html("<div class='alert alert-danger'>Records not found</div>");
            },
        });
    }

    function getCharts(filterval) {
        /*
         * Flot Jquery plugin is used for charts
         *
         * For more examples or getting extra plugins you can check http://www.flotcharts.org/
         * Plugins included in this template: pie, resize, stack, time
         */

        // Get the elements where we will attach the charts

        var url = "<?php echo base_url() ?>pwfpanel/getcharts";
        $.ajax({
            method: "POST",
            url: url,
            data: {filterval: filterval},
            dataType: "json",
            success: function (response) {
                if (response.status == 200) {

                    $("#totalv").html(response.totalv);
                    $("#totalu").html(response.totalu);
                    $("#totale").html(response.totale);

                    var chartOverview = $('#chart-overview');

                    // Random data for the charts
                    //var dataEarnings    = [[1, 11600], [2, 13950], [3, 10900], [4, 10050], [5, 11000], [6, 14300], [7, 12500], [8, 15050], [9, 12650], [10, 14000], [11, 15000], [12, 17900]];
                    //var dataOrders      = [[1, 3000], [2, 3500], [3, 2900], [4, 3800], [5, 2800], [6, 2408], [7, 2682], [8, 4400], [9, 5400], [10, 4750], [11, 6100], [12, 7560]];

                    var dataEarnings = response.vendors;
                    // Array with month labels used in Classic and Stacked chart
                    var chartMonths = [[1, 'Jan'], [2, 'Feb'], [3, 'Mar'], [4, 'Apr'], [5, 'May'], [6, 'Jun'], [7, 'Jul'], [8, 'Aug'], [9, 'Sep'], [10, 'Oct'], [11, 'Nov'], [12, 'Dec']];

                    // Overview Chart
                    $.plot(chartOverview,
                            [
                                {
                                    label: 'Patient',
                                    data: dataEarnings,
                                    lines: {show: true, fill: true, fillColor: {colors: [{opacity: 0.25}, {opacity: 0.25}]}},
                                    points: {show: true, radius: 6}
                                }
                            ],
                            {
                                colors: ['#1bbae1', '#333333', '#d9416c'],
                                legend: {show: true, position: 'nw', margin: [15, 10]},
                                grid: {borderWidth: 0, hoverable: true, clickable: true},
                                yaxis: {ticks: 3, tickColor: '#f1f1f1'},
                                xaxis: {ticks: chartMonths, tickColor: '#ffffff'}
                            }
                    );

                    // Creating and attaching a tooltip to the classic chart
                    var previousPoint = null, ttlabel = null;
                    chartOverview.bind('plothover', function (event, pos, item) {

                        if (item) {
                            if (previousPoint !== item.dataIndex) {
                                previousPoint = item.dataIndex;

                                $('#chart-tooltip').remove();
                                var x = item.datapoint[0], y = item.datapoint[1];


                                ttlabel = '<strong>' + y + '</strong> Patient';


                                $('<div id="chart-tooltip" class="chart-tooltip">' + ttlabel + '</div>')
                                        .css({top: item.pageY - 45, left: item.pageX + 5}).appendTo("body").show();
                            }
                        } else {
                            $('#chart-tooltip').remove();
                            previousPoint = null;
                        }
                    });






                } else {
                    $("#msg").html("<div class='alert alert-danger'>Records not found</div>");
                }
            },
            error: function (error, ror, r) {
                $("#msg").html("<div class='alert alert-danger'>Records not found</div>");
            },
        });
    }

    function downloadPDF2(id,name) {

        var canvasImg = document.getElementById(id).toDataURL("image/png", 1.0);
        var doc = new jsPDF('landscape');
        doc.setFontSize(33);
        doc.setFillColor(135, 124,45,0);
        doc.addImage(canvasImg, 'png', 10, 10, 280, 150);
        doc.save(name+'.pdf');
    }

    function getTables(filterval) {

        var url = "<?php echo base_url() ?>pwfpanel/getTablesVendor";
        $.ajax({
            method: "POST",
            url: url,
            data: {filterval: filterval},
            success: function (response) {
                $("#getTablesVendor").html(response);
            }
        });
        $.ajax({
            method: "POST",
            url: "<?php echo base_url() ?>pwfpanel/getTablesUsers",
            data: {filterval: filterval},
            success: function (response) {
                $("#getTablesUsers").html(response);
            }
        });
        $.ajax({
            method: "POST",
            url: "<?php echo base_url() ?>pwfpanel/getTablesEnquiries",
            data: {filterval: filterval},
            success: function (response) {
                $("#getTablesEnquiries").html(response);
            }
        });
    }

    new Chart(document.getElementById("pie-chart"), {
        type: 'pie',
        data: {
            labels: ["Fever", ""],
            datasets: [{
                    label: "Actual DOT",
                    backgroundColor: ["#3e95cd", "#8e5ea2"],
                    data: [100, 50]
                }]
        },
        options: {
            title: {
                display: true,
                text: 'Actual DOT'
            }
        }
    });

    new Chart(document.getElementById("bar-chart-horizontal"), {
        type: 'horizontalBar',
        data: {
            labels: ["Fever", "Sepsis", "UTI", "HP", "IRT"],
            datasets: [
                {
                    label: "Actual DOT",
                    backgroundColor: ["#3e95cd", "#8e5ea2", "#3cba9f", "#e8c3b9", "#c45850"],
                    data: [2478, 5267, 734, 784, 433]
                }
            ]
        },
        options: {
            legend: {display: false},
            title: {
                display: true,
                text: 'Actual DOT'
            }
        }
    });

    new Chart(document.getElementById("bar-chart-grouped"), {
        type: 'bar',
        data: {
            labels: ["1900", "1950", "1999", "2050"],
            datasets: [
                {
                    label: "Test1",
                    backgroundColor: "#3e95cd",
                    data: [133, 221, 783, 2478]
                }, {
                    label: "Test2",
                    backgroundColor: "#8e5ea2",
                    data: [408, 547, 675, 734]
                }
            ]
        },
        options: {
            title: {
                display: true,
                text: 'Care Unit'
            }
        }
    });

    function un_days() {
        $.ajax({
            method: "POST",
            url: "<?php echo base_url() ?>setting/un_days",
            data: {filterval: 0},
            success: function (response) {
                location.reload();
            }
        });
    }


    function getAntibioticByCareUnitProviderId(date1,date2) {
        var provider = $("#provider_doctor").val();
        var careUnit = $("#careUnit").val();
        var rx = $("#RX").val();
        var url = "<?php echo base_url() ?>reports/get_antibiotic_by_care_unit_provider_id";
        $.ajax({
            method: "POST",
            url: url,
            data: {careUnit: careUnit, provider: provider,rx:rx,date1:date1,date2,date2},
            dataType: "json",
            success: function (response) {
                if (response.status == 200) {
                    //console.log(response.antibiotic);
                    var ChartGraphName = [];
                    var ChartGraphPercentage = [];
                    var rxData = [];
                    response.antibiotic.forEach(element => {
                        ChartGraphName.push([element.md_name]);
                        ChartGraphPercentage.push(element.rx);
                        //rxData.push(element.data);
                    });
                    response.datasheet.forEach(element => {
                        rxData.push(element);
                    });
                    $('#canvas22').remove(); // this is my <canvas> element
                    if(rxData != ""){
                        $('#Graph-chart22').append('<canvas id="canvas22"><canvas>');
                    }
                    
                    var ctx = document.getElementById("canvas22");
                    var data = {
                        labels: ChartGraphName,
                        datasets: rxData
                    };
                    var myChart = new Chart(ctx, {
                        type: 'bar',
                        data: data,
                        options: {
                            "hover": {
                                "animationDuration": 0
                            },
                            "animation": {
                                "onComplete": function () {
                                    var chartInstance = this.chart,
                                            ctx = chartInstance.ctx;

                                    ctx.font = Chart.helpers.fontString(Chart.defaults.global.defaultFontSize, Chart.defaults.global.defaultFontStyle, Chart.defaults.global.defaultFontFamily);
                                    ctx.textAlign = 'center';
                                    ctx.textBaseline = 'bottom';

                                    this.data.datasets.forEach(function (dataset, i) {
                                        var meta = chartInstance.controller.getDatasetMeta(i);
                                        meta.data.forEach(function (bar, index) {
                                            var data = dataset.data[index];
                                            if (data > 0)
                                                ctx.fillText(data, bar._model.x, bar._model.y - 5);
                                        });
                                    });
                                }
                            },
                            legend: {
                                "display": true
                            },
                            responsive: true,
                            maintainAspectRatio: false,
                            plugins: [{
                                    beforeDraw: function (c) {
                                        var chartHeight = c.chart.height;
                                        c.scales['x-axis-0'].options.ticks.fontSize = chartHeight * 4 / 100;
                                    }
                                }],
                            title: {
                                display: true,
                                text: response.care_name
                            },
                            tooltips: {
                                "enabled": false
                            },
                            scales: {
                                xAxes: [{
                                        display: true,
                                        scaleLabel: {
                                            display: true,
                                            labelString: 'Provider MD'
                                        }
                                    }],
                                yAxes: [{
                                        display: true,
                                        scaleLabel: {
                                            display: true,
                                            labelString: 'Total Antibiotics Days'
                                        },
                                        ticks: {
                 beginAtZero: true,
                 userCallback: function(label, index, labels) {
                     // when the floored value is the same as the value we have a whole number
                     if (Math.floor(label) === label) {
                         return label;
                     }

                 },
             }
                                    }]
                            }
                        },
                    });
                } else {
                    $('#canvas1').remove();
                    $("#msg").html("<div class='alert alert-danger'>Records not found</div>");
                }
            },
            error: function (error, ror, r) {
                $("#msg").html("<div class='alert alert-danger'>Records not found</div>");
            },
        });
        getAntibioticByCareUnitProvider(careUnit, provider,rx);
        getAntibioticByCareUnitProviderPrice(careUnit, provider,rx);
    }
    
    function getAntibioticByCareUnitProvider(careUnit, provider,rx) {

       var date1= $("#date1").val();
       var date2= $("#date2").val();
        var url = "<?php echo base_url() ?>reports/get_antibiotic_by_care_unit_provider";
        $.ajax({
            method: "POST",
            url: url,
            data: {careUnit: careUnit, provider: provider,rx:rx,date1:date1,date2,date2},
            dataType: "json",
            success: function (response) {
                if (response.status == 200) {
                    //console.log(response.antibiotic);
                    var ChartGraphName = [];
                    var ChartGraphPercentage = [];
                    var rxData = [];
                    response.antibiotic.forEach(element => {
                        ChartGraphName.push([element.md_name]);
                        ChartGraphPercentage.push(element.rx);
                        //rxData.push(element.data);
                    });
                    response.datasheet.forEach(element => {
                        rxData.push(element);
                    });
                    $('#canvas21').remove(); // this is my <canvas> element
                    if(ChartGraphName != ""){
                        $('#Graph-chart21').append('<canvas id="canvas21"><canvas>');
                    }
                    
                    var ctx = document.getElementById("canvas21");
                    var data = {
                        labels: ChartGraphName,
                        datasets: rxData
                    };
                    var myChart = new Chart(ctx, {
                        type: 'bar',
                        data: data,
                        options: {
                            "hover": {
                                "animationDuration": 0
                            },
                            // "animation": {
                            //     "onComplete": function () {
                            //         var chartInstance = this.chart,
                            //                 ctx = chartInstance.ctx;

                            //         ctx.font = Chart.helpers.fontString(Chart.defaults.global.defaultFontSize, Chart.defaults.global.defaultFontStyle, Chart.defaults.global.defaultFontFamily);
                            //         ctx.textAlign = 'center';
                            //         ctx.textBaseline = 'bottom';

                            //         this.data.datasets.forEach(function (dataset, i) {
                            //             var meta = chartInstance.controller.getDatasetMeta(i);
                            //             meta.data.forEach(function (bar, index) {
                            //                 var data = dataset.data[index];
                            //                 if (data > 0)
                            //                     ctx.fillText(data, bar._model.x, bar._model.y - 5);
                            //             });
                            //         });
                            //     }
                            // },
                            legend: {
                                "display": true
                            },
                            responsive: true,
                            maintainAspectRatio: false,
                            plugins: [{
                                    beforeDraw: function (c) {
                                        var chartHeight = c.chart.height;
                                        c.scales['x-axis-0'].options.ticks.fontSize = chartHeight * 4 / 100;
                                    }
                                }],
                            title: {
                                display: true,
                                text: response.care_name
                            },
                            tooltips: {
                                "enabled": true
                            },
                            scales: {
                                xAxes: [{
                                        display: true,
                                        stacked: true,
                                        scaleLabel: {
                                            display: true,
                                            labelString: 'Provider MD'
                                        }
                                    }],
                                yAxes: [{
                                        display: true,
                                        stacked: true,
                                        scaleLabel: {
                                            display: true,
                                            labelString: 'Total Antibiotics Days'
                                        }
                                    }]
                            }
                        },
                    });
                    if(response.provider != null){
                        provider_md_rest(response.rest_graph);  
                    }
                } else {
                    $('#canvas1').remove();
                    $("#msg").html("<div class='alert alert-danger'>Records not found</div>");
                }
            },
            error: function (error, ror, r) {
                $("#msg").html("<div class='alert alert-danger'>Records not found</div>");
            },
        });
    }

    function provider_md_rest(rest_graph) {
        var ChartGraphName = [];
        var ChartGraphPercentage = [];
        var Color = [];

        rest_graph.forEach(element => {
            ChartGraphName.push([element.label]);
            ChartGraphPercentage.push(element.percentage);
            Color.push(element.backgroundColor);
        });

        $('#canvas23').remove(); // this is my <canvas> element
        $('#Graph-chart23').append('<canvas id="canvas23"><canvas>');
        var ctx = document.getElementById("canvas23");
        var data = {
            labels: ChartGraphName,
            datasets: [{
                    label: "%",
                    backgroundColor: Color,
                    data: ChartGraphPercentage
                }]
        }
        var myChart = new Chart(ctx, {
            type: 'bar',
            data: data,
            options: {
                "hover": {
                    "animationDuration": 0
                },
                "animation": {
                    "onComplete": function () {
                        var chartInstance = this.chart,
                                ctx = chartInstance.ctx;

                        ctx.font = Chart.helpers.fontString(Chart.defaults.global.defaultFontSize, Chart.defaults.global.defaultFontStyle, Chart.defaults.global.defaultFontFamily);
                        ctx.textAlign = 'center';
                        ctx.textBaseline = 'bottom';

                        this.data.datasets.forEach(function (dataset, i) {
                            var meta = chartInstance.controller.getDatasetMeta(i);
                            meta.data.forEach(function (bar, index) {
                                var data = dataset.data[index];
                                ctx.fillText(data, bar._model.x, bar._model.y - 5);
                            });
                        });
                    }
                },
                legend: {
                    "display": false
                },
                title: {
                    display: true,
                    text: "Care unit"
                },
                tooltips: {
                    "enabled": false
                },
                responsive: true,
                maintainAspectRatio: false,
                plugins: [{
                        beforeDraw: function (c) {
                            var chartHeight = c.chart.height;
                            c.scales['x-axis-0'].options.ticks.fontSize = chartHeight * 4 / 100;
                        }
                    }],
                scales: {
                    xAxes: [{
                            display: true,
                            scaleLabel: {
                                display: true,
                                labelString: 'Antibiotics'
                            }
                        }],
                    yAxes: [{
                            display: true,
                            scaleLabel: {
                                display: true,
                                labelString: 'Total Antibiotics Days'
                            }
                        }]
                }
            }
        });
    }

    function getAntibioticByCareUnitProviderPrice(careUnit, provider,rx) {
               var date1= $("#date1").val();
       var date2= $("#date2").val();
        var url = "<?php echo base_url() ?>reports/get_antibiotic_by_care_unit_provider_price";
        $.ajax({
            method: "POST",
            url: url,
            data: {careUnit: careUnit, provider: provider,rx:rx,date1:date1,date2,date2},
            dataType: "json",
            success: function (response) {
                if (response.status == 200) {

                var ChartGraphName = [];
                var ChartGraphPercentage = [];
                var Color = [];
                var name="All";
                console.log(provider+"==");
                if(provider != undefined){
                    name=response.provider_name;
                }

                response.rest_graph.forEach(element => {
                    ChartGraphName.push([element.label]);
                    ChartGraphPercentage.push(element.percentage);
                    Color.push(element.backgroundColor);
                });

                $('#canvas24').remove(); // this is my <canvas> element
                $('#Graph-chart24').append('<canvas id="canvas24"><canvas>');
                var ctx = document.getElementById("canvas24");
                var data = {
                    labels: ChartGraphName,
                    datasets: [{
                            label: "%",
                            backgroundColor: Color,
                            data: ChartGraphPercentage
                        }]
                }
                var myChart = new Chart(ctx, {
                    type: 'bar',
                    data: data,
                    options: {
                        "hover": {
                            "animationDuration": 0
                        },
                        "animation": {
                            "onComplete": function () {
                                var chartInstance = this.chart,
                                        ctx = chartInstance.ctx;

                                ctx.font = Chart.helpers.fontString(Chart.defaults.global.defaultFontSize, Chart.defaults.global.defaultFontStyle, Chart.defaults.global.defaultFontFamily);
                                ctx.textAlign = 'center';
                                ctx.textBaseline = 'bottom';

                                this.data.datasets.forEach(function (dataset, i) {
                                    var meta = chartInstance.controller.getDatasetMeta(i);
                                    meta.data.forEach(function (bar, index) {
                                        var data = dataset.data[index];
                                        ctx.fillText(data, bar._model.x, bar._model.y - 5);
                                    });
                                });
                            }
                        },
                        legend: {
                            "display": false
                        },
                        title: {
                            display: true,
                            text: response.care_name
                        },
                        tooltips: {
                            "enabled": false
                        },
                        responsive: true,
                        maintainAspectRatio: false,
                        plugins: [{
                                beforeDraw: function (c) {
                                    var chartHeight = c.chart.height;
                                    c.scales['x-axis-0'].options.ticks.fontSize = chartHeight * 4 / 100;
                                }
                            }],
                        scales: {
                            xAxes: [{
                                    display: true,
                                    scaleLabel: {
                                        display: true,
                                        labelString: 'Antibiotics ('+name+')'
                                    }
                                }],
                            yAxes: [{
                                    display: true,
                                    scaleLabel: {
                                        display: true,
                                        labelString: 'Cost Of Antibiotics'
                                    }
                                }]
                        }
                    }
                });
                } else {
                    $('#canvas1').remove();
                    $("#msg").html("<div class='alert alert-danger'>Records not found</div>");
                }
            },
            error: function (error, ror, r) {
                $("#msg").html("<div class='alert alert-danger'>Records not found</div>");
            },
        });
    }

    function getAntibioticByCareUnitStewardPrice(careUnit, provider,rx) {
               var date1= $("#date1").val();
       var date2= $("#date2").val();
        var careUnit = $("#careUnit").val();
        var provider = $("#steward").val();
        var rx = $("#RX").val();
        var url = "<?php echo base_url() ?>reports/get_antibiotic_by_care_unit_steward_price";
        $.ajax({
            method: "POST",
            url: url,
            data: {careUnit: careUnit, provider: provider,rx:rx,date1:date1,date2,date2},
            dataType: "json",
            success: function (response) {
                if (response.status == 200) {

                var ChartGraphName = [];
                var ChartGraphPercentage = [];
                var Color = [];
                var name="All";
                if(provider != undefined){
                    name=response.provider_name;
                }

                response.rest_graph.forEach(element => {
                    ChartGraphName.push([element.label]);
                    ChartGraphPercentage.push(element.percentage);
                    Color.push(element.backgroundColor);
                });

                $('#canvas25').remove(); // this is my <canvas> element
                $('#Graph-chart25').append('<canvas id="canvas25"><canvas>');
                var ctx = document.getElementById("canvas25");
                var data = {
                    labels: ChartGraphName,
                    datasets: [{
                            label: "%",
                            backgroundColor: Color,
                            data: ChartGraphPercentage
                        }]
                }
                var myChart = new Chart(ctx, {
                    type: 'bar',
                    data: data,
                    options: {
                        "hover": {
                            "animationDuration": 0
                        },
                        "animation": {
                            "onComplete": function () {
                                var chartInstance = this.chart,
                                        ctx = chartInstance.ctx;

                                ctx.font = Chart.helpers.fontString(Chart.defaults.global.defaultFontSize, Chart.defaults.global.defaultFontStyle, Chart.defaults.global.defaultFontFamily);
                                ctx.textAlign = 'center';
                                ctx.textBaseline = 'bottom';

                                this.data.datasets.forEach(function (dataset, i) {
                                    var meta = chartInstance.controller.getDatasetMeta(i);
                                    meta.data.forEach(function (bar, index) {
                                        var data = dataset.data[index];
                                        ctx.fillText(data, bar._model.x, bar._model.y - 5);
                                    });
                                });
                            }
                        },
                        legend: {
                            "display": false
                        },
                        title: {
                            display: true,
                            text: response.care_name
                        },
                        tooltips: {
                            "enabled": false
                        },
                        responsive: true,
                        maintainAspectRatio: false,
                        plugins: [{
                                beforeDraw: function (c) {
                                    var chartHeight = c.chart.height;
                                    c.scales['x-axis-0'].options.ticks.fontSize = chartHeight * 4 / 100;
                                }
                            }],
                        scales: {
                            xAxes: [{
                                    display: true,
                                    scaleLabel: {
                                        display: true,
                                        labelString: 'Antibiotics ('+name+')'
                                    }
                                }],
                            yAxes: [{
                                    display: true,
                                    scaleLabel: {
                                        display: true,
                                        labelString: 'Cost Of Antibiotics'
                                    }
                                }]
                        }
                    }
                });
                } else {
                    $('#canvas1').remove();
                    $("#msg").html("<div class='alert alert-danger'>Records not found</div>");
                }
            },
            error: function (error, ror, r) {
                $("#msg").html("<div class='alert alert-danger'>Records not found</div>");
            },
        });
    }
</script>