<?php
/*
 * To change this license header, choose License Headers in Project Properties.
 * To change this template file, choose Tools | Templates
 * and open the template in the editor.
 */

?>

<head>

    <style>
        canvas {
            -moz-user-select: none;
            -webkit-user-select: none;
            -ms-user-select: none;
        }
    </style>

</head>
<section class="content">



    <div class="row">
        <div class="col-lg-3 col-6">
            <!-- small box -->
            <div class="small-box bg-blue">
                <div class="inner">
                <h3><?php echo $tracing['0']['tracing']; ?> /
                    <?php echo $positive['0']['total_positive']; ?></h3>

                    <p>Tracing Started</p>
                </div>
                <div class="icon">
                    <i class="fa fa-thumb-tack"></i>
                </div>

            </div>
        </div>
        <!-- ./col -->
        <div class=" col-lg-3 col-6">
            <!-- small box -->
            <div class="small-box bg-red">
                <div class="inner">
                  
                    <h3>  <?php echo $ap['0']['positive']; ?></h3>
                    <p>Active Positive</p>
                </div>
                <div class="icon">
                    <i class="fa fa-user-plus"></i>
                </div>

            </div>
        </div>
        <!-- ./col -->
        <div class="col-lg-3 col-6">
            <!-- small box -->
            <div class="small-box bg-yellow">
                <div class="inner">
                    <h3><?php echo $p[0]['primary_contact']; ?></h3>

                    <p>Primary Contact</p>
                </div>
                <div class="icon">
                    <i class="fa fa-lock"></i>
                </div>

            </div>
        </div>
        <!-- ./col -->
        <div class=" col-lg-3 col-6">
            <!-- small box -->
            <div class="small-box bg-green">
                <div class="inner">
                    <h3><?php echo $s[0]['secondary_contact']; ?></h3>

                    <p>Secondary Contacts</p>
                </div>
                <div class="icon">
                    <i class="fa fa-shield"></i>
                </div>

            </div>
        </div>
        <!-- ./col -->
    </div>


    <div class="row">

        <div class="col-md-6">
            <div class="box box-solid">
                <div class="box-header with-border">

                    <i class="fa fa-bar-chart"></i>
                    <h3 class="box-title"></h3>
                    <span class="pull-right"> <a href="#" onclick="LoadBarChat()"> <i class="fa fa-refresh" aria-hidden="true"></i></a>
                    </span>
                    <strong> Positive case and Tracing Status</strong>
                </div>

                <div class="box-body">
                    <canvas id="positiveandtraced" width="100" height="75" aria-label="Kerala Police" role="img"></canvas>

                </div>

            </div>

        </div>


        <?php
        if ($user->unit_role == 899 || $user->unit_role == 1009) { ?>
            <div class="col-md-6">
                <div class="box box-solid">
                    <div class="box-header with-border">
                        <i class="fa fa-bar-chart"></i>
                        <h3 class="box-title"></h3>
                        <strong>Postive and Contact Persons</strong>
                    </div>

                    <div class="box-body">
                        <canvas id="lockviolations" width="100" height="78"></canvas>
                    </div>
                </div>
            </div>
        <?php } ?>

        <?php
        if ($user->unit_role != 899 && $user->unit_role != 1009) { ?>
            <div class="col-md-6">
                <div class="box box-solid">
                    <div class="box-header with-border">
                        <i class="fa fa-bar-chart"></i>
                        <h3 class="box-title"></h3>
                        <strong>Postive and Contact Persons</strong>
                    </div>

                    <div class="box-body">
                        <canvas id="dognetChart" width="100" height="78"></canvas>
                    </div>
                </div>
            </div>
        <?php } ?>






    </div>

    <div class="row">
        <div class="col-md-6">
            <div class="box " style="padding: 10px 10px 10px 10px">
                <div class="box-header with-border">
                    <h4 class="box-title">DMO Data Transfering from PS</h4>
                    <!-- <span style="float:right"><button class="btn btn-success" onclick="assignPS()"><i class="fa fa-building-o"></i> Assign Police Station Automatically </button> -->
                    </span>
                </div><!-- /.box-header -->

                <table id="dmotract-dt-table" class="table display compact table-striped table-bordered" cellspacing="0" width="100%">
                    <thead>
                        <tr>
                            <th></th>

                            <th>Date</th>
                            <th>Person Name</th>
                            <th>Person Type</th>
                            <th>From PS</th>
                            <th>To PS</th>
                            <th>From District</th>
                            <th>To District</th>



                        </tr>
                    </thead>
                    <tbody>
                    </tbody>
                </table>
            </div>
        </div>


        <div class="col-md-6">
            <div class="box box-solid">
                <div class="box-header with-border">
                    <i class="fa fa-bar-chart"></i>
                    <h3 class="box-title"></h3>
                    <strong> Quarantine check [<?php echo date("l jS \of F Y"); ?>]</strong>


                    <span style="float: right;"> <a href="#" onclick=" loadLayout('Qchek Details', 'dashboard/qdetails');"><span class="fa fa-eye"></span></a></span>


                </div>
                <div class="box-body">
                    <canvas id="lineFirArrest" width="100" height="70"></canvas>

                </div>
            </div>
        </div>
    </div>

</section>





<script>
    var dmotract_dt_table;




    $(document).ready(function() {
        //datatables
        dmotract_dt_table = $('#dmotract-dt-table').DataTable({
            "processing": true, //Feature control the processing indicator.
            "serverSide": true, //Feature control DataTables' server-side processing mode.
            "order": [], //Initial no order.
            "iDisplayLength": 5,
            "searching": false,
            "scrollX": true,
            // Load data for the table's content from an Ajax source
            "ajax": {
                "url": "<?php echo base_url('dashboard/getJurisdictionChange') ?>",
                "type": "POST"
            },
            "columns": [{
                    data: 'sl'
                },

                {
                    data: 'decl_date'
                },
                {
                    data: 'p_name'
                },
                {
                    data: 'person_type'
                },

                {
                    data: 'from_ps'
                },
                {
                    data: 'to_ps'
                },
                {
                    data: 'from_dist'
                },

                {
                    data: 'to_dist'
                },



            ],
            "columnDefs": [{
                    "targets": [0],
                    "orderable": false,
                },
                {
                    "targets": [-1],
                    "orderable": false
                }

            ],


        });
        var data = null;
        ///////  


        LoadBarChat();


        loadBarExplo();
        LoadDonutChart();
        loadLineChart();
    });

    function LoadBarChat() {
        $('#positiveandtraced').replaceWith('<canvas id="positiveandtraced" width="100" height="75"></canvas>');
        $.ajax({
            url: "<?php echo base_url('dashboard/getPsWisePositiveandTraced'); ?>",
            method: "GET",
            success: function(datas) {
                var data = eval(datas);
                var myData = data;
                var barChartData = {
                    labels: myData.mapProperty('psname'),
                    datasets: [{
                            label: 'Tracing Started',
                            backgroundColor: window.chartColors.green,
                            data: myData.mapProperty('tracedCase'),
                        },
                        {
                            label: 'Total Positive Case',
                            backgroundColor: window.chartColors.red,
                            data: myData.mapProperty('totcase'),
                        }
                    ]
                };
                var barnbStack = document.getElementById("positiveandtraced").getContext('2d');
                var positiveandtraced = new Chart(barnbStack, {
                    type: 'bar',
                    data: barChartData,
                    options: {
                        title: {
                            display: true,
                            text: 'Positive list and Tracing started '
                        },
                        tooltips: {
                            mode: 'index',
                            intersect: false
                        },
                        responsive: true,
                        scales: {
                            xAxes: [{
                                stacked: true,
                                ticks: {
                                    autoSkip: false
                                }
                            }],
                            yAxes: [{
                                ticks: {
                                    beginAtZero: true
                                }
                            }]
                        }
                    }

                });
            },
            error: function(data) {
                console.log(data);
            }
        });
    }




    function LoadDonutChart() {
        $.ajax({
            url: "<?php echo base_url('dashboard/getPsWisePositiveAndContactcount'); ?>",
            method: "GET",
            success: function(datas) {
                var data = eval(datas);
                var myData = data;
                var config = {
                    type: 'doughnut',
                    data: {
                        datasets: [{
                            data: [
                                myData.mapProperty('totpos'),
                                myData.mapProperty('totpri'),
                                myData.mapProperty('totsec'),

                            ],
                            backgroundColor: [
                                window.chartColors.red,
                                window.chartColors.orange,
                                window.chartColors.green,
                            ],
                        }],
                        labels: [
                            'Positive',
                            'Primary',
                            'Secondary',

                        ]
                    },
                    options: {
                        responsive: true,
                        title: {
                            display: true,
                            text: 'Positive & Contacts'
                        }
                    }

                };
                var ctx = document.getElementById('dognetChart').getContext('2d');
                var myDoughnut = new Chart(ctx, config);
            },
            error: function(data) {
                console.log(data);
            }
        });
    }



    function loadBarExplo() {
        $('#lockviolations').replaceWith('<canvas id="lockviolations" width="100" height="75"></canvas>');
        $.ajax({
            url: "<?php echo base_url('dashboard/getPsWisePositiveAndContactcount'); ?>",
            method: "GET",
            success: function(datas) {
                var data = eval(datas);
                var myData = data;
                var barChartData = {
                    labels: myData.mapProperty('psname'),
                    datasets: [{
                            label: 'Positive',
                            backgroundColor: window.chartColors.red,
                            data: myData.mapProperty('totpos'),

                        },
                        {
                            label: 'Primary',
                            backgroundColor: window.chartColors.yellow,
                            data: myData.mapProperty('totpri'),
                        },

                        {
                            label: 'Secondary',
                            backgroundColor: window.chartColors.green,
                            data: myData.mapProperty('totsec'),
                        }
                    ]
                };
                var barexploStack = document.getElementById("lockviolations").getContext('2d');
                var lockviolations = new Chart(barexploStack, {
                    type: 'line',
                    data: barChartData,
                    options: {
                        title: {
                            display: true,
                            text: 'Positive & Contacts'
                        },
                        tooltips: {
                            mode: 'index',
                            intersect: false
                        },
                        responsive: true,
                        scales: {
                            xAxes: [{
                                stacked: true,
                                ticks: {
                                    autoSkip: false,
                                }
                            }],
                            yAxes: [{
                                ticks: {
                                    beginAtZero: true
                                }
                            }]
                        }
                    },
                });
                document.getElementById("lockviolations").onclick = function(evt) {
                    var activePoints = lockviolations.getElementsAtEventForMode(evt, 'point', lockviolations.options);
                    var firstPoint = activePoints[0];
                    var label = lockviolations.data.labels[firstPoint._index];
                    var value = lockviolations.data.datasets[firstPoint._datasetIndex].data[firstPoint._index];
                    alert(label);
                };
            },
            error: function(data) {
                console.log(data);
            }
        });
    }

    function loadLineChart() {
        $('#lineFirArrest').replaceWith('<canvas id="lineFirArrest" width="100" height="72"></canvas>');
        $.ajax({
            url: "<?php echo base_url('dashboard/getQurantineCheck'); ?>",
            method: "GET",
            success: function(datas) {
                var data = eval(datas);
                var myData = data;
                var config = {
                    type: 'line',
                    data: {
                        labels: myData.mapProperty('psname'),
                        datasets: [{
                            label: 'Primary Contact',
                            backgroundColor: window.chartColors.blue,
                            borderColor: window.chartColors.blue,
                            data: myData.mapProperty('totprimary'),
                            fill: false,
                        }, {
                            label: 'Checked',
                            backgroundColor: window.chartColors.red,
                            borderColor: window.chartColors.red,
                            data: myData.mapProperty('chked'),
                            fill: false,
                        }]
                    },
                    options: {
                        responsive: true,
                        title: {
                            display: true,
                            text: 'Qurantine check'
                        },
                        tooltips: {
                            mode: 'index',
                            intersect: false,
                        },
                        hover: {
                            mode: 'nearest',
                            intersect: true
                        },
                        scales: {
                            xAxes: [{
                                display: true,
                                scaleLabel: {
                                    display: true,
                                    labelString: 'Police Stations'
                                },
                                ticks: {
                                    autoSkip: false
                                }
                            }],
                            yAxes: [{
                                display: true,
                                scaleLabel: {
                                    display: true,
                                    labelString: 'Primary Contact and Checked'
                                },
                                ticks: {
                                    beginAtZero: true
                                }
                            }]
                        }
                    }
                };
                var ctx = document.getElementById('lineFirArrest').getContext('2d');

                var myLine = new Chart(ctx, config);
            },
            error: function(data) {
                console.log(data);
            }
        });
    }

    // function initMap(type) {
    //     if (type == null) {
    //         var urlpsall = "<?php echo base_url('dashboard/listallpolingstation'); ?>";
    //     }
    //     if (type === 'lwe') {
    //         var urlpsall = "<?php echo base_url('dashboard/psInLwe'); ?>";
    //     }

    //     if (type === 'inacc') {
    //         var urlpsall = "<?php echo base_url('dashboard/psInacc'); ?>";
    //     }

    //     if (type == 'sen') {
    //         var urlpsall = "<?php echo base_url('dashboard/PsInSensitive'); ?>";
    //     }

    //     if (type == 'vul') {
    //         var urlpsall = "<?php echo base_url('dashboard/PsInCritical'); ?>";
    //     }
    //     var infoWin = new google.maps.InfoWindow();
    //     var iconBase = 'https://maps.google.com/mapfiles/ms/micons/';
    //     $.getJSON(urlpsall, function (json1) {
    //         var locations = [];
    //         $.each(json1, function (key, data) {
    //             locations.push({lat: Number(data.latitude), lng: Number(data.longitude), info: data.poling_station_name, ps: data.police_station, dist: data.dist_name});
    //         });
    //         var map = new google.maps.Map(document.getElementById('map'), {
    //             zoom: 4,
    //             center: {lat: 10.8505, lng: 76.2711}
    //         });
    //         // Create the search box and link it to the UI element.
    //         var input = document.getElementById('pac-input');
    //         var searchBox = new google.maps.places.SearchBox(input);
    //         map.controls[google.maps.ControlPosition.TOP_LEFT].push(input);
    //         // Bias the SearchBox results towards current map's viewport.
    //         map.addListener('bounds_changed', function () {
    //             searchBox.setBounds(map.getBounds());
    //         });
    //         searchBox.addListener('places_changed', function () {
    //             var places = searchBox.getPlaces();
    //             if (places.length == 0) {
    //                 return;
    //             }
    //             var bounds = new google.maps.LatLngBounds();
    //             places.forEach(function (place) {
    //                 if (!place.geometry) {
    //                     console.log("Returned place contains no geometry");
    //                     return;
    //                 }
    //                 if (place.geometry.viewport) {
    //                     // Only geocodes have viewport.
    //                     bounds.union(place.geometry.viewport);
    //                 } else {
    //                     bounds.extend(place.geometry.location);
    //                 }
    //             });
    //             map.fitBounds(bounds);
    //         });
    //         var markers = locations.map(function (location, i) {
    //             var marker = new google.maps.Marker({
    //                 position: location,
    //                 title: location.info
    //                         // icon: iconBase + "ylw-pushpin.png"
    //             });
    //             google.maps.event.addListener(marker, 'click', function (evt) {
    //                 infoWin.setContent(location.info + "</BR>" + location.ps + "</BR>" + location.dist);
    //                 infoWin.open(map, marker);
    //             })

    //             return marker;
    //         });
    //         var markerCluster = new MarkerClusterer(map, markers,
    //                 {
    //                     minimumClusterSize: 5,
    //                     imagePath: 'https://developers.google.com/maps/documentation/javascript/examples/markerclusterer/m'
    //                 });
    //         var ctaLayer3 = new google.maps.KmlLayer({
    //             url: '<?php echo base_url('public/map/police_districts_1.kml'); ?>',
    //             map: map
    //         });
    //     });
    // }


    function getSubmitList() {

        $.getJSON('<?php echo base_url('dashboard/getSubmitDistrictList') ?>', function(data) {


            $(".menu").empty();
            $('.menu').block();
            $.each(data, function(index, item) {
                if (item.total_submit == 1) {
                    $('.menu').append("<li><a href='#'><i class='fa fa-check text-green'></i>" + item.unit_name + "</li>");
                } else {
                    $('.menu').append("<li><a href='#'><i class='fa fa-times text-danger'></i>" + item.unit_name + "</li>");
                }


                //  alert(data[index].unit_name);

            });
            $('.menu').unblock();
        });
    }
</script>
<!-- <script src="<?php echo base_url('public/plugins/map/markerclusterer.js'); ?>"></script> -->


<script>
    // jQuery(function() {
    //     if (!window.google || !window.google.maps) {
    //         var script = document.createElement('script');
    //         script.type = 'text/javascript';
    //         script.src = 'https://maps.googleapis.com/maps/api/js?key=AIzaSyDgBJuNJh-SOjutzbG8u-rD50GhAw_gmEE&libraries=places&callback=initMap';
    //         document.body.appendChild(script);
    //     } else {

    //         initMap();
    //     }
    // });
</script>

<style>
    .pac-card {
        margin: 10px 10px 0 0;
        border-radius: 2px 0 0 2px;
        box-sizing: border-box;
        -moz-box-sizing: border-box;
        outline: none;
        box-shadow: 0 2px 6px rgba(0, 0, 0, 0.3);
        background-color: #fff;
        font-family: Roboto;
    }

    #pac-container {
        padding-bottom: 12px;
        margin-right: 12px;
    }

    .pac-controls {
        display: inline-block;
        padding: 5px 11px;
    }

    .pac-controls label {
        font-family: Roboto;
        font-size: 13px;
        font-weight: 300;
    }

    #pac-input {
        background-color: #fff;
        font-family: Roboto;
        font-size: 15px;
        font-weight: 300;
        margin-left: 10px;
        padding: 0 11px 0 13px;
        text-overflow: ellipsis;
        width: 350px;
    }

    #pac-input:focus {
        border-color: #4d90fe;
    }
</style>