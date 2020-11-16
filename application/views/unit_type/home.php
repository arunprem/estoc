                                                                                                                                                                                                                                                                                                                                                                                                                                                                                                                                                                                                                                                                                                                                                                                                                                                                                                                                                                                                                                                                                                                                                                                                                                                                                                                                                                                                                                                                                                                                                                                                                                                                                                                                                                                                                                                                                                                                                                                                                                                                                                                                                                                                                                                                                                                                                                                                                                                                                                                                                                                                                                                                                                                                                                                                                                                                                                                                                                                                                                                                                                                                                                                                                                                                                                                                                                                                                                                                                                                                                                                                                                                                                                                                                                                                                                                                                                                                                                                                                                                                                                                                                                                                                                                                                                                                                                                                                                                                                                                                                                                                                                                                                                                                                                                                                                                                                                                                                                                                                                                                                                                                                                                                                                                                                                                                                                                                                                                                                                                                                                                                                                                                                                                                                                                                                                                                                                                                                                                                                                                                                                                                                                                                                                                                                                                                                                                                                             <?php
if (!defined('BASEPATH'))
    exit('No direct script access allowed');
?>
<section class="content-header">
    <div class="box box-success">
        <div class="box-header with-border">
            <h4 class="box-title">
                <i class="fa fa-list"> </i> Unit Type Management</h4>
        </div>
    </div>
    <ol class="breadcrumb" style="padding: 20px">
        <li><a href="#"><i class="fa fa-dashboard"></i> Home</a></li>
        <li>App Management</li>
        <li class="active">Unit Type Management</li>
    </ol>
</section>
<section class="content">
    <div class="box box-default" style="padding: 10px 10px 10px 10px">
        <div class="box-header with-border">
            <h4 class="box-title"></h4>
            <button class="btn btn-success" onclick="newUnitType()"><i class="fa fa-plus-circle"></i> Add Unit Type</button>
        </div><!-- /.box-header -->

        <table id="unit-type-dt-table" class="table display compact table-striped table-bordered" cellspacing="0" width="100%">
            <thead>
                <tr>
                    <th></th>
                    <th>Id</th>
                    <th>Unit Type Description</th>
                    <th>Status</th>
                    <th></th>
                </tr>
            </thead>
            <tbody>
            </tbody>
        </table>
    </div>
</section>
<!-- Bootstrap modal -->
<div class="modal fade" id="unit-type-fm-modal" role="dialog" data-keyboard="false" data-backdrop="static">
    <div class="modal-dialog">
        <div class="modal-content panel-primary">
            <div class="modal-header panel-heading">
                <button type="button" id="modelColse" class="close" data-dismiss="modal" aria-label="Close"><span aria-hidden="true">&times;</span></button>
                <h3 class="modal-title">Unit Type Management</h3>
            </div>
            <div class="modal-body form">
                <div class="error-msg" ></div>
                <form class="form-horizontal clone-form">
                    <input type="hidden" name="<?php echo $this->security->get_csrf_token_name(); ?>" value="<?php echo $this->security->get_csrf_hash(); ?>">
                    <input type="hidden" value="" name="id"/>
                    <div class="form-body">
                        <div class="form-group">
                            <label class="control-label col-md-3">Unit Type</label>
                            <div class="col-md-9">
                                <input name="desc" placeholder="Description" class="form-control" type="text"  data-val="true" data-val-required="This field is required.">
                            </div>
                        </div>

                        <div class="form-group">
                            <label class="control-label col-md-3">Unit Type Status</label>
                            <div class="col-md-9">
                                <select id="status" name="status" class="form-control">
                                    <option value="1">Active</option>
                                    <option value="0">Deactive</option>
                                </select>
                            </div>
                        </div>                      
                    </div>
                    <div class="modal-footer">
                        <button type="submit"  id="submitButton"  class="btn btn-primary" ><i class="fa fa-save"></i> Save</button>
                        <button type="button" id="resetBtn" class="btn btn-danger" data-dismiss="modal"><i class="fa fa-close"></i>  Close</button>
                    </div>
                </form>
            </div>
        </div><!-- /.modal-content -->
    </div><!-- /.modal-dialog -->
</div><!-- /.modal -->

<script type="text/javascript">
    var unit_type_dt_table;
    $(document).ready(function() {

        //datatables
        unit_type_dt_table = $('#unit-type-dt-table').DataTable({
            "processing": true, //Feature control the processing indicator.
            "serverSide": true, //Feature control DataTables' server-side processing mode.
            "order": [], //Initial no order.

            // Load data for the table's content from an Ajax source
            "ajax": {
                "url": "<?php echo base_url('unit_type/ut_list') ?>",
                "type": "POST"
            },
            "columns": [
                {data: 'sl'},
                {data: 'id'},
                {data: 'desc'},
                {data: 'status'},
                {
                    data: 'id'
                }
            ],
            "columnDefs": [
                {
                    "targets": [0],
                    "orderable": false,
                },
                {
                    "targets": [-1],
                    "orderable": false
                },
                {
                    "targets": [1],
                    "visible": false,
                    "searchable": false
                }
            ],
            "fnRowCallback": function(nRow, aData, iDisplayIndex, iDisplayIndexFull) {

                if (aData.status == 0) {
                    $(nRow).removeClass().addClass('bg-danger');
                }

                return nRow;
            },
            "fnCreatedRow"
                    : function(nRow, aData, iDataIndex) {

                        

                        if (aData.status == 0) {
                            $('td:eq(3)', nRow).html("\ <a href='#' class='btn btn-primary btn-xs'  onclick=\"editUnitType(" + iDataIndex + ")\" data-toggle='tooltip' title='Edit Unit type'><span class='fa fa-edit' ></span></a> &nbsp; \n\
                                                        <a href='#' class='btn btn-success btn-xs' onclick='activateUnitType(" + aData.id + ")' title='Activate UnitType' data-toggle='tooltip'><span class='fa fa-check' ></span></a>");
                            $('td:eq(2)', nRow).html("<span class='label label-danger' data-toggle='tooltip' title='Deactivated'><i class='fa fa-times'></i></span>");
                        } else {
                            $('td:eq(3)', nRow).html("\ <a href='#' class='btn btn-primary btn-xs'  onclick=\"editUnitType(" + iDataIndex + ")\" data-toggle='tooltip' title='Edit Unit type'><span class='fa fa-edit' ></span></a> &nbsp; \n\
                                                        <a href='#' class='btn btn-danger btn-xs' onclick='removeUnitType(" + aData.id + ")' title='Deactivate UnitType' data-toggle='tooltip'><span class='fa fa-trash' ></span></a>");
                            $('td:eq(2)', nRow).html("<span class='label label-success' data-toggle='tooltip' title='Active'><i class='fa fa-check'></i></span>");
                        }

                    }, //Set column definition initialisation properties.
        });
        /////////////////////////////////////////////////////////


        /////////////////////////////////////////////////////////
    });

    var urlUnitType;
    var unitTypeId;

    function newUnitType() {
        if ($('#unit-type-modal').length) {
            $('#unit-type-modal').remove();
        }
        $('#unit-type-fm-modal').clone()
                .attr('id', 'unit-type-modal')
                .appendTo('body'); // show bootstrap modal when complete loaded
        $('#unit-type-modal').modal('show');
        $('.modal-title').text('Create New UnitType'); // Set title to Bootstrap modal title
        urlUnitType = '<?php echo base_url('unit_type/new_ut'); ?>';
        saveUnitType(urlUnitType);
    }

    function editUnitType(row) {
        var data = unit_type_dt_table.row(row).data();
        if (data) {
            if ($('#unit-type-modal').length) {
                $('#unit-type-modal').remove();
            }
            $('#unit-type-fm-modal').clone()
                    .attr('id', 'unit-type-modal')
                    .appendTo('body'); // show bootstrap modal when complete loaded
            $('#unit-type-modal').modal('show');
            //$('#perm-modal .clone-form')[0].reset();
            //$('#perm-modal .clone-form').data('bootstrapValidator').resetForm(true);
            $('#unit-type-modal .clone-form').loadJSON(data);
            $('.modal-title').text('Edit UnitType'); // Set title to Bootstrap modal title
            urlUnitType = '<?php echo base_url('unit_type/edit_ut'); ?>';
            saveUnitType(urlUnitType);
        }
    }

    function saveUnitType(urlUnitType) {
        ///////////////////////////////form submission/////////////////////
        $('#unit-type-modal .clone-form').bootstrapValidator({
            message: 'This value is not valid',
            feedbackIcons: {
                valid: 'glyphicon glyphicon-ok',
                invalid: 'glyphicon glyphicon-remove',
                validating: 'glyphicon glyphicon-refresh'
            },
            fields: {
                desc: {
                    message: 'The UnitType description is not valid',
                    validators: {
                        notEmpty: {
                            message: 'The UnitType description is required'
                        },
                        stringLength: {
                            min: 3,
                            max: 100,
                            message: 'The Permission description must be more than 3 and less than 100 characters long'
                        }
                    }
                },
                status: {
                    validators: {
                        regexp: {
                        regexp: /^[0-1]{1}$/,
                        message: 'Inalid selection'
                    }
                    }
                }
            }
        }).on('success.form.bv', function(e) {
            // Prevent form submission
            e.preventDefault();
            // Get the form instance
            var $form = $(e.target);
            // Get the BootstrapValidator instance
            var bv = $form.data('bootstrapValidator');
            // Use Ajax to submit form data
            /* $.post($form.attr('action'), $form.serialize(), function (result) {
             console.log(result);
             }, 'json');
             */

            $.ajax({
                type: "POST",
                url: urlUnitType,
                cache: false,
                data: $form.serialize(),
                dataType: 'json',
                beforeSend: function() {
                    $('#unit-type-modal').block();
                },
                success: function(data) {

                    $('#unit-type-modal').unblock();
                    if (data.success) {
                        showMsg('Success', data.msg, 'success');
                        $('#unit-type-modal').modal('hide');
                        reloadUnitType();
                    } else {
                        showMsg('Error', data.msg, 'danger');
                        $('#unit-type-modal .error-msg').html(data.msg);
                    }

                }
            });
        });
///////////////////////////////////////////////////////////////////
    }
    function removeUnitType(idp) {
        if (idp) {
            eModal.confirm('Are you sure to deactivate this item', 'Confirm to delete')
                    .then(function(r) {
                        $('#main-content').block();
                        $.post('<?php echo base_url('unit_type/deactivate'); ?>', {id: idp}, function(result) {
                            if (result.success) {
                                reloadUnitType(); // reload the user data
                                showMsg('Success', 'Successfully deactivated', 'success');
                            } else {
                                $('#main-content').unblock();
                                showMsg('Error', result.msg, 'danger');
                            }
                            $('#main-content').unblock();
                        }, 'json').complete(function() {
                            $('#main-content').unblock();
                        });
                    }
                    , function(r) {
                        return '';
                    });
        }
    }
    function activateUnitType(idp) {
        if (idp) {
            eModal.confirm('Are you sure to activate this item', 'Confirm to activate')
                    .then(function(r) {
                        $('#main-content').block();
                        $.post('<?php echo base_url('unit_type/activate'); ?>', {id: idp}, function(result) {
                            if (result.success) {
                                reloadUnitType(); // reload the user data
                                showMsg('Success', 'Successfully activated', 'success');
                            } else {
                                $('#main-content').unblock();
                                showMsg('Error', result.msg, 'danger');
                            }
                            $('#main-content').unblock();
                        }, 'json').complete(function() {
                            $('#main-content').unblock();
                        });
                    }
                    , function(r) {
                        return '';
                    });
        }
    }

    function reloadUnitType() {
        unit_type_dt_table.ajax.reload(null, false); //reload datatable ajax
    }


</script>
