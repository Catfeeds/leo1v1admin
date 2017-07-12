<?php
use App\Models\NewDB;

class TestCase extends Illuminate\Foundation\Testing\TestCase
{

    function __construct() {
        $this->afterApplicationCreated (
            function(){
                $this->beginTransaction();
            }
        );
    }

    /**
     * The base URL to use while testing the application.
     *
     * @var string
     */
    protected $baseUrl = 'http://dev.admin.yb1v1.com';

    public function withGloalSession($arr) {
        global $_SESSION;
        foreach ($arr as $k=>$v) {
            $_SESSION[$k]=$v;
        }
    }
    public function beginTransaction() {
        $arr=["" ];
        foreach($arr as $item ) {
            $db=NewDB::get($item);
            $db->resetTransation();
            $db->beginTransaction();
        }
    }

    /**
     * Creates the application.
     *
     * @return \Illuminate\Foundation\Application
     */
    public function createApplication()
    {
        $app = require __DIR__.'/../bootstrap/app.php';

        $app->make(Illuminate\Contracts\Console\Kernel::class)->bootstrap();

        return $app;
    }

    public function getCountFromDB($table,$where_arr) {

        $sql=$table->gen_sql("select count(*) from %s where  %s " ,
                             $table::DB_TABLE_NAME, [$table->where_str_gen($where_arr)]);

        return $table->main_get_value($sql);
    }

    public function seeInNewDB( $table, $where_arr ){

        $count=$this->getCountFromDB( $table,$where_arr);
        $this->assertGreaterThan(0, $count, sprintf(
            'Unable to find row in database table [%s] that matched attributes %s.', $table::DB_TABLE_NAME, json_encode($where_arr)
        ));
    }

    public function noSeeInNewDB( $table, $where_arr ){
        $count=$this->getCountFromDB( $table,$where_arr);
        $this->assertEquals(0, $count, sprintf(
            ' find row in database table [%s] that matched attributes %s.', $table::DB_TABLE_NAME, json_encode($where_arr)
        ));
    }

    public function SeeInNewDBCount( $table, $where_arr, $need_count ){
        $count=$this->getCountFromDB( $table,$where_arr);
        $this->assertEquals($need_count, $count, sprintf(
            ' [need_count=%d]  != [db_count=%d] database table [%s] that matched attributes %s.',
            $need_count,$count,
            $table::DB_TABLE_NAME, json_encode($where_arr)
        ));
    }
    public function  assertGlobalSessionHas($key,$value)    {
        global $_SESSION;
        $this->assertEquals($value, $_SESSION[$key]);
    }
    public function seeJsonRet($ret )  {
        $this->seeJson(["ret"=> $ret ]);

    }
    public function seeJsonSuccess()  {
        $this->seeJsonRet(0);
    }



}
