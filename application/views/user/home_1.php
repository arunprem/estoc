<?php
if (!defined('BASEPATH'))
    exit('No direct script access allowed');
?>
<table id="dg-user" class="easyui-datagrid" style="height:auto" 
			url="<?php echo base_url('usermanager/user_list'); ?>"
			toolbar="#toolbar-user" pagination="true" rownumbers="true"  singleSelect="true" pageSize=10>
  <thead data-options="frozen:true">  
     <tr>
      <th data-options="field:'id',hidden:true"></th>    
      <th data-options="field:'uname'" formatter="formatUser">Username</th>
      <th data-options="field:'role_desc'">Role</th>
      <th data-options="field:'runit'">Role Unit</th>
      <th data-options="field:'utrdesc'">Type</th>
      <th data-options="field:'status'" formatter="formatStatus">status</th>
     </tr>
  </thead>
  <thead>
    <tr>
      <th data-options="field:'uunit'">User Unit</th>
      <th data-options="field:'pen'">PEN</th>
      <th data-options="field:'p_name'">Profile Name</th>
      <th data-options="field:'email'">E-mail</th>
      <th data-options="field:'mob'">Mob</th>
    </tr>
  </thead>
</table>
<div id="toolbar-user"> 
<a href="#" class="easyui-linkbutton" iconCls="icon-add" plain="true" onclick="newUser()">New User</a> 
<a href="#" class="easyui-linkbutton" iconCls="icon-edit" plain="true" onclick="editUser()">Edit User</a> 

<span style="display:block; float:right">
<input class="easyui-searchbox" data-options="prompt:'Search User',searcher:searchUser" style="width:200px">
  </input>
  </span>
</div>
<div id="dlg-user-permission" class="easyui-dialog" style="width:540px;height:480px;padding:10px 20px"
			closed="true" buttons="#dlg-user-perm-buttons" 
            data-options="modal:true, 
            maximizable:true, 	 					 			  
            toolbar: [{  
                    text:'',  
                    iconCls:'icon-reload',  
                    handler:function(){  
                        $('#perm-user-tree').tree('reload');
                    }} ]">
  <div class="ftitle">Set Permission</div>
  <ul id="perm-user-tree" class="easyui-tree" >
  </ul>
</div>
<div id="dlg-user-perm-buttons"> <a href="#" class="easyui-linkbutton" iconCls="icon-ok" onclick="saveUserPermission()">Save</a> <a href="#" class="easyui-linkbutton" iconCls="icon-cancel" onclick="javascript:$('#dlg-user-permission').dialog('close')">Cancel</a> </div>

<div id="user-dlg" >
</div>

<script type="text/javascript">
		var urlUser;
		var userid;
		function newUser(){
			$('#user-dlg').dialog({  
   			 title: 'User Details',  
   			 width: 520,
   			 height: 450,
   			 closed: false,
   			 cache: false,
			 maximizable:true,
   			 href: '<?php echo base_url("usermanager/frm_new_user"); ?>',  
   			 modal: true,
			 onLoad:function(){  
       			$('#frm-new-user').form('clear');
				urlUser = '<?php echo base_url("usermanager/new_user") ;?>';
   			 },
			 buttons:[{
				text:'Save',
				handler:function(){
					saveUser('frm-new-user');
					}
			},{
				text:'Close',
				handler:function(){
					$('#user-dlg').dialog('close');
					}
			}]  
			});  
		}
		function editUser(){
			var row = $('#dg-user').datagrid('getSelected');
			if (row){
				//loadUserDialog('frm-edit-user');
				///////////////////////////////////
				$('#user-dlg').dialog({ 
				cache:false, 
   			 title: 'User Details',  
   			 width: 520,
   			 height: 450,
   			 closed: false,
   			 cache: false,
			 maximizable:true,
			 href:'<?php echo base_url("usermanager/frm_edit_user") ;?>',
   			 modal: true,
			 onLoad:function(){  
       			$('#frm-edit-user').form('clear');
				$('#frm-edit-user').form('load',row);
				$('#utuser').combogrid('setText',row.uunit);
				$('#utrole').combogrid('setText',row.runit);
				urlUser = '<?php echo base_url() ;?>usermanager/edit_user?id='+row.iduser;
   			 },
			 buttons:[{
				text:'Update',
				handler:function(){
					saveUser('frm-edit-user');
					}
			},{
				text:'Close',
				handler:function(){
					$('#user-dlg').dialog('close');
					}
			}]  
			});  

				///////////////////////////////////
				
			}
		}

		function saveUser(frm){
			$('#'+frm).form('submit',{
				url: urlUser,
				onSubmit: function(){
					return $(this).form('validate');
				},
				success: function(result){
					
					var result = eval('('+result+')');
					if (result.success){
								// close the dialog
						$('#user-dlg').dialog('close');
						$('#dg-user').datagrid('reload');// reload the user data
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
		
	
	function formatStatus(val,row){  
    if (val == 1){  
        return '<span style="color:Green; font-weight:bold">Active</span>';  
    } else {  
         return '<span style="color:Red;font-weight:bold">Blocked</span>';   
    }  
}  

function formatUser(val,row){  
        return '<span style="font-weight:bold">'+val+'</span>';  
    
}  
		
	function searchUser(val){
			$('#dg-user').datagrid('load',{
				key:val
			});
		
		}	
	
	
	$(function(){
		
		$('#dg-user').datagrid({
			onDblClickRow:function(index,row){
				editUser();
				}
			});
		
		
		
		})
	</script>