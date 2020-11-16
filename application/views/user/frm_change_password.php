                                                                                                                                                                                                                                                                                                                                                                                                                                                                                                                                                                                                                                                                                                                                                                                                                                                                                                                                                                                                                                                                                                                                                                                                                                                                                                                                                                                                                                                                                                                                                                                                                                                                                                                                                                                                                                                                                                                                                                                                                                                                                                                                                                                                                                                                                                                                                                                                                                                                                                                                                                                                                                                                                                                                                                                                                                                                                                                                                                                                                                                                                                                                                                                                                                                                                                                                                                                                                                                                                                                                                                                                                                                                                                                                                                                                                                                                                                                                                                                                                                                                                                                                                                                                                                                                                                                                                                                                                                                                                                                                                                                                                                                                                                                                                                                                                                                                                                                                                                                                                                                                                                                                                                                                                                                                                                                                                                                                                                                                                                                                                                                                                                                                                                                                                                                                                                                                                                                                                                                                                                                                                                                                                                                                                                                                                                                                                                                                                             <?php
if (!defined('BASEPATH'))
    exit('No direct script access allowed');
?>
<section class="content-header">
    <div class="box box-success">
        <div class="box-header with-border">
            <h4 class="box-title">
                <i class="fa fa-sitemap"> </i> Change Password</h4>
        </div>
    </div>
    <ol class="breadcrumb" style="padding: 20px">
        <li><a href="#"><i class="fa fa-dashboard"></i> Home</a></li>
        <li>Account Settings</li>
        <li class="active">Change Password</li>
    </ol>
</section>
<section class="content">
    <div class="box box-default" style="padding: 10px 10px 10px 10px">
        <div class="box-header with-border">
            <h4 class="box-title"></h4>           
        </div><!-- /.box-header -->
        <form class="form-horizontal" id="frm-change-pass" autocomplete="off" method="post">

            <input type="hidden" name="<?php echo $this->security->get_csrf_token_name(); ?>" value="<?php echo $this->security->get_csrf_hash(); ?>">
            <input type="hidden" value="" name="id"/>
            <div class="form-body">
                <div class="form-group">
                    <label class="control-label col-md-3">Old Password</label>
                    <div class="col-md-3">
                        <input name="opwd" placeholder="Old Password" class="form-control" autocomplete="off" type="password"  data-val="true" data-val-required="This field is required."  />
                    </div>
                </div> 
                <div class="form-group">
                    <label class="control-label col-md-3">New Password</label>
                    <div class="col-md-3">
                        <input name="npwd" placeholder="New Password" class="form-control" type="password"  data-val="true" data-val-required="This field is required."  />
                    </div>
                </div> 
                <div class="form-group">
                    <label class="control-label col-md-3">Confirm Password</label>
                    <div class="col-md-3">
                        <input name="cpwd" placeholder="Confirm Password" class="form-control" type="password"  data-val="true" data-val-required="This field is required."  />
                    </div>
                </div>                    
            </div>
            <div class="modal-footer">
                <div class="col-md-6">
                    <button id="resetBtn" class="btn btn-danger" type="reset" onclick="$('#frm-change-pass').data('bootstrapValidator').resetForm();"><i class="fa fa-close"></i>  Clear</button>
                    <button type="submit"  id="change-pass-submit-btn"  class="btn btn-primary" ><i class="fa fa-save"></i> Save</button>

                </div>
            </div>
        </form>

    </div>
</section>

<script type="text/javascript">

    $(function () {

        ///////////////////////////////form submission/////////////////////
        $('#frm-change-pass').bootstrapValidator({
            message: 'This value is not valid',
            excluded: [':disabled'],
            feedbackIcons: {
                valid: 'glyphicon glyphicon-ok',
                invalid: 'glyphicon glyphicon-remove',
                validating: 'glyphicon glyphicon-refresh'
            },
            fields: {
                opwd: {
                    message: 'The Rank description is not valid',
                    validators: {
                        notEmpty: {
                            message: 'The old password is required'
                        }
                    }
                },
                npwd: {
                    validators: {
                        notEmpty: {
                            message: 'The new password is required'
                        },
                        different: {
                            field: 'opwd',
                            message: 'New password should be different from old one'
                        },
                        stringLength: {
                            max: 20,
                            min: 8,
                            message: 'Minimum length should be 8 and maximum length is 20'
                        }
                    }
                },
                cpwd: {
                    validators: {
                        notEmpty: {
                            message: 'The confirm password is required'
                        },
                        identical: {
                            field: 'npwd',
                            message: 'The password and its confirm are not the same'
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
             console.log(result);
             }, 'json');
             */
            $.ajax({
                type: "POST",
                url: '<?php echo base_url(); ?>usermanager/change_password',
                cache: false,
                data: $form.serialize(),
                dataType: 'json',
                beforeSend: function () {
                    $('#main-content').block();
                },
                success: function (data) {
                    $('#main-content').unblock();
                    if (data.success) {
                        showMsg('Success', data.msg, 'success');
                    } else {
                        showMsg('Error', data.msg, 'danger');

                    }
                    resetChangePassForm();

                }
            });
        });
///////////////////////////////////////////////////////////////////
    });

    function resetChangePassForm() {
        $('#frm-change-pass').data('bootstrapValidator').resetForm();
        $('#frm-change-pass').trigger("reset");
    }
</script>
