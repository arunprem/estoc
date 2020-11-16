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
                    <h3 class="box-title"> Completed Profile Updation</h3>
                    <div class="box-header">
                        <h3 class="box-title">Export</h3>
                        <div class="dropdown pull-right">
                            <button class="btn btn-primary dropdown-toggle" type="button" data-toggle="dropdown">Export
                                <span class="caret"></span></button>
                            <ul class="dropdown-menu">
                                <li><a href="<?php echo base_url('dashboard/epoxrtCompletedUser'); ?>">Excel</a></li>

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
                                <th>Status</th>
                            </tr>
                            <?php foreach ($completed as $value) { ?>



                                <tr>
                                    <td></td>
                                    <td><?php echo $value['pen']; ?></td>
                                    <td><?php echo $value['p_name']; ?></td>
                                    <td><?php echo $value['unit_name']; ?></td>
                                    <td><?php echo $value['mob']; ?></td>
                                    <td>
                                        <?php if ($value['profile_status'] == 5) { ?>

                                            <div class="bg-green">Profile Completed</div>

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