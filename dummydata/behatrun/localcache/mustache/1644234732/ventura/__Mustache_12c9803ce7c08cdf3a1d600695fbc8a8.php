<?php

class __Mustache_12c9803ce7c08cdf3a1d600695fbc8a8 extends Mustache_Template
{
    private $lambdaHelper;

    public function renderInternal(Mustache_Context $context, $indent = '')
    {
        $this->lambdaHelper = new Mustache_LambdaHelper($this->mustache, $context);
        $buffer = '';

        $buffer .= $indent . '<div class="tab-content current-learning-content">
';
        $buffer .= $indent . '    <div class="panel-body panel-body-content tab-pane active">
';
        // 'haslearningitems' section
        $value = $context->find('haslearningitems');
        $buffer .= $this->sectionCd750dd74a398739fd1042b2c6e58520($context, $indent, $value);
        // 'haslearningitems' inverted section
        $value = $context->find('haslearningitems');
        if (empty($value)) {
            
            $buffer .= $indent . '        <p class="current_learning-no-content">';
            $value = $this->resolveValue($context->find('nocurrentlearning_rol_link'), $context);
            $buffer .= $value;
            $buffer .= '</p>
';
        }
        $buffer .= $indent . '    </div>
';
        $buffer .= $indent . '</div>
';

        return $buffer;
    }

    private function sectionB36a4949a787bbe08eac1aec40013b71(Mustache_Context $context, $indent, $value)
    {
        $buffer = '';
    
        if (!is_string($value) && is_callable($value)) {
            $source = '
                {{> &&template }}
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
                $partialstr = '&&template';
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

    private function sectionCd750dd74a398739fd1042b2c6e58520(Mustache_Context $context, $indent, $value)
    {
        $buffer = '';
    
        if (!is_string($value) && is_callable($value)) {
            $source = '
        <ul>
            {{#learningitems}}
                {{> &&template }}
            {{/learningitems}}
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
                
                $buffer .= $indent . '        <ul>
';
                // 'learningitems' section
                $value = $context->find('learningitems');
                $buffer .= $this->sectionB36a4949a787bbe08eac1aec40013b71($context, $indent, $value);
                $buffer .= $indent . '        </ul>
';
                $context->pop();
            }
        }
    
        return $buffer;
    }

}
