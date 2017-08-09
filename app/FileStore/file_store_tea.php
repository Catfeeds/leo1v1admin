<?php
namespace  App\FileStore;
class file_store_tea extends file_store_base {
    public $project_base_dir="tea";

    public function get_project_dir() {
        if (\App\Helper\Utils::check_env_is_release() ) {
            return $this->project_base_dir;
        }else{
            return $this->project_base_dir."_dev";
        }
    }
    public function get_dir($teacher_id, $dir ="")  {
        if ( $dir ) {
            return $this->get_project_dir() . "/" . $teacher_id ."/". trim($dir, "/") ."/" ;
        }else{
            return $this->get_project_dir() . "/" . $teacher_id . "/" ;
        }
    }

    public function get_file_path($teacher_id, $path )  {
        return $this->get_project_dir() . "/" . $teacher_id ."/". trim($path, "/")  ;
    }


    public function list_dir($teacher_id,$dir ) {
        $obj_dir = $this->get_dir($teacher_id, $dir);
        return parent::list_dir_ex($obj_dir );
    }

    public function del_file($teacher_id, $file_path ){
        $obj_dir = $this->get_dir($teacher_id);
        $obj_dir.=trim($file_path, "/");
        return $this->del_file_ex($file);

    }

    public function rename_file( $teacher_id, $old_file_path,$new_file_path ) {
        /*
        Array
            (
                [dirname] => /adfa/ss
                [basename] => afff.txt
                [extension] => txt
                [filename] => afff
            )
        */
        $old_file_path= $this-> get_file_path($teacher_id, $old_file_path);

        $old_path = pathinfo($old_file_path );
        $new_path=pathinfo($new_file_name);
        $new_file_name= $old_file_path["dirname"]. "/" . $new_file_name["filename"] . $old_file_path["extension"] ;

        $this->move_file($old_file_path,$new_file_name);

    }

    public function  download_url( $teacher_id, $file_path ) {
        $file_path= $this-> get_file_path($teacher_id, $file_path);
        return $this->download_url_ex($file_path);
    }
    public function add_dir($teacher_id, $dir ) {
        $dir= $this-> get_dir($teacher_id, $dir);
        return $this->add_file_ex($dir, "");
    }
}