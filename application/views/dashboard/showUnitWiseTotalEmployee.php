<?php
/*
 * To change this license header, choose License Headers in Project Properties.
 * To change this template file, choose Tools | Templates
 * and open the template in the editor.
 */
?>
<section class="content">
    <div class="row">
        

        




        <div class="col-xs-12">
            <div class="box">
                <div class="box-header">
                    <h3 class="box-title label-danger">Unit Wise Un Registered Employee</h3>
                    <div class="dropdown pull-right">
                        <button class="btn btn-primary dropdown-toggle" type="button" data-toggle="dropdown">Export
                            <span class="caret"></span></button>
                        <ul class="dropdown-menu">
                            <li><a href="<?php echo base_url('dashboard/totalemployees');?>">Total Employees In Excel</a></li>
                            <li><a href="<?php echo base_url('dashboard/exportUnregistered');?>">Total Un Registered In Excel</a></li>
                           
                         
                        </ul>
                    </div> 

                    <div class="box-tools">
                        <div class="input-group input-group-sm" style="width: 150px;">

                        </div>
                    </div>
                </div>
                <!-- /.box-header -->
                <div class="box-body table-responsive no-padding">
                    <table class="table table-hover">
                        <tbody><tr>
                                <th></th>
                                <th>Pen</th>
                                <th>Name</th>
                                <th>Unit Name</th>
                                <th>Phone Number</th>
                                <th>Rank</th>
                            </tr>
                            <?php foreach ($empRegi as $value) { ?>
                                <tr>
                                    <td></td>
                                    <td><?php echo $value['pen']; ?></td>
                                    <td><?php echo $value['emp_name']; ?></td>
                                    <td><?php echo $value['unit_name']; ?></td>
                                    <td><span class="label label-success"> <?php echo $value['mob_no']; ?></span></td>
                                    <td><?php echo $value['post_desc']; ?></td>
                                </tr>
                            <?php } ?>


                        </tbody></table>
                </div>
                <!-- /.box-body -->
            </div>
            <!-- /.box -->
        </div>
    </div>



</section>



<script>
    var dashboard_dt_table;


    $(document).ready(function () {
       
    });


    function drawbarChartUnitWiseIssue()
    {
        $('#barUnitWise').replaceWith('<canvas id="barUnitWise" width="500" height="400"></canvas>');
        $('#alilegent').replaceWith(' <div id="alilegent" class="legend"></div>');

        var canvas = document.getElementById('barUnitWise');
        $.ajax({
            url: "<?php echo base_url('dashboard/listUserUnitWiseDetails'); ?>",
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
                document.getElementById('alilegent').appendChild(legendHolder.firstChild);
            },
            error: function (data) {
                console.log(data);
            }
        });
    }

    function drawbarUnitWiseRegisterd()
    {
        $('#barUnitWiseRegisterd').replaceWith('<canvas id="barUnitWiseRegisterd" width="500" height="400"></canvas>');
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