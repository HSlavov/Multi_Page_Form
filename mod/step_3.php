<?php
if (!isset($_SESSION['step'][2])) {
    redirect("?p=submission_form&s=interest");
}

$missing = array();

if (isset ($_POST['btn1']) || isset($_POST['btn2'])) {

    if (isset($_POST['btn1'])) {
        unset($_POST['btn1']);
        $url = "?p=submission_form&s=interest";
    } elseif (isset($_POST['btn2'])) {
        unset($_POST['btn2']);

        if (!isset($_POST['color'])) {
            $missing['color'] = $required['color'];
        }

        $url = "?p=submission_form&s=search_engine";
    }

    if (empty($missing)) {
        post2session();
        $_SESSION['step'][3] = 3;
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
                <h1>Color</h1>
            </div>
            <br>
            <div class="alert alert-info">
                <p>What is your favorite color?</p>
            </div>
            <div class="well">
                <form action="" method="post">
                    <?php echo isValid('color', $missing); ?>
                    <table class="tbl_insert">
                        <tr>
                            <?php echo getColors('color'); ?>
                            <br/>
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
                            </th>
                        </tr>
                    </table>
                </form>
            </div>
        </div>
    </div>

<?php
require_once("_tmp_footer.php");
?>