<?php
/*
 * To change this license header, choose License Headers in Project Properties.
 * To change this template file, choose Tools | Templates
 * and open the template in the editor.
 */
?>
<section class="content-header">
    <div class="box box-success">
        <div class="box-header with-border">
            <h4 class="box-title">
                <i class="fa fa-sitemap"> </i> Manage Menu</h4>
        </div>

    </div>

    <ol class="breadcrumb" style="padding: 20px">
        <li><a href="#"><i class="fa fa-dashboard"></i> Home</a></li>
        <li><a href="#">Menu</a></li>
        <li class="active">Manage Menu</li>
    </ol>
</section>

<!-- Main content -->
<section class="content">

    <!-- Default box -->
    <div class="box">
        <div class="box-header with-border bg-gray-light">
            <h3 class="box-title">Menu</h3>  
        </div>
        <div class="box-body" style="min-height: 500px">
            <!---------------------------------------------->
            <div class="tree well" >
                <?php echo $menu_list; ?>
            </div>

            <!---------------------------------------------->           
        </div>
        <!-- /.box-body -->
        <div class="box-footer bg-gray-light">

        </div>
        <!-- /.box-footer-->
    </div>
    <!-- /.box -->


</section>
<!-- /.content -->



<!-- Bootstrap modal -->

<div class="modal fade" id="menu-fm-modal" role="dialog" data-keyboard="false" data-backdrop="false" tabindex="-1" >
    <div class="modal-dialog ">
        <div class="modal-content panel-primary">
            <div class="modal-header panel-heading">
                <button type="button" id="modelColse" class="close" data-dismiss="modal" aria-label="Close"><span aria-hidden="true">&times;</span></button>
                <h3 class="modal-title">Menu Management</h3>
            </div>


            <div class="modal-body form">
                <div class="error-msg"></div>
                <form  class="form-horizontal clone-form">
                    <input type="hidden" name="<?php echo $this->security->get_csrf_token_name(); ?>" value="<?php echo $this->security->get_csrf_hash(); ?>">
                    <input type="hidden" value="" name="id"/>
                    <input type="hidden" value="" name="act"/>
                    <div class="form-body">
                        <div class="form-group">
                            <label class="control-label col-md-3">Menu Title</label>
                            <div class="col-md-9">
                                <input name="title" placeholder="Menu Title" class="form-control" type="text" >
                            </div>
                        </div>
                        <div class="form-group">
                            <label class="control-label col-md-3">Menu Alias</label>
                            <div class="col-md-9">
                                <input name="alias" placeholder="Alias" class="form-control" type="text">
                            </div>
                        </div>
                        <div class="form-group">
                            <label class="control-label col-md-3">Menu Description</label>
                            <div class="col-md-9">
                                <textarea name="description" placeholder="Description" class="form-control" ></textarea>
                            </div>
                        </div>
                        <div class="form-group">
                            <label class="control-label col-md-3">Menu Path</label>
                            <div class="col-md-9">
                                <input name="path" placeholder="Path" class="form-control" type="text">
                            </div>
                        </div>
                        <div class="form-group">
                            <label class="control-label col-md-3">Permission</label>
                            <div class="col-md-9">
                                <select id="permission_select" name="permission" class="form-control">

                                </select>
                            </div>
                        </div>
                        <div class="form-group">
                            <label class="control-label col-md-3">Status</label>
                            <div class="col-md-9">
                                <select id="status" name="status" class="form-control">
                                    <option value="1">Active</option>
                                    <option value="0">Deactive</option>
                                </select>
                            </div>
                        </div>
                        <div class="form-group">
                            <label class="control-label col-md-3">Icon</label>
                            <div class="col-md-9">
                                <input name="params" placeholder="fa fa-icon name" class="form-control" type="text">
                            </div>
                        </div>

                    </div>
                    <div class="modal-footer">
                        <button type="submit"  id="submitButton"  class="btn btn-primary" ><i class="fa fa-save"></i> Save</button>
                        <button type="button" id="resetBtn" class="btn btn-danger" data-dismiss="modal"><i class="fa fa-close"></i>  Close</button>
                    </div>
                </form>
            </div>


        </div><!-- /.modal-content -->
    </div><!-- /.modal-dialog -->
</div><!-- /.modal -->

<script>
    $(document).ready(function () {
        $('#menu-tree').contextMenu({
            selector: 'li',
            delay: 500,
            autoHide: true,
            items: {
                "create": {
                    name: "Create Menu",
                    icon: "fa-plus",
                    items: {
                        "before": {
                            name: "Before",
                            icon: "fa-level-up",
                            callback: function (key, opt) {
                                createMenu($(this).attr('id'), 'create-before');
                            }},
                        "after": {
                            name: "After",
                            icon: "fa-level-down",
                            callback: function (key, opt) {
                                createMenu($(this).attr('id'), 'create-after');
                            }
                        },
                        "child": {
                            name: "Child",
                            icon: "fa-child",
                            callback: function (key, opt) {
                                createMenu($(this).attr('id'), 'create-child');
                            }

                        },
                    }
                },
                "sep1": "---------",
                "edit": {
                    name: "Edit Menu",
                    icon: "fa-edit",
                    callback: function (key, opt) {
                        editMenu($(this).attr('id'));
                    }
                },
                "sep2": "---------",
                "delete": {
                    name: "Delete Menu",
                    icon: "fa-close",
                    callback: function (key, opt) {
                        deleteMenu($(this).attr('id'));
                    }
                }
            }
        });
///////////////////////////////////////tree/////////////////////////////////
        $('.tree li:has(ul)').addClass('parent_li').find(' > span').attr('title', 'Collapse this branch');
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

//////////////////////////////////////////////////////////////////////////////////////////////
        $.getJSON("<?php echo base_url('permission/get_permission_list'); ?>", function (data) {

            $.each(data, function (key, value) {
                $('#permission_select').append(
                        $('<option value=' + value.id + '></option>').html(value.title)
                        );
            });
        });
//////////////////////////////////////////////////////////////////////////////////////////////

    });
    ///////////////////////custom script////////////////////////////
    var action_menu;
    var idMnu;
    var urlMnu = "<?php echo base_url('menu/new-perm'); ?>";

    function createMenu(idp, opt) {
        var row = $('#' + idp).data('all');
        if (row) {
            if ($('#menu-modal').length) {
                $('#menu-modal').remove();
            }
            $('#menu-fm-modal').clone()
                    .attr('id', 'menu-modal')
                    .appendTo('body'); // show bootstrap modal when complete loaded
            $('#menu-modal').modal('show');
            //$('#menu-modal .clone-form')[0].reset();
            //$('#menu-modal .clone-form').data('bootstrapValidator').resetForm(true);          
            //$('#menu-modal .clone-form').loadJSON(row);
            $('#menu-modal .clone-form [name="id"]').val(row.id);
            $('#menu-modal .clone-form [name="act"]').val(opt);
            $('.modal-title').text('Create Menu'); // Set title to Bootstrap modal title
            urlMnu = '<?php echo base_url('menu/new_menu'); ?>';
            saveMenu();

        }

    }
    function deleteMenu(idp) {
        var row = $('#' + idp).data('all');
        if (row && row.parent !=0) {

            eModal.confirm('Are you sure to delete this item', 'Confirm to delete')
                    .then(function (r) {
                        $('#main-content').block();
                        $.post('<?php echo base_url('menu/remove_menu'); ?>', {id: row.id}, function (result) {
                            if (result.success) {

                                reloadMenu(); // reload the user data
                                showMsg('Success', 'Successfully deleted', 'success');
                            } else {
                                showMsg('Error', result.msg, 'danger');
                            }
                            $('#main-content').unblock();
                        }, 'json');
                    }
                    , function (r) {
                        return '';
                    });
        }else{
            showMsg('Warning','You cannot delete this item','warning');
        }

    }


    function editMenu(idp) {
        var row = $('#' + idp).data('all');
        if (row && row.parent !=0) {
            if ($('#menu-modal').length) {
                $('#menu-modal').remove();
            }
            $('#menu-fm-modal').clone()
                    .attr('id', 'menu-modal')
                    .appendTo('body'); // show bootstrap modal when complete loaded
            $('#menu-modal').modal('show');
            //$('#menu-modal .clone-form')[0].reset();
            //$('#menu-modal .clone-form').data('bootstrapValidator').resetForm(true);
            $('#menu-modal .clone-form').loadJSON(row);
            $('.modal-title').text('Edit Menu'); // Set title to Bootstrap modal title
            urlMnu = '<?php echo base_url('menu/edit_menu'); ?>';
            idMnu = row.id;
            action_menu = 'edit-menu';
            saveMenu();

        }else{
            showMsg('Warning','You cannot edit this item','warning');
        }

    }

    function saveMenu() {
        ///////////////////////////////form submission/////////////////////
        $('#menu-modal .clone-form').bootstrapValidator({
            message: 'This value is not valid',
            feedbackIcons: {
                valid: 'glyphicon glyphicon-ok',
                invalid: 'glyphicon glyphicon-remove',
                validating: 'glyphicon glyphicon-refresh'
            },
            fields: {
                title: {
                    message: 'The Menu title is not valid',
                    validators: {
                        notEmpty: {
                            message: 'The Menu title is required'
                        },
                        stringLength: {
                            min: 3,
                            max: 100,
                            message: 'The Menu title must be more than 3 and less than 100 characters long'
                        }
                    }
                },
                alias: {
                    validators: {
                        notEmpty: {
                            message: 'The Alias is required and can\'t be empty'
                        }
                    }
                }
                ,
                path: {
                    validators: {
                        notEmpty: {
                            message: 'The path is required and can\'t be empty'
                        }
                    }
                }
                ,
                permsision: {
                    validators: {
                        notEmpty: {
                            message: 'The permission is required'
                        }
                    }
                }
                ,
                status: {
                    validators: {
                        notEmpty: {
                            message: 'Select the status'
                        }
                    }
                }
                ,
                params: {
                    validators: {
                        notEmpty: {
                            message: 'Add icons for the menu'
                        }
                    }
                }
            }
        }).on('success.form.bv', function (e) {
            // Prevent form submission
            e.preventDefault();
            // Get the form instance
            var $form = $(e.target);
            // Get the BootstrapValidator instance
            var bv = $form.data('bootstrapValidator');
            // Use Ajax to submit form data
            /* $.post($form.attr('action_menu'), $form.serialize(), function (result) {
             console.log(result);
             }, 'json');
             */

            $.ajax({
                type: "POST",
                url: urlMnu,
                cache: false,
                data: $form.serialize(),
                dataType: 'json',
                beforeSend: function () {
                    $('#menu-modal').block();
                },
                success: function (data) {
                    $('#menu-modal').unblock();
                    if (data.success) {
                        showMsg('Success', data.msg, 'success');
                        $('#menu-modal').modal('hide');
                        reloadMenu();
                    } else {
                        showMsg('Error', data.msg, 'danger');
                        $form.find("button[type=submit]").removeAttr('disabled');
                        // $('#menu-modal .error-msg').html(data.msg);
                    }

                }
            });
        });
///////////////////////////////////////////////////////////////////
    }

    function reloadMenu() {
        loadLayout('Manage Menu', 'menu/home');
    }

</script>