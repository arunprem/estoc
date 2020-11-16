<?php
if (!defined('BASEPATH'))
    exit('No direct script access allowed');
?>
<table id="dg-unit"  class="easyui-datagrid" style="width:100%;height:auto" 
       url="<?php echo base_url('unit/unit_list'); ?>"
       toolbar="#toolbar-unit" pagination="true" rownumbers="true" fitColumns="true" singleSelect="true" pageSize=10>
    <thead>
        <tr>
            <th data-options="field:'id',width:80">Id</th>
            <th data-options="field:'ncrb_id',width:120">Unit Code</th>
            <th data-options="field:'uname',width:200">Unit Name</th>
            <th data-options="field:'utype',width:200">Unit Type</th>
            <th data-options="field:'punit',width:280">Parent Unit</th>
            <th data-options="field:'pid',hidden:'true'">Parent id</th>
            <th data-options="field:'status',width:200,formatter:UnitFormatter">Status</th>
        </tr>
    </thead>
</table>
<div id="toolbar-unit"> 
    <a href="#" class="easyui-linkbutton" iconCls="icon-add" plain="true" onclick="newUnit()">New Unit</a> 
    <a href="#" class="easyui-linkbutton" iconCls="icon-edit" plain="true" onclick="editUnit()">Edit Unit</a> 
    <a href="#" class="easyui-linkbutton" iconCls="icon-cancel" plain="true" onclick="removeUnit()">Deactivate</a>  
    <a href="#" class="easyui-linkbutton" iconCls="icon-ok" plain="true" onclick="includeUnit()">Activate</a> 
    <a href="#" class="easyui-linkbutton" iconCls="icon-tree" plain="true" onclick="unitTree()">Unit Hierarchy</a> 
    <span style="display:block; float:right">
        <input class="easyui-searchbox" data-options="prompt:'Unit Name',searcher:searchUnit" style="width:200px">
        </input>
    </span>
</div>
<div id="dlg-unit" class="easyui-dialog" style="width:500px;height:300px;padding:10px 20px"
     closed="true" buttons="#dlg-unit-buttons" data-options="modal:true, maximizable:true">
    <div class="ftitle">Unit Details</div>
    <form id="fm-unit" method="post" novalidate>
        <div class="fitem">
            <label>Unit Code:</label>
            <input name="ncrb_id" class="easyui-validatebox"  validType="isNumber">
        </div>
        <div class="fitem">
            <label>Unit name:</label>
            <input name="uname" class="easyui-validatebox" required="true">
        </div>
        <div class="fitem">
            <label>Unit type:</label>
            <input class="easyui-combobox" name="utypeid" data-options="  
                   valueField:'id',  
                   textField:'unit_type_desc', 
                   url:'<?php echo base_url('unit_type/combo_list'); ?>',
                   panelHeight:'200'" 
                   id="utypeid" required="true"> 
        </div>
        <div class="fitem">
            <label>Head of Unit:</label>
            <input class="easyui-combobox" name="hou" data-options="  
                   valueField:'id',  
                   textField:'rname', 
                   url:'<?php echo base_url('rank/rank_head_combo'); ?>',
                   panelHeight:'200',
                   panelWidth:'300'
                   " 
                   id="hou" required="true"> 
        </div>
        <div class="fitem">
            <label>Parent Unit:</label>
            <input id="pid" name="pid" >
        </div>
    </form>
</div>
<div id="dlg-unit-buttons"> 
    <a href="#" class="easyui-linkbutton" iconCls="icon-ok" onclick="saveUnit()">Save</a> <a href="#" class="easyui-linkbutton" iconCls="icon-cancel" onclick="javascript:$('#dlg-unit').dialog('close')">Cancel</a> 
</div>
<div class="clear"></div>
<div id="unit-tree-dlg" class="easyui-dialog" closed="true">
    <table id="unit-tree" class="easyui-treegrid" style="width:100%;height:auto"  
           data-options="  
           url: '<?php echo base_url('unit/unit_tree'); ?>',
           method: 'get',  
           rownumbers: true,  
           idField: 'id',  
           treeField: 'unit_name',  
           loadFilter: unitTreeFilter  
           ">  
        <thead>  
            <tr>  
                <th field="unit_name" width="220">Name</th>  
                <th field="utype" width="100" align="right">Unit type</th>  
            </tr>  
        </thead>  
    </table>   
</div>
<script>
    var urlUnit;
    var idunit;
    function newUnit() {
        $('#dlg-unit').dialog('open').dialog('setTitle', 'New Unit');
        $('#fm-unit').form('clear');
        urlUnit = '<?php echo base_url("unit/new_unit"); ?>';
    }
    function editUnit() {
        var row = $('#dg-unit').datagrid('getSelected');

        if (row) {
            $('#dlg-unit').dialog('open').dialog('setTitle', 'Edit Unit');
            $('#fm-unit').form('clear');
            $('#fm-unit').form('load', row);
            $('#pid').combogrid('setText', row.punit);
            urlUnit = '<?php echo base_url(); ?>unit/edit_unit?id=' + row.id;

        }
    }
    function saveUnit() {
        $('#fm-unit').form('submit', {
            url: urlUnit,
            onSubmit: function () {
                return $(this).form('validate');
            },
            success: function (result) {

                var result = eval('(' + result + ')');
                if (result.success) {
                    $('#dlg-unit').dialog('close');		// close the dialog
                    $('#dg-unit').datagrid('reload');// reload the user data
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
    function removeUnit() {
        var row = $('#dg-unit').datagrid('getSelected');
        if (row) {
            $.messager.confirm('Confirm', 'Are you sure you want to remove this Unit?', function (r) {
                if (r) {
                    ///////////////////////

                    $.ajax({
                        url: '<?php echo base_url("unit/remove_unit"); ?>',
                        type: 'post',
                        dataType: 'json',
                        data: {
                            id: row.id
                        },
                        success: function (result) {

                            if (result.success) {
                                $('#dg-unit').datagrid('reload');
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
    function includeUnit() {
        var row = $('#dg-unit').datagrid('getSelected');
        if (row) {
            $.messager.confirm('Confirm', 'Are you sure you want to include this Unit?', function (r) {
                if (r) {
                    ///////////////////////

                    $.ajax({
                        url: '<?php echo base_url("unit/include_unit"); ?>',
                        type: 'post',
                        dataType: 'json',
                        data: {
                            id: row.id
                        },
                        success: function (result) {

                            if (result.success) {
                                $('#dg-unit').datagrid('reload');
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
    function searchUnit(val) {
        $('#dg-unit').datagrid('load', {
            key: val
        });

    }
    function UnitFormatter(val, row) {
        if (val == 1) {
            return '<span style="color:Green; font-weight:bold ">Active</span>';
        } else if (val == 0) {
            return '<span style="color:Red;font-weight:bold">Non Active</span>';
        }
    }
    $('#pid').combogrid({
        panelWidth: 550,
        delay: 500,
        mode: 'remote',
        idField: 'id',
        textField: 'utname',
        fitColumns: true,
        required: "true",
        url: '<?php echo base_url("unit/unit_combo"); ?>',
        columns: [[
                {field: 'id', hidden: 'true'},
                {field: 'ncrb_id', title: 'code', width: 60},
                {field: 'utname', title: 'Unit', width: 250},
                {field: 'rank', title: 'Unit Head', width: 250},
                {field: 'utype', title: 'Type', width: 120}
            ]]
    });

    function unitTree() {
        $('#unit-tree-dlg').dialog({
            title: 'Unit Hierarchy',
            width: 650,
            height: 450,
            closed: false,
            cache: false,
            maximizable: true,
            modal: true,
            onResize: function () {
                $(this).dialog('center');
            },
            buttons: [{
                    text: 'Reload',
                    handler: function () {
                        $('#unit-tree').treegrid('reload');
                    }
                },
                {
                    text: 'Close',
                    handler: function () {
                        $('#unit-tree-dlg').dialog('close');
                    }
                }]
        });
        $('#unit-tree-dlg').dialog('maximize');
    }

    function unitTreeFilter(data, parentId) {
        function setData() {
            var todo = [];
            for (var i = 0; i < data.length; i++) {
                todo.push(data[i]);
            }
            while (todo.length) {
                var node = todo.shift();
                if (node.children) {
                    node.state = 'closed';
                    node.children1 = node.children;
                    node.children = undefined;
                    todo = todo.concat(node.children1);
                }
            }
        }

        setData(data);
        var tg = $(this);
        var opts = tg.treegrid('options');
        opts.onBeforeExpand = function (row) {
            if (row.children1) {
                tg.treegrid('append', {
                    parent: row[opts.idField],
                    data: row.children1
                });
                row.children1 = undefined;
                tg.treegrid('expand', row[opts.idField]);
            }
            return row.children1 == undefined;
        };
        return data;
    }
</script>