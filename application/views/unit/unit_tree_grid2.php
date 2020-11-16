<?php
/*
 * To change this license header, choose License Headers in Project Properties.
 * To change this template file, choose Tools | Templates
 * and open the template in the editor.
 */
?>
<section class="content-header">
    <h1>
        Manage Units
        <small></small>
    </h1>
    <ol class="breadcrumb">
        <li><a href="#"><i class="fa fa-dashboard"></i> Home</a></li>
        <li><a href="#">Units</a></li>
        <li class="active">Manage Units</li>
    </ol>
</section>

<!-- Main content -->
<section class="content">

    <!-- Default box -->
    <div class="box">
        <div class="box-header with-border bg-gray-light">
            <h3 class="box-title">Units Hierarchy</h3>  
        </div>
        <div class="box-body" style="min-height: 500px">
            <table id="example" class="display table table-striped table-bordered" cellspacing="0" width="100%"></table>      
        </div>
        <!-- /.box-body -->
        <div class="box-footer bg-gray-light">

        </div>
        <!-- /.box-footer-->
    </div>
    <!-- /.box -->


</section>
<!-- /.content -->

<script>
    $(document).ready(function() {
        var columns = [
            {
                title: '',
                target: 0,
                className: 'treegrid-control',
                data: function(item) {
                    if (jQuery.isEmptyObject(item.children)) {
                        return '<span></span>';
                    }else{
                        
                        return '<span>+</span>';
                    }
                    
                }
            },
            {
                title: 'Unit Name',
                target: 1,
                data: function(item) {
                    return item.unit_name;
                }
            },
            {
                title: 'Type of Unit',
                target: 2,
                data: function(item) {
                    return item.utype;
                }
            }
            ,
            {
                title: 'Action',
                target: 3,
                data: function(item) {
                    return item.id;
                }
            }
        ];

        $('#example').DataTable({
            'columns': columns,
            'ajax': '<?php echo base_url(); ?>unit/unit_tree_grid',
            'treeGrid': {
                'left': 10,
                'expandIcon': '<span>+</span>',
                'collapseIcon': '<span>-</span>',
            },
            'info': false

        });
    });
</script>