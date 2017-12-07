<?php
/**
 * Created by PhpStorm.
 * User: Kushagra
 * Date: 12/3/2017
 * Time: 5:10 PM
 */
class db_mysql
{
    private static $instance;
    protected $link;
    protected $recent_link = null;
    protected $sql = '';
    public $query_count = 0;
    protected $error = '';
    protected $errno = '';
    protected $is_locked = false;
    public $show_errors = false;
    protected static $db_host;
    protected static $db_user;
    protected static $db_pass;
    protected static $db_name;

    private function __construct()
    {
        self::set_params();

        $this->link = @mysql_connect(self::$db_host, self::$db_user, self::$db_pass);

        if (is_resource($this->link) AND @mysql_select_db(self::$db_name, $this->link))
        {
            $this->recent_link =& $this->link;
            return $this->link;
        }
        else
        {
            // If we couldn't connect or select the db...
            $this->raise_error('db_mysql::__construct() - Could not select and/or connect to database: ' . self::$db_name);
        }
    }

    /**
     * Creates an instance of the class.
     */
    public static function getInstance()
    {
        if (!self::$instance)
        {
            self::$instance = new self();
        }
        return self::$instance;
    }

    /**
     * Sets connection/database parameters.
     */
    protected static function set_params()
    {
        global $dbconfig;

        self::$db_host = $dbconfig['host'];
        self::$db_user = $dbconfig['user'];
        self::$db_pass = $dbconfig['pass'];
        self::$db_name = $dbconfig['name'];
    }

    /**
     * Executes a sql query. If optional $only_first is set to true, it will
     * return the first row of the result as an array.
     */
    public function sql_query($sql, $only_first = false)
    {
        $this->recent_link =& $this->link;
        $this->sql =& $sql;
        $result = @mysql_query($sql, $this->link);

        $this->query_count++;

        if ($only_first)
        {
            $return = $this->fetch_array($result);
            $this->free_result($result);
            return $return;
        }
        return $result;
    }

    /**
     * Fetches a row from a query result and returns the values from that row as an array.
     */
    public function sql_fetchrow($result)
    {
        return @mysql_fetch_assoc($result);
    }

    /**
     * Will fetch all records from the database, and will optionally return the
     * value of a single field from all records.
     */
    public function fetch_all($sql, $field = '')
    {
        $return = array();

        if (($result = $this->query($sql)))
        {
            while ($row = $this->fetch_array($result))
            {
                $return[] = ($field) ? $row[$field] : $row;
            }
            $this->free_result($result);
        }
        return $return;
    }

    /**
     * Returns the number of rows in a result set.
     */
    public function num_rows($result)
    {
        return @mysql_num_rows($result);
    }

    /**
     * Retuns the number of rows affected by the most recent query
     */
    public function affected_rows()
    {
        return @mysql_affected_rows($this->recent_link);
    }

    /**
     * Returns the number of queries executed.
     */
    public function num_queries()
    {
        return $this->query_count;
    }

    /**
     * Lock database tables
     */
    public function lock($tables)
    {
        if (is_array($tables) AND count($tables))
        {
            $sql = '';

            foreach ($tables AS $name => $type)
            {
                $sql .= (!empty($sql) ? ', ' : '') . "$name $type";
            }

            $this->query("LOCK TABLES $sql");
            $this->is_locked = true;
        }
    }

    /**
     * Unlock tables
     */
    public function unlock()
    {
        if ($this->is_locked)
        {
            $this->query("UNLOCK TABLES");
            $this->is_locked = false;
        }
    }

    /**
     * Returns the ID of the most recently inserted item in an auto_increment field
     *
     * @return  integer
     */
    public function insert_id()
    {
        return @mysql_insert_id($this->link);
    }

    /**
     * Escapes a value to make it safe for using in queries.
     * @return string
     */
    public function prepare($value, $do_like = false)
    {
        $value = stripslashes($value);

        if ($do_like)
        {
            $value = str_replace(array('%', '_'), array('\%', '\_'), $value);
        }
        return mysql_real_escape_string($value, $this->link);
    }

    /**
     * Frees memory associated with a query result.
     */
    public function sql_freeresult($result)
    {
        return @mysql_free_result($result);
    }

    /**
     * Turns database error reporting on
     */
    public function show_errors()
    {
        $this->show_errors = true;
    }

    /**
     * Turns database error reporting off
     */
    public function hide_errors()
    {
        $this->show_errors = false;
    }

    /**
     * Closes our connection to MySQL.
     */
    public function close()
    {
        $this->sql = '';
        return @mysql_close($this->link);
    }

    /**
     * Returns the MySQL error message.
     */
    public function error()
    {
        $this->error = (is_null($this->recent_link)) ? '' : mysql_error($this->recent_link);
        return $this->error;
    }

    public function sql_escape($msg)
    {
        if (!$this->recent_link)
        {
            return @mysql_real_escape_string($msg);
        }

        return @mysql_real_escape_string($msg, $this->recent_link);
    }


    /**
     * Returns the MySQL error number.
     */

    function errno()
    {
        $this->errno = (is_null($this->recent_link)) ? 0 : mysql_errno($this->recent_link);
        return $this->errno;
    }

    /**
     * Gets the url/path of where we are when a MySQL error occurs.
     */
    protected function get_error_path()
    {
        if ($_SERVER['REQUEST_URI'])
        {
            $errorpath = $_SERVER['REQUEST_URI'];
        }
        else
        {
            if ($_SERVER['PATH_INFO'])
            {
                $errorpath = $_SERVER['PATH_INFO'];
            }
            else
            {
                $errorpath = $_SERVER['PHP_SELF'];
            }

            if ($_SERVER['QUERY_STRING'])
            {
                $errorpath .= '?' . $_SERVER['QUERY_STRING'];
            }
        }

        if (($pos = strpos($errorpath, '?')) !== false)
        {
            $errorpath = urldecode(substr($errorpath, 0, $pos)) . substr($errorpath, $pos);
        }
        else
        {
            $errorpath = urldecode($errorpath);
        }
        return $_SERVER['HTTP_HOST'] . $errorpath;
    }

    /**
     * If there is a database error, the script will be stopped and an error message displayed.
     *
     * @param  string  The error message. If empty, one will be built with $this->sql.
     * @return string
     */
    public function raise_error($error_message = '')
    {
        if ($this->recent_link)
        {
            $this->error = $this->error($this->recent_link);
            $this->errno = $this->errno($this->recent_link);
        }

        if ($error_message == '')
        {
            $this->sql = "Error in SQL query:\n\n" . rtrim($this->sql) . ';';
            $error_message =& $this->sql;
        }
        else
        {
            $error_message = $error_message . ($this->sql != '' ? "\n\nSQL:" . rtrim($this->sql) . ';' : '');
        }

        $message = htmlspecialchars("$error_message\n\nMySQL Error: {$this->error}\nError #: {$this->errno}\nFilename: " . $this->get_error_path());
        $message = '<code>' . nl2br($message) . '</code>';

        if (!$this->show_errors)
        {
            $message = "<!--\n\n$message\n\n-->";
        }
        die("There seems to have been a slight problem with our database, please try again later.<br /><br />\n$message");
    }
}

?>