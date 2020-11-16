<?php
if (!defined('BASEPATH'))
    exit('No direct script access allowed');
?>
<div style="padding:10px">   
    <form id="frm-edit-user" method="post" novalidate>
        <div class="fitem">
            <label>Username:</label>
            <input name="uname" class="easyui-validatebox" disabled="disabled" id="uname" >
        </div>
        <div class="fitem">
            <label>Password:</label>
            <input name="password" class="easyui-validatebox"  type="password" validType="minLength['8']">
        </div>
        <div class="fitem">
            <label>User Role:</label>
            <input class="easyui-combobox" name="urole" data-options="  
                   valueField:'id',  
                   textField:'description', 
                   url:'<?php echo base_url("role/role_list_combo"); ?>',
                   panelHeight:'auto'" required="true" >  
        </div>
        <div class="fitem">
            <label>User Unit:</label>
            <input id="utuser" name="utuser" >
        </div>
        <div class="fitem">
            <label>User Role:</label>
            <input id="utrole" name="utrole" >
        </div>
        <div class="fitem">
            <label>Name:</label>
            <input name="p_name" class="easyui-validatebox" required="true">
        </div>
        <div class="fitem">
            <label>PEN Number:</label>
            <input name="pen" class="easyui-validatebox" validType="isNumber">
        </div>
        <div class="fitem">
            <label>E mail:</label>
            <input name="email" class="easyui-validatebox" validType="email">
        </div>  
        <div class="fitem">
            <label>Mobile:</label>
            <input name="mob" class="easyui-validatebox" validType="isNumber">
        </div> 
        <div class="fitem">
            <label>Status:</label>
            <select class="easyui-combobox" name="status" style="width:150px;" data-options="panelHeight: 'auto', required:true" >  
                <option value="1" selected="selected">Active</option>  
                <option value="2">Non Active</option> 
            </select> 
        </div> 
    </form>       
</div>
</div>

<script>

    $('#utuser').combogrid({
        panelWidth: 550,
        delay: 500,
        mode: 'remote',
        idField: 'id',
        textField: 'utname',
        fitColumns: true,
        url: '<?php echo base_url("unit/unit_combo"); ?>',
        columns: [[
                {field: 'id', hidden: 'true'},
                {field: 'ncrb_id', title: 'code', width: 60},
                {field: 'utname', title: 'Unit', width: 250},
                {field: 'rank', title: 'Unit Head', width: 250},
                {field: 'utype', title: 'Type', width: 120}
            ]]
    });

    $('#utrole').combogrid({
        panelWidth: 550,
        delay: 500,
        mode: 'remote',
        idField: 'id',
        textField: 'utname',
        fitColumns: true,
        loadMsg: 'Please wait',
        url: '<?php echo base_url("unit/unit_combo"); ?>',
        columns: [[
                {field: 'id', hidden: 'true'},
                {field: 'ncrb_id', title: 'code', width: 60},
                {field: 'utname', title: 'Unit', width: 250},
                {field: 'rank', title: 'Unit Head', width: 250},
                {field: 'utype', title: 'Type', width: 120}
            ]]
    });


</script>