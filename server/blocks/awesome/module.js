/*
 * To change this license header, choose License Headers in Project Properties.
 * To change this template file, choose Tools | Templates
 * and open the template in the editor.
 */


if (!M.block_awesome) {
    M.block_awesome = {};
}

M.block_awesome.init_block = function(Y, block_instance, config){

    var block_editing = $('#inst'+block_instance.id+'.block_with_controls');

    function duplicate_block() {
        var block_instanceid = block_instance.id;
        $.ajax({
            method: "POST",
            url: M.cfg.wwwroot + "/blocks/awesome/ajax/duplicate_block.php",
            dataType: 'JSON',
            data: {block_instanceid: block_instanceid},
        }).done( function(){
            location.reload();
        });
    }

    block_editing.on('click', '.duplicate-awesome-block', function(e) {
        var $this = $(this);
        $this.html('cloning <i class="fa fa-spinner fa-spin"></i>');
        duplicate_block();
    });
};

M.block_awesome.init_color_picker = function(Y, textid) {
    $('#'+textid).minicolors({
        theme: 'bootstrap',
        position: 'bottom left',
        defaultValue: ''
    });
};