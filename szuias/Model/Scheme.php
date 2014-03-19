<?php

/**
 * 数据库结构基础类
 * @author shonenada
 *
 */

namespace Model;


class Scheme {

    static public function listDatabases () {
        return SchemeManager::sm()->listDatabases();
    }

    static public function listTables () {
        $tables = SchemeManager::sm()->listTables();
        $output = array();
        foreach ($tables as $table) {
            $output[] = new SchemeTable($table->getName());
        }
        return $output;
    }

    static public function nameOfTables() {
        $tables = SchemeManager::sm()->listTables();
        $names = array();
        foreach ($tables as $t) {
            $names[] = $t->getName();
        }
        return $names;
    }

    static public function dumpDatabase($file_path, $ts=array()) {
        if (empty($ts)){
            $tables = self::nameOfTables();
        }
        else {
            $tables = array_intersect($ts, self::nameOfTables());
        }
        $conn = SchemeManager::qb()->getConnection();
        mysql_connect($conn->getHost(), $conn->getUsername(), $conn->getPassword()) or die("数据库连接出错！");
        mysql_select_db($conn->getDatabase()) or die("数据库连接出错！");
        mysql_query("SET NAMES 'UTF8'");
        $dumpStr = "";
        foreach ($tables as $table){
            $dumpStr .= "DROP TABLE IF EXISTS `" . $table . "`;\n";
            $createtable = mysql_query("SHOW CREATE TABLE $table");
            $create = mysql_fetch_row($createtable);
            $create[1] = str_replace("\n", "", $create[1]);
            $create[1] = str_replace("\t", "", $create[1]);       
            $dumpStr  .= $create[1] . ";\n";
            
            $rows = mysql_query("SELECT * FROM `" . $table . "`");
            $numfields = mysql_num_fields($rows);
            $numrows = mysql_num_rows($rows);
            while ($row = mysql_fetch_row($rows)){
                $comma = "";
                $dumpStr  .= "INSERT INTO `" . $table . "` VALUES(";
                for($i = 0; $i < $numfields; $i++)
                {
                    if (get_magic_quotes_gpc()) {
                        $row[$i] = stripslashes($row[$i]);
                    }
                    $data = mysql_real_escape_string($row[$i]);
                    if (strlen($data) == 0 || $data == '')
                        $data = 'null';
                    else
                        $data = "'{$data}'";
                    $dumpStr  .= $comma . $data;
                    $comma = ",";
                }
                $dumpStr  .= ");\n";
            }
        }
        return self::writeSqlFile($file_path, $dumpStr);
    }

    private static function writeSqlFile($path, $sqlStr) {
        $basePath = realpath(dirname($_SERVER['SCRIPT_FILENAME'])) . '/';
        $savePath = $basePath . $path;
        $random = rand(10000, 99999);
        $fileName = date("Ymd", time()) . '_' . $random . '_all.sql';
        $saveTo = $savePath . $fileName;
        if (!$fp=fopen($saveTo, "w+"))
            return false;
        if (!fwrite($fp, $sqlStr))
            return false;
        if (!fclose($fp))
            return false;
        return true;
    }

}

class SchemeTable {

    private $table_name;

    public function __construct($table_name) {
        $this->table_name = $table_name;
    }

    public function getName() {
        return $this->table_name;
    }

    public function getRowNums() {
        $result = SchemeManager::qb()->select('COUNT(*) as count')->from(sprintf('%s', $this->table_name), 't')->execute()->fetch();
        if ($result && isset($result['count'])) {
            return $result['count'];
        }
        else {
            return 0;
        }
    }

}

class SchemeManager {

    static public $schemeManager = null;

    static public $queryBuilder = null;

    static public function init() {
        if (!file_exists(APPROOT . 'config/database.conf.php'))
            exit('Database config file not found!');
        
        $db_params = require(APPROOT . 'config/database.conf.php');
        $config = new \Doctrine\DBAL\Configuration();

        $conn = \Doctrine\DBAL\DriverManager::getConnection($db_params, $config);

        self::$schemeManager = $conn->getSchemaManager();
        self::$queryBuilder = $conn->createQueryBuilder();
    }

    static public function sm() {
        return SchemeManager::$schemeManager;
    }

    static public function qb() {
        return SchemeManager::$queryBuilder;
    }

} SchemeManager::init();