<?php
namespace App\Models;

class NewDB {

    static  public  $new_dbs=[];

    //事务嵌套处理
    private $transactions;
    /**
       @var PDO
     */
    private $pdo = null;
    private $dsn;
    private $username;
    private $password;
    static function close_all_dbs() {
        $db_count = count(static::$new_dbs);
        /**  @var  $db_item  NewDB */
        foreach(static::$new_dbs as $db_item ) {
            $db_item->close();
        }
        static::$new_dbs=[];
    }


    function __construct ($dsn, $username, $password ) {
        $this->dsn=$dsn;
        $this->username=$username;
        $this->password= $password;
        $this->transactions=0;
        $this->reset_connect();
    }
    function  __destruct() {
        /*
        if(!\App\Helper\Utils::check_env_is_test()) {
            \App\Helper\Utils::logger(" PDO FREE ");
        }
        */
        $this->pdo=null;
    }
    function reset_connect()  {
        $this->pdo=null;
        $this->pdo= new \PDO($this->dsn,$this->username,$this->password);
    }


    public function get_transactions() {
        return $this->transactions;
    }

    /**
     * @return NewDB
     */
    static   function get($config) {

        $db_config=config("database");
        $config_field =$db_config["default"];

        if ($config) {
            $config_field.="_".$config;
        }

        if(isset( static::$new_dbs[$config_field])){
            return  static::$new_dbs[$config_field];
        }else{
            /*
            'driver'    => 'mysql',
            'host'      => env('DB_HOST', 'localhost'),
            'database'  => env('DB_DATABASE', 'db_weiyi_admin'),
            'username'  => env('DB_USERNAME', 'root'),
            'password'  => env('DB_PASSWORD', 'ta0mee'),
            'charset'   => 'latin1',
            'prefix'    => '',
            'strict'    => false,
            'engine'    => null,
            */
            $config=$db_config["connections"][$config_field];
            //dd( $config);


            $db_type  = $config["driver"];
            $db_host  = $config["host"];
            $dbname   = $config["database"];
            $username = $config["username"];
            $password = $config["password"];
            $charset  = $config["charset"];
            $dsn      = "$db_type:host=$db_host;dbname=$dbname;charset=$charset";
            //$pdo_db= new \PDO($dsn,$username,$password);


            static::$new_dbs[$config_field] =new NewDB( $dsn, $username, $password);
            return  static::$new_dbs[$config_field];
        }

    }

    /**
     * return true if reconnet
     */
    public function check_error_and_re_connect(){
        $errorInfo=$this->errorInfo();
        if ($errorInfo[1]=2006) {//server go away,reconnect
            //$this->db->get("");
            \App\Helper\Utils::logger("  reconnect db");
            $this->reset_connect();
            return true;
        }else{
            return false;
        }
    }

    public function quote ($string) {
       return $this->pdo->quote($string);
    }
    /**
     *　@return  int -  影响行数
     */
    public function exec( $sql) {
        \App\Helper\Utils::logger("EXEC SQL:$sql");
        $ret= $this->pdo->exec($sql);
        if ($ret===false) {
            if($this->check_error_and_re_connect()) {
                $ret= $this->pdo->exec($sql);
            }
        }
        return $ret;
    }

    public function query( $sql) {
        \App\Helper\Utils::logger("QUERY SQL:$sql");
        $ret= $this->pdo->query($sql);
        if ($ret===false) {
            if($this->check_error_and_re_connect()) {
                $ret= $this->pdo->query($sql);
            }
        }
        return $ret;
    }

    public function errorInfo( ) {
        return $this->pdo->errorInfo();
    }

    //for test
    public function resetTransation() {

        $this->transactions=0;
        try {
            @$this->pdo->rollBack();
        }catch ( \Exception $e ) {

        }
    }


    public function beginTransaction( ) {


        ++$this->transactions;

        if ($this->transactions == 1)
        {
            $ret=$this->pdo->beginTransaction();
            if ($ret===false) {
                if($this->check_error_and_re_connect()) {
                    $ret=$this->pdo->beginTransaction();
                }
            }

        }else{
            $this->exec('SAVEPOINT trans'.$this->transactions );

        }

    }

    function commit (){
        if ($this->transactions == 1) {
            $this->pdo->commit();
        }
        --$this->transactions;
    }

    function rollback(){
        if ($this->transactions == 1) {
            $this->transactions = 0;
            $this->pdo->rollBack();
        } else {
            $this->pdo->exec('ROLLBACK TO trans'.$this->transactions );
            --$this->transactions;
        }
    }
   public  function close() {
        $this->pdo=null;
    }

    function lastInsertId(){
        return  $this->pdo->lastInsertId();
    }

    function do_transaction( $func)  {
        $this->beginTransaction();
        $ret=$func($this);
        if (!$ret) {
            $this->rollback();
        }else{
            $this->commit();
        }
        return $ret;
    }
};