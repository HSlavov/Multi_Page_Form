<?php
if (!isset($_SESSION['step'][1])) {
    redirect("?=submission_form");
}

$missing = array();

if (isset ($_POST['btn1']) || isset($_POST['btn2'])) {
    if (isset($_POST['btn1'])) {
        unset($_POST['btn1']);
        $url = "?p=submission_form";
    } elseif (isset($_POST['btn2'])) {
        unset($_POST['btn2']);
        $form = array();
        $url = "?p=submission_form&s=color";
        // process $_POST
        foreach ($_POST as $key => $value) {
            $value = is_array($value) ? $value : trim($value);
            $key = explode("#", $key);
            $form[$key[0]] = $value;
        }

        //if 'interest' hasn bee found display validation
        if (!array_key_exists('interests', $form)) {
            $missing['interests'] = $required['interests'];
        }
        $url = "?p=submission_form&s=color";
    }

    if (empty($missing)) {
        post2session(array('interests'));
        $_SESSION['step'][2] = 2;
        if (isset($url)) {
            redirect($url);
        }
    }
}

require_once("_tmp_header.php");
?>
    <div class="col-lg-3"></div>
    <div class="col-lg-6">
        <div class="panel panel-primary">
            <div class="panel-heading">
                <h1>Interests</h1>
            </div>
            <br>
            <div class="alert alert-info">
            <p>Tell us about your interests.</p>
            </div>
            <div class="well">
                <form action="" method="post">
                     <table class="tbl_insert">
                        <tr>
                            <?php echo getInterests('interests'); ?>
                        </tr>
                        <tr>
                            <th>
                                <label for="btn1" class="sbm btn_standard sbm_previous fl_l mr_r10">
                                    <input type="submit" id="btn1" name="btn1" class="btn btn-outline btn-danger"
                                           value="Previous"/>
                                </label>

                                <label for="btn2" class="sbm btn_standard fl_l sbm_next">
                                    <input type="submit" id="btn2" name="btn2" class="btn btn-outline btn-success"
                                           value="Next"/>
                                </label>

                                <label for="btn3" class=" ">
                                    <input type="reset" id="btn3" name="btn3" class="btn btn-warning"
                                           value="Reset" "/>
                                </label>
                            </th>
                        </tr>
                    </table>
                </form>
            </div>
        </div>
    </div>
    <div class="col-lg-3"></div>

<?php
require_once("_tmp_footer.php");
?>