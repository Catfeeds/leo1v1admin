$(function(){
    function load_data(){
        $.reload_self_page ( {
            start_time:	$('#id_start_time').val(),
            end_time:	$('#id_end_time').val()
        });
    }

    $('#id_add').on('click', function() {
        var name = $('<input />');
        var arr = [
            ['权限名',name],
        ];
        $.show_key_value_table('添加权限组',arr,{
            label: '确认',
            cssClass: 'btn-warning',
            action: function(dialog) {
                $.do_ajax('/company_wx/add_role_data',{
                    'name':name.val()
                });
            }
        });
    });

    // 成员授权
    $('.opt-user').on('click', function(){
        var id = $(this).parent().parent().find('.role_id').text();
        //window.open('/company_wx/show_department_users?id='+id);
        location.href='/company_wx/show_department_users?id='+id;
    });

    // 部门授权
    $('.opt-department').on('click', function() {
        var id = $(this).parent().parent().find('.role_id').text();
        location.href='/company_wx/show_department_users?type=1&id='+id;
    });

    // 职位授权
    $('.opt-position').on('click', function() {
        var id = $(this).parent().parent().find('.role_id').text();
        location.href='/company_wx/show_department_users?type=2&id='+id;
    });

    // 修改
    $(".opt-edit").on('click', function(){
        var id = $(this).parent().parent().find('.role_id').text();
        var name_val = $(this).parent().parent().find('.role_name').text();
        var name = $('<input />');
        var arr = [
            ['权限名',name],
        ];
        name.val(name_val);
        $.show_key_value_table('修改权限组名',arr,{
            label: '确认',
            cssClass: 'btn-warning',
            action: function(dialog) {
                $.do_ajax('/company_wx/update_role_data', {
                    'id': id,
                    'name': name.val()
                });
            }
        });
    });

    $('.opt-change').set_input_change_event(load_data);
});
