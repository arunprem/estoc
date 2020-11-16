                                                                                                                                                                                                                                                                                                                                                                                                                                                                                                                                                                                                                                                                                                                                                                                                                                                                                                                                                                                                                                                                                                                                                                                                                                                                                                                                                                                                                                                                                                                                                                                                                                                                                                                                                                                                                                                                                                                                                                                                                                                                                                                                                                                                                                                                                                                                                                                                                                                                                                                                                                                                                                                                                                                                                                                                                                                                                                                                                                                                                                                                                                                                                                                                                                                                                                                                                                                                                                                                                                                                                                                                                                                                                                                                                                                                                                                                                                                                                                                                                                                                                                                                                                                                                                                                                                                                                                                                                                                                                                                                                                                                                                                                                                                                                                                                                                                                                                                                                                                                                                                                                                                                                                                                                                                                                                                                                                                                                                                                                                                                                                                                                                                                                                                                                                                                                                                                                                                                                                                                                                                                                                                                                                                                                                                                                                                                                                                                                             <?php
if (!defined('BASEPATH'))
    exit('No direct script access allowed');
?>
<section class="content-header">
    <div class="box box-success">
        <div class="box-header with-border">
            <h4 class="box-title">
                <i class="fa fa-sitemap"> </i> Place Management</h4>
        </div>
    </div>
    <ol class="breadcrumb" style="padding: 20px">
        <li><a href="#"><i class="fa fa-dashboard"></i> Home</a></li>
        <li>Place</li>
        <li class="active">Place Management</li>
    </ol>
</section>
<section class="content">
    <div class="box box-default" style="padding: 10px 10px 10px 10px">
     
        <table id="place-dt-table" class="table display compact table-striped table-bordered" cellspacing="0" width="100%">
            <thead>
                <tr>
                <th></th>
                    <th>ID</th>
                    <th>DISTID</th>
                    <th>PSID</th>
                    <th>DISTRICT</th>
                    <th>POLICE STATION</th>                   
                    <th>Places</th>                    
                    <th></th>
                </tr>
            </thead>
            <tbody>
            </tbody>
        </table>
    </div>
</section>
<!-- Bootstrap modal -->
<div class="modal fade" id="place-fm-modal" role="dialog" data-keyboard="false" data-backdrop="static">
    <div class="modal-dialog">
        <div class="modal-content panel-primary">
            <div class="modal-header panel-heading">
                <button type="button" id="modelColse" class="close" data-dismiss="modal" aria-label="Close"><span aria-hidden="true">&times;</span></button>
                <h3 class="modal-title">Place Management</h3>
            </div>

            <form class="form-horizontal clone-form">
                <div class="modal-body form" style="max-height: 400px; overflow-y:auto">
                    <div class="error-msg" ></div>
                    <input type="hidden" name="<?php echo $this->security->get_csrf_token_name(); ?>" value="<?php echo $this->security->get_csrf_hash(); ?>">
                    <input type="hidden" value="" name="id" />
                    <input type="hidden" value="" name="distid" />
                    <input type="hidden" value="" name="psid" />
                    <div class="form-body" >
                        <div class="form-group">
                            <label class="control-label col-md-3">District Name</label>
                            <div class="col-md-9">
                                <input name="district" id="district" placeholder="Name of District" class="form-control" type="text"  data-val="true" data-val-required="This field is required.">
                            </div>
                        </div> 
                        <div class="form-group">
                            <label class="control-label col-md-3">Police Station</label>
                            <div class="col-md-9">
                                <input name="psname" id="psname" placeholder="Name of PS" class="form-control" type="text"  data-val="true" data-val-required="This field is required.">
                            </div>
                        </div>     
                                      
                        <div class="form-group">
                            <label class="control-label col-md-3">Places</label>
                            <div class="col-md-9">
                                <textarea name="places" type="text" placeholder="Places" class="form-control" ></textarea>
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
    var place_dt_table;
    $(document).ready(function () {
            ////////////////////////////////////////////////////////////////////////////////////////////
        //datatables
        place_dt_table = $('#place-dt-table').DataTable({
            "processing": true, //Feature control the processing indicator.
            "serverSide": true, //Feature control DataTables' server-side processing mode.
            "order": [], //Initial no order.
            // Load data for the table's content from an Ajax source
            "ajax": {
                "url": "<?php echo base_url('psplaces/place_list') ?>",
                "type": "POST"
            },
            "columns": [
                {data: 'sl'},
                {data: 'id'},
                {data: 'distid'},
                {data: 'psid'},
                {data: 'district'},
                {data: 'psname'},
                {data: 'places'},
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
                    "targets": [6],
                    "visible": false,
                    "searchable": false
                },           
                {
                    "targets": [7],
                    "orderable": false,
                }

            ],
            "fnRowCallback": function (nRow, aData, iDisplayIndex, iDisplayIndexFull) {
            },
            "fnCreatedRow"
                    : function (nRow, aData, iDataIndex) {
                        $('td:eq(2)', nRow).html("<strong><span class='text-uppercase'>" + aData.psname + "</span></strong>");
                       // $('td:eq(3)', nRow).html("<span class='text-uppercase'>" + aData.places + "</span>");
                        $('td:eq(3)', nRow).html("\ <a href='#' class='btn btn-primary btn-xs'  onclick=\"editPlaces(" +  iDataIndex + ")\" data-toggle='tooltip' title='Edit Place'><span class='fa fa-edit' ></span></a>");
                      
                    }, //Set column definition initialisation properties.
        });
        /////////////////////////////////////////////////////////
    });
    var urlUnit;
    var unitId;

    function editPlaces(row) {

         var data = place_dt_table.row(row).data();
        if (data) {
            if ($('#place-modal').length) {
                $('#place-modal').remove();
            }
            $('#place-fm-modal').clone()
                    .attr('id', 'place-modal')
                    .appendTo('body'); // show bootstrap modal when complete loaded
                    $("#place-modal #district").prop('disabled', true);
                    $("#place-modal #psname").prop('disabled', true);
            $('#place-modal').modal('show');
            //$('#perm-modal .clone-form')[0].reset();
            //$('#perm-modal .clone-form').data('bootstrapValidator').resetForm(true);

            //$('#place-modal .clone-form').loadJSON(data);
            populateForm('#place-modal .clone-form', data);

            $('.modal-title').text('Edit Station Places'); // Set title to Bootstrap modal title

                      ///////////////////////////////form submission/////////////////////
            $('#place-modal .clone-form').bootstrapValidator({
                message: 'This value is not valid',
                feedbackIcons: {
                    valid: 'glyphicon glyphicon-ok',
                    invalid: 'glyphicon glyphicon-remove',
                    validating: 'glyphicon glyphicon-refresh'
                },
                fields: {
                    places: {
                        message: 'The Station Place name is not valid',
                        validators: {
                            notEmpty: {
                                message: 'The Station Place name is required'
                            },
                            stringLength: {
                                min: 3,
                                max: 5000,
                                message: 'The Station Place name must be more than 3 and less than 5000 characters long'
                            },
                            blank: {
                            }
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
                    url: '<?php echo base_url('psplaces/edit_place'); ?>',
                    cache: false,
                    data: $form.serialize(),
                    dataType: 'json',
                    beforeSend: function () {
                        $('#place-modal').block();
                    },
                    success: function (data) {

                        $('#place-modal').unblock();
                        if (data.success) {
                            showMsg('Success', data.msg, 'success');
                            $('#place-modal').modal('hide');
                            reloadPlace();
                        } else {
                            for (var field in data.fields) {
                                bv.updateMessage(field, 'blank', data.fields[field]);
                                bv.updateStatus(field, 'INVALID', 'blank');
                             }
                            showMsg('Error', data.msg, 'danger');
                            $('#place-modal .error-msg').html(data.msg);
                        }
                    }
                });
            });//////////////////////////////////////////////////////////////////
        }
    }

    function reloadPlace() {
        place_dt_table.ajax.reload(null, false); //reload datatable ajax
    }

</script>
