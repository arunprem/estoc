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
            <div class="tree well" >
                <?php echo $unit_list; ?>
            </div>
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
    $(function () {

        $('#unit-tree').contextMenu({
            selector: 'li',
            delay: 500,
            autoHide: true,
            callback: function (key, options) {
                var m = "clicked: " + key + " on " + $(this).attr('id');
                window.console && console.log(m) || alert(m);
            },
            items: {
                "edit": {name: "Edit", icon: "edit"},
                "cut": {name: "Cut", icon: "cut"},
                "copy": {name: "Copy", icon: "copy"},
                "paste": {name: "Paste", icon: "paste"},
                "delete": {name: "Delete", icon: "fa-plus"},
                "sep1": "---------",
                "quit": {name: "Quit", icon: function ($element, key, item) {
                        return 'context-menu-icon fa-plus';
                    }}
            }
        });
    });


    $(function () {
        $('.tree li:has(ul)').addClass('parent_li').find(' > span').attr('title', 'Collapse this branch');
        $('li.parent_li').find(' > ul > li').hide();
        $('.tree li.parent_li > span').on('click', function (e) {
            var children = $(this).parent('li.parent_li').find(' > ul > li');
            if (children.is(":visible")) {
                children.hide('fast');
                $(this).attr('title', 'Expand this branch').find(' > i').addClass('icon-plus-sign').removeClass('icon-minus-sign');
            } else {
                children.show('fast');
                $(this).attr('title', 'Collapse this branch').find(' > i').addClass('icon-minus-sign').removeClass('icon-plus-sign');
            }
            e.stopPropagation();
        });
    });
</script>