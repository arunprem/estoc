<section class="content-header">
    <div class="box box-warning">
        <div class="box-header with-border">
            <h4 class="box-title">
                <i class="fa fa-fqrcode"></i> Generate QR Code
            </h4>
        </div>
    </div>
    <ol class="breadcrumb" style="padding: 20px">
        <li><a href="#"><i class="fa fa-dashboard"></i> Home</a></li>
        <li><a href="#"> Contact Tracing</a></li>
        <li><a href="#" class="active">QR Code</a></li>
    </ol>
</section>
<section class="content">
    <div class="box box-default" style="padding: 10px 10px 10px 10px">
        <div class="box-header with-border">
            <div class="row">
                <div class="col-md-7">
                    <h4>Search Contacts</h4>
                </div>
            </div>
            <form id="search-qr">
                <input type="hidden" name="<?php echo $this->security->get_csrf_token_name(); ?>" value="<?php echo $this->security->get_csrf_hash(); ?>">
                <div class="row bg-gray-light" style="padding: 10px 10px 10px 0px">
                    <div class="col-sm-2">
                        <div class="form-group">
                            <label>Contact ID </label>
                            <input id="id" type="text" value="" name="id" class="form-control" placeholder="ID" autocomplete="off">
                        </div>
                    </div>
                    <div class="col-sm-2">
                        <div class="form-group">
                            <label>Name </label>
                            <input id="name" type="text" value="" name="name" class="form-control" placeholder="Name" autocomplete="off">
                        </div>
                    </div>
                    <div class="col-sm-2">
                        <div class="form-group">
                            <label>Gender </label>
                            <select class="form-control" id="gender" name="gender" style="width:100%">
                                <option value="">Gender</option>
                                <option value="M">Male</option>
                                <option value="F">Female</option>
                                <option value="T">Transgender</option>
                            </select>
                        </div>
                    </div>
                    <div class="col-sm-2">
                        <div class="form-group">
                            <label>mobile </label>
                            <input id="mobile" autocomplete="off" type="text" value="" name="mobile" class="form-control" placeholder="mobile">
                        </div>
                    </div>
                    <div class="col-sm-2">
                        <div class="form-group">
                            <label>Surveillance </label>
                            <select class="form-control" id="surveillance_status" name="surveillance_status" style="width:100%">
                                <option value="">Select</option>
                                <option value="Y">YES</option>
                                <option value="N">NO</option>

                            </select>
                        </div>
                    </div>
                    <div class="col-sm-2">
                        <div class="form-group">
                            <label>Address </label>
                            <input id="address" autocomplete="off" type="text" value="" name="address" class="form-control" placeholder="address">
                        </div>
                    </div>
                    <div class="col-sm-3">
                        <div class="form-group">
                            <label>Police Station </label>
                            <select id="ps_id" name="ps_id" class="form-control " data-val="true" data-val-required="This field is required." style="width:100%">
                                <option value="">Select</option>
                            </select>
                        </div>
                    </div>
                    <div class="col-sm-3">
                        <div class="form-group">
                            <label>LSG </label>
                            <select id="lsg_id" name="lsg_id" class="form-control " data-val="true" data-val-required="This field is required." style="width:100%">
                                <option value="">Select</option>
                            </select>
                        </div>
                    </div>
                    <div class="col-sm-3">
                        <div class="form-group">
                            <label>Ward </label>
                            <select id="ward_id" name="ward_id" class="form-control " data-val="true" data-val-required="This field is required." style="width:100%">
                                <option value="">Select</option>
                            </select>
                        </div>
                    </div>
                    <div class="col-sm-2">
                        <div class="form-group">
                            <label>Date <span class="mute">(dd/mm/yyyy)</span></label>
                            <div class="input-group date">
                                <div class="input-group-addon">
                                    <i class="fa fa-calendar"></i>
                                </div>
                                <input type="text" name="decl_date" class="form-control pull-right" value="" id="decl_date">
                            </div>
                        </div>
                    </div>
                    <div class="col-sm-12">
                        <div class="form-group pull-right">
                            <a href="#" class="btn btn-danger" id="btn-clear-qr"><i class="fa fa-close"></i> Clear</a>
                            <a href="#" class="btn btn-primary" id="btn-search-qr"><i class="fa fa-search"></i> Search</a>
                        </div>
                    </div>
                </div>
            </form>
        </div><!-- /.box-header -->
        <table id="qr-dt-table" class="table display compact table-striped table-bordered " style="width: 100%;" cellspacing="0">
            <thead>
                <tr>
                    <th></th>
                    <th>Action</th>
                    <th>ID</th>
                    <th>Date</th>
                    <th>QR String</th>
                    <th>Name</th>
                    <th>Address</th>
                    <th>Ward</th>
                </tr>
            </thead>
            <tbody>
            </tbody>
        </table>
    </div>

</section>

<script type="text/javascript">
    var qr_dt_table;
    var qr_selected;
    $(document).ready(function() {

        $('#decl_date').datepicker({
            format: 'dd/mm/yyyy',
            autoclose: true,
            startDate: '-1y',
            endDate: '+1d',
            defaultViewDate: 'today',
            todayHighlight: true
        });

        $('#btn-search-qr').on('click', function() {
            // If you want totally refresh the datatable use this 
            qr_dt_table.ajax.reload();

        });

        $('#btn-clear-qr').on('click', function() {
            // If you want totally refresh the datatable use this 
            $('#search-qr').trigger("reset");
            qr_dt_table.ajax.reload();

        });
        //////////////////////////////////////////////////////////////////////////////
        $('#ps_id').select2({
            placeholder: "Select",
            theme: "bootstrap",
            triggerChange: true,
            allowClear: true,
            dropdownAutoWidth: true,
            data: <?php echo $ps; ?>
        });

        $('#gender,#surveillance_status').select2({
            placeholder: "Select",
            theme: "bootstrap",
            triggerChange: true,
            allowClear: true,
        });

        ///////////////////////////////////////////////////////////

        var options = {
            placeholder: "Select",
            theme: "bootstrap",
            triggerChange: true,
            allowClear: true,
            dropdownAutoWidth: true
        };

        $('#lsg_id').select2({
            theme: 'bootstrap',
            data: <?php echo $lsg ?>,
            allowClear: true
        });

        $('#ward_id').select2({
            theme: 'bootstrap',
            data: {},
            allowClear: true
        });
        ///////////////////////////////////////////////////////////

        var cascadeward = new Select2Cascade($('#lsg_id'), $('#ward_id'), '<?php echo base_url() . "master/wardByLsgCombo/"; ?>:parentId:', options);
        cascadeward.then(function(parent, child, items) {
            // Open the child listbox immediately
            child.select2('open');

        })

        ///////////////////////////////////////////////////////////


        //////////////////////////////////////////////////////////////////////////
        //////////////////////////////////////////////////////////////////////////


        //datatables
        qr_dt_table = $('#qr-dt-table').DataTable({
            "processing": true, //Feature control the processing indicator.
            "serverSide": true, //Feature control DataTables' server-side processing mode.
            "order": [], //Initial no order.
            "scrollX": true,
            "cache": false,
            "deferRender": false,
            "searching": false,

            // Load data for the table's content from an Ajax source
            "ajax": {
                "url": "<?php echo base_url('QR/contactsList') ?>",
                "type": "POST",
                "data": function(d) {
                    d.id = $('#id').val();
                    d.name = $('#name').val();
                    d.gender = $('#gender').val();
                    d.mobile = $('#mobile').val();
                    d.address = $('#address').val();
                    d.surveillance_status = $('#surveillance_status').val();
                    d.ps_id = $('#ps_id').val();
                    d.lsg_id = $('#lsg_id').val();
                    d.ward_id = $('#ward_id').val();
                    d.decl_date = $('#decl_date').val();
                    return d;
                },
                beforeSend: function() {
                    $('#main-content').block();
                },
                complete: function() {
                    $('#main-content').unblock();
                },

            },
            "dom": "Bfrtip",
            "buttons": [{
                "text": '<i class="fa fa-qrcode"></i> <i class="fa fa-download"></i>',
                "className": 'btn btn-success btn-md',
                action: function(e, dt, node, config) {
                    qr_selected = qr_dt_table.column(0).checkboxes.selected();
                    if (qr_selected.length >= 1 && qr_selected.length <= 20) {
                        downloadQr();
                        qr_dt_table.column(0).checkboxes.deselectAll();
                    } else {
                        eModal.alert('Select at least 1 and maximum 20 to print QR!');
                    }
                }
            }],
            "columns": [{
                    data: 'id'
                },
                {
                    data: 'id'
                },
                {
                    data: 'id'
                },
                {
                    data: 'decl_date'
                },
                {
                    data: 'random_qr'
                },
                {
                    data: 'p_name'
                },
                {
                    data: 'address'
                },
                {
                    data: 'ward'
                },

            ],
            "columnDefs": [{
                    'targets': 0,
                    'checkboxes': {
                        'selectRow': true,
                        "stateSave": false,
                        "orderable": false,
                    }

                },
                {
                    "targets": [3],
                    "width": "10%"
                },
                {
                    "targets": [1],
                    "width": "10%",
                    "orderable": false,
                },


            ],
            'select': {
                'style': 'multi'
            },
            'order': [
                [1, 'asc']
            ],
            "fnRowCallback": function(nRow, aData, iDisplayIndex, iDisplayIndexFull) {
                $('td:eq(5)', nRow).html("<span class='text-bold'>" + aData.p_name + "</span>");

            },
            "fnCreatedRow": function(nRow, aData, iDataIndex) {
                $('td:eq(1)', nRow).html("\<a href='#' class='btn btn-warning btn-xs'  onclick=\"setQRLink(" + aData.id + ",'" + aData.random_qr + "')\" data-toggle='tooltip' title='Link Contact to this QR'><span class='fa fa-home' ></span></a>\
                <a href='#' class='btn btn-success btn-xs'  onclick=\"regenerateQR(" + aData.id + ")\" data-toggle='tooltip' title='Regenerate QR'><span class='fa fa-refresh' ></span></a>");



            }, //Set column definition initialisation properties.
            //responsive

            ///
        });

        /////////////////////////////////////////////////////////                
        /////////////////////////////////////////////////////////
    });

    function setQRLink(id, random_qr) {
        if (random_qr != '') {
            var params = {
                buttons: [{
                    text: 'Close',
                    close: true,
                    style: 'danger',
                    click:function(){
                        qr_dt_table.ajax.reload();
                    }
                }],
                data: {
                    id: id,
                    random_qr: random_qr
                },
                method: 'POST',
                size: eModal.size.lg,
                title: 'Link Contact to QR code',
                url: '<?php echo base_url("QR/FrmLinkContact"); ?>'
            };
            eModal
                .ajax(params) 
                .then(function() {
                    // do something
                });
        }

    }

    function downloadQr() {
        var data = [{
            name: '<?php echo $this->security->get_csrf_token_name(); ?>',
            value: '<?php echo $this->security->get_csrf_hash(); ?>'
        }];
        qr_selected.each(function(d) {
            data.push({
                name: 'id[]',
                value: d
            });
        })
        myPost('<?php echo base_url("QR/downloadQR"); ?>', 'POST', data);
    }

    function myPost(action, method, values) {
        var form = $('<form/>', {
            action: action,
            method: method,
            target: '_blank'
        });
        $.each(values, function() {
            form.append($('<input/>', {
                type: 'hidden',
                name: this.name,
                value: this.value
            }));
        });
        form.appendTo('body').submit();
    }

    function regenerateQR(id) {
        var id = id;
        eModal.confirm('Do you really want to regenerate QR?', 'Regenerate QR')
            .then(function() {
                if (id > 0) {
                    var data = {
                        id: id
                    }
                    $.ajax({
                        type: "POST",
                        url: '<?php echo base_url("QR/regenerateQR"); ?>',
                        cache: false,
                        data: data,
                        dataType: 'json',
                        beforeSend: function() {
                            $('#main-content').block();
                        },
                        success: function(data) {

                            $('#main-content').unblock();
                            if (data.success) {
                                showMsg('Success', data.msg, 'success');
                                qr_dt_table.ajax.reload();
                            } else {
                                showMsg('Error', data.msg, 'danger');

                            }

                        }
                    });
                }
            }, function() {
                return false;
            });

    }
</script>