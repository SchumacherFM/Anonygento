#!/usr/bin/env php
<?php
/* ========================================================
 * File           : dumpyaml.php
 * Date           : July 15, 2010
 * Author         : Jonathan Franzone
 * Author Website : http://www.franzone.com
 *
 * Script to generate YAML (http://www.yaml.org/) from
 * a MySQL database. Will generate YAML for either all
 * tables in the specified database, only the specified
 * tables in the specified database or only the given
 * SQL query.
 * ========================================================
 */

/* ========================================================
 * Configuration (read from command-line)
 * ========================================================
 */
$DBHOST       = '';
$DBUSER       = '';
$DBPASS       = '';
$DBNAME       = '';
$TABLES       = array();
$DBQUERY      = '';
$QUERYNAME    = '';
$CONVERT_NAME = FALSE;

/**
 * Prints the usage for this script
 */
function usage()
{
    echo "
MySQLToYaml [Jonathan Franzone; http://www.franzone.com]

  Script to generate YAML (http://www.yaml.org/) from
  a MySQL database. Will generate YAML for either all
  tables in the specified database, only the specified
  tables in the specified database or only the given
  SQL query.

Usage : dumpyaml.php [options] -d <database>
        dumpyaml.php [options] -d <database> -t table1,table2
        dumpyaml.php [options] -d <database> -q \"SELECT * FROM `mytable`\" -qn custom_name

  -h <host>       MySQL database host
  -u <user>       MySQL username
  -p <pass>       MySQL password
  -d <database>   Name of the MySQL database to dump
  -t <table(s)>   Comma delimited list of tables to dump from database
  -q \"<query>\"    SQL query to dump
  -qn <query name>  Name of the \"table\" when using the -q option
  -n              Convert table names to class names

";
}

/**
 * Reads/parses the command-line arguments
 */
function read_args()
{

    global $argc, $argv, $DBHOST, $DBUSER, $DBPASS, $DBNAME, $TABLES, $DBQUERY, $QUERYNAME, $CONVERT_NAMES;

    while (!empty($argv)) {

        $arg = array_shift($argv);
        switch ($arg) {
            case '-h':
                $DBHOST = array_shift($argv);
                break;
            case '-u':
                $DBUSER = array_shift($argv);
                break;
            case '-p':
                $DBPASS = array_shift($argv);
                break;
            case '-d':
                $DBNAME = array_shift($argv);
                break;
            case '-t':
                $TABLES = explode(",", array_shift($argv));
                break;
            case '-q':
                $DBQUERY = array_shift($argv);
                break;
            case '-qn':
                $QUERYNAME = array_shift($argv);
                break;
            case '-n':
                $CONVERT_NAMES = TRUE;
                break;
        }
    }

    // Validate Command-Line Arguments
    $retVal = TRUE;
    if (empty($DBHOST)) {
        echo "-h <host> is required\n";
        $retVal = FALSE;
    }
    if (empty($DBUSER)) {
        echo "-u <user> is required\n";
        $retVal = FALSE;
    }
    if (empty($DBPASS)) {
        echo "-p <pass> is required\n";
        $retVal = FALSE;
    }
    if (empty($DBNAME)) {
        echo "-d <database> is required\n";
        $retVal = FALSE;
    }
    if (!empty($TABLES) && !empty($DBQUERY)) {
        echo "-t <table(s)> and -q \"<query>\" are mutually exclusive options\n";
        $retVal = FALSE;
    }
    if (!empty($DBQUERY) && empty($QUERYNAME)) {
        echo "-qn <query name> is required when using -q \"<query>\"\n";
        $retVal = FALSE;
    }
    return $retVal;
}

/**
 * Takes a table name with underscores and converts it to something
 * like a class name (CAML case and remove the underscores).
 */
function convert_name_to_class($string)
{

    // Return Value
    $retVal = '';

    $tokens = explode('_', $string);
    foreach ($tokens as $token) {
        $retVal .= strtoupper(substr($token, 0, 1));
        if (strlen($token) > 1) {
            $retVal .= substr($token, 1);
        }
    }

    // Return Value
    return $retVal;
}

/**
 * Takes a SQL query and dumps YAML
 */
function sql_to_yaml($link, $sql, $table)
{

    global $CONVERT_NAMES;

    // Run the query
    $result = mysql_query($sql, $link);

    if ($result) {

        // Output the table name
        echo ($CONVERT_NAMES === TRUE) ? convert_name_to_class($table) . ":\n" : "{$table}:\n";

        // Loop over the result set
        while ($row = mysql_fetch_assoc($result)) {

            // Output the row/collection indicator
            echo "  -\n";

            // Loop over the columns output names and values
            foreach ($row as $key => $value) {

                // Do have any newlines or line feeds?
                $literalFlag = (strpos($value, "\r") !== FALSE || strpos($value, "\n") !== FALSE) ? "| " : "";

                // Output the key/value pair
                echo "    {$key}: {$literalFlag}{$value}\n";
            }
        }
    }

    // Free the result resources
    mysql_free_result($result);
}

/**
 * Retrieves the database tables from the database and puts them into the $TABLES array
 */
function get_database_tables($link)
{

    global $TABLES;

    // Run the query
    $result = mysql_query('SHOW TABLES', $link);

    if ($result) {

        while ($row = mysql_fetch_row($result)) {
            $TABLES[] = $row[0];
        }
    }

    // Free the result resources
    mysql_free_result($result);
}

/**
 * Main program loop
 */
if (read_args()) {

    // Open database connection
    $link = mysql_connect($DBHOST, $DBUSER, $DBPASS);
    if (!$link) {
        die("Could not connect to DB [{$DBUSER}:{$DBPASS}@{$DBHOST}] :: " . mysql_error()) . "\n";
    }

    // Select the database
    if (mysql_select_db($DBNAME)) {

        // Output header
        echo "---\n";

        // Query Mode
        if (!empty($DBQUERY)) {
            sql_to_yaml($link, $DBQUERY, $QUERYNAME);
        } else {

            // If the user has not specified tables then query for them
            if (empty($TABLES)) {
                get_database_tables($link);
            }

            // Loop over tables and output YAML
            foreach ($TABLES as $tbl) {
                sql_to_yaml($link, "SELECT * FROM {$tbl}", $tbl);
                echo "\n";
            }
        }

        // Output footer
        echo "...\n";
    } else {
        echo "Could not select database [{$DBNAME}] :: " . mysql_error() . "\n";
    }

    // Close Database
    mysql_close($link);
} else {
    usage();
}
