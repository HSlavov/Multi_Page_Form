<?php
$sql = "SELECT * FROM survey WHERE id = '" . sql_escape($s) . "'";
$row = fetchOne($sql);

if (!empty($row)) {
    $int = array();
    $sql = "SELECT * FROM survey_interests WHERE survey = '" . sql_escape($s) . "'";
    $interests = fetchAll($sql);

    if (!empty($interests)) {
        foreach ($interests as $item) {
            $int[] = getInterest($item['interest']);
        }
    }
    ?>

    <div class="col-lg-3"></div>
    <div class="  col-lg-6">

        <div class="panel panel-default">
            <div class="panel-heading">
                <?php echo "<h1>Submission details</h1>"; ?>
            </div>
            <!-- /.panel-heading -->
            <div class="panel-body">
                <table width="100%" class="table table-striped table-bordered table-hover" id="dataTables-example">
                    <tr>
                        <th class="col-lg-3">Full name:</th>
                        <td><?php echo $row['first_name'] . " " . $row['last_name']; ?></td>
                    </tr>
                    <tr>
                        <th>Age:</th>
                        <td><?php echo $row['age']; ?></td>
                    </tr>
                    <tr>
                        <th>Gender:</th>
                        <td><?php echo $row['gender'] == 'm' ? 'Male' : 'Female'; ?></td>
                    </tr>
                    <tr>
                        <th>Country:</th>
                        <td><?php echo getCountries($row['country']); ?></td>
                    </tr>
                    <?php if (!empty($int)) { ?>
                        <tr>
                            <th>Interests:</th>
                            <td><?php echo implode(", ", $int); ?></td>
                        </tr>
                    <?php } ?>
                    <tr>
                        <th>Favorite color:</th>
                        <td><?php echo getColor($row['color']); ?></td>
                    </tr>
                    <tr>
                        <th>Favorite search engine:</th>
                        <td><?php echo getSearchEngine($row['search_engine']); ?></td>
                    </tr>
                </table>
                <a type="button" class="btn btn-info col-lg-12" href="?p=submissions">BACK </a>
            </div>
        </div>
    </div>
    <div class="col-lg-3"></div>
    <?php
}
?>

