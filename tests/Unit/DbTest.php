<?php

namespace Tests\Unit;

use Tests\TestCase;
use App\Models\t_login_log;

class DbTest extends TestCase
{
    /**
     * @var \App\Models\NewDB
     */
    public $db;
    public function test_transaction (){
        echo "test_transaction\n";
        $test_admin="__test_admin";
        $table=new t_login_log();
        $where_arr= [
            ["account='%s'",  $test_admin]
        ];

        $this->noSeeInNewDB($table,$where_arr);
        $table->add($test_admin,"",1);

        $this->SeeInNewDB($table, $where_arr);

        $this->db=$table->get_db( );
        $this->assertEquals($this->db->get_transactions() ,1  );

        $this->exec_transaction();


        $this->SeeInNewDBCount($table, $where_arr,2);



    }
    public function test_rollback() {

        echo "test_rollback\n";
        //去掉测试框架的 start_transaction

        $test_admin="__test_admin";
        $table=new t_login_log();
        $table->readony_on_select=false;
        $where_arr= [
            ["account='%s'",  $test_admin]
        ];
        $this->db=$table->get_db( );

        $this->assertEquals($this->db->get_transactions() ,1,"START "  );
        $this->db->rollback();
        $this->assertEquals($this->db->get_transactions() ,0  );

        $this->db->do_transaction(
            function ( ){
                $table=new t_login_log();
                $test_admin="__test_admin";
                $where_arr= [
                    ["account='%s'",  $test_admin]
                ];

                $ret= $this->exec_transaction();
                $this->SeeInNewDBCount($table, $where_arr,1);
                $ret= $this->exec_transaction_back();
                $this->SeeInNewDBCount($table, $where_arr,1);
                $table->add($test_admin,"",1);
                $this->SeeInNewDBCount($table, $where_arr,2);
                return false;
            });

        $this->SeeInNewDBCount($table, $where_arr,0);

    }

    public function exec_transaction(){
        return $this->db->do_transaction(
            function ( ){
                $table=new t_login_log();
                $test_admin="__test_admin";
                return $table->add($test_admin,"",1);
            });

    }

    public function exec_transaction_back(){
        return $this->db->do_transaction(
            function ( ){
                $table=new t_login_log();
                $test_admin="__test_admin";
                 $table->add($test_admin,"",1);
                return   false;
            });

    }

}
