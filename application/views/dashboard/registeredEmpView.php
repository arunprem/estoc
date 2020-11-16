<?php
/*
 * To change this license header, choose License Headers in Project Properties.
 * To change this template file, choose Tools | Templates
 * and open the template in the editor.
 */
?>
<section class="content">
    <div class="row">
        

        <section class="col-lg-12 connectedSortable ui-sortable">
            <div class="box">
                <div class="box-header">
                    <h3 class="box-title">Registered User Not Completed Profile Updation</h3>
                    <div class="box-header">
                        <h3 class="box-title"></h3>
                        <div class="dropdown pull-right">
                            <button class="btn btn-primary dropdown-toggle" type="button" data-toggle="dropdown">Export
                                <span class="caret"></span></button>
                            <ul class="dropdown-menu">
                                <li><a href="<?php echo base_url('dashboard/exportRegisteredUser'); ?>">Excel</a></li>

                            </ul>
                        </div> 

                        <div class="box-tools">
                            <div class="input-group input-group-sm" style="width: 150px;">

                            </div>
                        </div>
                    </div>
                </div>
                <!-- /.box-header -->
                <div class="box-body no-padding">
                    <table class="table table-condensed">
                        <tbody><tr>
                                <th style="width: 10px">#</th>
                                <th>Pen</th>
                                <th>Name</th>
                                <th>Unit</th>
                                <th>Phone</th>
                                <th>Progress</th>
                                <th>%</th>

                            </tr>
                            <?php foreach ($Regi as $value) { ?>



                                <tr>
                                    <td></td>
                                    <td><?php echo $value['pen']; ?></td>
                                    <td><?php echo $value['p_name']; ?></td>
                                    <td><?php echo $value['unit_name']; ?></td>
                                    <td><?php echo $value['mob']; ?></td>
                                    <td>
                                        <?php if ($value['profile_status'] == 0) { ?>
                                            <div class="progress progress-xs">
                                                <div class="progress-bar progress-bar-danger" style="width: 10%"></div>
                                            </div>
                                        <?php } ?>
                                        <?php if ($value['profile_status'] == 1) { ?>
                                            <div class="progress progress-xs">
                                                <div class="progress-bar progress-bar-danger" style="width: 20%"></div>
                                            </div>
                                        <?php } ?>
                                        <?php if ($value['profile_status'] == 2) { ?>
                                            <div class="progress progress-xs">
                                                <div class="progress-bar progress-bar-danger" style="width: 40%"></div>
                                            </div>
                                        <?php } ?>
                                        <?php if ($value['profile_status'] == 3) { ?>
                                            <div class="progress progress-xs">
                                                <div class="progress-bar progress-bar-warning" style="width: 60%"></div>
                                            </div>
                                        <?php } ?>

                                        <?php if ($value['profile_status'] == 4) { ?>
                                            <div class="progress progress-xs">
                                                <div class="progress-bar progress-bar-success" style="width: 90%"></div>
                                            </div>
                                        <?php } ?>
                                    </td>
                                    <td>

                                        <?php if ($value['profile_status'] == 0) { ?>
                                            <span class="badge bg-red">10%</span>
                                        <?php } ?>
                                        <?php if ($value['profile_status'] == 1) { ?>
                                            <span class="badge bg-red">20%</span>
                                        <?php } ?>
                                        <?php if ($value['profile_status'] == 2) { ?>
                                            <span class="badge bg-red">40%</span>
                                        <?php } ?>
                                        <?php if ($value['profile_status'] == 3) { ?>
                                            <span class="badge bg-yellow-active">60%</span>
                                        <?php } ?>                                      
                                        <?php if ($value['profile_status'] == 4) { ?>
                                            <span class="badge bg-green">90%</span>
                                        <?php } ?>
                                    </td>


                                </tr>
                            <?php } ?>

                        </tbody></table>
                </div>
                <!-- /.box-body -->
            </div>
        </section>

    </div>
</section>

<script>

    $(document).ready(function () {

      
    });

    function drawbarUnitWiseRegisterd()
    {
        $('#barUnitWiseRegisterd').replaceWith('<canvas id="barUnitWiseRegisterd" width="1000" height="400"></canvas>');
        $('#alilegentRegistered').replaceWith(' <div id="alilegent" class="legend"></div>');

        var canvas = document.getElementById('barUnitWiseRegisterd');
        $.ajax({
            url: "<?php echo base_url('dashboard/listUserUnitRegistered'); ?>",
            method: "GET",
            success: function (datas) {
                var data = eval(datas);
                var myData = data;
                var chartdata = {
                    labels: myData.mapProperty('unit_short_code'),
                    datasets: [
                        {
                            label: 'Total Employee Unit Wise',
                            fillColor: "rgba(60,141,188,0.9)",
                            strokeColor: "rgba(60,141,188,0.8)",
                            pointColor: "#3b8bba",
                            pointStrokeColor: "rgba(60,141,188,1)",
                            pointHighlightFill: "#fff",
                            pointHighlightStroke: "rgba(60,141,188,1)",
                            data: myData.mapProperty('total')
                        }
                    ]};
                var bar = new Chart(canvas.getContext('2d')).Bar(chartdata, {
                    tooltipTemplate: "<%if (label){%><%=label%>: <%}%><%= value %>",
                    animation: true,
                    maintainAspectRatio: true
                }); // 
                var legendHolder = document.createElement('div');
                legendHolder.innerHTML = bar.generateLegend();
                document.getElementById('alilegentRegistered').appendChild(legendHolder.firstChild);
            },
            error: function (data) {
                console.log(data);
            }
        });
    }






</script>


<style>
    .legend ul {
        list-style: none;
    }
    .legend ul li {
        display: inline;
        padding-left: 20px;
        position: relative;
        margin-bottom: 4px;
        border-radius: 5px;
        padding: 2px 8px 2px 28px;
        font-size: 14px;
        cursor: default;
        -webkit-transition: background-color 200ms ease-in-out;
        -moz-transition: background-color 200ms ease-in-out;
        -o-transition: background-color 200ms ease-in-out;
        transition: background-color 200ms ease-in-out;
    }
    .legend li span {
        display: inline;
        position: absolute;
        left: 0;
        top: 0;
        width: 15px;
        height: 65%;
        border-radius: 5px;
    }

    canvas {
        width: 100% !important;
        max-width:fit-content;
        height: auto !important;
    }


</style>