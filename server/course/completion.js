
M.core_completion = {};

M.core_completion.init = function(Y) {
    // Check the reload-forcing
    var changeDetector = Y.one('#completion_dynamic_change');
    if (changeDetector.get('value') > 0) {
        changeDetector.set('value', 0);
        window.location.reload();
        return;
    }

    var handle_success = function(id, o, args) {
        Y.one('#completion_dynamic_change').set('value', 1);

        if (o.responseText != 'OK') {
            alert('An error occurred when attempting to save your tick mark.\n\n('+o.responseText+'.)'); //TODO: localize
        } else {
            var completionicon = args.modulename.ancestor('form').ancestor().one('.completion-icon');
            completionicon.set('disabled', null);
        }

        args.ajax.remove();
    };

    var handle_failure = function(id, o, args) {
        alert('An error occurred when attempting to save your tick mark.\n\n('+o.responseText+'.)'); //TODO: localize
        args.ajax.remove();
    };

    var toggle = function(e) {
        e.preventDefault();

        var form = e.target;
        var cmid = 0;
        var completionstate = 0;
        var state = null;
        var image = null;
        var modulename = null;

        var inputs = Y.Node.getDOMNode(form).getElementsByTagName('input');
        for (var i=0; i<inputs.length; i++) {
            switch (inputs[i].name) {
                 case 'id':
                     cmid = inputs[i].value;
                     break;
                  case 'completionstate':
                     completionstate = inputs[i].value;
                     state = Y.one(inputs[i]);
                     break;
                  case 'modulename':
                     modulename = Y.one(inputs[i]);
                     break;
            }
            if (inputs[i].type == 'image') {
                image = Y.one(inputs[i]);
            }
        }

        // start spinning the ajax indicator
        var ajax = Y.Node.create('<div class="ajaxworking" />');
        form.append(ajax);

        var cfg = {
            method: "POST",
            data: 'id='+cmid+'&completionstate='+completionstate+'&fromajax=1&sesskey='+M.cfg.sesskey,
            on: {
                success: handle_success,
                failure: handle_failure
            },
            arguments: {state: state, image: image, ajax: ajax, modulename: modulename}
        };

        Y.use('io-base', function(Y) {
            Y.io(M.cfg.wwwroot+'/course/togglecompletion.php', cfg);
        });
    };

    // register submit handlers on manual tick completion forms
    Y.all('form.togglecompletion').each(function(form) {
        if (!form.hasClass('preventjs')) {
            Y.on('submit', toggle, form);
        }
    });

    Y.all('.activity .completion-icon').each(function (element) {
        var form = element.get('parentNode').one('form.togglecompletion');
        element.on('change', function(e) {
            var newVal = element.get('checked') ? 1 : 0;
            e.preventDefault();

            form.one('[name="completionstate"]').set('value', newVal);
            element.set('disabled', 'true');

            if (form.hasClass('preventjs')) {
                form.submit();
            } else {
                form.simulate('submit');
            }
        });
    });

    // hide the help if there are no completion toggles or icons
    var help = Y.one('#completionprogressid');
    if (help && !(Y.one('form.togglecompletion') || Y.one('.autocompletion'))) {
        help.setStyle('display', 'none');
    }
};


