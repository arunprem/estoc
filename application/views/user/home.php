<?php
if (!defined('BASEPATH'))
    exit('No direct script access allowed');
?>
<section class="content-header">
    <div class="box box-success">
        <div class="box-header with-border">
            <h4 class="box-title">
                <i class="fa fa-sitemap"> </i> User Management</h4>
        </div>
    </div>
    <ol class="breadcrumb" style="padding: 20px">
        <li><a href="#"><i class="fa fa-dashboard"></i> Home</a></li>
        <li>Users</li>
        <li class="active">User Management</li>
    </ol>
</section>
<section class="content">
    <div class="box box-default" style="padding: 10px 10px 10px 10px">
        <div class="box-header with-border">
            <h4 class="box-title"></h4>
            <button class="btn btn-success" onclick="newUser()"><i class="fa fa-plus-circle"></i> Add User</button>
        </div><!-- /.box-header -->

        <table id="user-dt-table" class="table display compact table-striped table-bordered" cellspacing="0" width="100%">
            <thead>
                <tr>
                    <th></th>
                    <th>Id</th>
                    <th>Utype</th>
                    <th>User Name</th>
                    <th>Role</th>
                    <th>Status</th>
                    <th>PEN</th>
                    <th>Name</th>
                    <th>Unit</th>
                    <th>Unit Type</th>

                    <th></th>
                </tr>
            </thead>
            <tbody>
            </tbody>
        </table>
    </div>
</section>
<!-- Bootstrap modal -->
<div class="modal fade" id="user-fm-modal" role="dialog" data-keyboard="false" data-backdrop="static">
    <div class="modal-dialog">
        <div class="modal-content panel-primary">
            <div class="modal-header panel-heading">
                <button type="button" id="modelColse" class="close" data-dismiss="modal" aria-label="Close"><span aria-hidden="true">&times;</span></button>
                <h3 class="modal-title">User Management</h3>
            </div>

            <form class="form-horizontal clone-form">
                <div class="modal-body form" style="max-height: 400px; overflow-y:auto">
                    <div class="error-msg" ></div>
                    <input type="hidden" name="<?php echo $this->security->get_csrf_token_name(); ?>" value="<?php echo $this->security->get_csrf_hash(); ?>">
                    <input type="hidden" value="" name="id" id="id"/>
                    <div class="form-body" >
                        <div class="form-group">
                            <label class="control-label col-md-3">Username</label>
                            <div class="col-md-9">
                                <input name="uname" id="uname" placeholder="Username" class="form-control" type="text"  data-val="true" data-val-required="This field is required.">
                            </div>
                        </div>                       
                        <div class="form-group">
                            <label class="control-label col-md-3">Password</label>
                            <div class="col-md-9">
                                <input name="password" id="password" placeholder="Password" class="form-control" type="text"  data-val="true" data-val-required="This field is required.">
                            </div>
                        </div>
                        <div class="form-group">
                            <label class="control-label col-md-3">User Role</label>
                            <div class="col-md-9">
                                <select id="user-role-select" name="urole" class="form-control">
                                    <option value="">Select Role</option>
                                </select>
                            </div>
                        </div> 
                        <div class="form-group">
                            <label class="control-label col-md-3">Unit</label>
                            <div class="col-md-9">
                                <select id="unit-role-select" name="utrole" class="form-control" style="width: 100%">

                                </select>
                            </div>
                        </div> 
                        <div class="form-group">
                            <label class="control-label col-md-3">User Status</label>
                            <div class="col-md-9">
                                <select id="status" name="status" class="form-control">
                                    <option value="1">Active</option>
                                    <option value="0">Deactive</option>
                                </select>
                            </div>
                        </div>   
                        <div class="form-group">
                            <label class="control-label col-md-3">PEN</label>
                            <div class="col-md-9">
                                <input name="pen" id="pen" placeholder="PEN" class="form-control" type="text"  data-val="true" data-val-required="This field is required.">                              
                            </div>
                        </div> 
                        <div class="form-group">
                            <label class="control-label col-md-3">Profile Name</label>
                            <div class="col-md-9">
                                <input name="pname" id="pname" placeholder="Profile Name" class="form-control" type="text"  data-val="true" data-val-required="This field is required.">
                            </div>
                        </div> 
                        <div class="form-group">
                            <label class="control-label col-md-3">Email</label>
                            <div class="col-md-9">
                                <input name="email" id="email" placeholder="Email" class="form-control" type="text"  data-val="true" data-val-required="This field is required.">
                            </div>
                        </div> 
                        <div class="form-group">
                            <label class="control-label col-md-3">Mobile</label>
                            <div class="col-md-9">
                                <input name="mobile" id="mobile" placeholder="Mobile" class="form-control" type="text"  >
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
    var user_dt_table;
    $(document).ready(function() {
        //////////////////////////////////////////////////////////////////////////////////////////////
        $.getJSON("<?php echo base_url('role/role_list_combo'); ?>", function(data) {

            $.each(data, function(key, value) {
                $('#user-role-select').append(
                        $('<option value=' + value.id + '></option>').html(value.title)
                        );
            });
        });
        //////////////////////////////////////////////////////////////////////////////////////////////

//////////////////////////////////////////////////////////////////////////////////////////////
        //datatables
        user_dt_table = $('#user-dt-table').DataTable({
            "processing": true, //Feature control the processing indicator.
            "serverSide": true, //Feature control DataTables' server-side processing mode.
            "order": [], //Initial no order.
            // Load data for the table's content from an Ajax source
            "ajax": {
                "url": "<?php echo base_url('usermanager/user_list') ?>",
                "type": "POST"
            },
            "columns": [
                {data: 'sl'},
                {data: 'id'},
                {data: 'utrole'},
                {data: 'uname'},
                {data: 'rst'},
                {data: 'status'},
                {data: 'pen'},
                {data: 'pname'},
                {data: 'unit'},
                {data: 'utydesc'},
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
                    "targets": [9],
                    "orderable": false,
                }

            ],
            "fnRowCallback": function(nRow, aData, iDisplayIndex, iDisplayIndexFull) {
            },
            "fnCreatedRow"
                    : function(nRow, aData, iDataIndex) {
                        $('td:eq(2)', nRow).html("<span class='label label-primary'>" + aData.rst + "</span>");
                        $('td:eq(3)', nRow).html("<span class='label label-default'>" + aData.priority + "</span>");
                        if (aData.status == 0) {
                            $('td:eq(8)', nRow).html("\ <a href='#' class='btn btn-primary btn-xs'  onclick=\"editUser(" + iDataIndex + ")\" data-toggle='tooltip' title='Edit Unit type'><span class='fa fa-edit' ></span></a> &nbsp; \n\
                                                        <a href='#' class='btn btn-success btn-xs' onclick='activateUser(" + aData.id + ")' title='Activate User' data-toggle='tooltip'><span class='fa fa-check' ></span></a>");
                            $('td:eq(3)', nRow).html("<span class='label label-danger' data-toggle='tooltip' title='Deactivated'><i class='fa fa-times'></i></span>");
                        } else {
                            $('td:eq(8)', nRow).html("\ <a href='#' class='btn btn-primary btn-xs'  onclick=\"editUser(" + iDataIndex + ")\" data-toggle='tooltip' title='Edit Unit type'><span class='fa fa-edit' ></span></a> &nbsp; \n\
                                                        <a href='#' class='btn btn-danger btn-xs' onclick='removeUser(" + aData.id + ")' title='Deactivate User' data-toggle='tooltip'><span class='fa fa-trash' ></span></a>");
                            $('td:eq(3)', nRow).html("<span class='label label-success' data-toggle='tooltip' title='Active'><i class='fa fa-check'></i></span>");
                        }
                    }, //Set column definition initialisation properties.
        });
        /////////////////////////////////////////////////////////
    });


    var urlUser;
    var userId;

    function newUser() {
        if ($('#user-modal').length) {
            $('#user-modal').remove();
        }
        $('#user-fm-modal').clone()
                .attr('id', 'user-modal')
                .appendTo('body'); // show bootstrap modal when complete loaded
        $('#user-modal').modal('show');
        $('.modal-title').text('Create New User'); // Set title to Bootstrap modal title
        $('#user-modal #unit-role-select').select2({
            ajax: {
                url: '<?php echo base_url(); ?>unit/all_unit_combo_by_user',
                method: 'post',
                dataType: 'json',
                delay: 250,
                cache: true,
                processResults: function(data) {
                    return {
                        results: data
                    };
                }
            },
            minimumInputLength: 2,
            placeholder: "Search & Select",
            allowClear: true
        });


        ///////////////////////////////form submission/////////////////////
        $('#user-modal .clone-form').bootstrapValidator({
            message: 'This value is not valid',
            feedbackIcons: {
                valid: 'glyphicon glyphicon-ok',
                invalid: 'glyphicon glyphicon-remove',
                validating: 'glyphicon glyphicon-refresh'
            },
            fields: {
                uname: {
                    message: 'The User name is not valid',
                    validators: {
                        notEmpty: {
                            message: 'The User name is required'
                        },
                        stringLength: {
                            min: 3,
                            max: 20,
                            message: 'The Username must be more than 3 and less than 20 characters long'
                        },
                        blank: {
                        }
                    }
                },
                password: {
                    message: 'The password is not valid',
                    validators: {
                        notEmpty: {
                            message: 'The password is required'
                        },
                        stringLength: {
                            min: 8,
                            max: 20,
                            message: 'The Password must be more than 8 and less than 20 characters long'
                        },
                        blank: {}
                    }
                },
                urole: {
                    validators: {
                        notEmpty: {
                            message: 'The role is required'
                        },
                        blank: {}
                    }
                },
                utrole: {
                    validators: {
                        notEmpty: {
                            message: 'The unit is required'
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
                },
                pen: {
                    validators: {
                        notEmpty: {
                            message: 'The PEN is required'
                        },
                        between: {
                            min: 100000,
                            max: 999999,
                            message: 'Invalid PEN'
                        },
                        blank: {}

                    }
                },
                pname: {
                    validators: {
                        notEmpty: {
                            message: 'The Name is required'
                        },
                        blank: {}
                    }
                },
                email: {
                    validators: {
                        emailAddress: {
                            message: 'invalid email'
                        },
                        blank: {}
                    }
                },
                mobile: {
                    validators: {
                        notEmpty: {
                            message: 'The Mobile is required'
                        },
                        regexp: {
                            regexp: /^(((\+?\(91\))|0|((00|\+)?91))-?)?[7-9]\d{9}$/,
                            message: 'Invalid Mobile Number'
                        },
                        blank: {}
                    }
                },
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
             
             }, 'json');
             */

            $.ajax({
                type: "POST",
                url: '<?php echo base_url('usermanager/new_user'); ?>',
                cache: false,
                data: $form.serialize(),
                dataType: 'json',
                beforeSend: function() {
                    $('#user-modal').block();
                },
                success: function(data) {

                    $('#user-modal').unblock();
                    if (data.success) {
                        showMsg('Success', data.msg, 'success');
                        $('#user-modal').modal('hide');
                        reloadUser();
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
                        $('#user-modal .error-msg').html(data.msg);
                    }

                }
            });
        });
///////////////////////////////////////////////////////////////////
    }

    function editUser(row) {
        var data = user_dt_table.row(row).data();
        if (data) {
            if ($('#user-modal').length) {
                $('#user-modal').remove();
            }
            $('#user-fm-modal').clone()
                    .attr('id', 'user-modal')
                    .appendTo('body'); // show bootstrap modal when complete loaded
            $('#user-modal').modal('show');
            //$('#perm-modal .clone-form')[0].reset();
            //$('#perm-modal .clone-form').data('bootstrapValidator').resetForm(true);

            //$('#user-modal .clone-form').loadJSON(data);
            populateForm('#user-modal .clone-form',data);


            /////////////////////////////////////////////////////////
            

            ////////////////////////////////////////////////////////
            $("#user-modal #unit-role-select").append('<option value="' + data.utrole + '">' + data.unit + '</option>');
            $("#user-modal #uname").prop('disabled', true);
            $('.modal-title').text('Edit User'); // Set title to Bootstrap modal title

            $('#user-modal #unit-role-select').select2({
                ajax: {
                    url: '<?php echo base_url(); ?>unit/all_unit_combo_by_user',
                    method: 'post',
                    dataType: 'json',
                    delay: 250,
                    cache: true,
                    processResults: function(data) {
                        return {
                            results: data
                        };
                    }
                },
                minimumInputLength: 2,
                placeholder: "Search & Select",
                allowClear: true
            });



            ///////////////////////////////form submission/////////////////////
            $('#user-modal .clone-form').bootstrapValidator({
                message: 'This value is not valid',
                feedbackIcons: {
                    valid: 'glyphicon glyphicon-ok',
                    invalid: 'glyphicon glyphicon-remove',
                    validating: 'glyphicon glyphicon-refresh'
                },
                fields: {
                    password: {
                        message: 'The password is not valid',
                        validators: {
                            stringLength: {
                                min: 8,
                                max: 20,
                                message: 'The Password must be more than 8 and less than 20 characters long'
                            },
                            blank: {}
                        }
                    },
                    urole: {
                        validators: {
                            notEmpty: {
                                message: 'The role is required'
                            },
                            blank: {}
                        }
                    },
                    utrole: {
                        validators: {
                            notEmpty: {
                                message: 'The unit is required'
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
                    },
                    pen: {
                        validators: {
                            notEmpty: {
                                message: 'The PEN is required'
                            },
                            between: {
                                min: 100000,
                                max: 999999,
                                message: 'Invalid PEN'
                            },
                            blank: {}

                        }
                    },
                    pname: {
                        validators: {
                            notEmpty: {
                                message: 'The Name is required'
                            },
                            blank: {}
                        }
                    },
                    email: {
                        validators: {
                            emailAddress: {
                                message: 'invalid email'
                            },
                            blank: {}
                        }
                    },
                    mobile: {
                        validators: {
                            notEmpty: {
                                message: 'The Mobile is required'
                            },
                            regexp: {
                                regexp: /^(((\+?\(91\))|0|((00|\+)?91))-?)?[7-9]\d{9}$/,
                                message: 'Invalid Mobile Number'
                            },
                            blank: {}
                        }
                    },
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
                
                 }, 'json');
                 */

                $.ajax({
                    type: "POST",
                    url: '<?php echo base_url('usermanager/edit_user'); ?>',
                    cache: false,
                    data: $form.serialize(),
                    dataType: 'json',
                    beforeSend: function() {
                        $('#user-modal').block();
                    },
                    success: function(data) {

                        $('#user-modal').unblock();
                        if (data.success) {
                            showMsg('Success', data.msg, 'success');
                            $('#user-modal').modal('hide');
                            reloadUser();
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
                            $('#user-modal .error-msg').html(data.msg);
                        }

                    }
                });
            });
///////////////////////////////////////////////////////////////////
        }
    }

    function saveUser(urlUser) {
        ///////////////////////////////form submission/////////////////////
        $('#user-modal .clone-form').bootstrapValidator({
            message: 'This value is not valid',
            feedbackIcons: {
                valid: 'glyphicon glyphicon-ok',
                invalid: 'glyphicon glyphicon-remove',
                validating: 'glyphicon glyphicon-refresh'
            },
            fields: {
                uname: {
                    message: 'The User name is not valid',
                    validators: {
                        notEmpty: {
                            message: 'The User name is required'
                        },
                        stringLength: {
                            min: 3,
                            max: 20,
                            message: 'The Username must be more than 3 and less than 20 characters long'
                        }
                    }
                },
                password: {
                    message: 'The password is not valid',
                    enabled: false,
                    validators: {
                        notEmpty: {
                            message: 'The password is required'
                        },
                        stringLength: {
                            min: 8,
                            max: 20,
                            message: 'The Password must be more than 8 and less than 20 characters long'
                        }
                    }
                },
                urole: {
                    validators: {
                        notEmpty: {
                            message: 'The role is required'
                        }
                    }
                },
                utrole: {
                    validators: {
                        notEmpty: {
                            message: 'The unit is required'
                        }
                    }
                },
                status: {
                    validators: {
                        regexp: {
                            regexp: /^[0-1]{1}$/,
                            message: 'Invalid selection'
                        }
                    }
                },
                pen: {
                    validators: {
                        notEmpty: {
                            message: 'The PEN is required'
                        },
                        between: {
                            min: 100000,
                            max: 999999,
                            message: 'Invalid PEN'
                        }

                    }
                },
                pname: {
                    validators: {
                        notEmpty: {
                            message: 'The Name is required'
                        },
                    }
                },
                email: {
                    validators: {
                        emailAddress: {
                            message: 'invalid email'
                        },
                    }
                },
                mobile: {
                    validators: {
                        notEmpty: {
                            message: 'The Mobile is required'
                        },
                        regexp: {
                            regexp: /^(((\+?\(91\))|0|((00|\+)?91))-?)?[7-9]\d{9}$/,
                            message: 'Invalid Mobile Number'
                        }
                    }
                },
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
             
             }, 'json');
             */

            $.ajax({
                type: "POST",
                url: urlUser,
                cache: false,
                data: $form.serialize(),
                dataType: 'json',
                beforeSend: function() {
                    $('#user-modal').block();
                },
                success: function(data) {

                    $('#user-modal').unblock();
                    if (data.success) {
                        showMsg('Success', data.msg, 'success');
                        $('#user-modal').modal('hide');
                        reloadUser();
                    } else {
                        showMsg('Error', data.msg, 'danger');
                        $('#user-modal .error-msg').html(data.msg);
                    }

                }
            });
        });
///////////////////////////////////////////////////////////////////
    }
    function removeUser(idp) {
        if (idp) {
            eModal.confirm('Are you sure to deactivate this User', 'Confirm to delete')
                    .then(function(r) {
                        $('#main-content').block();
                        $.post('<?php echo base_url('usermanager/deactivate'); ?>', {id: idp}, function(result) {
                            if (result.success) {
                                reloadUser(); // reload the user data
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
    function activateUser(idp) {
        if (idp) {
            eModal.confirm('Are you sure to activate this User', 'Confirm to activate')
                    .then(function(r) {
                        $('#main-content').block();
                        $.post('<?php echo base_url('usermanager/activate'); ?>', {id: idp}, function(result) {
                            if (result.success) {
                                reloadUser(); // reload the user data
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

    function reloadUser() {
        user_dt_table.ajax.reload(null, false); //reload datatable ajax
    }

</script>
