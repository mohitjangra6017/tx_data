<?php

class __Mustache_57107b69adf1c0c13c696ca9195c118a extends Mustache_Template
{
    private $lambdaHelper;

    public function renderInternal(Mustache_Context $context, $indent = '')
    {
        $this->lambdaHelper = new Mustache_LambdaHelper($this->mustache, $context);
        $buffer = '';

        $buffer .= $indent . '<div class="progressbar_container"';
        // 'width' section
        $value = $context->find('width');
        $buffer .= $this->section0202116746aae15b52a70a29b874c0ef($context, $indent, $value);
        $buffer .= ' id="';
        $value = $this->resolveValue($context->find('id'), $context);
        $buffer .= call_user_func($this->mustache->getEscape(), $value);
        $buffer .= '">
';
        $buffer .= $indent . '    <h2 id="';
        $value = $this->resolveValue($context->find('id'), $context);
        $buffer .= call_user_func($this->mustache->getEscape(), $value);
        $buffer .= '_status"></h2>
';
        $buffer .= $indent . '    <div class="progress progress-striped active">
';
        $buffer .= $indent . '        <div id="';
        $value = $this->resolveValue($context->find('id'), $context);
        $buffer .= call_user_func($this->mustache->getEscape(), $value);
        $buffer .= '_bar" class="bar" role="progressbar" aria-valuemin="0" aria-valuemax="100" aria-valuenow="';
        $value = $this->resolveValue($context->find('progress'), $context);
        $buffer .= call_user_func($this->mustache->getEscape(), $value);
        $buffer .= '" aria-label="';
        $value = $this->resolveValue($context->find('label'), $context);
        $buffer .= call_user_func($this->mustache->getEscape(), $value);
        $buffer .= '" style="width:';
        $value = $this->resolveValue($context->find('progress'), $context);
        $buffer .= call_user_func($this->mustache->getEscape(), $value);
        $buffer .= '%">
';
        $buffer .= $indent . '            <span class="progressbar__text">';
        $value = $this->resolveValue($context->find('progresstext'), $context);
        $buffer .= call_user_func($this->mustache->getEscape(), $value);
        $buffer .= '</span>
';
        $buffer .= $indent . '        </div>
';
        $buffer .= $indent . '    </div>
';
        $buffer .= $indent . '    ';
        // 'popover' section
        $value = $context->find('popover');
        $buffer .= $this->section9296a21a49c2067e36f55db08ce4676d($context, $indent, $value);
        $buffer .= '
';
        $buffer .= $indent . '    <p id="';
        $value = $this->resolveValue($context->find('id'), $context);
        $buffer .= call_user_func($this->mustache->getEscape(), $value);
        $buffer .= '_estimate"></p>
';
        $buffer .= $indent . '</div>
';
        $buffer .= $indent . '
';
        $buffer .= $indent . '<script type="text/javascript">
';
        $buffer .= $indent . '(function() {
';
        $buffer .= $indent . '    var el = document.getElementById(\'';
        $value = $this->resolveValue($context->find('id'), $context);
        $buffer .= call_user_func($this->mustache->getEscape(), $value);
        $buffer .= '\'),
';
        $buffer .= $indent . '        progressBar = document.getElementById(\'';
        $value = $this->resolveValue($context->find('id'), $context);
        $buffer .= call_user_func($this->mustache->getEscape(), $value);
        $buffer .= '_bar\'),
';
        $buffer .= $indent . '        statusIndicator = document.getElementById(\'';
        $value = $this->resolveValue($context->find('id'), $context);
        $buffer .= call_user_func($this->mustache->getEscape(), $value);
        $buffer .= '_status\'),
';
        $buffer .= $indent . '        estimateIndicator = document.getElementById(\'';
        $value = $this->resolveValue($context->find('id'), $context);
        $buffer .= call_user_func($this->mustache->getEscape(), $value);
        $buffer .= '_estimate\');
';
        $buffer .= $indent . '
';
        $buffer .= $indent . '    /**
';
        $buffer .= $indent . '     * Updates visibility of heading and estimate if they\'re empty
';
        $buffer .= $indent . '     */
';
        $buffer .= $indent . '    function checkText() {
';
        $buffer .= $indent . '        if (statusIndicator.textContent === \'\') {
';
        $buffer .= $indent . '            statusIndicator.style.display = \'none\';
';
        $buffer .= $indent . '        } else {
';
        $buffer .= $indent . '            statusIndicator.style.display = \'\';
';
        $buffer .= $indent . '        }
';
        $buffer .= $indent . '
';
        $buffer .= $indent . '        if (estimateIndicator.textContent === \'\') {
';
        $buffer .= $indent . '            estimateIndicator.style.display = \'none\';
';
        $buffer .= $indent . '        } else {
';
        $buffer .= $indent . '            estimateIndicator.style.display = \'\';
';
        $buffer .= $indent . '        }
';
        $buffer .= $indent . '    }
';
        $buffer .= $indent . '
';
        $buffer .= $indent . '    /**
';
        $buffer .= $indent . '     * Updates display of progress bar text based on the size of the text
';
        $buffer .= $indent . '     */
';
        $buffer .= $indent . '    function checkInvert(percent) {
';
        $buffer .= $indent . '        var progressBarText = progressBar.getElementsByClassName(\'progressbar__text\')[0];
';
        $buffer .= $indent . '        if (percent < 50) {
';
        $buffer .= $indent . '            el.classList.add(\'progress-invert\');
';
        $buffer .= $indent . '        } else {
';
        $buffer .= $indent . '            el.classList.remove(\'progress-invert\');
';
        $buffer .= $indent . '        }
';
        $buffer .= $indent . '    }
';
        $buffer .= $indent . '
';
        $buffer .= $indent . '    checkText();
';
        $buffer .= $indent . '    checkInvert(parseInt(progressBar.attributes[\'aria-valuenow\'].value, 10));
';
        $buffer .= $indent . '
';
        $buffer .= $indent . '    el.addEventListener(\'update\', function(e) {
';
        $buffer .= $indent . '        var msg = e.detail.message,
';
        $buffer .= $indent . '            percent = e.detail.percent,
';
        $buffer .= $indent . '            estimate = e.detail.estimate;
';
        $buffer .= $indent . '
';
        $buffer .= $indent . '        statusIndicator.textContent = msg;
';
        $buffer .= $indent . '        if (typeof require === \'function\') {
';
        $buffer .= $indent . '            // requireJS has been loaded - get the appropriate string
';
        $buffer .= $indent . '            require([\'core/str\'], function (strlib) {
';
        $buffer .= $indent . '                strlib.get_string(\'xpercent\', \'core\', percent).done(function (str) {
';
        $buffer .= $indent . '                    progressBar.getElementsByClassName(\'progressbar__text\')[0].textContent = str;
';
        $buffer .= $indent . '                    checkInvert(percent);
';
        $buffer .= $indent . '                })
';
        $buffer .= $indent . '            })
';
        $buffer .= $indent . '        } else {
';
        $buffer .= $indent . '            // requireJS is not available
';
        $buffer .= $indent . '            progressBar.getElementsByClassName(\'progressbar__text\')[0].textContent = percent + \'%\';
';
        $buffer .= $indent . '            checkInvert(percent);
';
        $buffer .= $indent . '        }
';
        $buffer .= $indent . '
';
        $buffer .= $indent . '        if (percent === 100) {
';
        $buffer .= $indent . '            el.classList.add(\'progress-success\');
';
        $buffer .= $indent . '            estimateIndicator.textContent = \'\';
';
        $buffer .= $indent . '        } else {
';
        $buffer .= $indent . '            if (estimate) {
';
        $buffer .= $indent . '                estimateIndicator.textContent = estimate;
';
        $buffer .= $indent . '            } else {
';
        $buffer .= $indent . '                estimateIndicator.textContent = \'\';
';
        $buffer .= $indent . '            }
';
        $buffer .= $indent . '            el.classList.remove(\'progress-success\');
';
        $buffer .= $indent . '        }
';
        $buffer .= $indent . '
';
        $buffer .= $indent . '        checkText();
';
        $buffer .= $indent . '        progressBar.setAttribute(\'aria-valuenow\', percent);
';
        $buffer .= $indent . '        progressBar.style.width = percent + \'%\';
';
        $buffer .= $indent . '    });
';
        $buffer .= $indent . '})();
';
        $buffer .= $indent . '</script>
';

        return $buffer;
    }

    private function section0202116746aae15b52a70a29b874c0ef(Mustache_Context $context, $indent, $value)
    {
        $buffer = '';
    
        if (!is_string($value) && is_callable($value)) {
            $source = ' style="width: {{width}}px;"';
            $result = call_user_func($value, $source, $this->lambdaHelper);
            if (strpos($result, '{{') === false) {
                $buffer .= $result;
            } else {
                $buffer .= $this->mustache
                    ->loadLambda((string) $result)
                    ->renderInternal($context);
            }
        } elseif (!empty($value)) {
            $values = $this->isIterable($value) ? $value : array($value);
            foreach ($values as $value) {
                $context->push($value);
                
                $buffer .= ' style="width: ';
                $value = $this->resolveValue($context->find('width'), $context);
                $buffer .= call_user_func($this->mustache->getEscape(), $value);
                $buffer .= 'px;"';
                $context->pop();
            }
        }
    
        return $buffer;
    }

    private function section9296a21a49c2067e36f55db08ce4676d(Mustache_Context $context, $indent, $value)
    {
        $buffer = '';
    
        if (!is_string($value) && is_callable($value)) {
            $source = '{{> core/popover}}';
            $result = call_user_func($value, $source, $this->lambdaHelper);
            if (strpos($result, '{{') === false) {
                $buffer .= $result;
            } else {
                $buffer .= $this->mustache
                    ->loadLambda((string) $result)
                    ->renderInternal($context);
            }
        } elseif (!empty($value)) {
            $values = $this->isIterable($value) ? $value : array($value);
            foreach ($values as $value) {
                $context->push($value);
                
                // Totara hack: if the partial starts with "." then try
                // to resolve it from the current context.
                $partialstr = 'core/popover';
                if (strpos($partialstr, '&&') === 0) {
                    $partialstr = $context->find(substr($partialstr, 2));
                }
                if ($partial = $this->mustache->loadPartial($partialstr)) {
                    $buffer .= $partial->renderInternal($context);
                }
                $context->pop();
            }
        }
    
        return $buffer;
    }

}
