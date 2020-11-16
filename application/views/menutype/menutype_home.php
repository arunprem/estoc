<?php
if (!defined('BASEPATH'))
    exit('No direct script access allowed');
?>


<table id="dg-menutype" class="easyui-datagrid" style="width:100%;height:auto" 
       url="<?php echo base_url('menutype/menutype_list'); ?>"
       toolbar="#toolbar-menutype" pagination="true" rownumbers="true" fitColumns="true" singleSelect="true" pageSize=10>
    <thead>
        <tr>
            <th data-options="field:'id',width:30">Id</th>
            <th data-options="field:'menutype',width:80">Menu Type</th>
            <th data-options="field:'description',width:80">Menu description</th>
            <th data-options="field:'status',width:80,formatter:formatMenutypeStatus">Status</th>
        </tr>
    </thead>
</table>
<div id="toolbar-menutype"> 
    <a href="#" class="easyui-linkbutton" iconCls="icon-add" plain="true" onclick="newMenutype()">New Menu Type</a> 
    <a href="#" class="easyui-linkbutton" iconCls="icon-edit" plain="true" onclick="editMenutype()">Edit Menu Type</a> 
</div>
<div id="dlg-menutype" class="easyui-dialog" style="width:500px;height:300px; padding:10px 20px"
     closed="true" buttons="#dlg-menutype-buttons" data-options="modal:true, maximizable:true">
    <div class="ftitle">Menu Type Details</div>
    <form id="frm-menutype" method="post" novalidate>
        <div class="fitem">
            <label>Menu Type:</label>
            <input name="menutype" class="easyui-validatebox" required="true">
        </div>
        <div class="fitem">
            <label>Description:</label>
            <input name="description" class="easyui-validatebox">
        </div>
        <div class="fitem">
            <label>Message:</label>
            <textarea name="message" id="message" ><p>Example data</p></textarea>
            <?php echo display_ckeditor($ckeditor); ?>
        </div>
        
        <div class="fitem">
            <label>Status:</label>
            <select class="easyui-combobox" name="status" required="true" panelHeight="auto" >
                <option value="1" selected >Active</option>
                <option value="0">Non Active</option>
            </select>
        </div>
    </form>
</div>
<div id="dlg-menutype-buttons"> <a href="#" class="easyui-linkbutton" iconCls="icon-ok" onclick="saveMenutype()">Save</a> <a href="#" class="easyui-linkbutton" iconCls="icon-cancel" onclick="javascript:$('#dlg-menutype').dialog('close')">Cancel</a> </div>

<script type="text/javascript">
    var urlMenutype;
    var menutypeId;
    function newMenutype() {
        $('#dlg-menutype').dialog('open').dialog('setTitle', 'New Menu Type');
        $('#frm-menutype').form('clear');
        urlMenutype = '<?php echo base_url("menutype/new_menutype"); ?>';
    }
    function editMenutype(){
        var row = $('#dg-menutype').datagrid('getSelected');
        if (row) {
            $('#dlg-menutype').dialog('open').dialog('setTitle', 'Edit Unit Type');
            $('#frm-menutype').form('load', row);
            urlMenutype = '<?php echo base_url(); ?>menutype/edit_menutype?id=' + row.id;
        }
    }
    function saveMenutype() {
        $('#frm-menutype').form('submit', {
            url: urlMenutype,
            onSubmit: function () {
                return $(this).form('validate');
            },
            success: function (result) {

                var result = eval('(' + result + ')');
                if (result.success) {
                    $('#dlg-menutype').dialog('close');		// close the dialog
                    $('#dg-menutype').datagrid('reload');// reload the user data
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
    
    function formatMenutypeStatus(val){
    if (val == 1) {
            return '<span style="color:Green; font-weight:bold ">Active</span>';
        } else if (val == 0) {
            return '<span style="color:Red;font-weight:bold">Non Active</span>';
        }
    }

</script>
