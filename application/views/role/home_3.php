<?php
if (!defined('BASEPATH'))
    exit('No direct script access allowed');
?>
<section class="content-header">

    <div class="box box-success">
        <div class="box-header with-border">
            <h4 class="box-title">
                <i class="fa fa-group"> </i> Role Management</h4>
        </div>
    </div>
    <ol class="breadcrumb" style="padding: 20px">
        <li><a href="#"><i class="fa fa-dashboard"></i> Home</a></li>
        <li>Configuration</li>
        <li class="active">Role Management</li>
    </ol>
</section>
<section class="content">
    <div class="box box-default" style="padding: 10px 10px 10px 10px">
        <div class="box-header with-border">
            <h4 class="box-title"></h4>
            <button class="btn btn-success" onclick="newRole()"><i class="fa fa-plus-circle"></i> Add Role</button>
        </div><!-- /.box-header -->

        <table id="role-dt-table" class="table display compact table-striped table-bordered" cellspacing="0" width="100%">
            <thead>
                <tr>
                    <th></th>
                    <th>Id</th>
                    <th>Role Description</th>
                    <th>Role Short Tag</th>
                    <th></th>
                </tr>
            </thead>
            <tbody>
            </tbody>
        </table>
    </div>
</section>
<!-- Bootstrap modal -->
<div class="modal fade" id="role-fm-modal" role="dialog" data-keyboard="false" data-backdrop="static">
    <div class="modal-dialog">
        <div class="modal-content panel-primary">
            <div class="modal-header panel-heading">
                <button type="button" id="modelColse" class="close" data-dismiss="modal" aria-label="Close"><span aria-hidden="true">&times;</span></button>
                <h3 class="modal-title">Role Management</h3>
            </div>
            <div class="modal-body form">
                <div class="error-msg" ></div>
                <form class="form-horizontal clone-form">
                    <input type="hidden" name="<?php echo $this->security->get_csrf_token_name(); ?>" value="<?php echo $this->security->get_csrf_hash(); ?>">
                    <input type="hidden" value="" name="id"/>
                    <div class="form-body">
                        <div class="form-group">
                            <label class="control-label col-md-3">Role Name</label>
                            <div class="col-md-9">
                                <input name="desc" placeholder="Description" class="form-control" type="text"  data-val="true" data-val-required="This field is required.">
                            </div>
                        </div>

                        <div class="form-group">
                            <label class="control-label col-md-3">Role Short Tag</label>
                            <div class="col-md-9">
                                <input name="st" placeholder="Short name without spaces" class="form-control" type="text"  data-val="true" data-val-required="This field is required.">
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

<div class="modal fade" id="role-pmn-fm-modal" role="dialog" data-keyboard="false" data-backdrop="static">
    <div class="modal-dialog" id="modal-dialog">
        <div class="modal-content panel-primary" id="modal-content">
            <div class="modal-header panel-heading">
                <button type="button" id="modelColse" class="close" data-dismiss="modal" aria-label="Close"><span aria-hidden="true">&times;</span></button>
                <h3 class="modal-title"></h3>

            </div>
            <form class="clone-form">
                <div class="modal-body group" id="tree-content" style="max-height: 450px; overflow-y: auto">
                    <div class="erro-msg"></div>
                    <div class="tree-role-permission">                       
                        <input type="hidden" name="<?php echo $this->security->get_csrf_token_name(); ?>" value="<?php echo $this->security->get_csrf_hash(); ?>">
                        <input type="hidden" value="" name="id"/>
                        <div class="tree" >
                            <?php echo $perm_list; ?>
                        </div> 
                    </div>

                </div>

                <div class="modal-footer panel-footer">
                    <button type="submit"  id="submitButton"  class="btn btn-primary" ><i class="fa fa-save"></i> Save</button>
                    <button type="button" id="resetBtn" class="btn btn-danger" data-dismiss="modal"><i class="fa fa-close"></i>  Close</button>
                </div>
            </form>
        </div><!-- /.modal-content -->
    </div><!-- /.modal-dialog -->   
    <script type="text/javascript">

        var role_dt_table;
        $(document).ready(function() {

            //datatables
            role_dt_table = $('#role-dt-table').DataTable({
                "processing": true, //Feature control the processing indicator.
                "serverSide": true, //Feature control DataTables' server-side processing mode.
                "order": [], //Initial no order.

                // Load data for the table's content from an Ajax source
                "ajax": {
                    "url": "<?php echo base_url('role/role_list') ?>",
                    "type": "POST"
                },
                "columns": [
                    {data: 'sl'},
                    {data: 'id'},
                    {data: 'desc'},
                    {data: 'st'},
                    {
                        data: 'id'
                    }
                ],
                "columnDefs": [
                    {
                        "targets": [0],
                        "orderable": false,
                    },
                    {
                        "targets": [-1],
                        "orderable": false
                    },
                    {
                        "targets": [1],
                        "visible": false,
                        "searchable": false
                    }
                ],
                "fnCreatedRow": function(nRow, aData, iDataIndex) {

                    $('td:eq(3)', nRow).html("\
            <a href='#' class='btn btn-success btn-xs' onclick=\"setPermission(" + aData.id + ",\'" + aData.desc + "\')\" title='Set Permissions'><span class='fa fa-sliders' ></span></a> &nbsp;\n\
            <a href='#' class='btn btn-primary btn-xs' title='Edit Role' onclick=\"editRole(" + iDataIndex + ")\"><span class='fa fa-edit' ></span></a> &nbsp; \n\
            <a href='#' class='btn btn-danger btn-xs' onclick='removeRole(" + aData.id + ")' title='Delete Role'><span class='fa fa-trash' ></span></a>");
                    $('td:eq(2)', nRow).html("<span class='label label-primary'>" + aData.st + "</span>");
                }, //Set column definition initialisation properties.
            });
            /////////////////////////////////////////////////////////


            /////////////////////////////////////////////////////////
        });

        ///////////////////////////////////////////////////////////////////////////////////////////

        ///////////////////////////////////////////////////////////////////////////////////////////    
        var urlRole;
        var roleid;
        function newRole() {
            if ($('#role-modal').length) {
                $('#role-modal').remove();
            }
            $('#role-fm-modal').clone()
                    .attr('id', 'role-modal')
                    .appendTo('body'); // show bootstrap modal when complete loaded
            $('#role-modal').modal('show');
            $('.modal-title').text('Create New Role'); // Set title to Bootstrap modal title
            urlRole = '<?php echo base_url('role/new_role'); ?>';
            saveRole(urlRole);
        }

        function editRole(row) {
            var data = role_dt_table.row(row).data();
            if (data) {
                if ($('#role-modal').length) {
                    $('#role-modal').remove();
                }
                $('#role-fm-modal').clone()
                        .attr('id', 'role-modal')
                        .appendTo('body'); // show bootstrap modal when complete loaded
                $('#role-modal').modal('show');
                //$('#perm-modal .clone-form')[0].reset();
                //$('#perm-modal .clone-form').data('bootstrapValidator').resetForm(true);
                $('#role-modal .clone-form').loadJSON(data);
                $('.modal-title').text('Edit Role'); // Set title to Bootstrap modal title
                urlRole = '<?php echo base_url('role/edit_role'); ?>';
                saveRole(urlRole);
            }
        }

        function saveRole(urlRole) {
            ///////////////////////////////form submission/////////////////////
            $('#role-modal .clone-form').bootstrapValidator({
                message: 'This value is not valid',
                feedbackIcons: {
                    valid: 'glyphicon glyphicon-ok',
                    invalid: 'glyphicon glyphicon-remove',
                    validating: 'glyphicon glyphicon-refresh'
                },
                fields: {
                    description: {
                        message: 'The Role description is not valid',
                        validators: {
                            notEmpty: {
                                message: 'The Role description is required'
                            },
                            stringLength: {
                                min: 3,
                                max: 100,
                                message: 'The Permission description must be more than 3 and less than 100 characters long'
                            }
                        }
                    },
                    short_tag: {
                        validators: {
                            notEmpty: {
                                message: 'The short tag is required and can\'t be empty'
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
                    url: urlRole,
                    cache: false,
                    data: $form.serialize(),
                    dataType: 'json',
                    beforeSend: function() {
                        $('#role-modal').block();
                    },
                    success: function(data) {

                        $('#role-modal').unblock();
                        if (data.success) {
                            showMsg('Success', data.msg, 'success');
                            $('#role-modal').modal('hide');
                            reloadRole();
                        } else {
                            showMsg('Error', data.msg, 'danger');
                            $('#role-modal .error-msg').html(data.msg);
                        }

                    }
                });
            });
///////////////////////////////////////////////////////////////////
        }
        function removeRole(idp) {
            if (idp) {
                eModal.confirm('Are you sure to delete this item', 'Confirm to delete')
                        .then(function(r) {
                            $('#main-content').block();
                            $.post('<?php echo base_url('role/remove_role'); ?>', {id: idp}, function(result) {
                                if (result.success) {
                                    reloadRole(); // reload the user data
                                    showMsg('Success', 'Successfully deleted', 'success');
                                } else {
                                    $('#main-content').unblock();
                                    showMsg('Error', result.msg, 'danger');
                                }
                                $('#main-content').unblock();
                            }, 'json').complete(function() {
                                $('#main-content').unblock();
                            });
                        }
                        , function(r) {
                            return '';
                        });
            }
        }

        function reloadRole() {
            role_dt_table.ajax.reload(null, false); //reload datatable ajax
        }

        function setPermission(idp, title) {
            // var row = $('#' + idp).data('all');
            //if (row) {
            if ($('#perm-role-modal').length) {
                $('#perm-role-modal').remove();
            }
            $('#role-pmn-fm-modal').clone()
                    .attr('id', 'perm-role-modal')
                    .appendTo('body'); // show bootstrap modal when complete loaded
            $('#perm-role-modal').modal('show');
            $('.modal-title').text(title + ' :: Set Permission'); // Set title to Bootstrap modal title
            $('#perm-role-modal').block(); //loading ajax
            $.getJSON("<?php echo base_url(); ?>role/get_permission_list_role?id=" + idp, function(data) {

                $('#perm-role-modal .clone-form').loadJSON(data); //loading ajax to form
                $('#perm-role-modal .clone-form [name="id"]').val(idp);
            }).done(function() {
                $('#perm-role-modal').unblock();
                ; //finished ajax
            });
            urlPmn = '<?php echo base_url('permission/edit_permission'); ?>';
            // idPmn = row.id;
            action = 'edit-permission';
            saveRolePermission();
        }


        function saveRolePermission() {
            $('#perm-role-modal ul#role-perm-checkbox').collapsibleCheckboxTree({
                initialState: 'expand' // Options - 'expand' (fully expanded), 'collapse' (fully collapsed) or default
            });
            ///////////////////////////////form submission/////////////////////
            $('#perm-role-modal .clone-form').bootstrapValidator().on('success.form.bv', function(e) {
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
                var p = [];
                $("#perm-role-modal .clone-form input:checkbox:checked").map(function() {
                    p.push($(this).val());
                });
                $.ajax({
                    type: "POST",
                    url: "<?php echo base_url(); ?>role/save_role_permission",
                    cache: false,
                    data: {
                        nodes: p,
                        role: $("#perm-role-modal .clone-form input[name=id]").val()
                    },
                    dataType: 'json',
                    beforeSend: function() {
                        $('#perm-role-modal').block();
                    },
                    success: function(data) {

                        $('#perm-role-modal').unblock();
                        if (data.success) {
                            showMsg('Success', data.msg, 'success');
                            $('#perm-role-modal').modal('hide');
                        } else {
                            $('#perm-role-modal .error-msg').html(data.msg);
                        }

                    }
                });
            });
        }
    </script>
