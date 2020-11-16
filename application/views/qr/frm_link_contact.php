<section class="content">
    <div class="box box-default">
        <div class="box-header">
            <form id="search-contacts">
                <input type="hidden" name="<?php echo $this->security->get_csrf_token_name(); ?>" value="<?php echo $this->security->get_csrf_hash(); ?>">
                <input type="hidden" value="<?php echo $id; ?>" name="fromid" id="fromid" />
                <input type="hidden" value="<?php echo $random_qr; ?>" name="qrcd" id="qrcd" />
                <div class="row bg-gray-light" style="padding: 10px 10px 10px 0px">
                    <div class="col-sm-4">
                        <div class="form-group">                            
                            <input id="sname" type="text" value="" name="name" class="form-control" placeholder="Name" autocomplete="off">
                        </div>
                    </div>
                    <div class="col-sm-3">
                        <div class="form-group">

                            <input id="smobile" autocomplete="off" type="text" value="" name="mobile" class="form-control" placeholder="mobile">
                        </div>
                    </div>
                    <div class="col-sm-3">
                        <div class="form-group">

                            <input id="saddress" autocomplete="off" type="text" value="" name="address" class="form-control" placeholder="address">
                        </div>
                    </div>
                    <div class="col-sm-2">
                        <div class="form-group pull-right">
                            <a href="#" class="btn btn-primary" id="btn-lnksearch-contacts"><i class="fa fa-search"></i> Search</a>
                        </div>
                    </div>
                </div>
            </form>

            <table id="lnk-dt-table" class="table display compact table-striped table-bordered " style="width: 100%;" cellspacing="0" width="100%">
                <thead>
                    <tr>
                        <th>Action</th>
                        <th>Name</th>
                        <th>Address</th>
                    </tr>
                </thead>
                <tbody>
                </tbody>
            </table>
        </div>

</section>

<script type="text/javascript">
    var lnk_dt_table;
    $(document).ready(function() {
        $('#btn-lnksearch-contacts').on('click', function() {
            // If you want totally refresh the datatable use this 
            lnk_dt_table.ajax.reload();

        });

        //////////////////////////////////////////////////////////////////////////
        //////////////////////////////////////////////////////////////////////////


        //datatables
        lnk_dt_table = $('#lnk-dt-table').DataTable({
            "processing": true, //Feature control the processing indicator.
            "serverSide": true, //Feature control DataTables' server-side processing mode.
            "order": [], //Initial no order.
            "scrollX": true,
            "cache": false,
            "deferRender": false,
            "searching": false,
            "dom": '<"top">rt<"bottom"p><"clear">',

            // Load data for the table's content from an Ajax source
            "ajax": {
                "url": "<?php echo base_url('QR/linkcontactsList') ?>",
                "type": "POST",
                "data": function(d) {
                    d.id = $('#fromid').val();
                    d.random_qr = $('#qrcd').val();
                    d.name = $('#sname').val();
                    d.mobile = $('#smobile').val();
                    d.address = $('#saddress').val();
                    return d;
                },
                beforeSend: function() {
                    $('#main-content').block();
                },
                complete: function() {
                    $('#main-content').unblock();
                },

            },

            "columns": [{
                    data: 'id'
                },
                {
                    data: 'p_name'
                },
                {
                    data: 'address'
                }

            ],
            "columnDefs": [{
                    "targets": [0],
                    "orderable": false,
                },
                {
                    "targets": [-1],
                    "orderable": false
                },

            ],
            "fnRowCallback": function(nRow, aData, iDisplayIndex, iDisplayIndexFull) {
                $('td:eq(1)', nRow).html("<span class='text-bold'>" + aData.p_name + "</span>");

            },
            "fnCreatedRow": function(nRow, aData, iDataIndex) {
                $('td:eq(0)', nRow).html("\ <a href='#' class='btn btn-default btn-sm'  onclick=\"setQRLinkto(" + aData.id + ")\" data-toggle='tooltip' title='Link as Contact'><span class='fa fa-link' ></span></a>");

            }, //Set column definition initialisation properties.
            //responsive

            ///
        });

        /////////////////////////////////////////////////////////                
        /////////////////////////////////////////////////////////
    });

    function setQRLinkto(id) {


        var data = {
            
            id: id,
            from_id:<?php echo $id; ?>
        }

        $.ajax({
            type: "POST",
            url: '<?php echo base_url("QR/setQRLink"); ?>',
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
                    lnk_dt_table.ajax.reload();
                } else {
                    showMsg('Error', data.msg, 'danger');

                }

            }
        });

    }
</script>