<?php

if (!isset($_SESSION)) {
    session_start();
}

require_once("dbase.php");
require_once("config.php");
//convert url parameters to variables
$p = "index";
if (isset($_GET)) {
    foreach ($_GET as $key => $value) {
        if (!empty($value)) {
            ${$key} = trim($value);
        }
    }
}

//prepare for sql interaction
function sql_escape($value)
{
    if (PHP_VERSION < 6) {
        $value = get_magic_quotes_gpc() ? stripslashes($value) : trim($value);
    }
    if (function_exists("mysql_real_escape_string")) {
        $value = mysql_real_escape_string($value);
    } else {
        $value = mysql_escape_string($value);
    }
    return $value;
}


//populate single record
function fetchOne($sql)
{
    global $conndb;
    mysql_select_db(DB_NAME, $conndb);
    $rs = mysql_query($sql, $conndb) or die("Database query error.<br/>" . mysql_error());
    $total = mysql_num_rows($rs);
    if ($total > 0) {
        $out = mysql_fetch_assoc($rs);
    }
    mysql_free_result($rs);
    return !empty($out) ? $out : null;
}

//populate array of records
function fetchAll($sql)
{
    global $conndb;
    mysql_select_db(DB_NAME, $conndb);
    $rs = mysql_query($sql, $conndb) or die("Database query error.<br/>" . mysql_error());
    while ($rows = mysql_fetch_assoc($rs)) {
        $out[] = $rows;
    }
    mysql_free_result($rs);
    return !empty($out) ? $out : null;
}


// execute sql statement
function executeSql($sql)
{
    global $conndb;
    mysql_select_db(DB_NAME, $conndb);
    $rs = mysql_query($sql, $conndb) or die("Database query error.<br/>" . mysql_error());
    return !$rs ? false : true;
}

//push content into layout
function pushLayout($cont)
{
    require_once("_tmp_header.php");
    echo $cont;
    require_once("_tmp_footer.php");
}

//render page
function render()
{
    global $p;
    global $s;
    $row = fetchPage($p);
    if (!empty($row)) {
        $content = $row['content'];
        $module = fetchModuleId($row['id']);
        if (!empty($module)) {
            echo getMod($module, $content);
        } else {
            pushLayout($content);
        }
    } else {
        echo getMod(1);
    }
}

// get page record
function fetchPage($identity)
{
    $sql = "SELECT * FROM pages WHERE identity = '";
    $sql .= sql_escape($identity);
    $sql .= "' LIMIT 1";
    $row = fetchOne($sql);
    return !empty($row) ? $row : null;
}

// get content of the module
function getMod($id, $content = null)
{
    global $p;
    global $s;
    global $required;
    $module = fetchModuleFile($id);
    $module_path = MOD_DIR . DS . $module;
    if (is_file($module_path)) {
        ob_start();
        require_once($module_path);
        return ob_get_clean();
    }
}


// fetch the name of the module
function fetchModuleFile($id)
{
    if (!empty($id)) {
        $sql = "SELECT file FROM modules WHERE id = ";
        $sql .= sql_escape($id);
        $sql .= " LIMIT 1 ";
        $row = fetchOne($sql);
    }
    return !empty($row) ? $row['file'] : null;
}


//fetch module by page id
function fetchModuleId($id)
{
    $sql = "SELECT modules_id FROM pages_modules WHERE pages_id = ";
    $sql .= sql_escape($id);
    $sql .= " LIMIT 1 ";
    $row = fetchOne($sql);

    return !empty($row) ? $row['modules_id'] : null;
}


// get age dropdown menu
function getAge($label, $limit = 80)
{
    $out = "<select name=\"{$label}\" id=\"{$label}\" class=\"form-control\"";
    for ($i = 17; $i <= $limit; $i++) {
        $out .= "<option  value=\"{$i}\"";
        $out .= stickyDropdown($label, $i);
        $out .= ">{$i}</option>";
    }
    $out .= "</select>";
    return $out;
}


// sticky dropdown menu
function stickyDropdown($par, $value)
{
    if (isset($_POST[$par]) && $_POST[$par] == $value) {
        return " selected=\"selected\"";
    } else if (isset($_SESSION[$par]) && $_SESSION[$par] == $value) {
        return " selected=\"selected\"";
    }
}

// populate all interests
function getInterests($name)
{
    $sql = "SELECT * FROM interests ORDER BY name";
    $count = 0;

    $rows = fetchAll($sql);

    if (!empty($rows)) {
        echo "<ul class=\"ul_check\">";

        foreach ($rows as $row) {
            echo "<th>";
            $count = $count + 1;
            echo "<label for=\"{$name}#" . $row['id'] . "\" class=\"radio\">";
            echo "<input type=\"checkbox\" name=\"{$name}#" . $row['id'] . "\" id=\"{$name}#" . $row['id'] . "\" value=\"" . $row['id'] . "\"";
            echo stickyCheck("{$name}#" . $row['id'], $row['id']);
            echo " /><span>" . $row['name'] . "</span></label>";

            echo "</th>";
            if (($count % 5) == 0) {
                echo "</tr>    <tr> ";
            }
        }
        echo "</ul>";
    }
    return isset($out) ? $out : null;
}

// populate the name of the interest
function getInterest($id)
{
    $sql = "SELECT * FROM interests WHERE id = '" . sql_escape($id) . "'";
    $row = fetchOne($sql);
    return !empty($row) ? $row['name'] : null;
}

//populate all colors
function getColors($name)
{
    $sql = "SELECT * FROM colors ORDER BY NAME  ASC ";
    $count = 0;

    $rows = fetchAll($sql);

    if (!empty($rows)) {
        echo "<ul class=\"ul_check\">";

        foreach ($rows as $row) {
            echo "<th>";
            $count = $count + 1;
            echo "<label for=\"{$name}#" . $row['id'] . "\" class=\"radio-inline\">";
            echo "<input type=\"radio\"name=\"{$name}\"id=\"{$name}#" . $row['id'] . "\"value=\"" . $row['id'] . "\"";
            echo stickyRadio("{$name}", $row['id']);
            echo " /><span>" . $row['name'] . "</span></label>";

            echo "</th>";
            if (($count % 2) == 0) {
                echo "</tr>    <tr> ";
            }
        }
        echo "</ul>";
    }
    return isset($out) ? $out : null;
}

//populate name of the color
function getColor($id)
{
    $sql = "SELECT * FROM colors WHERE id = '" . sql_escape($id) . "'";
    $row = fetchOne($sql);
    return !empty($row) ? $row['name'] : null;
}

//populate all search engine
function getSearchEngines($name)
{
    $sql = "SELECT * FROM search_engines ORDER BY NAME  ASC ";
    $count = 0;

    $rows = fetchAll($sql);

    if (!empty($rows)) {
        echo "<ul class=\"ul_check\">";

        foreach ($rows as $row) {
            echo "<th>";
            $count = $count + 1;
            echo "<label for=\"{$name}#" . $row['id'] . "\" class=\"radio-inline\">";
            echo "<input type=\"radio\"name=\"{$name}\"id=\"{$name}#" . $row['id'] . "\"value=\"" . $row['id'] . "\"";
            echo stickyRadio("{$name}", $row['id']);
            echo " /><span>" . $row['name'] . "</span></label>";

            echo "</th>";
            if (($count % 2) == 0) {
                echo "</tr>    <tr> ";
            }
        }
        echo "</ul>";
    }
    return isset($out) ? $out : null;
}

//populate search engine name
function getSearchEngine($id)
{
    $sql = "SELECT * FROM search_engines WHERE id = '" . sql_escape($id) . "'";
    $row = fetchOne($sql);
    return !empty($row) ? $row['name'] : null;
}


// get country dropdown menu
function getCountry($label)
{
    $sql = "SELECT * FROM countries ORDER BY name ASC";
    $rows = fetchAll($sql);

    if (!empty($rows)) {
        $out = " <select name=\"{$label}\" id=\"{$label}\" class=\"form-control\"";
        $out .= "<option value=\"\">Select One&hellip;</option>";
        foreach ($rows as $row) {
            $out .= "<option value=\"" . $row['id'] . "\"";
            $out .= stickyDropdown($label, $row['id']);
            $out .= ">" . $row['name'] . "</option>";
        }
        $out .= "</select>";
    }
    return isset($out) ? $out : null;
}

//populate the name of the country
function getCountries($id)
{
    $sql = "SELECT * FROM countries WHERE id = '" . sql_escape($id) . "'";
    $row = fetchOne($sql);
    return !empty($row) ? $row['name'] : null;
}


// sticky text field
function stickyTest($par)
{
    if (isset($_POST[$par])) {
        return stripslashes($_POST[$par]);
    } else if (isset($_SESSION[$par])) {
        return stripslashes($_SESSION[$par]);
    }
}


// sticky radio buttons
function stickyRadio($par, $value, $def = null)
{
    if (isset($_POST[$par]) && $_POST[$par] == $value) {
        return " checked=\"checked\"";
    } elseif (isset($_SESSION[$par]) && $_SESSION[$par] == $value) {
        return " checked=\"checked\"";
    } elseif ($value == $def) {
        return " checked=\"checked\"";
    }
}

// sticky checkbox
function stickyCheck($par, $value, $def = null)
{
    if ($_POST) {
        if (isset($_POST[$par]) && $_POST[$par] == $value) {
            return " checked=\"checked\"";
        }
    } else {
        if (isset($_SESSION[$par])) {
            return " checked=\"checked\"";
        } elseif ($value == $def) {
            return " checked=\"checked\"";
        }
    }
}

// get validation messages
function isValid($key, $array = null)
{
    if (!empty($array)) {
        return array_key_exists($key, $array) ? "<span class=\"help-block\">" . $array[$key] . "</span>" : null;
    } else {
        return null;
    }
}


// function to redirect to another page
function redirect($url = null)
{
    if ($url != null) {
        header("Location: {$url}");
        exit;
    }
}

function post2session($parts = array())
{
    $out = array();
    foreach ($_POST as $key => $value) {
        $value = is_array($value) ? $value : trim($value);
        $par = explode("#", $key);
        if (in_array($par[0], $parts)) {
            $out[$key] = $value;
        } else {
            $_SESSION[$key] = $value;
        }
    }

    if (!empty($out)) {
        foreach ($_SESSION as $key => $value) {
            $par = explode("#", $key);
            if (in_array($par[0], $parts) && !array_key_exists($key, $out)) {
                unset($_SESSION[$key]);
            }
        }

        foreach ($out as $key => $value) {
            $_SESSION[$key] = $value;
        }
    } else {
        foreach ($_SESSION as $key => $value) {
            $par = explode("#", $key);
            if (in_array($par[0], $parts)) {
                unset($_SESSION[$key]);
            }
        }
    }
}

function printSessions()
{
    if (isset($_SESSION)) {
        foreach ($_SESSION as $key => $value) {
            echo "Key: {$key} / Value : {$value} <br />";
        }
    }
}
