<?php
if (!defined('BASEPATH'))
    exit('No direct script access allowed');
?>

<table id="dg-role"  class="easyui-datagrid" style="width:100%;height:auto" 
       url="<?php echo base_url('role/role_list'); ?>"
       toolbar="#toolbar-role" pagination="true"  rownumbers="true" fitColumns="true" singleSelect="true" pageSize=10>
    <thead>
        <tr>
            <th data-options="field:'id',width:30">Id</th>
            <th data-options="field:'description',width:80">Role Name</th>
            <th data-options="field:'short_tag',width:80">Role Short Tag</th>
        </tr>
    </thead>
</table>
<div id="toolbar-role"> <a href="#" class="easyui-linkbutton" iconCls="icon-add" plain="true" onclick="newRole()">New Role</a> <a href="#" class="easyui-linkbutton" iconCls="icon-edit" plain="true" onclick="editRole()">Edit Role</a> <a href="#" class="easyui-linkbutton" iconCls="icon-remove" plain="true" onclick="removeRole()">Remove Role</a> <a href="#" class="easyui-linkbutton" iconCls="icon-settings" plain="true" onclick="setPermission()">Set Permission</a> </div>
<div id="dlg-role" class="easyui-dialog" style="width:500px;height:180px;padding:10px 20px"
     closed="true" buttons="#dlg-role-buttons" data-options="modal:true, maximizable:true">
    <div class="ftitle">Add new role</div>
    <form id="fm-role" method="post" novalidate>
        <div class="fitem">
            <label>Role Name:</label>
            <input name="description" class="easyui-validatebox" required="true">
        </div>
        <div class="fitem">
            <label>Short Tag:</label>
            <input name="short_tag" class="easyui-validatebox" required="true">
        </div>
    </form>
</div>
<div id="dlg-role-buttons"> <a href="#" class="easyui-linkbutton" iconCls="icon-ok" onclick="saveRole()">Save</a> <a href="#" class="easyui-linkbutton" iconCls="icon-cancel" onclick="javascript:$('#dlg-role').dialog('close')">Cancel</a> </div>
<div class="clear"></div>
<div id="dlg-role-permission" class="easyui-dialog" style="width:540px;height:480px;padding:10px 20px"
     closed="true" buttons="#dlg-role-buttons" data-options="modal:true, maximizable:true, 	 					 			  toolbar: [{  
     text:'',  
     iconCls:'icon-reload',  
     handler:function(){  
     $('#perm-role-tree').tree('reload');
     }} ]">
    <div class="ftitle">Set Permission</div>
    <ul id="perm-role-tree" class="easyui-tree" >
    </ul>
</div>
<div id="dlg-role-buttons"> <a href="#" class="easyui-linkbutton" iconCls="icon-ok" onclick="saveRolePermission()">Save</a> <a href="#" class="easyui-linkbutton" iconCls="icon-cancel" onclick="javascript:$('#dlg-role-permission').dialog('close')">Cancel</a> </div>

<script type="text/javascript">
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