                                                                                                                                                                                                                                                                                                                                                                                                                                                                                                                                                                                                                                                                                                                                                                                                                                                                                                                                                                                                                                                                                                                                                                                                                                                                                                                                                                                                                                                                                                                                                                                                                                                                                                                                                                                                                                                                                                                                                                                                                                                                                                                                                                                                                                                                                                                                                                                                                                                                                                                                                                                                                                                                                                                                                                                                                                                                                                                                                                                                                                                                                                                                                                                                                                                                                                                                                                                                                                                                                                                                                                                                                                                                                                                                                                                                                                                                                                                                                                                                                                                                                                                                                                                                                                                                                                                                                                                                                                                                                                                                                                                                                                                                                                                                                                                                                                                                                                                                                                                                                                                                                                                                                                                                                                                                                                                                                                                                                                                                                                                                                                                                                                                                                                                                                                                                                                                                                                                                                                                                                                                                                                                                                                                                                                                                                                                                                                                                                             <?php
if (!defined('BASEPATH'))
    exit('No direct script access allowed');
?>
<section class="content-header">
    <div class="box box-success">
        <div class="box-header with-border">
            <h4 class="box-title">
                <i class="fa fa-sitemap"> </i> Unit Management</h4>
        </div>
    </div>
    <ol class="breadcrumb" style="padding: 20px">
        <li><a href="#"><i class="fa fa-dashboard"></i> Home</a></li>
        <li>Units</li>
        <li class="active">Unit Management</li>
    </ol>
</section>
<section class="content">
    <div class="box box-default" style="padding: 10px 10px 10px 10px">
        <div class="box-header with-border">
            <h4 class="box-title"></h4>
            <button class="btn btn-success" onclick="newUnit()"><i class="fa fa-plus-circle"></i> Add Unit</button>
        </div><!-- /.box-header -->

        <table id="unit-dt-table" class="table display compact table-striped table-bordered" cellspacing="0" width="100%">
            <thead>
                <tr>
                    <th></th>
                    <th>ID</th>
                    <th>PID</th>
                    <th>UTYPE</th>
                    <th>RHEAD</th>
                    <th>NCRB ID</th>                   
                    <th>UNIT NAME</th>                    
                    <th>UNIT TYPE</th>                    
                    <th>RANK HOD</th>
                    <th>PARENT UNIT</th>
                    <th>ADMIN/SUB UNIT</th>
                    <th>STATUS</th>
                    <th></th>
                </tr>
            </thead>
            <tbody>
            </tbody>
        </table>
    </div>
</section>
<!-- Bootstrap modal -->
<div class="modal fade" id="unit-fm-modal" role="dialog" data-keyboard="false" data-backdrop="static">
    <div class="modal-dialog">
        <div class="modal-content panel-primary">
            <div class="modal-header panel-heading">
                <button type="button" id="modelColse" class="close" data-dismiss="modal" aria-label="Close"><span aria-hidden="true">&times;</span></button>
                <h3 class="modal-title">Unit Management</h3>
            </div>

            <form class="form-horizontal clone-form">
                <div class="modal-body form" style="max-height: 400px; overflow-y:auto">
                    <div class="error-msg" ></div>
                    <input type="hidden" name="<?php echo $this->security->get_csrf_token_name(); ?>" value="<?php echo $this->security->get_csrf_hash(); ?>">
                    <input type="hidden" value="" name="id" />
                    <div class="form-body" >
                        <div class="form-group">
                            <label class="control-label col-md-3">Unit Name</label>
                            <div class="col-md-9">
                                <input name="unit_name" id="unit_name" placeholder="Name of Unit" class="form-control" type="text"  data-val="true" data-val-required="This field is required.">
                            </div>
                        </div>                       
                        <div class="form-group">
                            <label class="control-label col-md-3">NCRB ID</label>
                            <div class="col-md-9">
                                <input name="ncrb_id" type="text" placeholder="NCRB Code" class="form-control" id="ncrb_id" >
                            </div>
                        </div>
                        <div class="form-group">
                            <label class="control-label col-md-3">Unit Type</label>
                            <div class="col-md-9">
                                <select id="unit-type-select" name="unit_type" class="form-control" style="width: 100%">
                                    <option value="">Select Unit Type</option>
                                </select>
                            </div>
                        </div> 
                        <div class="form-group">
                            <label class="control-label col-md-3">Parent Unit</label>
                            <div class="col-md-9">
                                <select id="unit-parent-select" name="parent_id" class="form-control" style="width: 100%">
                                </select>
                            </div>
                        </div> 
                        <div class="form-group">
                            <label class="control-label col-md-3">Rank of HOD</label>
                            <div class="col-md-9">
                                <select id="unit-hod-select" name="head_rank" class="form-control" style="width: 100%">
                                    <option value="">Select Rank</option>
                                </select>
                            </div>
                        </div> 
                        <div class="form-group">
                            <label class="control-label col-md-3">Administrative Unit</label>
                            <div class="col-md-9">
                                <select id="status" name="is_parent_unit" class="form-control" style="width: 100%">
                                    <option value="0">No</option>
                                    <option value="1">Yes</option>
                                </select>
                            </div>
                        </div> 
                        <div class="form-group">
                            <label class="control-label col-md-3">Unit Status</label>
                            <div class="col-md-9">
                                <select id="status" name="status" class="form-control" style="width: 100%">
                                    <option value="1">Active</option>
                                    <option value="0">Deactive</option>
                                </select>
                            </div>
                        </div>   

                    </div>
                </div>
                <div class="modal-footer">
                    <button type="submit"  id="submitButton"  class="btn btn-primary" ><i class="fa fa-save"></i> Save</button>
                    <button type="button" id="resetBtn" class="btn btn-danger" data-dismiss="modal"><i class="fa fa-close"></i>  Close</button>
                </div>
            </form>

        </div><!-- /.modal-content -->
    </div><!-- /.modal-dialog -->
</div><!-- /.modal -->

<script type="text/javascript">
    var unit_dt_table;
    $(document).ready(function () {
        //////////////////////////////////////////////////////////////////////////////////////////////
        $.getJSON("<?php echo base_url('rank/rank_head_combo'); ?>", function (data) {

            $.each(data, function (key, value) {
                $('#unit-hod-select').append(
                        $('<option value=' + value.id + '></option>').html(value.rname)
                        );
            });
        });
        //////////////////////////////////////////////////////////////////////////////////////////////
        //////////////////////////////////////////////////////////////////////////////////////////////
        $.getJSON("<?php echo base_url('unit_type/combo_list'); ?>", function (data) {

            $.each(data, function (key, value) {
                $('#unit-type-select').append(
                        $('<option value=' + value.id + '></option>').html(value.unit_type_desc)
                        );
            });
        });
        //////////////////////////////////////////////////////////////////////////////////////////////

//////////////////////////////////////////////////////////////////////////////////////////////
        //datatables
        unit_dt_table = $('#unit-dt-table').DataTable({
            "processing": true, //Feature control the processing indicator.
            "serverSide": true, //Feature control DataTables' server-side processing mode.
            "order": [], //Initial no order.
            // Load data for the table's content from an Ajax source
            "ajax": {
                "url": "<?php echo base_url('unit/unit_list') ?>",
                "type": "POST"
            },
            "columns": [
                {data: 'sl'},
                {data: 'id'},
                {data: 'parent_id'},
                {data: 'unit_type'},
                {data: 'head_rank'},
                {data: 'ncrb_id'},
                {data: 'unit_name'},
                {data: 'unit_type_desc'},
                {data: 'rank_short_tag'},
                {data: 'parent_unit'},
                {data: 'is_parent_unit'},
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
                },
                {
                    "targets": [2],
                    "visible": false,
                    "searchable": false
                },
                {
                    "targets": [3],
                    "visible": false,
                    "searchable": false
                },
                {
                    "targets": [4],
                    "visible": false,
                    "searchable": false
                },
                {
                    "targets": [11],
                    "orderable": false,
                }

            ],
            "fnRowCallback": function (nRow, aData, iDisplayIndex, iDisplayIndexFull) {
            },
            "fnCreatedRow"
                    : function (nRow, aData, iDataIndex) {
                        $('td:eq(2)', nRow).html("<strong><span class='text-uppercase'>" + aData.unit_name + "</span></strong>");
                        $('td:eq(3)', nRow).html("<span class='label label-danger'>" + aData.unit_type_desc + "</span>");
                        $('td:eq(4)', nRow).html("<span class='label label-primary'>" + aData.rank_short_tag + "</span>");
                        if (aData.is_parent_unit == 0) {
                            $('td:eq(6)', nRow).html("<span class='label label-success'>S</span>");
                        } else if (aData.is_parent_unit == 1) {
                            $('td:eq(6)', nRow).html("<span class='label label-danger'>A</span>");
                        }
                        if (aData.status == 0) {
                            $('td:eq(8)', nRow).html("\ <a href='#' class='btn btn-primary btn-xs'  onclick=\"editUnit(" + iDataIndex + ")\" data-toggle='tooltip' title='Edit Unit'><span class='fa fa-edit' ></span></a> &nbsp; \n\
                                                        <a href='#' class='btn btn-success btn-xs' onclick='activateUnit(" + aData.id + ")' title='Activate Unit' data-toggle='tooltip'><span class='fa fa-check' ></span></a>");
                            $('td:eq(7)', nRow).html("<span class='label label-danger' data-toggle='tooltip' title='Deactivated'><i class='fa fa-times'></i></span>");
                        } else {
                            $('td:eq(8)', nRow).html("\ <a href='#' class='btn btn-primary btn-xs'  onclick=\"editUnit(" + iDataIndex + ")\" data-toggle='tooltip' title='Edit Unit'><span class='fa fa-edit' ></span></a> &nbsp; \n\
                                                        <a href='#' class='btn btn-danger btn-xs' onclick='removeUnit(" + aData.id + ")' title='Deactivate Unit' data-toggle='tooltip'><span class='fa fa-trash' ></span></a>");
                            $('td:eq(7)', nRow).html("<span class='label label-success' data-toggle='tooltip' title='Active'><i class='fa fa-check'></i></span>");
                        }
                    }, //Set column definition initialisation properties.
        });
        /////////////////////////////////////////////////////////
    });
    var urlUnit;
    var unitId;
    function newUnit() {
        if ($('#unit-modal').length) {
            $('#unit-modal').remove();
        }
        $('#unit-fm-modal').clone()
                .attr('id', 'unit-modal')
                .appendTo('body'); // show bootstrap modal when complete loaded
        $('#unit-modal').modal('show');
        $('.modal-title').text('Create New Unit'); // Set title to Bootstrap modal title
        $('#unit-modal #unit-parent-select').select2({
            ajax: {
                url: '<?php echo base_url(); ?>unit/all_unit_combo',
                method: 'post',
                dataType: 'json',
                delay: 250,
                cache: true,
                processResults: function (data) {
                    return {
                        results: data
                    };
                }
            },
            minimumInputLength: 2,
            placeholder: "Unit-Type-HOD",
            allowClear: true
        });
        ///////////////////////////////form submission/////////////////////
        $('#unit-modal .clone-form').bootstrapValidator({
            message: 'This value is not valid',
            feedbackIcons: {
                valid: 'glyphicon glyphicon-ok',
                invalid: 'glyphicon glyphicon-remove',
                validating: 'glyphicon glyphicon-refresh'
            },
            fields: {
                unit_name: {
                    message: 'The Unit name is not valid',
                    validators: {
                        notEmpty: {
                            message: 'The Unit name is required'
                        },
                        stringLength: {
                            min: 3,
                            max: 50,
                            message: 'The Unit name must be more than 3 and less than 50 characters long'
                        },
                        blank: {
                        }
                    }
                },
                ncrb_id: {
                    message: 'The NCRB Code is not valid',
                    validators: {
                        stringLength: {
                            min: 2,
                            max: 20,
                            message: 'The NCRB Code must be more than 2 and less than 20 characters long'
                        },
                        integer: {
                            message: 'The NCRB Code must be an integer'
                        },
                        blank: {}
                    }
                },
                unit_type: {
                    validators: {
                        notEmpty: {
                            message: 'The Unit type is required'
                        },
                        integer: {
                            message: 'Invalid Unit type'
                        },
                        blank: {}
                    }
                },
                parent_id: {
                    validators: {
                        notEmpty: {
                            message: 'The Parent Unit is required'
                        },
                        integer: {
                            message: 'Invalid Unit type'
                        },
                        blank: {}
                    }
                },
                head_rank: {
                    validators: {
                        notEmpty: {
                            message: 'The HOD is required'
                        },
                        integer: {
                            message: 'Invalid Rank'
                        },
                        blank: {}
                    }
                },
                is_parent_unit: {
                    validators: {
                        regexp: {
                            regexp: /^[0-1]{1}$/,
                            message: 'Invalid selection'
                        },
                        blank: {}
                    }
                },
                status: {
                    validators: {
                        regexp: {
                            regexp: /^[0-1]{1}$/,
                            message: 'Invalid selection'
                        },
                        blank: {}
                    }
                }
            }
        }).on('success.form.bv', function (e) {
            // Prevent form submission
            e.preventDefault();
            // Get the form instance
            var $form = $(e.target);
            // Get the BootstrapValidator instance
            var bv = $form.data('bootstrapValidator');
            // Use Ajax to submit form data
            /* $.post($form.attr('action'), $form.serialize(), function (result) {
             
             }, 'json');
             */

            $.ajax({
                type: "POST",
                url: '<?php echo base_url('unit/new_unit'); ?>',
                cache: false,
                data: $form.serialize(),
                dataType: 'json',
                beforeSend: function () {
                    $('#unit-modal').block();
                },
                success: function (data) {

                    $('#unit-modal').unblock();
                    if (data.success) {
                        showMsg('Success', data.msg, 'success');
                        $('#unit-modal').modal('hide');
                        reloadUnit();
                    } else {
                        for (var field in data.fields) {
                            //alert(data.fields[field]);

                            // Show the custom message

                            bv.updateMessage(field, 'blank', data.fields[field]);
                            bv.updateStatus(field, 'INVALID', 'blank');
                            // Set the field as invalid

                            // Show the custom message
                            // Set the field as invalid

                        }

                        showMsg('Error', data.msg, 'danger');
                        $('#unit-modal .error-msg').html(data.msg);
                    }

                }
            });
        });
///////////////////////////////////////////////////////////////////
    }

    function editUnit(row) {
        var data = unit_dt_table.row(row).data();
        if (data) {
            if ($('#unit-modal').length) {
                $('#unit-modal').remove();
            }
            $('#unit-fm-modal').clone()
                    .attr('id', 'unit-modal')
                    .appendTo('body'); // show bootstrap modal when complete loaded
            $('#unit-modal').modal('show');
            //$('#perm-modal .clone-form')[0].reset();
            //$('#perm-modal .clone-form').data('bootstrapValidator').resetForm(true);

            //$('#unit-modal .clone-form').loadJSON(data);
            populateForm('#unit-modal .clone-form', data);
            $("#unit-modal #unit-parent-select").append('<option value="' + data.parent_id + '">' + data.parent_unit + '</option>');
            $('.modal-title').text('Edit Unit'); // Set title to Bootstrap modal title

            $('#unit-modal #unit-parent-select').select2({
                ajax: {
                    url: '<?php echo base_url(); ?>unit/all_unit_combo',
                    method: 'post',
                    dataType: 'json',
                    delay: 250,
                    cache: true,
                    processResults: function (data) {
                        return {
                            results: data
                        };
                    }
                },
                minimumInputLength: 2,
                placeholder: "Unit-Type-HOD",
                allowClear: true
            });
            ///////////////////////////////form submission/////////////////////
            $('#unit-modal .clone-form').bootstrapValidator({
                message: 'This value is not valid',
                feedbackIcons: {
                    valid: 'glyphicon glyphicon-ok',
                    invalid: 'glyphicon glyphicon-remove',
                    validating: 'glyphicon glyphicon-refresh'
                },
                fields: {
                    unit_name: {
                        message: 'The Unit name is not valid',
                        validators: {
                            notEmpty: {
                                message: 'The Unit name is required'
                            },
                            stringLength: {
                                min: 3,
                                max: 50,
                                message: 'The Unit name must be more than 3 and less than 50 characters long'
                            },
                            blank: {
                            }
                        }
                    },
                    ncrb_id: {
                        message: 'The NCRB Code is not valid',
                        validators: {
                            stringLength: {
                                min: 2,
                                max: 20,
                                message: 'The NCRB Code must be more than 2 and less than 20 characters long'
                            },
                            integer: {
                                message: 'The NCRB Code must be an integer'
                            },
                            blank: {}
                        }
                    },
                    unit_type: {
                        validators: {
                            notEmpty: {
                                message: 'The Unit type is required'
                            },
                            integer: {
                                message: 'Invalid Unit type'
                            },
                            blank: {}
                        }
                    },
                    parent_id: {
                        validators: {
                            notEmpty: {
                                message: 'The Parent Unit is required'
                            },
                            integer: {
                                message: 'Invalid Unit type'
                            },
                            blank: {}
                        }
                    },
                    head_rank: {
                        validators: {
                            notEmpty: {
                                message: 'The HOD is required'
                            },
                            integer: {
                                message: 'Invalid Rank'
                            },
                            blank: {}
                        }
                    },
                    is_parent_unit: {
                        validators: {
                            regexp: {
                                regexp: /^[0-1]{1}$/,
                                message: 'Invalid selection'
                            },
                            blank: {}
                        }
                    },
                    status: {
                        validators: {
                            regexp: {
                                regexp: /^[0-1]{1}$/,
                                message: 'Invalid selection'
                            },
                            blank: {}
                        }
                    }
                }
            }).on('success.form.bv', function (e) {
                // Prevent form submission
                e.preventDefault();
                // Get the form instance
                var $form = $(e.target);
                // Get the BootstrapValidator instance
                var bv = $form.data('bootstrapValidator');
                // Use Ajax to submit form data
                /* $.post($form.attr('action'), $form.serialize(), function (result) {
                 
                 }, 'json');
                 */

                $.ajax({
                    type: "POST",
                    url: '<?php echo base_url('unit/edit_unit'); ?>',
                    cache: false,
                    data: $form.serialize(),
                    dataType: 'json',
                    beforeSend: function () {
                        $('#unit-modal').block();
                    },
                    success: function (data) {

                        $('#unit-modal').unblock();
                        if (data.success) {
                            showMsg('Success', data.msg, 'success');
                            $('#unit-modal').modal('hide');
                            reloadUnit();
                        } else {
                            for (var field in data.fields) {
                                //alert(data.fields[field]);

                                // Show the custom message

                                bv.updateMessage(field, 'blank', data.fields[field]);
                                bv.updateStatus(field, 'INVALID', 'blank');
                                // Set the field as invalid

                                // Show the custom message
                                // Set the field as invalid

                            }

                            showMsg('Error', data.msg, 'danger');
                            $('#unit-modal .error-msg').html(data.msg);
                        }

                    }
                });
            });
///////////////////////////////////////////////////////////////////
        }
    }

    
    function removeUnit(idp) {
        if (idp) {
            eModal.confirm('Are you sure to deactivate this Unit', 'Confirm to delete').then(function (r) {
                        $('#main-content').block();
                        $.post('<?php echo base_url('unit/deactivate'); ?>', {id: idp}, function (result) {
                            if (result.success) {
                                reloadUnit(); // reload the unit data
                                showMsg('Success', 'Successfully deactivated', 'success');
                            } else {
                                $('#main-content').unblock();
                                showMsg('Error', result.msg, 'danger');
                            }
                            $('#main-content').unblock();
                        }, 'json').complete(function () {
                            $('#main-content').unblock();
                        });
                    }
                    , function (r) {
                        return '';
                    });
        }
    }
    function activateUnit(idp) {
        if (idp) {
            eModal.confirm('Are you sure to activate this Unit', 'Confirm to activate')
                    .then(function (r) {
                        $('#main-content').block();
                        $.post('<?php echo base_url('unit/activate'); ?>', {id: idp}, function (result) {
                            if (result.success) {
                                reloadUnit(); // reload the unit data
                                showMsg('Success', 'Successfully activated', 'success');
                            } else {
                                $('#main-content').unblock();
                                showMsg('Error', result.msg, 'danger');
                            }
                            $('#main-content').unblock();
                        }, 'json').complete(function () {
                            $('#main-content').unblock();
                        });
                    }
                    , function (r) {
                        return '';
                    });
        }
    }

    function reloadUnit() {
        unit_dt_table.ajax.reload(null, false); //reload datatable ajax
    }

</script>
