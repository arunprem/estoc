<?php
if (!defined('BASEPATH'))
    exit('No direct script access allowed');
?>
<table id="unit-tree" class="easyui-tree" style="width:100%;height:auto"  
       data-options="  
       url: '<?php echo base_url('unit/unit_tree'); ?>',
       method: 'get',  
       rownumbers: true,  
       idField: 'id',  
       treeField: 'unit_name'
       ">  
    <thead>  
        <tr>  
            <th field="unit_name" width="220">Name</th>  
            <th field="utype" width="100" align="right">Unit type</th>  
        </tr>  
    </thead>  
</table>   
<script>
    
</script>