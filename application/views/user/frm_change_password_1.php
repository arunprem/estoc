<?php
if (!defined('BASEPATH'))
    exit('No direct script access allowed');
?>
<div style="padding:10px; width:400px" class="formbox" id="change-pass-wrapper">  
    <h2>Change Password </h2> 
    <form id="frm-change-pass" method="post" novalidate>
        <div class="fitem">
            <label>Old Password:</label>
            <input name="opwd" class="easyui-validatebox" required="true" type="password"  />
        </div>
        <div class="fitem">
            <label>New Password:</label>
            <input name="npwd" class="easyui-validatebox" required="true" type="password" id="npwd" validType="minLength['8']" >
        </div>
        <div class="fitem">
            <label>Confirm Password:</label>
            <input name="cpwd" class="easyui-validatebox" required="true" type="password" validType="equals['#npwd']">
        </div>
        <div id="password-change-buttons" style="margin-left:100px"> <a href="#" class="easyui-linkbutton" iconCls="icon-ok" onclick="changePassword()">Change</a> <a href="#" class="easyui-linkbutton" iconCls="icon-cancel" onclick="javascript:$('#frm-change-pass').form('clear')">Clear</a> </div>
    </form>       
</div>
<script>
function changePassword(){
	$('#frm-change-pass').form('submit',{
				url: '<?php echo base_url();?>usermanager/change_password',
				onSubmit: function(){
					return $(this).form('validate');
				},
				success: function(result){
					$('#frm-change-pass').form('clear');
					var result = eval('('+result+')');
					if (result.success){
                                            $('#change-pass-wrapper').html(result.msg);
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
</script> 
