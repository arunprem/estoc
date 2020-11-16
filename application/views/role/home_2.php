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
            <button class="btn btn-success" onclick="addRole()"><i class="fa fa-plus-circle"></i> Add Role</button>
        </div><!-- /.box-header -->

        <table id="role-dt" class="table display compact table-striped table-bordered" cellspacing="0" width="100%">
            <thead>
                <tr>
                    <th>Id</th>
                    <th>Name</th>
                    <th>Short Tag</th>
                    <th style="width:300px;">Action</th>
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
                <h3 class="modal-title">Role Management Form</h3>
            </div>


            <div class="modal-body form">
                <form class="form-horizontal clone-form">
                    <input type="hidden" name="<?php echo $this->security->get_csrf_token_name(); ?>" value="<?php echo $this->security->get_csrf_hash(); ?>">
                    <input type="hidden" value="" name="id"/>
                    <div class="form-body">
                        <div class="form-group">
                            <label class="control-label col-md-3">Role Name</label>
                            <div class="col-md-9">
                                <input name="gpname" placeholder="Role Name" class="form-control" type="text"  data-val="true" data-val-required="This field is required.">
                            </div>
                        </div>

                        <div class="form-group">
                            <label class="control-label col-md-3">Description</label>
                            <div class="col-md-9">
                                <textarea name="description" placeholder="Role Description"class="form-control"></textarea>
                            </div>
                        </div>


                        <div class="form-group">
                            <label class="control-label col-md-3">Role Status</label>
                            <div class="col-md-9">
                                <select id="status" name="status" class="form-control">
                                    <option value="1">Active</option>
                                    <option value="0">Deactive</option>
                                </select>
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
            <div class="modal-body group" id="tree-content">
                <div class="tree-Role">
                    <!-- in this example the tree is populated from inline HTML -->
                </div>
                <div class="modal-footer">
                    <button type="submit"  id="getChecked"  class="btn btn-warning" onclick="saveTages()"><i class="fa fa-tags" ></i> Tag</button>
                    <button type="button" id="resetBtn" class="btn btn-danger" data-dismiss="modal"><i class="fa fa-close"></i>  Close</button>
                </div>
            </div>
        </div><!-- /.modal-content -->
    </div><!-- /.modal-dialog -->
</div>
<script type="text/javascript">

    var role_dt;
    $(document).ready(function () {

        role_dt = $('#role-dt').DataTable({
            "processing": true, //Feature control the processing indicator.
            "serverSide": true, //Feature control DataTables' server-side processing mode.
                        // Load data for the table's content from an Ajax source
            "ajax": {
                "url": "<?php echo base_url('role/role_list') ?>",
                "type": "POST",
                "error": function () {
                    showMsg('Error', 'Error Loading data', 'danger');
                }
            },
            "columns": [
                {"data": "idrole"},
                {"data": "description"},
                {"data": "short_tag"},
                {"data": "idrole"}
            ],
            //Set column definition initialisation properties.
            "columnDefs": [
                {
                    "targets": [-1], //last column
                    "orderable": false, //set not orderable
                }

            ]
        });

        /*
         
         $('#groupfrom').bootstrapValidator({
         message: 'This value is not valid',
         fields: {
         gpname: {
         message: 'The Group Name is not valid',
         validators: {
         notEmpty: {
         message: 'The Group Name is required and can\'t be empty'
         }
         
         
         }
         },
         description: {
         message: 'The Description is not valid',
         validators: {
         notEmpty: {
         message: 'The Description is required and can\'t be empty'
         }
         
         
         }
         },
         status: {
         validators: {
         notEmpty: {
         message: 'The status menu is required and can\'t be empty'
         }
         
         
         }
         }
         
         }
         }).on('success.form.bv', function (evt) {
         
         
         if (save_method === "add-group")
         {
         groupUrl = "<?php echo base_url('groupmanagement/groupAdd') ?>";
         }
         else {
         groupUrl = "<?php echo base_url('groupmanagement/groupUpdate') ?>";
         }
         
         
         //alert("script running");
         evt.preventDefault();
         var url = groupUrl;
         var postData = $(this).serialize();
         
         $.post(url, postData, function (o) {
         
         if (o.status == true)
         {
         $('#inners').show();
         $("#inners").html('<div class="box box-success box-solid">\n\
         <div class="box-header with-border">\n\
         <h3 class="box-title"><i class="fa fa-check"> </i> Success</h3>\n\
         <div class="box-tools pull-right">\n\
         <button type="button" class="btn btn-box-tool" data-widget="collapse"><i class="fa fa-minus"></i>\
         </div>\n\
         </div>\n\
         <div class="box-body">'
         + o.sucsses +
         ' </div>\n\
         </div>');
         
         setTimeout(function () {
         $('#inners').fadeOut('slow');
         }, 3500);
         $('#group').modal('hide');
         reload_table();
         
         }
         else if (o.status == false)
         {
         $('#inners').show();
         $("#inners").html('<div class="box box-danger box-solid">\n\
         <div class="box-header with-border">\n\
         <h3 class="box-title"><i class="fa fa-close"></i> Error </h3>\n\
         <div class="box-tools pull-right">\n\
         <button type="button" class="btn btn-box-tool" data-widget="collapse"><i class="fa fa-minus"></i>\
         </div>\n\
         </div>\n\
         <div class="box-body">'
         + o.error +
         ' </div>\n\
         </div>');
         
         setTimeout(function () {
         $('#inners').fadeOut('slow');
         }, 3500);
         
         }
         
         
         }, 'json');
         
         });
         */

    });






///////////////////////////////////////////////////////////////////////////////////////////    
    var urlRole;
    var roleid;
    function newRole() {
        $('#dlg-role').dialog('open').dialog('setTitle', 'New Role');
        $('#fm-role').form('clear');
        urlRole = '<?php echo base_url('role/new_role'); ?>';
    }
    function editRole() {
        var row = $('#dg-role').datagrid('getSelected');
        if (row) {
            $('#dlg-role').dialog('open').dialog('setTitle', 'Edit Role');
            $('#fm-role').form('load', row);
            urlRole = '<?php echo base_url(); ?>role/edit_role?id=' + row.id;
        }
    }
    function saveRole() {
        $('#fm-role').form('submit', {
            url: urlRole,
            onSubmit: function () {
                return $(this).form('validate');
            },
            success: function (result) {

                var result = eval('(' + result + ')');
                if (result.success) {
                    $('#dlg-role').dialog('close');		// close the dialog
                    $('#dg-role').datagrid('reload');// reload the user data
                    $.messager.show({
                        title: 'Success',
                        msg: result.msg
                    });
                } else {
                    $.messager.show({
                        title: 'Error',
                        msg: result.msg
                    });
                }
            }
        });
    }
    function removeRole() {
        var row = $('#dg-role').datagrid('getSelected');
        if (row) {
            $.messager.confirm('Confirm', 'Are you sure you want to remove this Role?', function (r) {
                if (r) {
                    ///////////////////////

                    $.ajax({
                        url: '<?php echo base_url('role/remove_role'); ?>',
                        type: 'post',
                        dataType: 'json',
                        data: {
                            id: row.id
                        },
                        success: function (result) {

                            //var result = eval('('+result+')');

                            if (result.success) {
                                $('#dg-role').datagrid('reload');
                                $.messager.show({
                                    title: 'Success',
                                    msg: result.msg
                                });
                            } else {
                                $.messager.show({
                                    title: 'Error',
                                    msg: result.msg
                                });
                            }
                        }

                    });
                }
            });
        }
    }

    function setPermission() {
        var row = $('#dg-role').datagrid('getSelected');
        if (row) {
            $('#dlg-role-permission').dialog('open').dialog('setTitle', 'Set Permission for ' + row.description);
            urlRole = '<?php echo base_url(); ?>role/save_role_permission';
            roleid = row.id;
            $('#perm-role-tree').tree({
                url: '<?php echo base_url(); ?>role/get_permission_list_role?id=' + roleid,
                checkbox: true
            });
        }
    }

    function saveRolePermission() {
        var chk = $('#perm-role-tree').tree('getChecked');
        var ind = $('#perm-role-tree').tree('getChecked', 'indeterminate');
        var nodes = chk.concat(ind);
        var p = new Array();
        for (var i = 0; i < nodes.length; i++) {
            p[i] = nodes[i].id;
        }

        if (p) {
            $.ajax({
                url: urlRole,
                type: 'post',
                dataType: 'json',
                data: {
                    nodes: p,
                    role: roleid
                },
                success: function (result) {

                    //var result = eval('('+result+')');

                    if (result.success) {
                        $('#perm-tree').tree('reload');
                        $('#dlg-role-permission').dialog('close');
                        $.messager.show({
                            title: 'Success',
                            msg: result.msg
                        });
                    } else {
                        $.messager.show({
                            title: 'Error',
                            msg: result.msg
                        });
                    }
                }

            });


        }

    }
</script>