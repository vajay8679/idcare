<script src="<?php echo base_url() . 'backend_asset/admin/js/' ?>app.js"></script>
<script src="https://cdn.jsdelivr.net/npm/chart.js@2.8.0"></script>
<script src="https://cdnjs.cloudflare.com/ajax/libs/jspdf/1.2.61/jspdf.min.js"></script>
<script>
    $("#date1").datepicker({
        dateFormat: 'mm/dd/yy'
    });
    $("#date2").datepicker({
        dateFormat: 'mm/dd/yy'
    });
    $("#date2").on('change', function() {
        var date = Date.parse($(this).val());
        if (date > Date.now()) {
            alert('Selected date must be less than today date');
            $(this).val('');
        }
    });
    $("#date1").on('change', function() {
        var date = Date.parse($(this).val());
        if (date > Date.now()) {
            alert('Selected date must be less than today date');
            $(this).val('');
        }
    });

    function getReports() {
        var date1 = $("#date1").val();
        var date2 = $("#date2").val();
        if (Date.parse(date1) > Date.parse(date2)) {
            alert('From date must be less than To date');
            $("#date1").val('');
        }
        if (date1 != '' && date2 != '') {
            getAntibioticByCareUnit('', '', date1, date2);
        }
    }
    getAntibioticByCareUnit();

    function getAntibioticByCareUnit(careUnit, rx, date1, date2, symptom_onset,criteria_met,md_stayward_response,culture_source,date_of_start_abx,date_of_start_abx1,date_of_start_abx2,date_of_start_abx3,organism,precautions) {


        if (date1 != '' && date2 != '') {
            date1 = $("#date1").val();
            date2 = $("#date2").val();
        }
        if (date_of_start_abx != '' && date_of_start_abx1 != '') {
            date_of_start_abx = $("#date_of_start_abx").val();
            date_of_start_abx1 = $("#date_of_start_abx1").val();
        }

        if (date_of_start_abx2 != '' && date_of_start_abx3 != '') {
            date_of_start_abx2 = $("#date_of_start_abx2").val();
            date_of_start_abx3 = $("#date_of_start_abx3").val();
        }else if(date_of_start_abx2 == '2021'){
            date_of_start_abx2 = $("#date_of_start_abx2").val();
        }else if(date_of_start_abx3 == '2021'){
            date_of_start_abx3 = $("#date_of_start_abx3").val();
        }else if(date_of_start_abx2 == '2022'){
            date_of_start_abx2 = $("#date_of_start_abx2").val();
        }else if(date_of_start_abx3 == '2022'){
            date_of_start_abx3 = $("#date_of_start_abx3").val();
        }else if(date_of_start_abx2 == '2023'){
            date_of_start_abx2 = $("#date_of_start_abx2").val();
        }else if(date_of_start_abx3 == '2023'){
            date_of_start_abx3 = $("#date_of_start_abx3").val();
        }

        careUnit = $("#careUnit").val();
        symptom_onset = $("#symptom_onset").val();
        criteria_met = $("#criteria_met").val();
        md_stayward_response = $("#md_stayward_response").val();
        culture_source = $("#culture_source").val();
        organism = $("#organism").val();
        precautions = $("#precautions").val();
        
        rx = $("#RX").val();

        getAntibioticByCareUnitFacilitydays(careUnit, rx, date1, date2, symptom_onset,criteria_met,md_stayward_response,culture_source,date_of_start_abx,date_of_start_abx1,date_of_start_abx2,date_of_start_abx3,organism,precautions);
        getAntibioticByCareUnitFacilitydaysaverage(careUnit, rx, date1, date2, symptom_onset,criteria_met,md_stayward_response,culture_source,date_of_start_abx2,date_of_start_abx3,organism,precautions);
        getProviderStewardAgreementdays(careUnit, rx, date1, date2, symptom_onset,criteria_met,md_stayward_response,culture_source,date_of_start_abx2,date_of_start_abx3,organism,precautions);
        getAntibioticByCareUnitFacility(careUnit, rx, date1, date2, symptom_onset,criteria_met,md_stayward_response,culture_source,date_of_start_abx2,date_of_start_abx3,organism,precautions);
        getAntibioticByCareUnitFacilityPie(careUnit, rx, date1, date2, symptom_onset,criteria_met,md_stayward_response,culture_source,date_of_start_abx2,date_of_start_abx3,organism,precautions);
        get_dx_by_actual_dots(careUnit, rx, date1, date2, symptom_onset,criteria_met,md_stayward_response,culture_source,date_of_start_abx,date_of_start_abx1,date_of_start_abx2,date_of_start_abx3,organism,precautions);
        get_avg_dx_by_actual_dots(careUnit, rx, date1, date2, symptom_onset,criteria_met,md_stayward_response,culture_source,date_of_start_abx2,date_of_start_abx3,organism,precautions);
        getAntibioticByCareUnitProvider(careUnit, rx, date1, date2, symptom_onset,criteria_met,md_stayward_response,culture_source,date_of_start_abx,date_of_start_abx1,date_of_start_abx2,date_of_start_abx3,organism,precautions);
        getAntibioticByCareUnitProvideraverage(rx, date1, date2, symptom_onset,criteria_met,md_stayward_response,culture_source,date_of_start_abx,date_of_start_abx1,date_of_start_abx2,date_of_start_abx3,organism,precautions); 
        getAntibioticByCareUnitProviderPie(careUnit, rx, date1, date2, symptom_onset,criteria_met,md_stayward_response,culture_source,date_of_start_abx2,date_of_start_abx3,organism,precautions);
        get_dx_by_actual_dots_pie(careUnit, rx, date1, date2, symptom_onset,criteria_met,md_stayward_response,culture_source,date_of_start_abx2,date_of_start_abx3,organism,precautions);
        total_abx_days(careUnit, rx, date1, date2, symptom_onset,criteria_met,md_stayward_response,culture_source,date_of_start_abx,date_of_start_abx1,date_of_start_abx2,date_of_start_abx3,organism,precautions);
        total_abx_days_pie(careUnit, rx, date1, date2, symptom_onset,criteria_met,md_stayward_response,culture_source,date_of_start_abx,date_of_start_abx1,date_of_start_abx2,date_of_start_abx3,organism,precautions);
        getAntibioticByCareUnitProviderNew(careUnit, rx, date1, date2, symptom_onset,criteria_met,md_stayward_response,culture_source,date_of_start_abx2,date_of_start_abx3,organism,precautions);
        getDaysByProvideANDStewardNew(careUnit, rx, date1, date2, symptom_onset,criteria_met,md_stayward_response,culture_source,date_of_start_abx2,date_of_start_abx3,organism,precautions);
        get_days_cost(careUnit, rx, date1, date2, symptom_onset,criteria_met,md_stayward_response,culture_source,date_of_start_abx2,date_of_start_abx3,organism,precautions);
        getDaysByProvideANDStewardNewinitialandactual(careUnit, rx, date1, date2, symptom_onset,criteria_met,md_stayward_response,culture_source,date_of_start_abx2,date_of_start_abx3,organism,precautions);
        getDaysByProvideANDStewardNewinitialandactualcost(careUnit, rx, date1, date2, symptom_onset,criteria_met,md_stayward_response,culture_source,date_of_start_abx2,date_of_start_abx3,organism,precautions);
        total_abx_days_dollar(careUnit, rx, date1, date2, symptom_onset,criteria_met,md_stayward_response,culture_source,date_of_start_abx2,date_of_start_abx3,organism,precautions);
        get_days_costsavedbysteward(careUnit, rx, date1, date2, symptom_onset,criteria_met,md_stayward_response,culture_source,date_of_start_abx2,date_of_start_abx3,organism,precautions)
        get_cost_cost(careUnit, rx, date1, date2, symptom_onset,criteria_met,md_stayward_response,culture_source,date_of_start_abx2,date_of_start_abx3,organism,precautions);


    }

    /** canvas43 **/
    function getAntibioticByCareUnitFacilitydays(careUnit, rx, date1, date2, symptom_onset,criteria_met,md_stayward_response,culture_source,date_of_start_abx,date_of_start_abx1,date_of_start_abx2,date_of_start_abx3,organism,precautions) {

        if (careUnit == "") {
            var careUnit = $("#careUnit").val();
        }
        var steward = $("#steward").val();
        var provider_doctor = $("#provider_doctor").val();
        var url = "<?php echo base_url() ?>reportsSummary/get_antibiotic_by_care_unit_facility_days";
        $.ajax({
            method: "POST",
            url: url,
            data: {
                careUnit: careUnit,
                steward: steward,
                rx: rx,
                date1: date1,
                date2: date2,
                provider: provider_doctor,
                symptom_onset: symptom_onset,
                criteria_met: criteria_met,
                md_stayward_response: md_stayward_response,
                culture_source:culture_source,
                date_of_start_abx:date_of_start_abx,
                date_of_start_abx1:date_of_start_abx1,
                date_of_start_abx2:date_of_start_abx2,
                date_of_start_abx3:date_of_start_abx3,
                organism:organism,
                precautions:precautions,
                
            },
            dataType: "json",
            success: function(response) {
                if (response.status == 200) {
                    // console.log(response.antibiotic);
                    var ChartGraphName = [];
                    var ChartGraphDays = [];
                    var backgroundColors = [];

                    response.antibiotic.forEach(element => {
                        ChartGraphName.push([element.name]);
                        backgroundColors.push([element.backgroundColor]);
                        ChartGraphDays.push(element.days);
                    });

                    $('#canvas43').remove(); // this is my <canvas> element
                    $('#Graph-chart43').append('<canvas id="canvas43"><canvas>');
                    var ctx = document.getElementById("canvas43");
                    var data = {
                        labels: ChartGraphName,
                        datasets: [{
                            label: "%",
                            backgroundColor: backgroundColors,
                            data: ChartGraphDays
                            // data: ChartGraphPercentage
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
                                "onComplete": function() {
                                    var chartInstance = this.chart,
                                        ctx = chartInstance.ctx;

                                    ctx.font = Chart.helpers.fontString(Chart.defaults.global.defaultFontSize, Chart.defaults.global.defaultFontStyle, Chart.defaults.global.defaultFontFamily);
                                    ctx.textAlign = 'center';
                                    ctx.textBaseline = 'bottom';

                                    this.data.datasets.forEach(function(dataset, i) {
                                        var meta = chartInstance.controller.getDatasetMeta(i);
                                        meta.data.forEach(function(bar, index) {
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
                                 text: ''
                             },
                            tooltips: {
                                "enabled": false
                            },
                            responsive: true,
                            maintainAspectRatio: false,
                            plugins: [{
                                beforeDraw: function(c) {
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
                                        labelString: 'Total Antibiotic Days by Antibiotic'
                                    },
                                    ticks: {
                                        min: 0,
                                    }

                                }]
                            }
                        }
                    });
                } else {
                    $('#canvas43').remove();
                    $("#msg").html("<div class='alert alert-danger'>Records not found</div>");
                }
            },
            error: function(error, ror, r) {
                $("#msg").html("<div class='alert alert-danger'>Records not found</div>");
            },
        });
    }



     /** canvas433 **/
     function getAntibioticByCareUnitFacilitydaysaverage(careUnit, rx, date1, date2, symptom_onset,criteria_met,md_stayward_response,culture_source,date_of_start_abx2,date_of_start_abx3,organism,precautions) {

if (careUnit == "") {
    var careUnit = $("#careUnit").val();
}
var steward = $("#steward").val();
var provider_doctor = $("#provider_doctor").val();
var url = "<?php echo base_url() ?>reportsSummary/get_antibiotic_by_care_unit_facility_days_average";
$.ajax({
    method: "POST",
    url: url,
    data: {
        careUnit: careUnit,
        steward: steward,
        rx: rx,
        date1: date1,
        date2: date2,
        provider: provider_doctor,
        symptom_onset: symptom_onset,
        criteria_met: criteria_met,
        md_stayward_response: md_stayward_response,
        culture_source:culture_source,
        date_of_start_abx2:date_of_start_abx2,
        date_of_start_abx3:date_of_start_abx3,
        organism:organism,
        precautions:precautions,
        
    },
    dataType: "json",
    success: function(response) {
        if (response.status == 200) {
            // console.log(response.antibiotic);
            var ChartGraphName = [];
            var ChartGraphDays = [];
           // var backgroundColors = [];

            var avg_days1 =[];
             var backgroundColors = [];
             
             response.antibiotic.forEach(element => {
                ChartGraphName.push(element.name);
                backgroundColors.push(element.backgroundColor);
                 avg_days1.push(element.avg_dayss1);
                 //rxData.push(element.data);
             });

            /* response.antibiotic.forEach(element => {
                ChartGraphName.push([element.name]);
                backgroundColors.push([element.backgroundColor]);
                ChartGraphDays.push(element.days);
            }); */

            $('#canvas433').remove(); // this is my <canvas> element
            $('#Graph-chart433').append('<canvas id="canvas433"><canvas>');
            var ctx = document.getElementById("canvas433");
            var data = {
                labels: ChartGraphName,
                datasets: [{
                  //  label: "%",
                     data: avg_days1,
                     backgroundColor: "red",
                     borderWidth: 1
                    // data: ChartGraphPercentage
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
                        "onComplete": function() {
                            var chartInstance = this.chart,
                                ctx = chartInstance.ctx;

                            ctx.font = Chart.helpers.fontString(Chart.defaults.global.defaultFontSize, Chart.defaults.global.defaultFontStyle, Chart.defaults.global.defaultFontFamily);
                            ctx.textAlign = 'center';
                            ctx.textBaseline = 'bottom';

                            this.data.datasets.forEach(function(dataset, i) {
                                var meta = chartInstance.controller.getDatasetMeta(i);
                                meta.data.forEach(function(bar, index) {
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
                         text: ''
                     },
                    tooltips: {
                        "enabled": false
                    },
                    responsive: true,
                    maintainAspectRatio: false,
                    plugins: [{
                        beforeDraw: function(c) {
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
                                labelString: 'Average of all Antibiotics by all Antibiotics for this time period'
                            },
                            ticks: {
                                min: 0,
                            }

                        }]
                    }
                }
            });
        } else {
            $('#canvas433').remove();
            $("#msg").html("<div class='alert alert-danger'>Records not found</div>");
        }
    },
    error: function(error, ror, r) {
        $("#msg").html("<div class='alert alert-danger'>Records not found</div>");
    },
});
}

 /** canvas49 **/
function getProviderStewardAgreementdays(careUnit, rx, date1, date2, symptom_onset,criteria_met,md_stayward_response,culture_source,date_of_start_abx2,date_of_start_abx3,organism,precautions) {

if (careUnit == "") {
    var careUnit = $("#careUnit").val();
}
var steward = $("#steward").val();
var provider_doctor = $("#provider_doctor").val();
var url = "<?php echo base_url() ?>reportsSummary/get_provider_steward_agreement_days";
$.ajax({
    method: "POST",
    url: url,
    data: {
        careUnit: careUnit,
        steward: steward,
        rx: rx,
        date1: date1,
        date2: date2,
        provider: provider_doctor,
        symptom_onset: symptom_onset,
        criteria_met: criteria_met,
        md_stayward_response: md_stayward_response,
        culture_source:culture_source,
        date_of_start_abx2:date_of_start_abx2,
        date_of_start_abx3:date_of_start_abx3,
        organism:organism,
        precautions:precautions,
        
    },
    dataType: "json",
    success: function(response) {
        if (response.status == 200) {
            // console.log(response.antibiotic);
            var ChartGraphName = [];
            var ChartGraphDays = [];
            var ChartGraphDayss = [];
            var ChartGraphDaysss = [];
            var ChartGraphDayssss = [];
            var backgroundColors = [];

            response.antibiotic.forEach(element => {
                ChartGraphName.push([element.name]);
                backgroundColors.push([element.backgroundColor]);
                ChartGraphDays.push(element.agree);
                ChartGraphDayss.push(element.disagree);
                ChartGraphDaysss.push(element.Neutral);
                ChartGraphDayssss.push(element.NoResponse);
            });

            $('#canvas49').remove(); // this is my <canvas> element
            $('#Graph-chart49').append('<canvas id="canvas49"><canvas>');
            var ctx = document.getElementById("canvas49");
            var data = {
                labels: ChartGraphName,
                datasets: [
                    {
                        label: "Agree",
                        backgroundColor: "blue",
                        data: ChartGraphDays
                    },
                    {
                        label: "Disagree",
                        backgroundColor: "red",
                        data: ChartGraphDayss
                    },
                    {
                        label: "NoResponse",
                        backgroundColor: "purple",
                        data: ChartGraphDayssss
                    },
                    {
                        label: "Neutral",
                        backgroundColor: "green",
                        data: ChartGraphDaysss
                    }
                ]
            }

                var chart = new Chart(ctx, {
                type: 'bar',
                data: data,
                options: {
                    "hover": {
                                "animationDuration": 0
                            },
                                        "animation": {
                                            duration: 500,
                                easing: "easeOutQuart",
                                onComplete: function () {
                                    var ctx = this.chart.ctx;
                                    ctx.font = Chart.helpers.fontString(Chart.defaults.global.defaultFontFamily, 'normal', Chart.defaults.global.defaultFontFamily);
                                    ctx.textAlign = 'center';
                                    ctx.textBaseline = 'bottom';

                                    this.data.datasets.forEach(function (dataset) {
                                        for (var i = 0; i < dataset.data.length; i++) {
                                            var model = dataset._meta[Object.keys(dataset._meta)[0]].data[i]._model,
                                                scale_max = dataset._meta[Object.keys(dataset._meta)[0]].data[i]._yScale.maxHeight;
                                            ctx.fillStyle = '#444';
                                            var y_pos = model.y - 5;
                                            // Make sure data value does not get overflown and hidden
                                            // when the bar's value is too close to max value of scale
                                            // Note: The y value is reverse, it counts from top down
                                            if ((scale_max - model.y) / scale_max >= 0.93)
                                                y_pos = model.y + 20; 
                                            ctx.fillText(dataset.data[i], model.x, y_pos);
                                        }
                                    });               
                                }
                            }, 

                            
                    barValueSpacing: 20,
                    scales: {
                        xAxes: [{
                                        display: true,
                                        scaleLabel: {
                                            display: true,
                                            labelString: 'Provider '/* Steward Agreement (Agree/Disagree/Neutral) */
                                        }
                                    }],
                        yAxes: [{
                            display: true,
                            scaleLabel: {
                                            display: true,
                                            labelString:'Count of Agree / Disagree / Neutral / NoResponse' /* 'Total No.of Provider Steward Agreement' */
                                        },
                            ticks: {
                                min: 0,
                            }
                        }]
                    }
                
                }
});
        } else {
            $('#canvas49').remove();
            $("#msg").html("<div class='alert alert-danger'>Records not found</div>");
        }
    },
    error: function(error, ror, r) {
        $("#msg").html("<div class='alert alert-danger'>Records not found</div>");
    },
});
}
    /** canvas41 **/
    function getAntibioticByCareUnitFacility(careUnit, rx, date1, date2, symptom_onset,criteria_met,md_stayward_response,culture_source,date_of_start_abx2,date_of_start_abx3,organism,precautions) {

        if (careUnit == "") {
            var careUnit = $("#careUnit").val();
        }
        var steward = $("#steward").val();
        var provider_doctor = $("#provider_doctor").val();
        var url = "<?php echo base_url() ?>reportsSummary/get_antibiotic_by_care_unit_facility";
        $.ajax({
            method: "POST",
            url: url,
            data: {
                careUnit: careUnit,
                steward: steward,
                rx: rx,
                date1: date1,
                date2: date2,
                provider: provider_doctor,
                symptom_onset: symptom_onset,
                criteria_met: criteria_met,
                md_stayward_response: md_stayward_response,
                culture_source:culture_source,
                date_of_start_abx2:date_of_start_abx2,
                date_of_start_abx3:date_of_start_abx3,
                organism:organism,
                precautions:precautions,
                
                
            },
            dataType: "json",
            success: function(response) {
                if (response.status == 200) {
                    // console.log(response.antibiotic);
                    var ChartGraphName = [];
                    var ChartGraphPercentage = [];
                    var backgroundColors = [];

                    response.antibiotic.forEach(element => {
                        ChartGraphName.push([element.name]);
                        backgroundColors.push([element.backgroundColor]);
                        ChartGraphPercentage.push(element.percentage);
                    });

                    $('#canvas41').remove(); // this is my <canvas> element
                    $('#Graph-chart41').append('<canvas id="canvas41"><canvas>');
                    var ctx = document.getElementById("canvas41");
                    var data = {
                        labels: ChartGraphName,
                        datasets: [{
                            label: "%",
                            backgroundColor: backgroundColors,
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
                                "onComplete": function() {
                                    var chartInstance = this.chart,
                                        ctx = chartInstance.ctx;

                                    ctx.font = Chart.helpers.fontString(Chart.defaults.global.defaultFontSize, Chart.defaults.global.defaultFontStyle, Chart.defaults.global.defaultFontFamily);
                                    ctx.textAlign = 'center';
                                    ctx.textBaseline = 'bottom';

                                    this.data.datasets.forEach(function(dataset, i) {
                                        var meta = chartInstance.controller.getDatasetMeta(i);
                                        meta.data.forEach(function(bar, index) {
                                            var data = dataset.data[index];
                                            ctx.fillText(data, bar._model.x, bar._model.y - 5);
                                        });
                                    });
                                }
                            },
                            legend: {
                                "display": false
                            },
                            /*  title: {
                                 display: true,
                                 text: response.care_name
                             }, */
                            tooltips: {
                                "enabled": false
                            },
                            responsive: true,
                            maintainAspectRatio: false,
                            plugins: [{
                                beforeDraw: function(c) {
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
                                        labelString: '% Initial Days On Therapy'
                                    }
                                }]
                            }
                        }
                    });
                } else {
                    $('#canvas41').remove();
                    $("#msg").html("<div class='alert alert-danger'>Records not found</div>");
                }
            },
            error: function(error, ror, r) {
                $("#msg").html("<div class='alert alert-danger'>Records not found</div>");
            },
        });
    }
    /** canvas42 **/
    function getAntibioticByCareUnitFacilityPie(careUnit, rx, date1, date2, symptom_onset,criteria_met,md_stayward_response,culture_source,date_of_start_abx2,date_of_start_abx3,organism,precautions) {
        console.log('g2', careUnit);
        if (careUnit == "") {
            var careUnit = $("#careUnit").val();
        }
        var steward = $("#steward").val();
        var provider_doctor = $("#provider_doctor").val();
        var url = "<?php echo base_url() ?>reportsSummary/get_antibiotic_by_care_unit_facility";
        $.ajax({
            method: "POST",
            url: url,
            data: {
                careUnit: careUnit,
                rx: rx,
                steward: steward,
                date1: date1,
                date2: date2,
                provider: provider_doctor,
                symptom_onset: symptom_onset,
                criteria_met: criteria_met,
                md_stayward_response: md_stayward_response,
                culture_source:culture_source,
                date_of_start_abx2:date_of_start_abx2,
                date_of_start_abx3:date_of_start_abx3,
                organism:organism,
                precautions:precautions,
                
                
            },
            dataType: "json",
            success: function(response) {
                if (response.status == 200) {
                    //console.log(response.antibiotic);
                    var ChartGraphName = [];
                    var ChartGraphPercentage = [];
                    var rxData = [];
                    var label = [];
                    var backgroundColor = [];


                    response.antibiotic.forEach(element => {
                        label.push([element.name]);
                        backgroundColor.push([element.backgroundColor]);
                        ChartGraphPercentage.push(element.percentage);
                    });

                    $('#canvas42').remove(); // this is my <canvas> element
                    $('#Graph-chart42').append('<canvas id="canvas42"><canvas>');
                    var ctx = document.getElementById("canvas42");
                    var data = {
                        labels: label,
                        datasets: [{
                            backgroundColor: backgroundColor,
                            data: ChartGraphPercentage
                        }]
                    };
                    console.log(data);
                    console.log('==================');
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
                                text: 'Antibiotics'
                            }
                        },
                    });
                } else {
                    $('#canvas42').remove();
                    $("#msg").html("<div class='alert alert-danger'>Records not found</div>");
                }
            },
            error: function(error, ror, r) {
                $("#msg").html("<div class='alert alert-danger'>Records not found</div>");
            },
        });
    }
    /** canvas21 **/
    function getAntibioticByCareUnitProvider(careUnit, rx, date1, date2, symptom_onset,criteria_met,md_stayward_response,culture_source,date_of_start_abx,date_of_start_abx1,date_of_start_abx2,date_of_start_abx3,organism,precautions) {

// console.log('g3', careUnit);

 var careUnit = $("#careUnit").val();
 var steward = $("#steward").val();
 var RX = $("#RX").val();
 var provider_doctor = $("#provider_doctor").val();

 // console.log(careUnit+' careUnit');
 // console.log(steward+' steward');
 // console.log(RX+' RX');
 // console.log(provider_doctor+' provider_doctor');

 var url = "<?php echo base_url() ?>reportsSummary/get_antibiotic_by_care_unit_provider";
 $.ajax({
     method: "POST",
     url: url,
     data: {
         careUnit: careUnit,
         rx: RX,
         steward: steward,
         provider_doctor: provider_doctor,
         date1: date1,
         date2: date2,
         symptom_onset: symptom_onset,
         criteria_met: criteria_met,
         md_stayward_response: md_stayward_response,
         culture_source:culture_source,
         date_of_start_abx:date_of_start_abx,
         date_of_start_abx1:date_of_start_abx1,
         date_of_start_abx2:date_of_start_abx2,
         date_of_start_abx3:date_of_start_abx3,
         organism:organism,
         precautions:precautions,
         
         
     },
     dataType: "json",
     success: function(response) {
         if (response.status == 200) {
             // console.log('percent test',response.percent_a);
             var ChartGraphName = [];
             var ChartGraphPercentage = [];
             var rxData = [];
             response.antibiotic.forEach(element => {
                 ChartGraphName.push([element.md_name]);
                 ChartGraphPercentage.push(element.rx);
                 //rxData.push(element.data);
             });
             response.datasheet.forEach(element => {
                 //console.log(element);
                 rxData.push(element);
             });
             $('#canvas21').remove(); // this is my <canvas> element
             if (ChartGraphName != "") {
                 $('#Graph-chart21').append('<canvas id="canvas21"><canvas>');
             }
             //console.log(rxData);
             var ctx = document.getElementById("canvas21");
             var data = {
                 labels: ChartGraphName,
                 datasets: [{
                     label: 'Days',
                     data: response.data,
                     backgroundColor: response.backgroundColor,
                     borderWidth: 1
                 }]
             };
             var myChart = new Chart(ctx, {
                 type: 'bar',
                 data: data,
                 options: {
                     "hover": {
                         "animationDuration": 0
                     },

                     "animation": {
                     duration: 1,
                         "onComplete": function() {
                             var chartInstance = this.chart,
                                 ctx = chartInstance.ctx;

                             ctx.font = Chart.helpers.fontString(Chart.defaults.global.defaultFontSize, Chart.defaults.global.defaultFontStyle, Chart.defaults.global.defaultFontFamily);
                            // ctx.textAlign = 'center';
                             ctx.textBaseline = 'bottom';
                            // ctx.font = "9px Open Sans";
                             this.data.datasets.forEach(function(dataset, i) {
                                 var meta = chartInstance.controller.getDatasetMeta(i);
                                 meta.data.forEach(function(bar, index) {
                                     /* var data = dataset.data[index];
                                     ctx.fillText(data, bar._model.x, bar._model.y - 5); */
                                     ctx.fillStyle = 'black';
                     dataset.data[index] = parseFloat(dataset.data[index]).toFixed(0);
                     var data = dataset.data[index];
                     ctx.save();
                     var random = Math.floor(Math.random() * 2) + 5;
                     ctx.translate(bar._model.x, bar._model.y - random);
                     ctx.rotate(-0.5 * Math.PI);
                     ctx.fillText(data, 0, 7);
                     ctx.restore();
                                 });
                             });
                         }
                     }, 


                     legend: {
                         "display": false
                     },
                     responsive: true,
                     maintainAspectRatio: false,
                     plugins: [{
                         beforeDraw: function(c) {
                             var chartHeight = c.chart.height;
                             c.scales['x-axis-0'].options.ticks.fontSize = chartHeight * 4 / 100;
                         }
                     }],
                     title: {
                         display: true,
                         text: ''
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
                             ticks: {
                                 stepSize: 30,
                             },

                             display: true,
                             stacked: true,
                             scaleLabel: {
                                 display: true,
                                 labelString: 'Total Antibiotic Days by Provider'
                             }
                         }]
                     }
                 },
             });
             if (response.provider != null) {
                 provider_md_rest(response.rest_graph);
             }
         } else {
             $('#canvas21').remove();
             $("#msg").html("<div class='alert alert-danger'>Records not found</div>");
         }
     },
     error: function(error, ror, r) {
         $("#msg").html("<div class='alert alert-danger'>Records not found</div>");
     },
 });
}

// canvas211
 function getAntibioticByCareUnitProvideraverage(/* careUnit, */ rx, date1, date2, symptom_onset,criteria_met,md_stayward_response,culture_source,date_of_start_abx,date_of_start_abx1,date_of_start_abx2,date_of_start_abx3,organism,precautions) {

// console.log('g3', careUnit);

 //var careUnit = $("#careUnit").val();
 var steward = $("#steward").val();
 var RX = $("#RX").val();
 var provider_doctor = $("#provider_doctor").val();

 // console.log(careUnit+' careUnit');
 // console.log(steward+' steward');
 // console.log(RX+' RX');
 // console.log(provider_doctor+' provider_doctor');

 var url = "<?php echo base_url() ?>reportsSummary/get_antibiotic_by_care_unit_provider_average";
 $.ajax({
     method: "POST",
     url: url,
     data: {
         //careUnit: careUnit,
         rx: RX,
         steward: steward,
         provider_doctor: provider_doctor,
         date1: date1,
         date2: date2,
         symptom_onset: symptom_onset,
         criteria_met: criteria_met,
         md_stayward_response: md_stayward_response,
         culture_source:culture_source,
         date_of_start_abx:date_of_start_abx,
         date_of_start_abx1:date_of_start_abx1,
         date_of_start_abx2:date_of_start_abx2,
         date_of_start_abx3:date_of_start_abx3,
         organism:organism,
         precautions:precautions,
         
         
     },
     dataType: "json",
     success: function(response) {
         if (response.status == 200) {
             // console.log('percent test',response.percent_a);
             var ChartGraphName = [];
            /*  var ChartGraphPercentage = [];
             var rxData = [];
             var Total_providers =[];
             var total_days = []; */
             var avg_days =[];
             var backgroundColors = [];
             
             response.antibiotic.forEach(element => {
                ChartGraphName.push(element.name);
                backgroundColors.push(element.backgroundColor);
                 avg_days.push(element.avg_dayss);
                 //rxData.push(element.data);
             });
             console.log('percent test',Total_providers);
             console.log('percent test2',ChartGraphName);
             
             // $('#total_percent_filter').append('<span id="table_iddd"><span>');

             // var ctxss = document.getElementById("table_iddd");
            /*  $('#Total_providers').text(Total_providers);
             $('#total_days').text(total_days);
             $('#avg_days').text(avg_days);
 */

            /*  response.antibiotic.forEach(element => {
                 ChartGraphName.push([element.md_name]);
                 ChartGraphPercentage.push(element.rx);
                 //rxData.push(element.data);
             }); */
            /*  response.datasheet.forEach(element => {
                 //console.log(element);
                 rxData.push(element);
             }); */
             $('#canvas211').remove(); // this is my <canvas> element
             if (ChartGraphName != "") {
                 $('#Graph-chart211').append('<canvas id="canvas211"><canvas>');
             }
             //console.log(rxData);
             var ctx = document.getElementById("canvas211");
             var data = {
                 labels: ChartGraphName,
                 datasets: [{
                     //label: 'Days',
                     data: avg_days,
                     backgroundColor: "red",
                     borderWidth: 1
                 }]
             };
             var myChart = new Chart(ctx, {
                 type: 'bar',
                 data: data,
                 options: {
                     "hover": {
                         "animationDuration": 0
                     },

                     "animation": {
                     duration: 1,
                         "onComplete": function() {
                             var chartInstance = this.chart,
                                 ctx = chartInstance.ctx;

                             ctx.font = Chart.helpers.fontString(Chart.defaults.global.defaultFontSize, Chart.defaults.global.defaultFontStyle, Chart.defaults.global.defaultFontFamily);
                            // ctx.textAlign = 'center';
                             ctx.textBaseline = 'bottom';
                            // ctx.font = "9px Open Sans";
                             this.data.datasets.forEach(function(dataset, i) {
                                 var meta = chartInstance.controller.getDatasetMeta(i);
                                 meta.data.forEach(function(bar, index) {
                                     /* var data = dataset.data[index];
                                     ctx.fillText(data, bar._model.x, bar._model.y - 5); */
                                     ctx.fillStyle = 'black';
                     dataset.data[index] = parseFloat(dataset.data[index]).toFixed(2);
                     var data = dataset.data[index];
                     ctx.save();
                     var random = Math.floor(Math.random() * 2) + 5;
                     ctx.translate(bar._model.x, bar._model.y - random);
                     ctx.rotate(-0.5 * Math.PI);
                     ctx.fillText(data, 0, 7);
                     ctx.restore();
                                 });
                             });
                         }
                     }, 


                     legend: {
                         "display": false
                     },
                     responsive: true,
                     maintainAspectRatio: false,
                     plugins: [{
                         beforeDraw: function(c) {
                             var chartHeight = c.chart.height;
                             c.scales['x-axis-0'].options.ticks.fontSize = chartHeight * 4 / 100;
                         }
                     }],
                     title: {
                         display: true,
                         text: ''
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
                                 labelString: 'Days'
                             }
                         }],
                         yAxes: [{
                            afterFit: function(scale) {
         scale.width = 45 //<-- set value as you wish 
      },
                             ticks: {
                                 stepSize: 30,
                             },

                             display: true,
                             stacked: true,
                             scaleLabel: {
                                 display: true,
                                 labelString: 'Average of all ABX by all providers for this time period '
                             }
                         }]
                     }
                 },
             });
             if (response.provider != null) {
                 provider_md_rest(response.rest_graph);
             }
         } else {
             $('#canvas211').remove();
             $("#msg").html("<div class='alert alert-danger'>Records not found</div>");
         }
     },
     error: function(error, ror, r) {
         $("#msg").html("<div class='alert alert-danger'>Records not found</div>");
     },
 });
}




    /** canvas30 **/
    function getAntibioticByCareUnitProviderPie(careUnit, rx, date1, date2, symptom_onset,criteria_met,md_stayward_response,culture_source,date_of_start_abx2,date_of_start_abx3,organism,precautions) {
        //var careUnit = $("#careUnit").val();

        var careUnit = $("#careUnit").val();
        var steward = $("#steward").val();
        var RX = $("#RX").val();
        var provider_doctor = $("#provider_doctor").val();
        var url = "<?php echo base_url() ?>reportsSummary/get_antibiotic_by_care_unit_provider_pie";
        $.ajax({
            method: "POST",
            url: url,
            data: {
                careUnit: careUnit,
                rx: RX,
                steward: steward,
                provider_doctor: provider_doctor,
                date1: date1,
                date2: date2,
                symptom_onset: symptom_onset,
                criteria_met: criteria_met,
                md_stayward_response: md_stayward_response,
                culture_source:culture_source,
                date_of_start_abx2:date_of_start_abx2,
                date_of_start_abx3:date_of_start_abx3,
                organism:organism,
                precautions:precautions,
                
                
            },
            dataType: "json",
            success: function(response) {
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
                    $('#canvas30').remove(); // this is my <canvas> element
                    if (ChartGraphName != "") {
                        $('#Graph-chart30').append('<canvas id="canvas30"><canvas>');
                    }
                    var ctx = document.getElementById("canvas30");
                    var data = {
                        labels: response.labels,
                        datasets: [{
                            label: '',
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
                            /*  title: {
                                display: true,
                                text: 'Provider MD'
                            },  */
                            tooltips: {
                                "enabled": true
                            },
                            /* scales: {
                                xAxes: [{
                                        display: true,
                                        stacked: true,
                                        scaleLabel: {
                                            display: true,
                                            labelString: 'Provider MD'
                                        }
                                    }],
                                yAxes: [{
                                    ticks: {
                                        stepSize: 30,
                                           },

                                        display: true,
                                        stacked: true,
                                        scaleLabel: {
                                            display: true,
                                            labelString: 'Total Antibiotics Days'
                                        }
                                    }]
                            }   */
                            responsive: true,
                            maintainAspectRatio: false,
                            title: {
                                display: true,
                                text: 'Provider MD'
                            }
                        },
                    });
                } else {
                    $('#canvas30').remove();
                    $("#msg").html("<div class='alert alert-danger'>Records not found</div>");
                }
            },
            error: function(error, ror, r) {
                $("#msg").html("<div class='alert alert-danger'>Records not found</div>");
            },
        });


        /* var careUnit = $("#careUnit").val();
                var steward = $("#steward").val();
                var RX = $("#RX").val();
                var provider_doctor = $("#provider_doctor").val();
                var url = "<?php echo base_url() ?>reportsSummary/get_antibiotic_by_care_unit_provider_pie";
                $.ajax({
                    method: "POST",
                    url: url,
                    data: {careUnit: careUnit,rx:RX,steward:steward,provider_doctor:provider_doctor,date1:date1,date2:date2,symptom_onset:symptom_onset},
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
                            $('#canvas30').remove(); // this is my <canvas> element
                            $('#Graph-chart30').append('<canvas id="canvas30"><canvas>');

                            var ctx = document.getElementById("canvas30");
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
                                                    labelString: 'Days'
                                                },
                                                ticks: {
                                                    stepSize : 30,
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
                            $('#canvas30').remove();
                            $("#msg").html("<div class='alert alert-danger'>Records not found</div>");
                        }
                    },
                    error: function (error, ror, r) {
                        $("#msg").html("<div class='alert alert-danger'>Records not found</div>");
                    },
                }); */
    }
    /** canvas32 **/
    function get_dx_by_actual_dots(careUnit, rx, date1, date2, symptom_onset,criteria_met,md_stayward_response,culture_source,date_of_start_abx,date_of_start_abx1,date_of_start_abx2,date_of_start_abx3,organism,precautions) {
        var careUnit = $("#careUnit").val();
        var steward = $("#steward").val();
        var RX = $("#RX").val();
        var provider_doctor = $("#provider_doctor").val();
        var url = "<?php echo base_url() ?>reportsSummary/get_dx_by_actual_dots_new";
        $.ajax({
            method: "POST",
            url: url,
            data: {
                careUnit: careUnit,
                rx: RX,
                steward: steward,
                provider_doctor: provider_doctor,
                date1: date1,
                date2: date2,
                symptom_onset: symptom_onset,
                criteria_met: criteria_met,
                md_stayward_response: md_stayward_response,
                culture_source:culture_source,
                date_of_start_abx:date_of_start_abx,
                date_of_start_abx1:date_of_start_abx1,
                date_of_start_abx2:date_of_start_abx2,
                date_of_start_abx3:date_of_start_abx3,
                organism:organism,
                precautions:precautions,
                
                
            },
            dataType: "json",
            success: function(response) {

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
                        datasets: [{
                            label: "%",
                            backgroundColor: response.color,
                            data: ChartGraphPercentage
                        }]
                    };
                    var myChart = new Chart(ctx, {
                        type: 'bar',
                        data: data,
                        options: {
                            "hover": {
                                "animationDuration": 0
                            },
                            "animation": {
                                "onComplete": function() {
                                    var chartInstance = this.chart,
                                        ctx = chartInstance.ctx;

                                    ctx.font = Chart.helpers.fontString(Chart.defaults.global.defaultFontSize, Chart.defaults.global.defaultFontStyle, Chart.defaults.global.defaultFontFamily);
                                    ctx.textAlign = 'center';
                                    ctx.textBaseline = 'bottom';

                                    this.data.datasets.forEach(function(dataset, i) {
                                        var meta = chartInstance.controller.getDatasetMeta(i);
                                        meta.data.forEach(function(bar, index) {
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
                                text: ''
                            },
                            tooltips: {
                                "enabled": true
                            },
                            responsive: true,
                            maintainAspectRatio: false,
                            plugins: [{
                                beforeDraw: function(c) {
                                    var chartHeight = c.chart.height;
                                    c.scales['x-axis-0'].options.ticks.fontSize = chartHeight * 4 / 100;
                                }
                            }],
                            scales: {
                                xAxes: [{
                                    display: true,
                                    scaleLabel: {
                                        display: true,
                                        labelString: 'Diagnosis'
                                    }
                                }],
                                yAxes: [{
                                    display: true,
                                    scaleLabel: {
                                        display: true,
                                        labelString: 'Total Antibiotic Days by Diagnosis' /* Antibiotics Days */
                                    },
                                    ticks: {
                                        min: 0
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
            error: function(error, ror, r) {
                $("#msg").html("<div class='alert alert-danger'>Records not found</div>");
            },
        });
    }



     /** canvas322 **/
     function get_avg_dx_by_actual_dots(careUnit, rx, date1, date2, symptom_onset,criteria_met,md_stayward_response,culture_source,date_of_start_abx2,date_of_start_abx3,organism,precautions) {
        var careUnit = $("#careUnit").val();
        var steward = $("#steward").val();
        var RX = $("#RX").val();
        var provider_doctor = $("#provider_doctor").val();
        var url = "<?php echo base_url() ?>reportsSummary/get_avg_dx_by_actual_dots_new";
        $.ajax({
            method: "POST",
            url: url,
            data: {
                careUnit: careUnit,
                rx: RX,
                steward: steward,
                provider_doctor: provider_doctor,
                date1: date1,
                date2: date2,
                symptom_onset: symptom_onset,
                criteria_met: criteria_met,
                md_stayward_response: md_stayward_response,
                culture_source:culture_source,
                date_of_start_abx2:date_of_start_abx2,
                date_of_start_abx3:date_of_start_abx3,
                organism:organism,
                precautions:precautions,
                
                
            },
            dataType: "json",
            success: function(response) {

                if (response.status == 200) {
                    //console.log(response.antibiotic);
                    var ChartGraphName = [];
                    var ChartGraphPercentage = [];
                    var avg_days2 =[];
                    var backgroundColors = [];
             
             response.antibiotic.forEach(element => {
                ChartGraphName.push(element.name);
                backgroundColors.push(element.backgroundColor);
                 avg_days2.push(element.avg_daysss1);
                 //rxData.push(element.data);
             });

                  /*   response.antibiotic.forEach(element => {
                        ChartGraphName.push([element.name]);
                        ChartGraphPercentage.push(element.percentage);
                    }); */

                    $('#canvas322').remove(); // this is my <canvas> element
                    $('#Graph-chart322').append('<canvas id="canvas322"><canvas>');
                    var ctx = document.getElementById("canvas322");
                    var data = {
                        labels: ChartGraphName,
                        datasets: [{

                            data: avg_days2,
                            backgroundColor: "red",
                            borderWidth: 1


                            /* label: "%",
                            backgroundColor: response.color,
                            data: ChartGraphPercentage */
                        }]
                    };
                    var myChart = new Chart(ctx, {
                        type: 'bar',
                        data: data,
                        options: {
                            "hover": {
                                "animationDuration": 0
                            },
                            "animation": {
                                "onComplete": function() {
                                    var chartInstance = this.chart,
                                        ctx = chartInstance.ctx;

                                    ctx.font = Chart.helpers.fontString(Chart.defaults.global.defaultFontSize, Chart.defaults.global.defaultFontStyle, Chart.defaults.global.defaultFontFamily);
                                    ctx.textAlign = 'center';
                                    ctx.textBaseline = 'bottom';

                                    this.data.datasets.forEach(function(dataset, i) {
                                        var meta = chartInstance.controller.getDatasetMeta(i);
                                        meta.data.forEach(function(bar, index) {
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
                                text: ''
                            },
                            tooltips: {
                                "enabled": true
                            },
                            responsive: true,
                            maintainAspectRatio: false,
                            plugins: [{
                                beforeDraw: function(c) {
                                    var chartHeight = c.chart.height;
                                    c.scales['x-axis-0'].options.ticks.fontSize = chartHeight * 4 / 100;
                                }
                            }],
                            scales: {
                                xAxes: [{
                                    display: true,
                                    scaleLabel: {
                                        display: true,
                                        labelString: 'Diagnosis'
                                    }
                                }],
                                yAxes: [{
                                    display: true,
                                    scaleLabel: {
                                        display: true,
                                        labelString: 'Average of all Diagnosis by all Diagnosis for this time period' /* Antibiotics Days */
                                    },
                                    ticks: {
                                        min: 0
                                    }
                                }]
                            }
                        }
                    });
                } else {
                    $('#canvas322').remove();
                    $("#msg").html("<div class='alert alert-danger'>Records not found</div>");
                }
            },
            error: function(error, ror, r) {
                $("#msg").html("<div class='alert alert-danger'>Records not found</div>");
            },
        });
    }




    /** canvas33 **/
    function get_dx_by_actual_dots_pie(careUnit, rx, date1, date2, symptom_onset,criteria_met,md_stayward_response,culture_source,date_of_start_abx2,date_of_start_abx3,organism,precautions) {
        //var careUnit = $("#careUnit").val();
        var careUnit = $("#careUnit").val();
        var steward = $("#steward").val();
        var RX = $("#RX").val();
        var provider_doctor = $("#provider_doctor").val();
        var url = "<?php echo base_url() ?>reportsSummary/get_dx_by_actual_dots_new";
        $.ajax({
            method: "POST",
            url: url,
            data: {
                careUnit: careUnit,
                rx: RX,
                steward: steward,
                provider_doctor: provider_doctor,
                date1: date1,
                date2: date2,
                symptom_onset: symptom_onset,
                criteria_met: criteria_met,
                md_stayward_response: md_stayward_response,
                culture_source:culture_source,
                date_of_start_abx2:date_of_start_abx2,
                date_of_start_abx3:date_of_start_abx3,
                organism:organism,
                precautions:precautions,
                
                
            },
            dataType: "json",
            success: function(response) {
                if (response.status == 200) {
                    //console.log(response.antibiotic);
                    var ChartGraphName = [];
                    var ChartGraphPercentage = [];
                    var rxData = [];
                    var label = [];
                    var backgroundColor = [];
                    $('#canvas33').remove(); // this is my <canvas> element
                    $('#Graph-chart33').append('<canvas id="canvas33"><canvas>');
                    var ctx = document.getElementById("canvas33");
                    var data = {
                        labels: response.labels,
                        datasets: [{
                            backgroundColor: response.color,
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
                                text: 'Diagnosis'
                            }
                        },
                    });
                } else {
                    $('#canvas33').remove();
                    $("#msg").html("<div class='alert alert-danger'>Records not found</div>");
                }
            },
            error: function(error, ror, r) {
                $("#msg").html("<div class='alert alert-danger'>Records not found</div>");
            },
        });

        //actual_dot_vs_new_dot_by_care_unit_md_steward();
    }

    /** canvas34 **/
    function total_abx_days(careUnit, rx, date1, date2, symptom_onset,criteria_met,md_stayward_response,culture_source,date_of_start_abx,date_of_start_abx1,date_of_start_abx2,date_of_start_abx3,organism,precautions) {
        var careUnit = $("#careUnit").val();
        var steward = $("#steward").val();
        var RX = $("#RX").val();
        var provider_doctor = $("#provider_doctor").val();
        var url = "<?php echo base_url() ?>reportsSummary/total_abx_days";
        $.ajax({
            method: "POST",
            url: url,
            data: {
                careUnit: careUnit,
                rx: RX,
                steward: steward,
                provider_doctor: provider_doctor,
                date1: date1,
                date2: date2,
                symptom_onset: symptom_onset,
                criteria_met: criteria_met,
                md_stayward_response: md_stayward_response,
                culture_source:culture_source,
                date_of_start_abx:date_of_start_abx,
                date_of_start_abx1:date_of_start_abx1,
                date_of_start_abx2:date_of_start_abx2,
                date_of_start_abx3:date_of_start_abx3,
                organism:organism,
                precautions:precautions,
                

                
            },
            dataType: "json",
            success: function(response) {
                if (response.status == 200) {
                    //console.log(response.antibiotic);
                    var ChartGraphName = [];
                    var ChartGraphPercentage = [];

                    response.antibiotic.forEach(element => {
                        ChartGraphName.push([element.name]);
                        ChartGraphPercentage.push(element.percentage);
                    });

                    $('#canvas34').remove(); // this is my <canvas> element
                    $('#Graph-chart34').append('<canvas id="canvas34"><canvas>');
                    var ctx = document.getElementById("canvas34");
                    var data = {
                        // labels: ['Total Antibiotics'],
                    labels: ['Total Antibiotic Days on Therapy'],
                        datasets: [{
                            label: "",
                            backgroundColor: ['#1bbae1'],
                            data: response.total_days
                        }]
                    };
                    var myChart = new Chart(ctx, {
                        type: 'bar',
                        data: data,
                        options: {
                            "hover": {
                                "animationDuration": 0
                            },
                            "animation": {
                                "onComplete": function() {
                                    var chartInstance = this.chart,
                                        ctx = chartInstance.ctx;

                                    ctx.font = Chart.helpers.fontString(Chart.defaults.global.defaultFontSize, Chart.defaults.global.defaultFontStyle, Chart.defaults.global.defaultFontFamily);
                                    ctx.textAlign = 'center';
                                    ctx.textBaseline = 'bottom';

                                    this.data.datasets.forEach(function(dataset, i) {
                                        var meta = chartInstance.controller.getDatasetMeta(i);
                                        meta.data.forEach(function(bar, index) {
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
                                text: ''
                            },
                            tooltips: {
                                "enabled": true
                            },
                            responsive: true,
                            maintainAspectRatio: false,
                            plugins: [{
                                beforeDraw: function(c) {
                                    var chartHeight = c.chart.height;
                                    c.scales['x-axis-0'].options.ticks.fontSize = chartHeight * 4 / 100;
                                }
                            }],
                            scales: {
                                xAxes: [{
                                    display: true,
                                    scaleLabel: {
                                        display: true,
                                        labelString: 'Total Antibiotics'
                                    }
                                }],
                                yAxes: [{
                                    display: true,
                                    scaleLabel: {
                                        display: true,
                                        labelString: /* Total Days*/ 'Total Antibiotic Days on Therapy '
                                    },
                                    ticks: {
                                        min: 0
                                    }
                                }]
                            }
                        }
                    });
                } else {
                    $('#canvas34').remove();
                    $("#msg").html("<div class='alert alert-danger'>Records not found</div>");
                }
            },
            error: function(error, ror, r) {
                $("#msg").html("<div class='alert alert-danger'>Records not found</div>");
            },
        });
    }

    /** canvas35 **/
    /** canvas34 **/
    function total_abx_days_pie(careUnit, rx, date1, date2, symptom_onset,criteria_met,md_stayward_response,culture_source,date_of_start_abx,date_of_start_abx1,date_of_start_abx2,date_of_start_abx3,organism,precautions) {
        var careUnit = $("#careUnit").val();
        var steward = $("#steward").val();
        var RX = $("#RX").val();
        var provider_doctor = $("#provider_doctor").val();
        var url = "<?php echo base_url() ?>reportsSummary/total_abx_days";
        $.ajax({
            method: "POST",
            url: url,
            data: {
                careUnit: careUnit,
                rx: RX,
                steward: steward,
                provider_doctor: provider_doctor,
                date1: date1,
                date2: date2,
                symptom_onset: symptom_onset,
                criteria_met: criteria_met,
                md_stayward_response: md_stayward_response,
                culture_source:culture_source,
                date_of_start_abx:date_of_start_abx,
                date_of_start_abx1:date_of_start_abx1,
                date_of_start_abx2:date_of_start_abx2,
                date_of_start_abx3:date_of_start_abx3,
                organism:organism,
                precautions:precautions,
                
                
            },
            dataType: "json",
            success: function(response) {
                if (response.status == 200) {
                    //console.log(response.antibiotic);
                    var ChartGraphName = [];
                    var ChartGraphPercentage = [];

                    response.antibiotic.forEach(element => {
                        ChartGraphName.push([element.name]);
                        ChartGraphPercentage.push(element.percentage);
                    });

                    $('#canvas35').remove(); // this is my <canvas> element
                    $('#Graph-chart35').append('<canvas id="canvas35"><canvas>');
                    var ctx = document.getElementById("canvas35");
                    var data = {
                        labels: ['Total Antibiotic Days on Therapy' /* Total Antibiotics */ , 'Total Steward Antibiotic Days On Therapy' /* Actual DOT */ ],
                        datasets: [{
                            label: "",
                            backgroundColor: ['#1bbae1', '#d9416c'],
                            data: response.total_days
                        }]
                    };
                    var myChart = new Chart(ctx, {
                        type: 'bar',
                        data: data,
                        options: {
                            "hover": {
                                "animationDuration": 0
                            },
                            "animation": {
                                "onComplete": function() {
                                    var chartInstance = this.chart,
                                        ctx = chartInstance.ctx;

                                    ctx.font = Chart.helpers.fontString(Chart.defaults.global.defaultFontSize, Chart.defaults.global.defaultFontStyle, Chart.defaults.global.defaultFontFamily);
                                    ctx.textAlign = 'center';
                                    ctx.textBaseline = 'bottom';

                                    this.data.datasets.forEach(function(dataset, i) {
                                        var meta = chartInstance.controller.getDatasetMeta(i);
                                        meta.data.forEach(function(bar, index) {
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
                                "enabled": true
                            },
                            responsive: true,
                            maintainAspectRatio: false,
                            plugins: [{
                                beforeDraw: function(c) {
                                    var chartHeight = c.chart.height;
                                    c.scales['x-axis-0'].options.ticks.fontSize = chartHeight * 4 / 100;
                                }
                            }],
                            scales: {
                                xAxes: [{
                                    display: true,
                                    scaleLabel: {
                                        display: true,
                                        // labelString: 'Total Antibiotics'
                                    }
                                }],
                                yAxes: [{
                                    display: true,
                                    scaleLabel: {
                                        display: true,
                                        labelString: 'Total Days On Therapy'
                                    },
                                    ticks: {
                                        min: 0
                                    }
                                }]
                            }
                        }
                    });
                } else {
                    $('#canvas35').remove();
                    $("#msg").html("<div class='alert alert-danger'>Records not found</div>");
                }
            },
            error: function(error, ror, r) {
                $("#msg").html("<div class='alert alert-danger'>Records not found</div>");
            },
        });
    }





    /** canvas46**/
    function total_abx_days_dollar(careUnit, rx, date1, date2, symptom_onset,criteria_met,md_stayward_response,culture_source,date_of_start_abx2,date_of_start_abx3,organism,precautions) {
        var careUnit = $("#careUnit").val();
        var steward = $("#steward").val();
        var RX = $("#RX").val();
        var provider_doctor = $("#provider_doctor").val();
        var url = "<?php echo base_url() ?>reportsSummary/total_abx_days_dollar";
        $.ajax({
            method: "POST",
            url: url,
            data: {
                careUnit: careUnit,
                rx: RX,
                steward: steward,
                provider_doctor: provider_doctor,
                date1: date1,
                date2: date2,
                symptom_onset: symptom_onset,
                criteria_met: criteria_met,
                md_stayward_response: md_stayward_response,
                culture_source:culture_source,
                date_of_start_abx2:date_of_start_abx2,
                date_of_start_abx3:date_of_start_abx3,
                organism:organism,
                precautions:precautions,
                
                
            },
            dataType: "json",
            success: function(response) {
                if (response.status == 200) {
                    //console.log(response.antibiotic);
                    var ChartGraphName = [];
                    var ChartGraphPercentage = [];

                    response.antibiotic.forEach(element => {
                        ChartGraphName.push([element.name]);
                        ChartGraphPercentage.push(element.percentage);
                    });

                    $('#canvas46').remove(); // this is my <canvas> element
                    $('#Graph-chart46').append('<canvas id="canvas46"><canvas>');
                    var ctx = document.getElementById("canvas46");
                    var data = {
                        labels: ['Total Antibiotic Dollars on Therapy' /* Total Antibiotics */ , 'Total Steward Dollars on Therapy' /* Actual DOT */ ],
                        datasets: [{
                            label: "",
                            backgroundColor: ['#1bbae1', '#d9416c'],
                            data: response.total_amount
                        }]
                    };
                    var myChart = new Chart(ctx, {
                        type: 'bar',
                        data: data,
                        options: {
                            "hover": {
                                "animationDuration": 0
                            },
                            "animation": {
                                "onComplete": function() {
                                    var chartInstance = this.chart,
                                        ctx = chartInstance.ctx;

                                    ctx.font = Chart.helpers.fontString(Chart.defaults.global.defaultFontSize, Chart.defaults.global.defaultFontStyle, Chart.defaults.global.defaultFontFamily);
                                    ctx.textAlign = 'center';
                                    ctx.textBaseline = 'bottom';

                                    this.data.datasets.forEach(function(dataset, i) {
                                        var meta = chartInstance.controller.getDatasetMeta(i);
                                        meta.data.forEach(function(bar, index) {
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
                                "enabled": true
                            },
                            responsive: true,
                            maintainAspectRatio: false,
                            plugins: [{
                                beforeDraw: function(c) {
                                    var chartHeight = c.chart.height;
                                    c.scales['x-axis-0'].options.ticks.fontSize = chartHeight * 4 / 100;
                                }
                            }],
                            scales: {
                                xAxes: [{
                                    display: true,
                                    scaleLabel: {
                                        display: true,
                                        // labelString: 'Total Antibiotics'
                                    }
                                }],
                                yAxes: [{
                                    display: true,
                                    scaleLabel: {
                                        display: true,
                                        labelString: 'Total Dollars On Therapy Before And After'
                                    },
                                    ticks: {
                                        min: 0
                                    }
                                }]
                            }
                        }
                    });
                } else {
                    $('#canvas46').remove();
                    $("#msg").html("<div class='alert alert-danger'>Records not found</div>");
                }
            },
            error: function(error, ror, r) {
                $("#msg").html("<div class='alert alert-danger'>Records not found</div>");
            },
        });
    }






    /** canvas36 **/
    function getAntibioticByCareUnitProviderNew(careUnit, rx, date1, date2, symptom_onset,criteria_met,md_stayward_response,culture_source,date_of_start_abx2,date_of_start_abx3,organism,precautions) {
        var careUnit = $("#careUnit").val();
        var steward = $("#steward").val();
        var RX = $("#RX").val();
        var provider_doctor = $("#provider_doctor").val();
        var url = "<?php echo base_url() ?>reportsSummary/get_antibiotic_by_care_unit_provider";
        $.ajax({
            method: "POST",
            url: url,
            data: {
                careUnit: careUnit,
                rx: RX,
                steward: steward,
                provider_doctor: provider_doctor,
                date1: date1,
                date2: date2,
                symptom_onset: symptom_onset,
                criteria_met: criteria_met,
                md_stayward_response: md_stayward_response,
                culture_source:culture_source,
                date_of_start_abx2:date_of_start_abx2,
                date_of_start_abx3:date_of_start_abx3,
                organism:organism,
                precautions:precautions,
                
            },
            dataType: "json",
            success: function(response) {
                if (response.status == 200) {
                    // console.log('hii',response.antibiotic);
                    var ChartGraphName = [];
                    var ChartGraphPercentage = [];
                    var rxData = [];
                    response.antibiotic.forEach(element => {
                        ChartGraphName.push([element.md_name]);
                        ChartGraphPercentage.push(element.rx);
                        //rxData.push(element.data);
                    });
                    response.datasheet.forEach(element => {
                        //console.log(element);
                        rxData.push(element);
                    });
                    $('#canvas36').remove(); // this is my <canvas> element
                    if (ChartGraphName != "") {
                        $('#Graph-chart36').append('<canvas id="canvas36"><canvas>');
                    }
                    //console.log(rxData);
                    var ctx = document.getElementById("canvas36");
                    var data = {
                        labels: ChartGraphName,
                        datasets: [{
                            label: 'Days',
                            data: response.data,
                            backgroundColor: response.backgroundColor,
                            borderWidth: 1
                        }]
                    };
                    var myChart = new Chart(ctx, {
                        type: 'bar',
                        data: data,
                        options: {
                            "hover": {
                                "animationDuration": 0
                            },
                            legend: {
                                "display": false
                            },
                            responsive: true,
                            maintainAspectRatio: false,
                            plugins: [{
                                beforeDraw: function(c) {
                                    var chartHeight = c.chart.height;
                                    c.scales['x-axis-0'].options.ticks.fontSize = chartHeight * 4 / 100;
                                }
                            }],
                            /*  title: {
                                 display: true,
                                 text: response.care_name
                             }, */
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
                                    ticks: {
                                        stepSize: 30,
                                    },

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
                    if (response.provider != null) {
                        provider_md_rest(response.rest_graph);
                    }
                } else {
                    $('#canvas36').remove();
                    $("#msg").html("<div class='alert alert-danger'>Records not found</div>");
                }
            },
            error: function(error, ror, r) {
                $("#msg").html("<div class='alert alert-danger'>Records not found</div>");
            },
        });
    }

    /** canvas37 **/
    function getDaysByProvideANDStewardNew(careUnit, rx, date1, date2, symptom_onset,criteria_met,md_stayward_response,culture_source,date_of_start_abx2,date_of_start_abx3,organism,precautions) {

        //var careUnit = $("#careUnit").val();
        var careUnit = $("#careUnit").val();
        var steward = $("#steward").val();
        var RX = $("#RX").val();
        var provider_doctor = $("#provider_doctor").val();
        var url = "<?php echo base_url() ?>reportsSummary/get_days_provider_and_steward";
        $.ajax({
            method: "POST",
            url: url,
            data: {
                careUnit: careUnit,
                rx: RX,
                steward: steward,
                provider_doctor: provider_doctor,
                date1: date1,
                date2: date2,
                symptom_onset: symptom_onset,
                criteria_met: criteria_met,
                md_stayward_response: md_stayward_response,
                culture_source:culture_source,
                date_of_start_abx2:date_of_start_abx2,
                date_of_start_abx3:date_of_start_abx3,
                organism:organism,
                precautions:precautions,
                
                
            },
            dataType: "json",
            success: function(response) {
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
                    $('#canvas37').remove(); // this is my <canvas> element
                    $('#Graph-chart37').append('<canvas id="canvas37"><canvas>');

                    var ctx = document.getElementById("canvas37");
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
                            duration: 1,
                                "onComplete": function() {
                                    var chartInstance = this.chart,
                                        ctx = chartInstance.ctx;

                                    ctx.font = Chart.helpers.fontString(Chart.defaults.global.defaultFontSize, Chart.defaults.global.defaultFontStyle, Chart.defaults.global.defaultFontFamily);
                                   // ctx.textAlign = 'center';
                                    ctx.textBaseline = 'bottom';
                                   // ctx.font = "9px Open Sans";
                                    this.data.datasets.forEach(function(dataset, i) {
                                        var meta = chartInstance.controller.getDatasetMeta(i);
                                        meta.data.forEach(function(bar, index) {
                                            /* var data = dataset.data[index];
                                            ctx.fillText(data, bar._model.x, bar._model.y - 5); */
                                            ctx.fillStyle = 'black';
                            dataset.data[index] = parseFloat(dataset.data[index]).toFixed(0);
                            var data = dataset.data[index];
                            ctx.save();
                            var random = Math.floor(Math.random() * 2) + 5;
                            ctx.translate(bar._model.x, bar._model.y - random);
                            ctx.rotate(-0.5 * Math.PI);
                            ctx.fillText(data, 0, 7);
                            ctx.restore();
                                        });
                                    });
                                }
                            }, 


                            responsive: true,
                            maintainAspectRatio: false,
                            plugins: [{
                                beforeDraw: function(c) {
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
                                        labelString: 'Days'
                                    },
                                    ticks: {
                                        stepSize: 30,
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
                    $('#canvas37').remove();
                    $("#msg").html("<div class='alert alert-danger'>Records not found</div>");
                }
            },
            error: function(error, ror, r) {
                $("#msg").html("<div class='alert alert-danger'>Records not found</div>");
            },
        });
    }

    /** canvas38 **/
    function get_days_cost(careUnit, rx, date1, date2, symptom_onset,criteria_met,md_stayward_response,culture_source,date_of_start_abx2,date_of_start_abx3,organism,precautions) {
        var careUnit = $("#careUnit").val();
        var steward = $("#steward").val();
        var RX = $("#RX").val();
        var provider_doctor = $("#provider_doctor").val();
        var url = "<?php echo base_url() ?>reportsSummary/get_days_cost";
        $.ajax({
            method: "POST",
            url: url,
            data: {
                careUnit: careUnit,
                rx: RX,
                steward: steward,
                provider_doctor: provider_doctor,
                date1: date1,
                date2: date2,
                symptom_onset: symptom_onset,
                criteria_met: criteria_met,
                md_stayward_response: md_stayward_response,
                culture_source:culture_source,
                date_of_start_abx2:date_of_start_abx2,
                date_of_start_abx3:date_of_start_abx3,
                organism:organism,
                precautions:precautions,
                
                
            },
            dataType: "json",
            success: function(response) {
                if (response.status == 200) {
                    //console.log(response.antibiotic);
                    var ChartGraphName = [];
                    var ChartGraphPercentage = [];

                    $('#canvas38').remove(); // this is my <canvas> element
                    $('#Graph-chart38').append('<canvas id="canvas38"><canvas>');
                    var ctx = document.getElementById("canvas38");
                    var data = {
                        labels: ['Total'],
                        datasets: [{
                            label: "",
                            backgroundColor: ['#1bbae1'],
                            data: response.total_days
                        }]
                    };
                    var myChart = new Chart(ctx, {
                        type: 'bar',
                        data: data,
                        options: {
                            "hover": {
                                "animationDuration": 0
                            },
                            "animation": {
                                "onComplete": function() {
                                    var chartInstance = this.chart,
                                        ctx = chartInstance.ctx;

                                    ctx.font = Chart.helpers.fontString(Chart.defaults.global.defaultFontSize, Chart.defaults.global.defaultFontStyle, Chart.defaults.global.defaultFontFamily);
                                    ctx.textAlign = 'center';
                                    ctx.textBaseline = 'bottom';

                                    this.data.datasets.forEach(function(dataset, i) {
                                        var meta = chartInstance.controller.getDatasetMeta(i);
                                        meta.data.forEach(function(bar, index) {
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
                                text: 'Antibiotic Days Saved'
                            },
                            tooltips: {
                                "enabled": true
                            },
                            responsive: true,
                            maintainAspectRatio: false,
                            plugins: [{
                                beforeDraw: function(c) {
                                    var chartHeight = c.chart.height;
                                    c.scales['x-axis-0'].options.ticks.fontSize = chartHeight * 4 / 100;
                                }
                            }],
                            scales: {
                                xAxes: [{
                                    display: true,
                                    scaleLabel: {
                                        display: true,
                                        labelString: ''
                                    }
                                }],
                                yAxes: [{
                                    display: true,
                                    scaleLabel: {
                                        display: true,
                                        labelString: 'Sum Of All Provider – Sum Of All Steward Days Therapy' /* Antibiotic Days Saved */
                                    },
                                    ticks: {
                                        min: 0
                                    }
                                }]
                            }
                        }
                    });
                } else {
                    $('#canvas37').remove();
                    $("#msg").html("<div class='alert alert-danger'>Records not found</div>");
                }
            },
            error: function(error, ror, r) {
                $("#msg").html("<div class='alert alert-danger'>Records not found</div>");
            },
        });
    }





    /** canvas47 **/
    function get_days_costsavedbysteward(careUnit, rx, date1, date2, symptom_onset,criteria_met,md_stayward_response,culture_source,date_of_start_abx2,date_of_start_abx3,organism,precautions) {
        var careUnit = $("#careUnit").val();
        var steward = $("#steward").val();
        var RX = $("#RX").val();
        var provider_doctor = $("#provider_doctor").val();
        var url = "<?php echo base_url() ?>reportsSummary/get_days_costsavedbysteward";
        $.ajax({
            method: "POST",
            url: url,
            data: {
                careUnit: careUnit,
                rx: RX,
                steward: steward,
                provider_doctor: provider_doctor,
                date1: date1,
                date2: date2,
                symptom_onset: symptom_onset,
                criteria_met: criteria_met,
                md_stayward_response: md_stayward_response,
                culture_source:culture_source,
                date_of_start_abx2:date_of_start_abx2,
                date_of_start_abx3:date_of_start_abx3,
                organism:organism,
                precautions:precautions,
                
            },
            dataType: "json",
            success: function(response) {
                if (response.status == 200) {
                    //console.log(response.antibiotic);
                    var ChartGraphName = [];
                    var ChartGraphPercentage = [];

                    $('#canvas47').remove(); // this is my <canvas> element
                    $('#Graph-chart47').append('<canvas id="canvas47"><canvas>');
                    var ctx = document.getElementById("canvas47");
                    var data = {
                        labels: ['Total Dollars'],
                        datasets: [{
                            label: "",
                            backgroundColor: ['#1bbae1'],
                            data: response.total_amount_saved
                        }]
                    };
                    var myChart = new Chart(ctx, {
                        type: 'bar',
                        data: data,
                        options: {
                            "hover": {
                                "animationDuration": 0
                            },
                            "animation": {
                                "onComplete": function() {
                                    var chartInstance = this.chart,
                                        ctx = chartInstance.ctx;

                                    ctx.font = Chart.helpers.fontString(Chart.defaults.global.defaultFontSize, Chart.defaults.global.defaultFontStyle, Chart.defaults.global.defaultFontFamily);
                                    ctx.textAlign = 'center';
                                    ctx.textBaseline = 'bottom';

                                    this.data.datasets.forEach(function(dataset, i) {
                                        var meta = chartInstance.controller.getDatasetMeta(i);
                                        meta.data.forEach(function(bar, index) {
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
                                text: 'Total Dollars Saved'
                            },
                            tooltips: {
                                "enabled": true
                            },
                            responsive: true,
                            maintainAspectRatio: false,
                            plugins: [{
                                beforeDraw: function(c) {
                                    var chartHeight = c.chart.height;
                                    c.scales['x-axis-0'].options.ticks.fontSize = chartHeight * 4 / 100;
                                }
                            }],
                            scales: {
                                xAxes: [{
                                    display: true,
                                    scaleLabel: {
                                        display: true,
                                        labelString: ''
                                    }
                                }],
                                yAxes: [{
                                    display: true,
                                    scaleLabel: {
                                        display: true,
                                        labelString: 'Total Dollars Saved by Steward' /* Antibiotic Days Saved */
                                    },
                                    ticks: {
                                        min: 0
                                    }
                                }]
                            }
                        }
                    });
                } else {
                    $('#canvas47').remove();
                    $("#msg").html("<div class='alert alert-danger'>Records not found</div>");
                }
            },
            error: function(error, ror, r) {
                $("#msg").html("<div class='alert alert-danger'>Records not found</div>");
            },
        });
    }








    /** canvas39 **/
    function get_cost_cost(careUnit, rx, date1, date2, symptom_onset,criteria_met,md_stayward_response,culture_source,date_of_start_abx2,date_of_start_abx3,organism,precautions) {
        var careUnit1 = $("#careUnit").val();
        var url = "<?php echo base_url() ?>reportsSummary/get_cost_cost";
        $.ajax({
            method: "POST",
            url: url,
            data: {
                careUnit: careUnit,
                rx: RX,
                steward: steward,
                provider_doctor: provider_doctor,
                date1: date1,
                date2: date2,
                symptom_onset: symptom_onset,
                criteria_met: criteria_met,
                md_stayward_response: md_stayward_response,
                culture_source:culture_source,
                date_of_start_abx2:date_of_start_abx2,
                date_of_start_abx3:date_of_start_abx3,
                organism:organism,
                precautions:precautions,
                
                
            },
            dataType: "json",
            success: function(response) {
                if (response.status == 200) {
                    //console.log(response.antibiotic);
                    var ChartGraphName = [];
                    var ChartGraphPercentage = [];

                    $('#canvas39').remove(); // this is my <canvas> element
                    $('#Graph-chart39').append('<canvas id="canvas39"><canvas>');
                    var ctx = document.getElementById("canvas39");
                    var data = {
                        labels: ['Total'],
                        datasets: [{
                            label: "",
                            backgroundColor: ['#008000'],
                            data: response.total_days
                        }]
                    };
                    var myChart = new Chart(ctx, {
                        type: 'bar',
                        data: data,
                        options: {
                            "hover": {
                                "animationDuration": 0
                            },
                            "animation": {
                                "onComplete": function() {
                                    var chartInstance = this.chart,
                                        ctx = chartInstance.ctx;

                                    ctx.font = Chart.helpers.fontString(Chart.defaults.global.defaultFontSize, Chart.defaults.global.defaultFontStyle, Chart.defaults.global.defaultFontFamily);
                                    ctx.textAlign = 'center';
                                    ctx.textBaseline = 'bottom';

                                    this.data.datasets.forEach(function(dataset, i) {
                                        var meta = chartInstance.controller.getDatasetMeta(i);
                                        meta.data.forEach(function(bar, index) {
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
                                text: 'Cost Saved'
                            },
                            tooltips: {
                                "enabled": true
                            },
                            responsive: true,
                            maintainAspectRatio: false,
                            plugins: [{
                                beforeDraw: function(c) {
                                    var chartHeight = c.chart.height;
                                    c.scales['x-axis-0'].options.ticks.fontSize = chartHeight * 4 / 100;
                                }
                            }],
                            scales: {
                                xAxes: [{
                                    display: true,
                                    scaleLabel: {
                                        display: true,
                                        labelString: ''
                                    }
                                }],
                                yAxes: [{
                                    display: true,
                                    scaleLabel: {
                                        display: true,
                                        labelString: 'Cost Saved'
                                    }
                                }]
                            }
                        }
                    });
                } else {
                    $('#canvas39').remove();
                    $("#msg").html("<div class='alert alert-danger'>Records not found</div>");
                }
            },
            error: function(error, ror, r) {
                $("#msg").html("<div class='alert alert-danger'>Records not found</div>");
            },
        });
    }




    /** canvas44 **/
    function getDaysByProvideANDStewardNewinitialandactual(careUnit, rx, date1, date2, symptom_onset,criteria_met,md_stayward_response,culture_source,date_of_start_abx2,date_of_start_abx3,organism,precautions) {

        //var careUnit = $("#careUnit").val();
        var careUnit = $("#careUnit").val();
        var steward = $("#steward").val();
        var RX = $("#RX").val();
        var provider_doctor = $("#provider_doctor").val();
        var url = "<?php echo base_url() ?>reportsSummary/get_days_provider_and_steward_new";
        $.ajax({
            method: "POST",
            url: url,
            data: {
                careUnit: careUnit,
                rx: RX,
                steward: steward,
                provider_doctor: provider_doctor,
                date1: date1,
                date2: date2,
                symptom_onset: symptom_onset,
                criteria_met: criteria_met,
                md_stayward_response: md_stayward_response,
                culture_source:culture_source,
                date_of_start_abx2:date_of_start_abx2,
                date_of_start_abx3:date_of_start_abx3,
                organism:organism,
                precautions:precautions,
                
                
            },
            dataType: "json",
            success: function(response) {
                if (response.status == 200) {
                    var ChartGraphName = [];
                    var ChartGraphPercentage = [];
                    var rxData = [];

                    response.antibiotic.forEach(element => {
                        ChartGraphName.push([element.name]);
                    });
                    response.datasheet.forEach(element => {
                        rxData.push(element);
                    });
                    //console.log(ChartGraphName);
                    //console.log(ChartGraphPercentage);
                    $('#canvas44').remove(); // this is my <canvas> element
                    $('#Graph-chart44').append('<canvas id="canvas44"><canvas>');

                    var ctx = document.getElementById("canvas44");
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
                            duration: 1,
                                "onComplete": function() {
                                    var chartInstance = this.chart,
                                        ctx = chartInstance.ctx;

                                    ctx.font = Chart.helpers.fontString(Chart.defaults.global.defaultFontSize, Chart.defaults.global.defaultFontStyle, Chart.defaults.global.defaultFontFamily);
                                   // ctx.textAlign = 'center';
                                    ctx.textBaseline = 'bottom';
                                   // ctx.font = "10px Open Sans";
                                    this.data.datasets.forEach(function(dataset, i) {
                                        var meta = chartInstance.controller.getDatasetMeta(i);
                                        meta.data.forEach(function(bar, index) {
                                            /* var data = dataset.data[index];
                                            ctx.fillText(data, bar._model.x, bar._model.y - 5); */
                                            ctx.fillStyle = 'black';
                            dataset.data[index] = parseFloat(dataset.data[index]).toFixed(0);
                            var data = dataset.data[index];
                            ctx.save();
                            var random = Math.floor(Math.random() * 2) + 5;
                            ctx.translate(bar._model.x, bar._model.y - random);
                            ctx.rotate(-0.5 * Math.PI);
                            ctx.fillText(data, 0, 7);
                            ctx.restore();
                                        });
                                    });
                                }
                            }, 








                            responsive: true,
                            maintainAspectRatio: false,
                            plugins: [{
                                beforeDraw: function(c) {
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
                                        labelString: 'Antibiotics'
                                    }
                                }],
                                yAxes: [{
                                    display: true,
                                    scaleLabel: {
                                        display: true,
                                        labelString: 'Days'
                                    },
                                    ticks: {
                                        stepSize: 30,
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
                    $('#canvas44').remove();
                    $("#msg").html("<div class='alert alert-danger'>Records not found</div>");
                }
            },
            error: function(error, ror, r) {
                $("#msg").html("<div class='alert alert-danger'>Records not found</div>");
            },
        });
    }




    /** canvas45 **/
    function getDaysByProvideANDStewardNewinitialandactualcost(careUnit, rx, date1, date2, symptom_onset,criteria_met,md_stayward_response,culture_source,date_of_start_abx2,date_of_start_abx3,organism,precautions) {

        //var careUnit = $("#careUnit").val();
        var careUnit = $("#careUnit").val();
        var steward = $("#steward").val();
        var RX = $("#RX").val();
        var provider_doctor = $("#provider_doctor").val();
        var url = "<?php echo base_url() ?>reportsSummary/get_days_provider_and_steward_cost";
        $.ajax({
            method: "POST",
            url: url,
            data: {
                careUnit: careUnit,
                rx: RX,
                steward: steward,
                provider_doctor: provider_doctor,
                date1: date1,
                date2: date2,
                symptom_onset: symptom_onset,
                criteria_met: criteria_met,
                md_stayward_response: md_stayward_response,
                culture_source:culture_source,
                date_of_start_abx2:date_of_start_abx2,
                date_of_start_abx3:date_of_start_abx3,
                organism:organism,
                precautions:precautions,
                
                
            },
            dataType: "json",
            success: function(response) {
                if (response.status == 200) {
                    var ChartGraphName = [];
                    var ChartGraphPercentage = [];
                    var rxData = [];

                    response.antibiotic.forEach(element => {
                        ChartGraphName.push([element.name]);
                    });
                    response.datasheet.forEach(element => {
                        rxData.push(element);
                    });
                    //console.log(ChartGraphName);
                    //console.log(ChartGraphPercentage);
                    $('#canvas45').remove(); // this is my <canvas> element
                    $('#Graph-chart45').append('<canvas id="canvas45"><canvas>');

                    var ctx = document.getElementById("canvas45");
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
                            duration: 1,
                                "onComplete": function() {
                                    var chartInstance = this.chart,
                                        ctx = chartInstance.ctx;

                                    ctx.font = Chart.helpers.fontString(Chart.defaults.global.defaultFontSize, Chart.defaults.global.defaultFontStyle, Chart.defaults.global.defaultFontFamily);
                                   // ctx.textAlign = 'center';
                                    ctx.textBaseline = 'bottom';
                                  //  ctx.font = "9px Open Sans";
                                    this.data.datasets.forEach(function(dataset, i) {
                                        var meta = chartInstance.controller.getDatasetMeta(i);
                                        meta.data.forEach(function(bar, index) {
                                            /* var data = dataset.data[index];
                                            ctx.fillText(data, bar._model.x, bar._model.y - 5); */
                                            ctx.fillStyle = 'black';
                            dataset.data[index] = parseFloat(dataset.data[index]).toFixed(0);
                            var data = dataset.data[index];
                            ctx.save();
                            var random = Math.floor(Math.random() * 2) + 5;
                            ctx.translate(bar._model.x, bar._model.y - random);
                            ctx.rotate(-0.5 * Math.PI);
                            ctx.fillText(data, 0, 7);
                            ctx.restore();
                                        });
                                    });
                                }
                            }, 

                            




                            responsive: true,
                            maintainAspectRatio: false,
                            plugins: [{
                                beforeDraw: function(c) {
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
                                        labelString: 'Antibiotics'
                                    }
                                }],
                                yAxes: [{
                                    display: true,
                                    scaleLabel: {
                                        display: true,
                                        labelString: 'Cost On Therapy'
                                    },
                                    ticks: {
                                        stepSize: 3000,
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
                    $('#canvas45').remove();
                    $("#msg").html("<div class='alert alert-danger'>Records not found</div>");
                }
            },
            error: function(error, ror, r) {
                $("#msg").html("<div class='alert alert-danger'>Records not found</div>");
            },
        });
    }







    function getAntibioticByCareUnitSteward(careUnit, rx) {
        //var careUnit = $("#careUnit").val();
        var careUnit = $("#careUnit").val();
        var steward = $("#steward").val();
        var RX = $("#RX").val();
        var provider_doctor = $("#provider_doctor").val();
        var url = "<?php echo base_url() ?>reportsSummary/get_antibiotic_by_care_unit_md_steward";
        $.ajax({
            method: "POST",
            url: url,
            data: {
                careUnit: careUnit,
                steward: steward,
                rx: rx
            },
            dataType: "json",
            success: function(response) {
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
                    if (ChartGraphName != "") {
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
            error: function(error, ror, r) {
                $("#msg").html("<div class='alert alert-danger'>Records not found</div>");
            },
        });

        actual_dot_vs_new_dot_by_care_unit_md_steward();
    }

    function getAntibioticByCareUnitSteward_oldbarcharts(careUnit, rx) {
        //var careUnit = $("#careUnit").val();
        var steward = $("#steward").val();
        var url = "<?php echo base_url() ?>reportsSummary/get_antibiotic_by_care_unit_md_steward";
        $.ajax({
            method: "POST",
            url: url,
            data: {
                careUnit: careUnit,
                steward: steward,
                rx: rx
            },
            dataType: "json",
            success: function(response) {
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
                    if (ChartGraphName != "") {
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
                                beforeDraw: function(c) {
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
            error: function(error, ror, r) {
                $("#msg").html("<div class='alert alert-danger'>Records not found</div>");
            },
        });

        actual_dot_vs_new_dot_by_care_unit_md_steward();
    }

    function getDaysSavedByCareUnit(careUnit, rx) {
        //var careUnit = $("#careUnit").val();
        var url = "<?php echo base_url() ?>reportsSummary/get_days_saved_by_care_unit";
        $.ajax({
            method: "POST",
            url: url,
            data: {
                careUnit: careUnit,
                rx: rx
            },
            dataType: "json",
            success: function(response) {
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
            error: function(error, ror, r) {
                $("#msg").html("<div class='alert alert-danger'>Records not found</div>");
            },
        });
    }

    function actual_dot_vs_new_dot_by_care_unit(careUnit, rx) {
        //var careUnit = $("#careUnit").val();
        var url = "<?php echo base_url() ?>reportsSummary/actual_dot_vs_new_dot_by_care_unit";
        $.ajax({
            method: "POST",
            url: url,
            data: {
                careUnit: careUnit,
                rx: rx
            },
            dataType: "json",
            success: function(response) {
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
            error: function(error, ror, r) {
                $("#msg").html("<div class='alert alert-danger'>Records not found</div>");
            },
        });
    }

    function actual_dot_vs_new_dot_by_care_unit_md_steward(careUnit, rx) {
        //var careUnit = $("#careUnit").val();
        var steward = $("#steward").val();
        var url = "<?php echo base_url() ?>reportsSummary/actual_dot_vs_new_dot_by_care_unit_md_steward";
        $.ajax({
            method: "POST",
            url: url,
            data: {
                careUnit: careUnit,
                steward: steward,
                rx: rx
            },
            dataType: "json",
            success: function(response) {
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
                                beforeDraw: function(c) {
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
            error: function(error, ror, r) {
                $("#msg").html("<div class='alert alert-danger'>Records not found</div>");
            },
        });
    }

    function actual_dot_vs_new_dot_by_care_unit_provider_doctor(careUnit, rx) {
        //var careUnit = $("#careUnit").val();
        var provider_doctor = $("#provider_doctor").val();
        var url = "<?php echo base_url() ?>reportsSummary/actual_dot_vs_new_dot_by_care_unit_provider_doctor";
        $.ajax({
            method: "POST",
            url: url,
            data: {
                careUnit: careUnit,
                provider_doctor: provider_doctor,
                rx: rx
            },
            dataType: "json",
            success: function(response) {
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
                                beforeDraw: function(c) {
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
            error: function(error, ror, r) {
                $("#msg").html("<div class='alert alert-danger'>Records not found</div>");
            },
        });
    }

    function get_rx_by_actual_dots(careUnit, rx) {
        var careUnit1 = $("#careUnit").val();
        var url = "<?php echo base_url() ?>reportsSummary/get_rx_by_actual_dots";
        $.ajax({
            method: "POST",
            url: url,
            data: {
                careUnit: careUnit,
                rx: rx
            },
            dataType: "json",
            success: function(response) {
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
                        datasets: [{
                            label: "%",
                            backgroundColor: ["#3e95cd", "#58aed1", "#374f2f", "#644ab8", "#c45850"],
                            data: ChartGraphPercentage
                        }]
                    };
                    var myChart = new Chart(ctx, {
                        type: 'bar',
                        data: data,
                        options: {
                            "hover": {
                                "animationDuration": 0
                            },
                            "animation": {
                                "onComplete": function() {
                                    var chartInstance = this.chart,
                                        ctx = chartInstance.ctx;

                                    ctx.font = Chart.helpers.fontString(Chart.defaults.global.defaultFontSize, Chart.defaults.global.defaultFontStyle, Chart.defaults.global.defaultFontFamily);
                                    ctx.textAlign = 'center';
                                    ctx.textBaseline = 'bottom';

                                    this.data.datasets.forEach(function(dataset, i) {
                                        var meta = chartInstance.controller.getDatasetMeta(i);
                                        meta.data.forEach(function(bar, index) {
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
                                beforeDraw: function(c) {
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
            error: function(error, ror, r) {
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
            data: {
                filterval: filterval
            },
            dataType: "json",
            success: function(response) {
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
                    var chartMonths = [
                        [1, 'Jan'],
                        [2, 'Feb'],
                        [3, 'Mar'],
                        [4, 'Apr'],
                        [5, 'May'],
                        [6, 'Jun'],
                        [7, 'Jul'],
                        [8, 'Aug'],
                        [9, 'Sep'],
                        [10, 'Oct'],
                        [11, 'Nov'],
                        [12, 'Dec']
                    ];

                    // Overview Chart
                    $.plot(chartOverview,
                        [{
                            label: 'Patient',
                            data: dataEarnings,
                            lines: {
                                show: true,
                                fill: true,
                                fillColor: {
                                    colors: [{
                                        opacity: 0.25
                                    }, {
                                        opacity: 0.25
                                    }]
                                }
                            },
                            points: {
                                show: true,
                                radius: 6
                            }
                        }], {
                            colors: ['#1bbae1', '#333333', '#d9416c'],
                            legend: {
                                show: true,
                                position: 'nw',
                                margin: [15, 10]
                            },
                            grid: {
                                borderWidth: 0,
                                hoverable: true,
                                clickable: true
                            },
                            yaxis: {
                                ticks: 3,
                                tickColor: '#f1f1f1'
                            },
                            xaxis: {
                                ticks: chartMonths,
                                tickColor: '#ffffff'
                            }
                        }
                    );

                    // Creating and attaching a tooltip to the classic chart
                    var previousPoint = null,
                        ttlabel = null;
                    chartOverview.bind('plothover', function(event, pos, item) {

                        if (item) {
                            if (previousPoint !== item.dataIndex) {
                                previousPoint = item.dataIndex;

                                $('#chart-tooltip').remove();
                                var x = item.datapoint[0],
                                    y = item.datapoint[1];


                                ttlabel = '<strong>' + y + '</strong> Patient';


                                $('<div id="chart-tooltip" class="chart-tooltip">' + ttlabel + '</div>')
                                    .css({
                                        top: item.pageY - 45,
                                        left: item.pageX + 5
                                    }).appendTo("body").show();
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
            error: function(error, ror, r) {
                $("#msg").html("<div class='alert alert-danger'>Records not found</div>");
            },
        });
    }

    function downloadPDF21(id, name) {
        var canvasImg = document.getElementById(id).toDataURL("image/png", 1.0);
        var doc = new jsPDF('landscape');
        doc.setFontSize(13);
        doc.setFillColor(135, 124, 45, 0);
        doc.text(120, 10, 'Total Antibiotic Days by Provider');
        doc.addImage(canvasImg, 'png', 10, 10, 280, 160);
        doc.save(name + '.pdf');
    }

    function downloadPDF30(id, name) {
var canvasImg = document.getElementById(id).toDataURL("image/png", 1.0);
var doc = new jsPDF('landscape');
doc.setFontSize(13);
doc.setFillColor(135, 124, 45, 0);
doc.text(100, 10, 'Total Antibiotic Days by Provider- Pie Chart');
doc.addImage(canvasImg, 'png', 10, 10, 280, 160);
doc.save(name + '.pdf');
}


function downloadPDF43(id, name) {
var canvasImg = document.getElementById(id).toDataURL("image/png", 1.0);
var doc = new jsPDF('landscape');
doc.setFontSize(13);
doc.setFillColor(135, 124, 45, 0);
doc.text(115, 10, 'Total Antibiotic Days by Antibiotic');
doc.addImage(canvasImg, 'png', 10, 10, 280, 160);
doc.save(name + '.pdf');
}

function downloadPDF42(id, name) {
var canvasImg = document.getElementById(id).toDataURL("image/png", 1.0);
var doc = new jsPDF('landscape');
doc.setFontSize(13);
doc.setFillColor(135, 124, 45, 0);
doc.text(100, 10, 'Total Initial Days On Therapy by Antibiotic-Pie Chart');
doc.addImage(canvasImg, 'png', 10, 10, 280, 160);
doc.save(name + '.pdf');
}

function downloadPDF32(id, name) {
var canvasImg = document.getElementById(id).toDataURL("image/png", 1.0);
var doc = new jsPDF('landscape');
doc.setFontSize(13);
doc.setFillColor(135, 124, 45, 0);
doc.text(110, 10, 'Total Antibiotic Days by Diagnosis');
doc.addImage(canvasImg, 'png', 10, 10, 280, 160);
doc.save(name + '.pdf');
}

function downloadPDF33(id, name) {
var canvasImg = document.getElementById(id).toDataURL("image/png", 1.0);
var doc = new jsPDF('landscape');
doc.setFontSize(13);
doc.setFillColor(135, 124, 45, 0);
doc.text(100, 10, 'Total Antibiotic Days by Diagnosis-Pie Chart');
doc.addImage(canvasImg, 'png', 10, 10, 280, 160);
doc.save(name + '.pdf');
}


function downloadPDF49(id, name) {
var canvasImg = document.getElementById(id).toDataURL("image/png", 1.0);
var doc = new jsPDF('landscape');
doc.setFontSize(13);
doc.setFillColor(135, 124, 45, 0);
doc.text(120, 10, 'Provider and Steward Agreement');
doc.addImage(canvasImg, 'png', 10, 10, 280, 160);
doc.save(name + '.pdf');
}

function downloadPDF34(id, name) {
var canvasImg = document.getElementById(id).toDataURL("image/png", 1.0);
var doc = new jsPDF('landscape');
doc.setFontSize(13);
doc.setFillColor(135, 124, 45, 0);
doc.text(125, 10, 'Total Antibiotic Days on Therapy');
doc.addImage(canvasImg, 'png', 10, 10, 280, 160);
doc.save(name + '.pdf');
}

function downloadPDF35(id, name) {
var canvasImg = document.getElementById(id).toDataURL("image/png", 1.0);
var doc = new jsPDF('landscape');
doc.setFontSize(13);
doc.setFillColor(135, 124, 45, 0);
doc.text(80, 10, 'Total Antibiotic Days on Therapy vs. Steward Antibiotic Days on Therapy');
doc.addImage(canvasImg, 'png', 10, 10, 280, 160);
doc.save(name + '.pdf');
}

function downloadPDF37(id, name) {
var canvasImg = document.getElementById(id).toDataURL("image/png", 1.0);
var doc = new jsPDF('landscape');
doc.setFontSize(13);
doc.setFillColor(135, 124, 45, 0);
doc.text(90, 10, 'Total Antibiotic Days on Therapy:  Provider vs. Steward');
doc.addImage(canvasImg, 'png', 10, 10, 280, 160);
doc.save(name + '.pdf');
}

function downloadPDF38(id, name) {
var canvasImg = document.getElementById(id).toDataURL("image/png", 1.0);
var doc = new jsPDF('landscape');
doc.setFontSize(13);
doc.setFillColor(135, 124, 45, 0);
doc.text(125, 10, 'Total Antibiotic Days Saved');
doc.addImage(canvasImg, 'png', 10, 10, 280, 160);
doc.save(name + '.pdf');
}

function downloadPDF46(id, name) {
var canvasImg = document.getElementById(id).toDataURL("image/png", 1.0);
var doc = new jsPDF('landscape');
doc.setFontSize(13);
doc.setFillColor(135, 124, 45, 0);
doc.text(80, 10, 'Total Antibiotic Dollars on Therapy vs. Steward Dollars on Therapy');
doc.addImage(canvasImg, 'png', 10, 10, 280, 160);
doc.save(name + '.pdf');
}

function downloadPDF47(id, name) {
var canvasImg = document.getElementById(id).toDataURL("image/png", 1.0);
var doc = new jsPDF('landscape');
doc.setFontSize(13);
doc.setFillColor(135, 124, 45, 0);
doc.text(130, 10, 'Total Dollars Saved');
doc.addImage(canvasImg, 'png', 10, 10, 280, 160);
doc.save(name + '.pdf');
}

function downloadPDF44(id, name) {
var canvasImg = document.getElementById(id).toDataURL("image/png", 1.0);
var doc = new jsPDF('landscape');
doc.setFontSize(13);
doc.setFillColor(135, 124, 45, 0);
doc.text(100, 10, 'Antibiotic Days on Therapy:  Provider vs. Steward');
doc.addImage(canvasImg, 'png', 10, 10, 280, 160);
doc.save(name + '.pdf');
}

function downloadPDF45(id, name) {
var canvasImg = document.getElementById(id).toDataURL("image/png", 1.0);
var doc = new jsPDF('landscape');
doc.setFontSize(13);
doc.setFillColor(135, 124, 45, 0);
doc.text(100, 10, 'Antibiotic Dollars on Therapy:  Provider vs. Steward');
doc.addImage(canvasImg, 'png', 10, 10, 280, 160);
doc.save(name + '.pdf');
}

function downloadPDF211(id, name) {
var canvasImg = document.getElementById(id).toDataURL("image/png", 1.0);
var doc = new jsPDF('landscape');
doc.setFontSize(13);
doc.setFillColor(135, 124, 45, 0);
doc.text(30, 10, 'Average Antibiotic Days by Provider');
doc.addImage(canvasImg, 'png', 10, 10, 80, 180);
doc.save(name + '.pdf');
}

function downloadPDF433(id, name) {
var canvasImg = document.getElementById(id).toDataURL("image/png", 1.0);
var doc = new jsPDF('landscape');
doc.setFontSize(13);
doc.setFillColor(135, 124, 45, 0);
doc.text(30, 10, 'Average Antibiotic Days by Antibiotic');
doc.addImage(canvasImg, 'png', 10, 10, 80, 180);
doc.save(name + '.pdf');
}

function downloadPDF322(id, name) {
var canvasImg = document.getElementById(id).toDataURL("image/png", 1.0);
var doc = new jsPDF('landscape');
doc.setFontSize(13);
doc.setFillColor(135, 124, 45, 0);
doc.text(30, 10, 'Average Antibiotic Days by Diagnosis');
doc.addImage(canvasImg, 'png', 10, 10, 80, 180);
doc.save(name + '.pdf');
}


    function downloadPDF22(canvas21,canvas43,canvas32,canvas49,canvas34,canvas35,canvas37,canvas38,canvas46,canvas47,canvas44,canvas45, name) {
       
var canvasImg = document.getElementById("canvas21").toDataURL("image/png", 1.0);
var canvasImg2 = document.getElementById("canvas43").toDataURL("image/png", 1.0);
var canvasImg4 = document.getElementById("canvas32").toDataURL("image/png", 1.0);
var canvasImg6 = document.getElementById("canvas49").toDataURL("image/png", 1.0);
var canvasImg7 = document.getElementById("canvas34").toDataURL("image/png", 1.0);
var canvasImg8 = document.getElementById("canvas35").toDataURL("image/png", 1.0);
var canvasImg9 = document.getElementById("canvas37").toDataURL("image/png", 1.0);
var canvasImg10 = document.getElementById("canvas38").toDataURL("image/png", 1.0);
var canvasImg11 = document.getElementById("canvas46").toDataURL("image/png", 1.0);
var canvasImg12 = document.getElementById("canvas47").toDataURL("image/png", 1.0);
var canvasImg13 = document.getElementById("canvas44").toDataURL("image/png", 1.0);
var canvasImg14 = document.getElementById("canvas45").toDataURL("image/png", 1.0);
var canvasImg15 = document.getElementById("canvas211").toDataURL("image/png", 1.0);

var doc = new jsPDF('landscape');
doc.setFontSize(13);
doc.setFillColor(135, 124, 45, 0);
doc.text(125, 10, 'Total Antibiotic Days on Therapy');
doc.addImage(canvasImg7, 'png', 10, 10, 280, 160);
doc.addPage();
doc.text(90, 10, 'Total Antibiotic Days on Therapy vs. Steward Antibiotic Days on Therapy');
doc.addImage(canvasImg8, 'png', 10, 10, 280, 160);
doc.addPage();
doc.text(120, 10, 'Total Antibiotic Days by Provider');
doc.addImage(canvasImg, 'png', 10, 10, 280, 160);
doc.addPage();
doc.text(30, 10, 'Average Antibiotic Days by Provider');
doc.addImage(canvasImg15, 'png', 10, 10, 80, 160);
doc.addPage();
doc.text(115, 10, 'Total Antibiotic Days by Antibiotic');
doc.addImage(canvasImg2, 'png', 10, 10, 280, 160);
doc.addPage();
doc.text(110, 10, 'Total Antibiotic Days by Diagnosis');
doc.addImage(canvasImg4, 'png', 10, 10, 280, 160);
doc.addPage();
doc.text(100, 10, 'Provider and Steward Agreement');
doc.addImage(canvasImg6, 'png', 10, 10, 280, 160);
doc.addPage();
/* doc.text(125, 10, 'Total Antibiotic Days on Therapy');
doc.addImage(canvasImg7, 'png', 10, 10, 280, 160);
doc.addPage();
doc.text(100, 10, 'Total Antibiotic Days on Therapy vs. Steward Antibiotic Days on Therapy');
doc.addImage(canvasImg8, 'png', 10, 10, 280, 160);
doc.addPage(); */
doc.text(90, 10, 'Total Antibiotic Days on Therapy:  Provider vs. Steward');
doc.addImage(canvasImg9, 'png', 10, 10, 280, 160);
doc.addPage();
doc.text(125, 10, 'Total Antibiotic Days Saved');
doc.addImage(canvasImg10, 'png', 10, 10, 280, 160);
doc.addPage();
doc.text(80, 10, 'Total Antibiotic Dollars on Therapy vs. Steward Dollars on Therapy');
doc.addImage(canvasImg11, 'png', 10, 10, 280, 160);
doc.addPage();
doc.text(125, 10, 'Total Dollars Saved');
doc.addImage(canvasImg12, 'png', 10, 10, 280, 160);
doc.addPage();
doc.text(110, 10, 'Antibiotic Days on Therapy:  Provider vs. Steward');
doc.addImage(canvasImg13, 'png', 10, 10, 280, 160);
doc.addPage();
doc.text(110, 10, 'Antibiotic Dollars on Therapy:  Provider vs. Steward');
doc.addImage(canvasImg14, 'png', 10, 10, 280, 160);

doc.save('Report' + '.pdf');
}


function downloadPDF23(canvas21,canvas43,canvas34,canvas211,canvas32, name) {
       
    var canvasImg = document.getElementById("canvas21").toDataURL("image/png", 1.0);
    var canvasImg2 = document.getElementById("canvas43").toDataURL("image/png", 1.0);
    var canvasImg7 = document.getElementById("canvas34").toDataURL("image/png", 1.0);
    var canvasImg15 = document.getElementById("canvas211").toDataURL("image/png", 1.0);
    var canvasImg8 = document.getElementById("canvas32").toDataURL("image/png", 1.0);

    var doc = new jsPDF('landscape');
    doc.setFontSize(13);
    doc.setFillColor(135, 124, 45, 0);
    doc.text(125, 10, 'Total Antibiotic Days on Therapy');
    doc.addImage(canvasImg7, 'png', 10, 10, 280, 160);
    doc.addPage();
    doc.text(120, 10, 'Total Antibiotic Days by Provider');
    doc.addImage(canvasImg, 'png', 10, 10, 280, 160);
    doc.addPage();
    doc.text(30, 10, 'Average Antibiotic Days by Provider');
    doc.addImage(canvasImg15, 'png', 10, 10, 80, 160);
    doc.addPage();
    doc.text(115, 10, 'Total Antibiotic Days by Antibiotic');
    doc.addImage(canvasImg2, 'png', 10, 10, 280, 160);
    doc.addPage();
    doc.text(110, 10, 'Total Antibiotic Days by Diagnosis');
    doc.addImage(canvasImg8, 'png', 10, 10, 280, 160);
    
    doc.save('MonthlyReport' + '.pdf');
    }




    function getTables(filterval) {

        var url = "<?php echo base_url() ?>pwfpanel/getTablesVendor";
        $.ajax({
            method: "POST",
            url: url,
            data: {
                filterval: filterval
            },
            success: function(response) {
                $("#getTablesVendor").html(response);
            }
        });
        $.ajax({
            method: "POST",
            url: "<?php echo base_url() ?>pwfpanel/getTablesUsers",
            data: {
                filterval: filterval
            },
            success: function(response) {
                $("#getTablesUsers").html(response);
            }
        });
        $.ajax({
            method: "POST",
            url: "<?php echo base_url() ?>pwfpanel/getTablesEnquiries",
            data: {
                filterval: filterval
            },
            success: function(response) {
                $("#getTablesEnquiries").html(response);
            }
        });
    }

    new Chart(document.getElementById("pie-chart"), {
        type: 'pie',
        data: {
            labels: ["Fever", ""],
            datasets: [{
                label: "Actual Days of Therapy",
                backgroundColor: ["#3e95cd", "#8e5ea2"],
                data: [100, 50]
            }]
        },
        options: {
            title: {
                display: true,
                text: 'Actual Days of Therapy'
            }
        }
    });

    new Chart(document.getElementById("bar-chart-horizontal"), {
        type: 'horizontalBar',
        data: {
            labels: ["Fever", "Sepsis", "UTI", "HP", "IRT"],
            datasets: [{
                label: "Actual Days of Therapy",
                backgroundColor: ["#3e95cd", "#8e5ea2", "#3cba9f", "#e8c3b9", "#c45850"],
                data: [2478, 5267, 734, 784, 433]
            }]
        },
        options: {
            legend: {
                display: false
            },
            title: {
                display: true,
                text: 'Actual Days of Therapy'
            }
        }
    });

    new Chart(document.getElementById("bar-chart-grouped"), {
        type: 'bar',
        data: {
            labels: ["1900", "1950", "1999", "2050"],
            datasets: [{
                label: "Test1",
                backgroundColor: "#3e95cd",
                data: [133, 221, 783, 2478]
            }, {
                label: "Test2",
                backgroundColor: "#8e5ea2",
                data: [408, 547, 675, 734]
            }]
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
            data: {
                filterval: 0
            },
            success: function(response) {
                location.reload();
            }
        });
    }


    function getAntibioticByCareUnitProviderId() {
        var provider = $("#provider_doctor").val();
        var careUnit = $("#careUnit").val();
        var rx = $("#RX").val();
        var url = "<?php echo base_url() ?>reportsSummary/get_antibiotic_by_care_unit_provider_id";
        $.ajax({
            method: "POST",
            url: url,
            data: {
                careUnit: careUnit,
                provider: provider,
                rx: rx
            },
            dataType: "json",
            success: function(response) {
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
                    if (rxData != "") {
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
                                "onComplete": function() {
                                    var chartInstance = this.chart,
                                        ctx = chartInstance.ctx;

                                    ctx.font = Chart.helpers.fontString(Chart.defaults.global.defaultFontSize, Chart.defaults.global.defaultFontStyle, Chart.defaults.global.defaultFontFamily);
                                    ctx.textAlign = 'center';
                                    ctx.textBaseline = 'bottom';

                                    this.data.datasets.forEach(function(dataset, i) {
                                        var meta = chartInstance.controller.getDatasetMeta(i);
                                        meta.data.forEach(function(bar, index) {
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
                                beforeDraw: function(c) {
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
            error: function(error, ror, r) {
                $("#msg").html("<div class='alert alert-danger'>Records not found</div>");
            },
        });
        //getAntibioticByCareUnitProvider(careUnit, provider,rx);
        //getAntibioticByCareUnitProviderPrice(careUnit, provider,rx);
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
                    "onComplete": function() {
                        var chartInstance = this.chart,
                            ctx = chartInstance.ctx;

                        ctx.font = Chart.helpers.fontString(Chart.defaults.global.defaultFontSize, Chart.defaults.global.defaultFontStyle, Chart.defaults.global.defaultFontFamily);
                        ctx.textAlign = 'center';
                        ctx.textBaseline = 'bottom';

                        this.data.datasets.forEach(function(dataset, i) {
                            var meta = chartInstance.controller.getDatasetMeta(i);
                            meta.data.forEach(function(bar, index) {
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
                    beforeDraw: function(c) {
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

    function getAntibioticByCareUnitProviderPrice(careUnit, provider, rx) {
        var url = "<?php echo base_url() ?>reportsSummary/get_antibiotic_by_care_unit_provider_price";
        $.ajax({
            method: "POST",
            url: url,
            data: {
                careUnit: careUnit,
                provider: provider,
                rx: rx
            },
            dataType: "json",
            success: function(response) {
                if (response.status == 200) {

                    var ChartGraphName = [];
                    var ChartGraphPercentage = [];
                    var Color = [];
                    var name = "All";
                    console.log(provider + "==");
                    if (provider != undefined) {
                        name = response.provider_name;
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
                                "onComplete": function() {
                                    var chartInstance = this.chart,
                                        ctx = chartInstance.ctx;

                                    ctx.font = Chart.helpers.fontString(Chart.defaults.global.defaultFontSize, Chart.defaults.global.defaultFontStyle, Chart.defaults.global.defaultFontFamily);
                                    ctx.textAlign = 'center';
                                    ctx.textBaseline = 'bottom';

                                    this.data.datasets.forEach(function(dataset, i) {
                                        var meta = chartInstance.controller.getDatasetMeta(i);
                                        meta.data.forEach(function(bar, index) {
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
                                beforeDraw: function(c) {
                                    var chartHeight = c.chart.height;
                                    c.scales['x-axis-0'].options.ticks.fontSize = chartHeight * 4 / 100;
                                }
                            }],
                            scales: {
                                xAxes: [{
                                    display: true,
                                    scaleLabel: {
                                        display: true,
                                        labelString: 'Antibiotics (' + name + ')'
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
            error: function(error, ror, r) {
                $("#msg").html("<div class='alert alert-danger'>Records not found</div>");
            },
        });
    }

    function getAntibioticByCareUnitStewardPrice(careUnit, provider, rx) {
        var careUnit = $("#careUnit").val();
        var provider = $("#steward").val();
        var rx = $("#RX").val();
        var url = "<?php echo base_url() ?>reportsSummary/get_antibiotic_by_care_unit_steward_price";
        $.ajax({
            method: "POST",
            url: url,
            data: {
                careUnit: careUnit,
                provider: provider,
                rx: rx
            },
            dataType: "json",
            success: function(response) {
                if (response.status == 200) {

                    var ChartGraphName = [];
                    var ChartGraphPercentage = [];
                    var Color = [];
                    var name = "All";
                    if (provider != undefined) {
                        name = response.provider_name;
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
                                "onComplete": function() {
                                    var chartInstance = this.chart,
                                        ctx = chartInstance.ctx;

                                    ctx.font = Chart.helpers.fontString(Chart.defaults.global.defaultFontSize, Chart.defaults.global.defaultFontStyle, Chart.defaults.global.defaultFontFamily);
                                    ctx.textAlign = 'center';
                                    ctx.textBaseline = 'bottom';

                                    this.data.datasets.forEach(function(dataset, i) {
                                        var meta = chartInstance.controller.getDatasetMeta(i);
                                        meta.data.forEach(function(bar, index) {
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
                                beforeDraw: function(c) {
                                    var chartHeight = c.chart.height;
                                    c.scales['x-axis-0'].options.ticks.fontSize = chartHeight * 4 / 100;
                                }
                            }],
                            scales: {
                                xAxes: [{
                                    display: true,
                                    scaleLabel: {
                                        display: true,
                                        labelString: 'Antibiotics (' + name + ')'
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
            error: function(error, ror, r) {
                $("#msg").html("<div class='alert alert-danger'>Records not found</div>");
            },
        });
    }
</script>