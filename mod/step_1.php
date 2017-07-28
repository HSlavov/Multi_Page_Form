<?php
//array to store validation messages
$missing = array();

if (isset($_POST['first_name'])) {
    foreach ($_POST as $key => $value) {
        $value = is_array($value) ? $value : trim($value);
        if (empty($value) && array_key_exists($key, $required)) {
            $missing[$key] = $required[$key];
        }
    }

    if (!isset($_POST['gender'])) {
        $missing['gender'] = $required['gender'];
    }

    if (empty($missing)) {
        post2session();
        $_SESSION['step'][1] = 1;
        redirect("?p=submission_form&s=interest");
    }
}

require_once("_tmp_header.php");

?>
    <div class="col-lg-3"></div>
    <div class="col-lg-6">
        <div class="panel panel-primary">
            <div class="panel-heading">
                <h1>Personal details</h1>
            </div>
            <br>
            <div class="alert alert-info">
                <p>Please tell us a little bit about yourself.</p>
            </div>
            <div class="well">
                <form action="" method="POST" role="form">
                    <table cellpadding="0" cellspacing="0" border="0" class="tbl_insert">
                        <div class="form-group">
                            <tr>
                                <label for="first_name" class="form-inline">First name: *</label>
                                <input type="text" name="first_name" id="first_name" class="form-control"
                                       value="<?php echo stickyTest('first_name') ?>"/>
                                <p><?php echo isValid('first_name', $missing); ?> </p>
                            </tr>


                            <tr>
                                <label for="last_name" class="control-label">Last name: *</label>
                                <input type="text" name="last_name" id="last_name" class="form-control"
                                       value="<?php echo stickyTest('last_name') ?>"/>
                                <p>  <?php echo isValid('last_name', $missing); ?></p>
                            </tr>


                            <tr>
                                <label for="age" class="control-label">Age: *</label>
                                <?php echo getAge('age'); ?>
                                <p><?php echo isValid('age', $missing); ?></p>
                            </tr>

                            <tr>
                                <label for="gender" class="control-label">Gender: * &nbsp;</label>
                                <?php echo isValid('gender', $missing); ?>
                                <label for="gender_m" class="radio-inline">
                                    <input type="radio" name="gender" id="gender_m"
                                           value="m" <?php echo stickyRadio('gender', 'm'); ?> /><span>Male</span>
                                </label>
                                <label for="gender_f" class="radio-inline">
                                    <input type="radio" name="gender" id="gender_f"
                                           value="f" <?php echo stickyRadio('gender', 'f'); ?> /><span>Female</span>
                                </label>
                                <p></p>
                            </tr>

                            <tr>
                                <label for="country" class="control-label">Country: *</label>
                                <?php echo getCountry('country'); ?>
                                <p><?php echo isValid('country', $missing); ?></p>
                            </tr>

                            <tr>
                                <label for="btn" class="sbm btn_standard sbm_next">
                                    <input type="submit" id="btn" class="btn btn-outline btn-success" Value="Next"/>
                                </label>
                            </tr>
                        </div>
                    </table>
                </form>
            </div>
        </div>
    </div>
    <div class="col-lg-3"></div>
<?php
require_once("_tmp_footer.php");
?>