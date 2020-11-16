<?php
/*
 * To change this license header, choose License Headers in Project Properties.
 * To change this template file, choose Tools | Templates
 * and open the template in the editor.
 */
?>
<footer class="main-footer no-print">
    <div class="pull-right hidden-xs">
        <b>Version</b> 1.0.4 [Stable]
    </div>
    <strong>Developed by Kerala Police ICT Centre</strong>
    <div id="inners" class="col-md-3" style="z-index: 2000"></div>
</footer>
</div>
<!-- ./wrapper -->
<script>
    var base_url = "<?php echo base_url(); ?>";
</script>

<!-- jQuery 2.2.3 -->
<script src="<?php echo base_url(); ?>public/plugins/jQuery/jquery.js"></script>
<script src="<?php echo base_url(); ?>public/libs/context-menu/jquery.contextMenu.min.js"></script>
<script src="<?php echo base_url(); ?>public/js/jquery.loadJSON.js"></script>
<!-- Bootstrap 3.3.6 -->
<script src="<?php echo base_url(); ?>public/bootstrap/js/bootstrap.min.js"></script>

<script src="<?php echo base_url(); ?>public/js/eModal.min.js"></script>

<!-- SlimScroll -->
<script src="<?php echo base_url(); ?>public/plugins/slimScroll/jquery.slimscroll.min.js"></script>
<!-- FastClick -->
<script src="<?php echo base_url(); ?>public/plugins/fastclick/fastclick.js"></script>
<!-- AdminLTE App -->
<script src="<?php echo base_url(); ?>public/js/app.min.js"></script>


<script src="<?php echo base_url(); ?>public/js/jquery.blockUI.js"></script>
<script src="<?php echo base_url(); ?>public/libs/validator/js/bootstrapValidator.min.js"></script>

<!-- Data Tables -->
<script src="<?php echo base_url(); ?>public/libs/datatables/datatables.min.js"></script>
<script src="<?php echo base_url(); ?>public/libs/datatables/dataTables.checkboxes.min.js"></script>
<script src="<?php echo base_url(); ?>public/libs/datatables/dataTables.responsive.min.js"></script>

<script src="<?php echo base_url(); ?>public/libs/tree/treeview.js" type="text/javascript"></script>
<script src="<?php echo base_url(); ?>public/js/jquery.collapsibleCheckboxTree.js" type="text/javascript"></script>
<script src="<?php echo base_url(); ?>public/plugins/select2/select2.min.js" type="text/javascript"></script>
<script src="<?php echo base_url(); ?>public/js/scripts.js" type="text/javascript"></script>
<script language="javascript" src="<?php echo base_url('public/plugins/datepicker/bootstrap-datepicker.js'); ?>"></script>
<script language="javascript" src="<?php echo base_url('public/plugins/daterangepicker/daterangepicker.js'); ?>"></script>

<script language="javascript" src="<?php echo base_url('public/plugins/bootstrap-datetimepicker/js/moment.js'); ?>"></script>
<script language="javascript" src="<?php echo base_url('public/plugins/bootstrap-datetimepicker/js/bootstrap-datetimepicker.min.js'); ?>"></script>

<script language="javascript" src="<?php echo base_url('public/plugins/bootstrap-toggle/bootstrap-toggle.min.js'); ?>"></script>
<script language="javascript" src="<?php echo base_url('public/plugins/jtabledit/jquery.tabledit.min.js'); ?>"></script>

<script src="<?php echo base_url(); ?>public/plugins/cropper/cropper.min.js"></script>
<script src="<?php echo base_url(); ?>public/plugins/cropper/jquery-cropper.min.js"></script>
<script language="javascript" src="<?php echo base_url('public/plugins/chartjs-new/dst/Chart.min.js'); ?>"></script>
<script language="javascript" src="<?php echo base_url('public/plugins/chartjs-new/dst/utils.js'); ?>"></script>

<script language="javascript" src="<?php echo base_url('public/plugins/iCheck/icheck.min.js'); ?>"></script>
<script src="<?php echo base_url(); ?>public/plugins/leaflet/leaflet.js"></script>
<!-- <script src="<?php echo base_url(); ?>public/plugins/leaflet/dist/leaflet.markercluster.js"></script> -->
<script src="<?php echo base_url(); ?>public/plugins/leaflet/dist/leaflet.markercluster-src.js"></script>
<script src="<?php echo base_url(); ?>public/plugins/leaflet/dist/leaflet-kmz.js"></script>
<script src="<?php echo base_url(); ?>public/plugins/leaflet/dist/Leaflet.fullscreen.min.js"></script>






<script>
    var master;

    Array.prototype.mapProperty = function(property) {
        return this.map(function(obj) {
            return obj[property];
        });
    };

    $(function() {


        $.ajaxSetup({
            cache: false,
            contentType: "application/x-www-form-urlencoded",
            error: function(jqXHR, textStatus, errorThrown) {
                //showMsg('Error','Cannot process now','danger')
                if (jqXHR.statusText == 'abort') {
                    return;
                }
                var msg
                if (jqXHR.status === 404) {
                    msg = "Element not found.";
                } else {
                    msg = "Error: " + textStatus + ": " + errorThrown;
                }

                //eModal.alert(msg,'Error');
                showMsg('Error', msg, 'danger')
                $.unblockUI();
                $(".blockUI").fadeOut("slow");
            },
            data: {
                '<?php echo $this->security->get_csrf_token_name(); ?>': '<?php echo $this->security->get_csrf_hash(); ?>'
            },
            beforeSend: function(xhr) {

                xhr.setRequestHeader("AJX", "X_AJAX");
            },
            complete: function(xhr, textStatus) {
                if (textStatus == 'error') {
                    $.unblockUI();
                    $(".blockUI").fadeOut("slow");
                }
                if (xhr.getResponseHeader("SESSION") == 0) {
                    showMsg('Session Expired', 'Your session has been expired, Please login again', 'danger')
                    window.parent.location = xhr.getResponseHeader("LOGOUT");
                };
            },
            //  timeout: 1000000 // Timeout of 60 seconds
        });
        //////////////////////////////////////////////////////
        $('[data-toggle="tooltip"]').tooltip();
        /*  $.blockUI();
          $.getJSON("<?php echo base_url(); ?>master/get", function (data) {
              master = data;
          }).done(function () {
            //  console.log(master);
              $.unblockUI();
          });*/
        ////////////////////////
        ///////////////////////



    });
    //////////////////////////////////custom scripts//////////////////////////////////////////////////////////////
    function loadLayout(title, url) {

        $("#main-content").empty().block();
        $("#main-content").load(base_url + url, function() {
            $("#main-content").unblock();
        });
    }

    function postLayout(action, data) {

        $("#main-content").empty().block();
        $("#main-content").load(base_url + action, data, function() {
            $("#main-content").unblock();
        });

    }



    function loadPage(url) {
        $("#main-content").empty().block();
        $("#main-content").load(base_url + url, function() {
            $("#main-content").unblock();
        });
    }

    function showMsg(t, m, s) {
        $('#inners').show();
        $("#inners").html('<div class="box box-' + s + ' box-solid">\n\
                 <div class="box-header with-border">\n\
                <h3 class="box-title"><i class="fa fa-check"> </i> ' + t + '</h3>\n\
                <div class="box-tools pull-right">\n\
                <button type="button" class="btn btn-box-tool" data-widget="collapse"><i class="fa fa-minus"></i>\
                 </div>\n\
                </div>\n\
                <div class="box-body">' +
            m +
            ' </div>\n\
                 </div>');
        setTimeout(function() {
            $('#inners').fadeOut('slow');
        }, 3500);
    }

    function populateForm(form, data) {
        //console.log("PopulateForm, All form data: " + JSON.stringify(data));

        $.each(data, function(key, value) // all json fields ordered by name
            {
                //console.log("Data Element: " + key + " value: " + value );
                var $ctrls = $(form).find('[name=' + key + ']'); //all form elements for a name. Multiple checkboxes can have the same name, but different values

                //console.log("Number found elements: " + $ctrls.length );

                if ($ctrls.is('select')) //special form types
                {
                    $('option', $ctrls).each(function() {
                        if (this.value == value)
                            this.selected = true;
                    });
                } else if ($ctrls.is('textarea')) {
                    $ctrls.val(value);
                } else {
                    switch ($ctrls.attr("type")) //input type
                    {
                        case "text":
                        case "hidden":
                            $ctrls.val(value);
                            break;
                        case "radio":
                            if ($ctrls.length >= 1) {
                                //console.log("$ctrls.length: " + $ctrls.length + " value.length: " + value.length);
                                $.each($ctrls, function(index) { // every individual element
                                    var elemValue = $(this).attr("value");
                                    var elemValueInData = singleVal = value;
                                    if (elemValue === value) {
                                        $(this).prop('checked', true);
                                    } else {
                                        $(this).prop('checked', false);
                                    }
                                });
                            }
                            break;
                        case "checkbox":
                            if ($ctrls.length > 1) {
                                //console.log("$ctrls.length: " + $ctrls.length + " value.length: " + value.length);
                                $.each($ctrls, function(index) // every individual element
                                    {
                                        var elemValue = $(this).attr("value");
                                        var elemValueInData = undefined;
                                        var singleVal;
                                        for (var i = 0; i < value.length; i++) {
                                            singleVal = value[i];
                                            console.log("singleVal : " + singleVal + " value[i][1]" + value[i][1]);
                                            if (singleVal === elemValue) {
                                                elemValueInData = singleVal
                                            };
                                        }

                                        if (elemValueInData) {
                                            //console.log("TRUE elemValue: " + elemValue + " value: " + value);
                                            $(this).prop('checked', true);
                                            //$(this).prop('value', true);
                                        } else {
                                            //console.log("FALSE elemValue: " + elemValue + " value: " + value);
                                            $(this).prop('checked', false);
                                            //$(this).prop('value', false);
                                        }
                                    });
                            } else if ($ctrls.length == 1) {
                                $ctrl = $ctrls;
                                if (value) {
                                    $ctrl.prop('checked', true);
                                } else {
                                    $ctrl.prop('checked', false);
                                }

                            }
                            break;
                    } //switch input type
                }
            }) // all json fields
    }
    // populate form


    loadLayout('Dashboard', 'home/dashboard');
</script>

</body>

</html>