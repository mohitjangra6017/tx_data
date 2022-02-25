<?php

class __Mustache_a43d6ea0269b842a827d6105e52c05d1 extends Mustache_Template
{
    private $lambdaHelper;

    public function renderInternal(Mustache_Context $context, $indent = '')
    {
        $this->lambdaHelper = new Mustache_LambdaHelper($this->mustache, $context);
        $buffer = '';

        $buffer .= $indent . '<div class="searchform">
';
        $buffer .= $indent . '    <form action="';
        $value = $this->resolveValue($context->find('actionurl'), $context);
        $buffer .= call_user_func($this->mustache->getEscape(), $value);
        $buffer .= '" style="display: inline;">
';
        $buffer .= $indent . '        <fieldset class="invisiblefieldset">
';
        $buffer .= $indent . '            <legend class="accesshide">';
        // 'str' section
        $value = $context->find('str');
        $buffer .= $this->sectionBec198647490b4fd7cf2a5fed37757ce($context, $indent, $value);
        $buffer .= '</legend>
';
        $buffer .= $indent . '            <input type="hidden" name="id" value="';
        $value = $this->resolveValue($context->find('courseid'), $context);
        $buffer .= call_user_func($this->mustache->getEscape(), $value);
        $buffer .= '">
';
        $buffer .= $indent . '            <label class="accesshide" for="searchform_search">';
        // 'str' section
        $value = $context->find('str');
        $buffer .= $this->sectionBec198647490b4fd7cf2a5fed37757ce($context, $indent, $value);
        $buffer .= '</label>
';
        $buffer .= $indent . '            <input id="searchform_search" name="search" type="text" size="16">
';
        $buffer .= $indent . '            <button id="searchform_button" type="submit" title=';
        // 'quote' section
        $value = $context->find('quote');
        $buffer .= $this->section1abd7f467b6663ce2e524708ea22c59c($context, $indent, $value);
        $buffer .= '>';
        // 'str' section
        $value = $context->find('str');
        $buffer .= $this->sectionCd40f67c442c6cf3e4e18af0c0f7a7ca($context, $indent, $value);
        $buffer .= '</button><br>
';
        $buffer .= $indent . '            <a href="';
        $value = $this->resolveValue($context->find('advancedsearchurl'), $context);
        $buffer .= call_user_func($this->mustache->getEscape(), $value);
        $buffer .= '">';
        // 'str' section
        $value = $context->find('str');
        $buffer .= $this->section54c0d8543fef7896590f9327e345b78c($context, $indent, $value);
        $buffer .= '</a>
';
        // 'helpicon' section
        $value = $context->find('helpicon');
        $buffer .= $this->section05aa320cfaae3196b971e10ee8e657b8($context, $indent, $value);
        $buffer .= $indent . '        </fieldset>
';
        $buffer .= $indent . '    </form>
';
        $buffer .= $indent . '</div>
';

        return $buffer;
    }

    private function sectionBec198647490b4fd7cf2a5fed37757ce(Mustache_Context $context, $indent, $value)
    {
        $buffer = '';
    
        if (!is_string($value) && is_callable($value)) {
            $source = 'search';
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
                
                $buffer .= 'search';
                $context->pop();
            }
        }
    
        return $buffer;
    }

    private function section1abd7f467b6663ce2e524708ea22c59c(Mustache_Context $context, $indent, $value)
    {
        $buffer = '';
    
        if (!is_string($value) && is_callable($value)) {
            $source = '{{#str}}search{{/str}}';
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
                
                // 'str' section
                $value = $context->find('str');
                $buffer .= $this->sectionBec198647490b4fd7cf2a5fed37757ce($context, $indent, $value);
                $context->pop();
            }
        }
    
        return $buffer;
    }

    private function sectionCd40f67c442c6cf3e4e18af0c0f7a7ca(Mustache_Context $context, $indent, $value)
    {
        $buffer = '';
    
        if (!is_string($value) && is_callable($value)) {
            $source = 'go';
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
                
                $buffer .= 'go';
                $context->pop();
            }
        }
    
        return $buffer;
    }

    private function section54c0d8543fef7896590f9327e345b78c(Mustache_Context $context, $indent, $value)
    {
        $buffer = '';
    
        if (!is_string($value) && is_callable($value)) {
            $source = 'advancedsearch, block_search_forums';
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
                
                $buffer .= 'advancedsearch, block_search_forums';
                $context->pop();
            }
        }
    
        return $buffer;
    }

    private function section05aa320cfaae3196b971e10ee8e657b8(Mustache_Context $context, $indent, $value)
    {
        $buffer = '';
    
        if (!is_string($value) && is_callable($value)) {
            $source = '
                {{>core/help_icon}}
            ';
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
                $partialstr = 'core/help_icon';
                if (strpos($partialstr, '&&') === 0) {
                    $partialstr = $context->find(substr($partialstr, 2));
                }
                if ($partial = $this->mustache->loadPartial($partialstr)) {
                    $buffer .= $partial->renderInternal($context, $indent . '                ');
                }
                $context->pop();
            }
        }
    
        return $buffer;
    }

}
