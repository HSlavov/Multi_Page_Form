<?php
require_once("_tmp_header.php");

if (isset($s)) {
    echo getMod(9);
} else {

    $sql = "SELECT * FROM survey ORDER BY date_submitted DESC ";
    $rows = fetchAll($sql);

    if (!empty($rows)) {
        ?>
        <div class="col-lg-3"></div>
        <div class="  col-lg-6">

            <div class="panel panel-default">
                <div class="panel-heading">
                    <?php echo $content; ?>
                </div>
                <!-- /.panel-heading -->
                <div class="panel-body">
                    <table width="100%" class="table table-striped table-bordered table-hover" id="dataTables-example">
                        <thead>
                        <tr>
                            <th class="col-lg-9">Full name</th>
                            <th class="">Date submitted</th>
                            <th> </th>
                        </tr>
                        </thead>
                        <tbody>
                        <?php foreach ($rows as $row) { ?>
                            <tr>
                                <th><?php echo $row['first_name'] . "  " . $row['last_name']; ?></th>
                                <th class=""><?php echo date("H:i:s      d-m-Y", strtotime($row['date_submitted'])); ?></th>
                                <th><a href="?p=<?php echo $p; ?>&amp;s=<?php echo $row['id']; ?>">View</a></th>
                                </th>
                            </tr>
                        <?php } ?>
                        </tbody>
                    </table>
                   </div>
            </div>
        </div>
        <div class="col-lg-3"></div>
        <?php
    } else {
        ?>
        <p>There are currently no submission available.</p>
        <?php
    }
}
require_once("_tmp_footer.php");
?>
