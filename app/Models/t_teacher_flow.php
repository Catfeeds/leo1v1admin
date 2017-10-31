<?php
namespace App\Models;
use \App\Enums as E;
class t_teacher_flow extends \App\Models\Zgen\z_t_teacher_flow
{
	public function __construct()
	{
		parent::__construct();
	}

    public function get_trial_lecture_pass_time($phone) {
        $where_arr = [['phone=%u',$phone,0]];
        $sql = $this->gen_sql_new("select trial_lecture_pass_time from %s where %s",
                                  self::DB_TABLE_NAME,
                                  $where_arr
        );
        return $this->main_get_value($sql);
    }

    public function get_id_for_phone($phone) {
        $where_arr = [
            ["phone='%s'",$phone,0]
        ];
        $sql = $this->gen_sql_new("select teacherid from %s where %s"
                                  ,self::DB_TABLE_NAME
                                  ,$where_arr
        );
        return $this->main_get_value($sql);
    }

    public function get_all_trial_time() {
        $sql = $this->gen_sql_new("select teacherid,trial_lecture_pass_time from %s",self::DB_TABLE_NAME);
        return $this->main_get_list($sql, function( $item) {
            return $item['teacherid'];
        });
    }

    public function get_all_list($where=[]) {
        $sql = $this->gen_sql_new("select teacherid,phone,trial_lecture_pass_time,simul_test_lesson_pass_time,train_through_new_time from %s where %s",self::DB_TABLE_NAME,$where);
        return $this->main_get_list($sql, function( $item) {
            return $item['teacherid'];
        });
    }

    public function get_tea_list($start_time, $end_time) {
        $where_arr = [
            ['tf.trial_lecture_pass_time>%u', $start_time, 0],
            ['tf.trial_lecture_pass_time<%u', $end_time, 0],
            // 'tf.subject>0'
        ];
        $sql = $this->gen_sql_new("select tf.subject,tf.grade,tf.teacherid,tf.trial_lecture_pass_time,tf.simul_test_lesson_pass_time,tf.train_through_new_time,t.identity from %s tf left join %s t on tf.teacherid=t.teacherid where %s",
                                  self::DB_TABLE_NAME,
                                  t_teacher_info::DB_TABLE_NAME,
                                  $where_arr
        );
        return $this->main_get_list($sql);
    }

    // check phone
    public function get_data() {
        $sql = $this->gen_sql_new("select teacherid,trial_lecture_pass_time,phone from %s where phone   in ('13333864955','13100726285','15639429667','17783122188','18040529908','17858323612','13696254701','18516100395','15690203925','15501711630','18827049060','18701874127','13976187731','15123230386','15937042790','17822010709','17171279003','18910531527','18794224308','13292131031','15972999854','15002939210','15864184290','18086059521','18093359176','15201392847','13409956501','15527787323','13277993489','18271699008','18883319750','17685488213','18051979692','18685269667','13809003877','15527891556','17816870039','13536538044','18236073025','17862977885','18359550132','15808441561','15071334132','13122790327','15997664188','13514650695','13545863909','18332797592','15057559809','18707124382','13567127842','15907118062','15172655049','13458663765','15926320616','13391066160','18844631956','18613756835','18896541712','18835118973','15620867799','15966502932','17764576564','13735572526','13842206047','13317187480','15720626256','15293755589','13797301790','15902745070','15046177138','13323203623','13518134043','13482063987','15131724018','13103187747','17816869572','18398430580','13733560779','17863526325','13258994157','13271670400','15671600028','15229313515','13125169958','13961010603','13085281640','18559787006','13784975011','18312917258','13851229439','18164007830','13555814388','13811055887','18955848593','15527085794','13832769021','18271414032','18981033883','17853145383','18717858692','15009259427','18811786420','15527535571','17671885210','18307201779','13770621620','13224647797','15961709799','15220466793','18627116125','13638691517','15093154302','15171486976','17865603765','13836839763','13849433565','13750063345','18640271950','15997336082','18086423741','18332717559','13663061002','13803942929','13697118650','17320507867','13964883354','13693883858','15522190906','15216686873','15521009957','18710725530','15029666252','15001828106','18677261554','18217505898','15663862993','13846367856','18716442833','13587862681','15234919851','15872351096','13833170471','13591245488','13920886511','18313953061','15889729193','13730058708','15947962035','15603072325','18322068560','18676467691','13550250979','18435102265','13102971925','13635577968','13856190998','18683125406','17810288788','18944650302','15102117281','15896798859','13676817706','18380461730','18841025548','13931035287','18543296186','18953188903','17853131418','13086279379','17554370980','13713921197','18556515178','18709198532','15828581959','13954836408','13020839573','15097760008','15161509550','15996239726','18207122013','15612078782','17720497629','13473356473','13051511068','13933025128','13930089176','18321402492','18435149698','15389256838','17706732632','15053000068','13330461339','18380587677','13524496009','15370879872','18724507268','15091375752','18684770193','13163536612','18336758912','15132913938','15732625383','15111322313','15733063398','13468623381','13135665409','15221871518','18437978760','18032782591','18030059552','18817836089','13223051280','15236273822','18912386312','13884655203','13972369843','15058119051','15670489109','15189807662','15011218736','18233697129','18091314225','18653656295','18300738654','18783175471','13315028619','13626219595','18616284529','13915187552','18811508220','15959319701','13913025976','15141237568','13575788798','13931368056','15510008807','15893215393','13886054552','15633579758','18306425524','15757118195','13817633692','17862700920','13720405436','17712138265','18538531256','18512823612','18753708078','13939034639','18817607110','18896725172','13523393371','18893715365','15727561610','13882149102','18632857665','15222708891','15774033912','13588218414','13940340881','18013885783','15641144830','13367192007','13479810627','15700704753','15640075618','13953477271','15810834368','13574236923','15801275712','13983443091','18258265924','13259794897','13920922185','18245234423','13584078044','18523843810','17809299700','13588004621','13258957910','15223359133','15069237536','18856526330','18640371207','18701831819','18673683283','18202510745','18118582980','13817265496','13782957221','13915012292','13735522729','15730154037','15551180923','13500781560','17512541226','18758263439','18792612976','15140192087','18201950590','15324306168','15986343843','15503664810','13821480681','15183095663','13718813928','18640101047','18315130915','18607999803','13131843605','18232010322','13939473328','18270896280','15533171706','18428305977','15543692347','18985113624','15129270568','15386875862','15002167977','18309845052','13958240666','13776694165','18811371801','18914615277','15957159246','15937779044','13170913519','18750711038','13576835737','17325235186','13177064148','18298372738','18716452434','15869154382','13679219753','15936640013','13416345165','15603283615','18078772638','13651703248','13033062356','13193292703','18831823759','18814723642','18814122171','13310986485','15018331024','15885635869','18800127599','13383233558','18844737268','17816874568','18305217322','15734070117','15220061415','18861377867','13792357365','17858901235','17092770197','13817515662','18875857989','18621814728','15638416985','18256900763','18638139062','18720707569','15290840890','13834427607','15870660755','18833018513','18229478890','17625925297','18979500958','13125198637','13772043452','13237172708','18003897983','13054189724','18254860823','15058753131','15261897256','13061082526','15110521079','15832915916','15835629361','18755469823','15107968670','17319317626','15983304576','18515420836','15168343523','18328088913','18330688417','13930450235','17792519789','13179014480','18801012209','18972728872','13503968722','13221055437','15939610486','13979816057','13671537580','13335351801','15869422112','15904092116','13918157193','15774609550','15720603508','18352572616','13297555031','15638259358','15548052868','15528803253','18363867881','13822431195','15045537574','17368959509','17740659881','13767015567','13405843035','13696175742','15321006602','18845121802','18014303686','18829285126','18954550212','13966060906','18981808323','13635846382','15065441200','15262051579','18778061419','15279798601','18629258184','13387899930','13991154893','13527299063','15295668069','13593166089','13939772640','13651542251','13665478948','15295049068','13367293303','17736123559','13556369496','13724022459','13734610537','13510669475','13582239082','13582684963','13132521050','15263121105','18865519582','13261735856','18080822278','13840367149','15156514647','18892647010','17354006972','18080350794','15732157290','18340814853','17381758378'
        ",
                                  self::DB_TABLE_NAME
        );
        return $this->main_get_list($sql, function( $item) {
            return $item['teacherid'];
        });
    }

}


