<?php

if (!defined('BASEPATH'))
    exit('No direct script access allowed');
?>
<script type="text/javascript">
		var url;
		function newMenuCat(){
			$('#dlg').dialog('open').dialog('setTitle','New Menu Category');
			$('#fm').form('clear');
			url = '<?php echo base_url() ;?>menucategory/newcat';
		}
		function editMenuCat(){
			var row = $('#dg').datagrid('getSelected');
			if (row){
				$('#dlg').dialog('open').dialog('setTitle','Menu Category');
				$('#fm').form('load',row);
				url = '<?php echo base_url() ;?>menucategory/editcat?id='+row.catid;
			}
		}
		function saveMenuCat(){
			$('#fm').form('submit',{
				url: url,
				onSubmit: function(){
					return $(this).form('validate');
				},
				success: function(result){
					
					var result = eval('('+result+')');
					if (result.success){
						$('#dlg').dialog('close');		// close the dialog
						$('#dg').datagrid('reload');// reload the user data
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
		function removeMenuCat(){
			var row = $('#dg').datagrid('getSelected');
			if (row){
				$.messager.confirm('Confirm','Are you sure you want to remove this Category?',function(r){
					if (r){
						$.post('<?php echo base_url() ;?>menucategory/removect',{id:row.catid},function(result){
							if (result.success){
								$('#dg').datagrid('reload');	// reload the user data
								$.messager.show({	// show error message
									title: 'Success',
									msg: result.msg
								});
							} else {
								$.messager.show({	// show error message
									title: 'Error',
									msg: result.msg
								});
							}
						},'json');
					}
				});
			}
		}
	</script>
    
<table id="dg" title="Menu Category Administration" class="easyui-datagrid" style="width:auto;height:auto" 
			url="<?php echo base_url(); ?>menucategory/menucat_list"
			toolbar="#toolbar" pagination="true" rownumbers="true" fitColumns="true" singleSelect="true" pageSize=20>
		<thead>
			<tr>
				<th data-options="field:'catid',width:30">Category Id</th>
				<th data-options="field:'menu_cat',width:80">Category Name</th>
				<th data-options="field:'description',width:80">Category Description</th>
			</tr>
		</thead>
	</table>
	<div id="toolbar">
		<a href="#" class="easyui-linkbutton" iconCls="icon-add" plain="true" onclick="newMenuCat()">New Category</a>
		<a href="#" class="easyui-linkbutton" iconCls="icon-edit" plain="true" onclick="editMenuCat()">Edit Category</a>
		<a href="#" class="easyui-linkbutton" iconCls="icon-remove" plain="true" onclick="removeMenuCat()">Remove Category</a>
	</div>
	
	<div id="dlg" class="easyui-dialog" style="width:500px;height:180px;padding:10px 20px"
			closed="true" buttons="#dlg-buttons" data-options="modal:true, maximizable:true">
		<div class="ftitle">Add menu category</div>
		<form id="fm" method="post" novalidate>
			<div class="fitem">
				<label>Menu Category:</label>
				<input name="menu_cat" class="easyui-validatebox" required="true">
			</div>
			<div class="fitem">
				<label>Description:</label>
				<textarea name="description" class="easyui-validatebox" ></textarea>
			</div>
		</form>
	</div>
	<div id="dlg-buttons">
		<a href="#" class="easyui-linkbutton" iconCls="icon-ok" onclick="saveMenuCat()">Save</a>
		<a href="#" class="easyui-linkbutton" iconCls="icon-cancel" onclick="javascript:$('#dlg').dialog('close')">Cancel</a>
	</div>
   <div class="clear"></div>