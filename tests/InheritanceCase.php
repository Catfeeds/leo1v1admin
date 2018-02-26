<?php
namespace Tests;
use Tests\TestCase;

class InheritanceCase extends TestCase{

    public function noSeeInNewDB( $table, $where_arr ){
        $count=$this->getCountFromDB( $table,$where_arr);
        echo '实际值:'.$count."\n";
        $this->assertEquals(0, $count, sprintf(
            ' find row in database table [%s] that matched attributes %s.', $table::DB_TABLE_NAME, json_encode($where_arr)
        ));
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



}