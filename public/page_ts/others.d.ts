interface C_Enum_map {
    append_option_list(enum_type :string , $item: JQuery, no_show_all_flag?:boolean, need_list?: any ):void;
    append_option_list_by_not_id(enum_type :string , $item: JQuery, no_show_all_flag?:boolean, need_list?: any ):void;
    td_show_desc(enum_type :string , $item: JQuery ):void;
    td_show_descs(enum_type :string , $item: JQuery ):void;
    get_desc(  enum_type :string ,value: any ):string;

}

interface C_qiniu{
    uploader(obj:any):any;
}

declare var  Enum_map: C_Enum_map;
declare var  Qiniu: C_qiniu;
declare var  plupload:any;
declare var  g_power_list: any;
declare var  g_enum_map : any;
declare var  g_sid: any;
declare var  g_data_ex_list:any;
declare var  audiojs : any;
interface Window {
    navigate (str:string);
    download_show();
    SVG( obj:any);
}

declare function load_data():any ;

declare module "enum_map" {
    export = Enum_map;
}

declare module "g_power_list" {
    export = g_power_list;
}

declare module "g_enum_map" {
    export = g_enum_map;
}

declare module "window" {
    export = Window;
}

