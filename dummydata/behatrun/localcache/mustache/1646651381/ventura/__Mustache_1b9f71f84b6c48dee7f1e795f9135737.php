<?php

class __Mustache_1b9f71f84b6c48dee7f1e795f9135737 extends Mustache_Template
{
    private $lambdaHelper;

    public function renderInternal(Mustache_Context $context, $indent = '')
    {
        $this->lambdaHelper = new Mustache_LambdaHelper($this->mustache, $context);
        $buffer = '';

        $buffer .= $indent . '<div class="form-filetypes-descriptions">
';
        // 'hasdescriptions' section
        $value = $context->find('hasdescriptions');
        $buffer .= $this->section2fdcdd9fa6a851c1379f94b4ae281917($context, $indent, $value);
        // 'hasdescriptions' inverted section
        $value = $context->find('hasdescriptions');
        if (empty($value)) {
            
            $buffer .= $indent . '    <p>';
            // 'str' section
            $value = $context->find('str');
            $buffer .= $this->section30129dc0794ae04b28e7bc4b7f11d4d6($context, $indent, $value);
            $buffer .= '</p>
';
        }
        $buffer .= $indent . '</div>
';

        return $buffer;
    }

    private function sectionD2bf6be9032e9306d9c10cf4294f3aa5(Mustache_Context $context, $indent, $value)
    {
        $buffer = '';
    
        if (!is_string($value) && is_callable($value)) {
            $source = '
        <li>{{description}} <small class="text-muted muted">{{extensions}}</small></li>
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
                
                $buffer .= $indent . '        <li>';
                $value = $this->resolveValue($context->find('description'), $context);
                $buffer .= call_user_func($this->mustache->getEscape(), $value);
                $buffer .= ' <small class="text-muted muted">';
                $value = $this->resolveValue($context->find('extensions'), $context);
                $buffer .= call_user_func($this->mustache->getEscape(), $value);
                $buffer .= '</small></li>
';
                $context->pop();
            }
        }
    
        return $buffer;
    }

    private function section2fdcdd9fa6a851c1379f94b4ae281917(Mustache_Context $context, $indent, $value)
    {
        $buffer = '';
    
        if (!is_string($value) && is_callable($value)) {
            $source = '
    <ul class="list-unstyled unstyled">
        {{#descriptions}}
        <li>{{description}} <small class="text-muted muted">{{extensions}}</small></li>
        {{/descriptions}}
    </ul>
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
                
                $buffer .= $indent . '    <ul class="list-unstyled unstyled">
';
                // 'descriptions' section
                $value = $context->find('descriptions');
                $buffer .= $this->sectionD2bf6be9032e9306d9c10cf4294f3aa5($context, $indent, $value);
                $buffer .= $indent . '    </ul>
';
                $context->pop();
            }
        }
    
        return $buffer;
    }

    private function section30129dc0794ae04b28e7bc4b7f11d4d6(Mustache_Context $context, $indent, $value)
    {
        $buffer = '';
    
        if (!is_string($value) && is_callable($value)) {
            $source = 'noselection, form';
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
                
                $buffer .= 'noselection, form';
                $context->pop();
            }
        }
    
        return $buffer;
    }

}
