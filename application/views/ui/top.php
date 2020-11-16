<?php
if (!defined('BASEPATH'))
    exit('No direct script access allowed');

/*
 * To change this license header, choose License Headers in Project Properties.
 * To change this template file, choose Tools | Templates
 * and open the template in the editor.
 */
?>
<div data-options="region:'north'" style="height:50px;">
    <div class="easyui-layout" data-options="fit:true">
        <div data-options="region:'west',border:false" style="width:200px; padding: 8px 10px 0px 10px; text-align: center; background-color: #3c8dbc; color: #fff ">
            <img src="<?php echo base_url(); ?>public/images/logo_32.png" style="vertical-align: middle"  >
            <span style="font-weight: bold; font-size: 1.2em">KERALA POLICE</span>
        </div>
        <div data-options="region:'center',split:false,border:false" style="height:50px;padding: 10px ; text-align: right; background-color: #3c8dbc; color: #fff " >
            <div style="float: left; padding-top: 5px; font-size: 1.5em;">Digital Establishment Register Ver 1.0 <span style="font-size: 0.5em; color: #eeeef7">beta</span></div>
            <a href="#" class="easyui-linkbutton" data-options="iconCls:'icon-dashboard',plain:true" onClick="loadLayout('Dashboard', 'home/dashboard')" style="color: #fff">Dashboard</a>           
            <a href="#" class="easyui-menubutton" data-options="menu:'#mm1',iconCls:'icon-user'" style="color: #fff" ><?php echo $user->p_name; ?></a>

        </div>

        <div id="mm1" style="width:150px;">
            
            
            <div data-options="iconCls:'icon-settings'" onClick="loadLayout('Change Password', 'usermanager/frm_change_password')">Settings</div>
            <div class="menu-sep"></div>
            <div href="<?php echo base_url() . "user/logout"; ?>" data-options="iconCls:'icon-lock'">Logout</div>
        </div>
    </div>

</div>