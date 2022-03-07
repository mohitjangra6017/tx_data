<?php

class __Mustache_e681edecee70209d9aa8ef38801f9d1b extends Mustache_Template
{
    private $lambdaHelper;

    public function renderInternal(Mustache_Context $context, $indent = '')
    {
        $this->lambdaHelper = new Mustache_LambdaHelper($this->mustache, $context);
        $buffer = '';

        $buffer .= $indent . '
';
        $buffer .= $indent . '<div id="quickaccess-popover-container" class="totara_core__QuickAccess popover-region collapsed"
';
        $buffer .= $indent . '     data-core-autoinitialise="true"
';
        $buffer .= $indent . '     data-core-autoinitialise-amd="totara_core/quickaccessmenu">
';
        $buffer .= $indent . '    <div class="nav-link totara_core__QuickAccess_icon popover-region-toggle"
';
        $buffer .= $indent . '         role="button"
';
        $buffer .= $indent . '         aria-controls="quickaccess-popover-content"
';
        $buffer .= $indent . '         aria-haspopup="true"
';
        $buffer .= $indent . '         aria-label="';
        // 'str' section
        $value = $context->find('str');
        $buffer .= $this->section6c8ab1999652c59bc6ccd7e3ccd33a71($context, $indent, $value);
        $buffer .= '"
';
        $buffer .= $indent . '         tabindex="0">
';
        $buffer .= $indent . '        ';
        // 'flex_icon' section
        $value = $context->find('flex_icon');
        $buffer .= $this->section1bef791b4dd144184db10aaf42fd894e($context, $indent, $value);
        // 'flex_icon' section
        $value = $context->find('flex_icon');
        $buffer .= $this->section6d0b930a486bbb98cb9389a7e1e5d37c($context, $indent, $value);
        $buffer .= '
';
        $buffer .= $indent . '    </div>
';
        $buffer .= $indent . '
';
        $buffer .= $indent . '    <aside id="quickaccess-popover-content" class="totara_core__QuickAccess_menu popover-region-content totara_core__QuickAccess_menu--loading">
';
        $buffer .= $indent . '        <div class="totara_core__QuickAccess_menu_content_loadingContainer">
';
        $buffer .= $indent . '            ';
        // 'flex_icon' section
        $value = $context->find('flex_icon');
        $buffer .= $this->sectionB1757c41fc32cd38d3028f6093a280de($context, $indent, $value);
        $buffer .= '
';
        $buffer .= $indent . '        </div>
';
        $buffer .= $indent . '    </aside>
';
        $buffer .= $indent . '</div>
';

        return $buffer;
    }

    private function section6c8ab1999652c59bc6ccd7e3ccd33a71(Mustache_Context $context, $indent, $value)
    {
        $buffer = '';
    
        if (!is_string($value) && is_callable($value)) {
            $source = 'quickaccessmenu:showmenuwindow,totara_core';
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
                
                $buffer .= 'quickaccessmenu:showmenuwindow,totara_core';
                $context->pop();
            }
        }
    
        return $buffer;
    }

    private function section1bef791b4dd144184db10aaf42fd894e(Mustache_Context $context, $indent, $value)
    {
        $buffer = '';
    
        if (!is_string($value) && is_callable($value)) {
            $source = 'settings';
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
                
                $buffer .= 'settings';
                $context->pop();
            }
        }
    
        return $buffer;
    }

    private function section6d0b930a486bbb98cb9389a7e1e5d37c(Mustache_Context $context, $indent, $value)
    {
        $buffer = '';
    
        if (!is_string($value) && is_callable($value)) {
            $source = 'nav-down,,,totara_core__QuickAccess_chevron';
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
                
                $buffer .= 'nav-down,,,totara_core__QuickAccess_chevron';
                $context->pop();
            }
        }
    
        return $buffer;
    }

    private function sectionB1757c41fc32cd38d3028f6093a280de(Mustache_Context $context, $indent, $value)
    {
        $buffer = '';
    
        if (!is_string($value) && is_callable($value)) {
            $source = 'loading';
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
                
                $buffer .= 'loading';
                $context->pop();
            }
        }
    
        return $buffer;
    }

}
