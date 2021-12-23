M.repository_adapt = M.repository_adapt || {

        init: function (Y, selector) {

            var $button = $(selector);
            var ajaxfile = M.cfg.wwwroot+'/repository/kineoadapt/testapiconn_ajax.php';
            var connectionsuccessfulstr = M.util.get_string('connectionsuccessful', 'repository_kineoadapt')
            var connectionnotsuccessfulstr = M.util.get_string('connectionnotsuccessful', 'repository_kineoadapt')
            var connectionnotsuccessful_norepostr = M.util.get_string('connectionnotsuccessful_norepo', 'repository_kineoadapt')

            $button.click(function(){
                $.ajax({url: ajaxfile, success: function(result){
                    var resultob = JSON.parse(result);
                    if (resultob.result == 1) {
                        alert(connectionsuccessfulstr);
                    } else if (resultob.result == -1){
                        alert(connectionnotsuccessful_norepostr);
                    } else {
                        alert(connectionnotsuccessfulstr);
                    }
                }});
                return false;
            });

        }

    };