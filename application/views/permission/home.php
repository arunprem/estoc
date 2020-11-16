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
                <i class="fa fa-user-secret"> </i> Manage Permission</h4>
        </div>

    </div>
    <ol class="breadcrumb" style="padding: 20px">
        <li><a href="#"><i class="fa fa-dashboard"></i> Home</a></li>
        <li><a href="#">Permission</a></li>
        <li class="active">Manage Permissions</li>
    </ol>
</section>

<!-- Main content -->
<section class="content">

    <!-- Default box -->
    <div class="box">
        <div class="box-header with-border bg-gray-light">
            <h3 class="box-title">Permissions</h3>  
        </div>
        <div class="box-body" style="min-height: 500px">
            <!---------------------------------------------->
            <div class="tree well" >
                <?php echo $perm_list; ?>
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

<div class="modal fade" id="perm-fm-modal" role="dialog" data-keyboard="false" data-backdrop="false" tabindex="-1" >
    <div class="modal-dialog ">
        <div class="modal-content panel-primary">
            <div class="modal-header panel-heading">
                <button type="button" id="modelColse" class="close" data-dismiss="modal" aria-label="Close"><span aria-hidden="true">&times;</span></button>
                <h3 class="modal-title">Permission Management</h3>
            </div>


            <div class="modal-body form">
                <div class="error-msg"></div>
                <form  class="form-horizontal clone-form">
                    <input type="hidden" name="<?php echo $this->security->get_csrf_token_name(); ?>" value="<?php echo $this->security->get_csrf_hash(); ?>">
                    <input type="hidden" value="" name="id"/>
                    <input type="hidden" value="" name="act"/>
                    <div class="form-body">
                        <div class="form-group">
                            <label class="control-label col-md-3">Permission Name</label>
                            <div class="col-md-9">
                                <input name="pdesc" placeholder="Permission Name" class="form-control" type="text" >
                            </div>
                        </div>
                        <div class="form-group">
                            <label class="control-label col-md-3">Permission Alias</label>
                            <div class="col-md-9">
                                <input name="alias" placeholder="Alias" class="form-control" type="text">
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
    $(document).ready(function() {
        $('#perm-tree').contextMenu({
            selector: 'li',
            delay: 500,
            autoHide: true,
            items: {
                "create": {
                    name: "Create Permission",
                    icon: "fa-plus",
                    items: {
                        "before": {
                            name: "Before",
                            icon: "fa-level-up",
                            callback: function(key, opt) {
                                createPmn($(this).attr('id'), 'create-before');
                            }},
                        "after": {
                            name: "After",
                            icon: "fa-level-down",
                            callback: function(key, opt) {
                                createPmn($(this).attr('id'), 'create-after');
                            }
                        },
                        "child": {
                            name: "Child",
                            icon: "fa-child",
                            callback: function(key, opt) {
                                createPmn($(this).attr('id'), 'create-child');
                            }

                        },
                    }
                },
                "sep1": "---------",
                "edit": {
                    name: "Edit Permission",
                    icon: "fa-edit",
                    callback: function(key, opt) {
                        editPermission($(this).attr('id'));
                    }
                },
                "sep2": "---------",
                "delete": {
                    name: "Delete Permission",
                    icon: "fa-close",
                    callback: function(key, opt) {
                        deletePermission($(this).attr('id'));
                    }
                }
            }
        });
///////////////////////////////////////tree/////////////////////////////////
        $('.tree li:has(ul)').addClass('parent_li').find(' > span').attr('title', 'Collapse this branch');
        $('.tree li.parent_li > span').on('click', function(e) {
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
    ///////////////////////custom script////////////////////////////
    var action;
    var idPmn;
    var urlPmn = "<?php echo base_url('permission/new-perm'); ?>";

    function createPmn(idp, opt) {
        var row = $('#' + idp).data('all');
        if (row) {
            if ($('#perm-modal').length) {
                $('#perm-modal').remove();
            }
            $('#perm-fm-modal').clone()
                    .attr('id', 'perm-modal')
                    .appendTo('body'); // show bootstrap modal when complete loaded
            $('#perm-modal').modal('show');
            //$('#perm-modal .clone-form')[0].reset();
            //$('#perm-modal .clone-form').data('bootstrapValidator').resetForm(true);          
            //$('#perm-modal .clone-form').loadJSON(row);
            $('#perm-modal .clone-form [name="id"]').val(row.id);
            $('#perm-modal .clone-form [name="act"]').val(opt);
            $('.modal-title').text('Create Permission'); // Set title to Bootstrap modal title
            urlPmn = '<?php echo base_url('permission/new_permission'); ?>';
            savePermission();

        }

    }
    function deletePermission(idp) {
        var row = $('#' + idp).data('all');
        if (row) {
            eModal.confirm('Are you sure to delete this item', 'Confirm to delete')
                    .then(function(r) {
                        $('#main-content').block();
                        $.post('<?php echo base_url('permission/remove_permission'); ?>', {id: row.id}, function(result) {
                            if (result.success) {
                                reloadPermission(); // reload the user data
                                showMsg('Success', 'Successfully deleted', 'success');
                            } else {
                                showMsg('Error', result.msg, 'danger');
                            }
                            $('#main-content').unblock();
                        }, 'json');
                    }
                    , function(r) {
                        return '';
                    });
        }

    }


    function editPermission(idp) {
        var row = $('#' + idp).data('all');
        if (row) {
            if ($('#perm-modal').length) {
                $('#perm-modal').remove();
            }
            $('#perm-fm-modal').clone()
                    .attr('id', 'perm-modal')
                    .appendTo('body'); // show bootstrap modal when complete loaded
            $('#perm-modal').modal('show');
            //$('#perm-modal .clone-form')[0].reset();
            //$('#perm-modal .clone-form').data('bootstrapValidator').resetForm(true);
            $('#perm-modal .clone-form').loadJSON(row);
            $('.modal-title').text('Edit Permission'); // Set title to Bootstrap modal title
            urlPmn = '<?php echo base_url('permission/edit_permission'); ?>';
            idPmn = row.id;
            action = 'edit-permission';
            savePermission();

        }

    }

    function savePermission() {
        ///////////////////////////////form submission/////////////////////
        $('#perm-modal .clone-form').bootstrapValidator({
            message: 'This value is not valid',
            feedbackIcons: {
                valid: 'glyphicon glyphicon-ok',
                invalid: 'glyphicon glyphicon-remove',
                validating: 'glyphicon glyphicon-refresh'
            },
            fields: {
                pdesc: {
                    message: 'The Permission description is not valid',
                    validators: {
                        notEmpty: {
                            message: 'The Permission description is required'
                        },
                        stringLength: {
                            min: 3,
                            max: 100,
                            message: 'The Permission description must be more than 3 and less than 100 characters long'
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
            }
        }).on('success.form.bv', function(e) {
            // Prevent form submission
            e.preventDefault();
            // Get the form instance
            var $form = $(e.target);
            // Get the BootstrapValidator instance
            var bv = $form.data('bootstrapValidator');
            // Use Ajax to submit form data
            /* $.post($form.attr('action'), $form.serialize(), function (result) {
             console.log(result);
             }, 'json');
             */

            $.ajax({
                type: "POST",
                url: urlPmn,
                cache: false,
                data: $form.serialize(),
                dataType: 'json',
                beforeSend: function() {
                    $('#perm-modal').block();
                },
                success: function(data) {

                    $('#perm-modal').unblock();
                    if (data.success) {
                        showMsg('Success', data.msg, 'success');
                        $('#perm-modal').modal('hide');
                        reloadPermission();
                    } else {
                        $('#perm-modal .error-msg').html(data.msg);
                    }

                }
            });
        });
///////////////////////////////////////////////////////////////////
    }

    function reloadPermission() {
        loadLayout('Manage Permission', 'permission/home');
    }

</script>
