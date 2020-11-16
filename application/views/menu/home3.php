<?php
if (!defined('BASEPATH'))
    exit('No direct script access allowed');
?>

<div id="menu-panel" class="easyui-panel"  style="width:100%;height:auto;padding:10px;">  
    <ul id="menu-tree" class="easyui-tree" data-options="
        url: '<?php echo base_url("menu/menu_list"); ?>',
        animate: true,
        dnd:true,
        onContextMenu: function(e,node){
        e.preventDefault();
        $(this).tree('select',node.target);
        $('#menu-tool').menu('show',{
        left: e.pageX,
        top: e.pageY
        });
        },
        onDrop: function(targetNode, source, point){  
        var targetId = $('#menu-tree').tree('getNode', targetNode).id;

        $.ajax({  
        url: '<?php echo base_url("menu/move_menu"); ?>',  
        type: 'post',  
        dataType: 'json',  
        data: {  
        id: source.id,  
        targetId: targetId,  
        point: point  
        },
        success:function(result){

        //var result = eval('('+result+')');

        if (result.success){
        $('#menu-tree').tree('reload');
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
        " >
    </ul>
    <div id="menu-tool" class="easyui-menu" style="width:120px;">
        <div data-options="iconCls:'icon-add'" > <span>Create</span>
            <div style="width:150px;">
                <div onclick="createMenuBefore()" >Before</div>
                <div onclick="createMenuAfter()" >After</div>
                <div onclick="createMenuChild()" >Child</div>
            </div>
        </div>
        <div onclick="editMenu()" data-options="iconCls:'icon-edit'">Edit</div>
        <div onclick="deleteMenu()" data-options="iconCls:'icon-remove'">Delete</div>
        <div class="menu-sep"></div>
        <div onclick="expandMenu()">Expand</div>
        <div onclick="collapseMenu()">Collapse</div>
    </div>

    <div id="menu-dlg" >

    </div>

    <script type="text/javascript">
        var menuaction;
        var idadmin;
        var url = "<?php echo base_url('menu/new_menu'); ?>";
        function collapseMenu() {
            var node = $('#menu-tree').tree('getSelected');
            $('#menu-tree').tree('collapseMenu', node.target);
        }
        function expandMenu() {
            var node = $('#menu-tree').tree('getSelected');
            $('#menu-tree').tree('expandMenu', node.target);
        }
        function createMenuBefore() {
            loadDialogMenu('frm_new_menu');
            $('#menu-fm').form('clear');
            var node = $('#menu-tree').tree('getSelected');
            idadmin = node.id;
            url = '<?php echo base_url("menu/new_menu"); ?>';
            menuaction = "create-before";

        }

        function createMenuAfter() {
            var node = $('#menu-tree').tree('getSelected');
            idadmin = node.id;
            loadDialogMenu('frm_new_menu');
            $('#menu-fm').form('clear');
            var node = $('#menu-tree').tree('getSelected');
            idadmin = node.id;
            url = '<?php echo base_url("menu/new_menu"); ?>';
            menuaction = "create-after";
        }

        function createMenuChild() {
            var node = $('#menu-tree').tree('getSelected');
            idadmin = node.id;
            loadDialogMenu('frm_new_menu');
            $('#menu-fm').form('clear');
            var node = $('#menu-tree').tree('getSelected');
            idadmin = node.id;
            url = '<?php echo base_url("menu/new_menu"); ?>';
            menuaction = "create-child";

        }
        function editMenu() {
            var row = $('#menu-tree').tree('getSelected');
            if (row) {
                loadDialogMenu('frm_edit_menu?id=' + row.id);
                url = '<?php echo base_url("menu/edit_menu"); ?>';
                idadmin = row.id;
                menuaction = 'editmenu';

            }
        }
        function saveMenu() {
     
            //var params = getMenuParams();
            $('#menu-fm').form('submit', {
                url: url + '?act=' + menuaction + '&id=' + idadmin,
                   
                onSubmit: function (fieldparam) {
                    fieldparam.params = getMenuParams();
                    return $(this).form('validate');
                },
                success: function (result) {
                    var result = eval('(' + result + ')');
                    if (result.success) {
                        $('#menu-dlg').dialog('close');		// close the dialog
                        $('#menu-tree').tree('reload');// reload the user data
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
        function deleteMenu() {
            var row = $('#menu-tree').tree('getSelected');
            if (row) {
                $.messager.confirm('Confirm', 'Are you sure you want to remove this Menu Item ?', function (r) {
                    if (r) {
                        $.post('<?php echo base_url("menu/remove_menu"); ?>', {id: row.id}, function (result) {
                            if (result.success) {
                                $('#menu-tree').tree('reload');	// reload the user data
                                $.messager.show({// show error message
                                    title: 'Success',
                                    msg: result.msg
                                });
                            } else {
                                $.messager.show({// show error message
                                    title: 'Error',
                                    msg: result.msg
                                });
                            }
                        }, 'json');
                    }
                });
            }
        }
        function getMenuParams() {
            var rows = JSON.stringify($('#menuparam-dg').datagrid('getRows'));
            return rows;
        }

        function loadDialogMenu(href) {
            $('#menu-dlg').dialog({
                title: 'Menu Item',
                width: 820,
                height: 550,
                closed: false,
                cache: false,
                maximizable: true,
                href: '<?php echo base_url(); ?>menu/' + href,
                modal: true,
                buttons: [{
                        text: 'Save',
                        handler: function () {
                            saveMenu();
                        }
                    }, {
                        text: 'Close',
                        handler: function () {
                            $('#menu-dlg').dialog('close');
                        }
                    }]
            });

        }

        function expandMenu() {
            var node = $('#menu-tree').tree('getSelected');
            $('#menu-tree').tree('expand', node.target);
        }

        function collapseMenu() {
            var node = $('#menu-tree').tree('getSelected');
            $('#menu-tree').tree('collapse', node.target);
        }

    </script>