<?php
if (!defined('BASEPATH'))
    exit('No direct script access allowed');
?>
<table id="dg-wmenu" class="easyui-datagrid" style="width:100%;height:auto" url="<?php echo base_url('menu/menu_list'); ?>" toolbar="#toolbar-wmenu" pagination="true" rownumbers="true" fitColumns="true" singleSelect="true" pageSize=10>
    <thead>
        <tr>
            <th data-options="field:'menu_id',width:30">Id</th>
            <th data-options="field:'title',width:80">Menu</th>
            <th data-options="field:'alias',width:80">Menu alias</th>
            <th data-options="field:'published',width:80,formatter:formatMenuPublishedStatus">Status</th>
            <th data-options="field:'menutype_title',width:80,">Menu Type</th>
            <th data-options="field:'lang',width:80,formatter:formatMenuLangStatus">Language</th>
            <th data-options="field:'lang_status',width:80,formatter:formatMenuTranslationStatus">Translation</th>
        </tr>
    </thead>
</table>
<div id="toolbar-wmenu"> 
    <div class="easyui-panel" style="padding:5px;">
        <a href="#" class="easyui-menubutton" data-options="menu:'#new-menu',iconCls:'icon-add'" >New</a>
    </div>
    <div id="new-menu" style="width:auto;">
        <?php foreach ($menutypes as $menu) { ?>
            <div data-options="iconCls:'icon-mini-add'" onclick="newMenu('<?php echo $menu["alias"]; ?>')"><?php echo $menu["title"]; ?></div>
        <?php } ?>
    </div>
</div>
<div id="dlg-wmenu" class="easyui-dialog"
     closed="true" buttons="#dlg-wmenu-buttons" data-options="modal:true, maximizable:true">
</div>
<div id="dlg-wmenu-buttons"> <a href="#" class="easyui-linkbutton" iconCls="icon-ok" onclick="saveMenu()">Save</a> <a href="#" class="easyui-linkbutton" iconCls="icon-cancel" onclick="javascript:$('#dlg-wmenu').dialog('close')">Cancel</a> </div>

<script type="text/javascript">
    var urlMenu;
    var wmenuId;

    function newMenu(ptype) {
        urlmenuForm = '<?php echo base_url(); ?>menu/new_menu_form/'+ptype+'';
        urlMenu = '<?php echo base_url(); ?>menu/new_menu';
        $('#dlg-wmenu').dialog({
            title: 'New Menu',
            width: 820,
            height: 650,
            closed: false,
            cache: false,
            href: urlmenuForm,
            modal: true,
            onResize:function(){
                $(this).dialog('center');
            }
        });
    }

    function editMenu() {
        var row = $('#dg-wmenu').datagrid('getSelected');
        if (row) {
            $('#dlg-wmenu').dialog('open').dialog('setTitle', 'Edit Unit Type');
            $('#frm-wmenu').form('load', row);
            urlMenu = '<?php echo base_url(); ?>menu/edit_menu?id=' + row.id;
        }
    }

    function saveMenu() {
        $('#frm-wmenu').form('submit', {
            url: urlMenu,
            onSubmit: function () {
                return $(this).form('validate');
            },
            success: function (result) {

                var result = eval('(' + result + ')');
                if (result.success) {
                    $('#dlg-wmenu').dialog('close');		// close the dialog
                    $('#dg-wmenu').datagrid('reload');// reload the user data
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

    function formatMenuPublishedStatus(val) {
        if (val == 1) {
            return '<span style="color:Green; font-weight:bold ">Y</span>';
        } else if (val == 0) {
            return '<span style="color:Red;font-weight:bold">N</span>';
        }
    }
    function formatMenuLangStatus(val) {

        return '<span style="color:Green; font-weight:bold ">' + val + '</span>';

    }
    function formatMenuTranslationStatus(val) {
        if (val == 1) {
            return '<span style="color:Green; font-weight:bold ">Y</span>';
        } else if (val == 0) {
            return '<span style="color:Red;font-weight:bold">N</span>';
        }
    }

</script>