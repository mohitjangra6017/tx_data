<?php

class __Mustache_db8f5890d87038f397c5e2880514479f extends Mustache_Template
{
    private $lambdaHelper;

    public function renderInternal(Mustache_Context $context, $indent = '')
    {
        $this->lambdaHelper = new Mustache_LambdaHelper($this->mustache, $context);
        $buffer = '';

        // 'pagination' section
        $value = $context->find('pagination');
        $buffer .= $this->sectionB8b40edeef5afabf2a69cf7f78a48b9e($context, $indent, $value);

        return $buffer;
    }

    private function sectionEad55b4903218304df224d06b4a35773(Mustache_Context $context, $indent, $value)
    {
        $buffer = '';
    
        if (!is_string($value) && is_callable($value)) {
            $source = 'previous, block_current_learning';
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
                
                $buffer .= 'previous, block_current_learning';
                $context->pop();
            }
        }
    
        return $buffer;
    }

    private function section39c31e4771c6695e252dce1effb238b8(Mustache_Context $context, $indent, $value)
    {
        $buffer = '';
    
        if (!is_string($value) && is_callable($value)) {
            $source = '<li class="{{active}}"><a data-page="{{page}}" href="{{link}}">{{page}}</a></li>';
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
                
                $buffer .= '<li class="';
                $value = $this->resolveValue($context->find('active'), $context);
                $buffer .= call_user_func($this->mustache->getEscape(), $value);
                $buffer .= '"><a data-page="';
                $value = $this->resolveValue($context->find('page'), $context);
                $buffer .= call_user_func($this->mustache->getEscape(), $value);
                $buffer .= '" href="';
                $value = $this->resolveValue($context->find('link'), $context);
                $buffer .= call_user_func($this->mustache->getEscape(), $value);
                $buffer .= '">';
                $value = $this->resolveValue($context->find('page'), $context);
                $buffer .= call_user_func($this->mustache->getEscape(), $value);
                $buffer .= '</a></li>';
                $context->pop();
            }
        }
    
        return $buffer;
    }

    private function sectionB69d8ef0cf51964817757b531e66ea4a(Mustache_Context $context, $indent, $value)
    {
        $buffer = '';
    
        if (!is_string($value) && is_callable($value)) {
            $source = 'next, block_current_learning';
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
                
                $buffer .= 'next, block_current_learning';
                $context->pop();
            }
        }
    
        return $buffer;
    }

    private function sectionB8b40edeef5afabf2a69cf7f78a48b9e(Mustache_Context $context, $indent, $value)
    {
        $buffer = '';
    
        if (!is_string($value) && is_callable($value)) {
            $source = '
    <div class="panel-footer">
        <p class="count">{{text}}</p>
        {{^onepage}}<nav>
            <ul class="pagination">
                <li class="{{previousclass}}">
                    <a data-page="prev" href="#" aria-label="{{#str}}previous, block_current_learning{{/str}}">
                        <span aria-hidden="true">&laquo;</span>
                    </a>
                </li>
                {{#pages}}<li class="{{active}}"><a data-page="{{page}}" href="{{link}}">{{page}}</a></li>{{/pages}}
                <li class="{{nextclass}}">
                    <a data-page="next" href="#" aria-label="{{#str}}next, block_current_learning{{/str}}">
                        <span aria-hidden="true">&raquo;</span>
                    </a>
                </li>
            </ul>
        </nav>{{/onepage}}
    </div>
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
                
                $buffer .= $indent . '    <div class="panel-footer">
';
                $buffer .= $indent . '        <p class="count">';
                $value = $this->resolveValue($context->find('text'), $context);
                $buffer .= call_user_func($this->mustache->getEscape(), $value);
                $buffer .= '</p>
';
                $buffer .= $indent . '        ';
                // 'onepage' inverted section
                $value = $context->find('onepage');
                if (empty($value)) {
                    
                    $buffer .= '<nav>
';
                    $buffer .= $indent . '            <ul class="pagination">
';
                    $buffer .= $indent . '                <li class="';
                    $value = $this->resolveValue($context->find('previousclass'), $context);
                    $buffer .= call_user_func($this->mustache->getEscape(), $value);
                    $buffer .= '">
';
                    $buffer .= $indent . '                    <a data-page="prev" href="#" aria-label="';
                    // 'str' section
                    $value = $context->find('str');
                    $buffer .= $this->sectionEad55b4903218304df224d06b4a35773($context, $indent, $value);
                    $buffer .= '">
';
                    $buffer .= $indent . '                        <span aria-hidden="true">&laquo;</span>
';
                    $buffer .= $indent . '                    </a>
';
                    $buffer .= $indent . '                </li>
';
                    $buffer .= $indent . '                ';
                    // 'pages' section
                    $value = $context->find('pages');
                    $buffer .= $this->section39c31e4771c6695e252dce1effb238b8($context, $indent, $value);
                    $buffer .= '
';
                    $buffer .= $indent . '                <li class="';
                    $value = $this->resolveValue($context->find('nextclass'), $context);
                    $buffer .= call_user_func($this->mustache->getEscape(), $value);
                    $buffer .= '">
';
                    $buffer .= $indent . '                    <a data-page="next" href="#" aria-label="';
                    // 'str' section
                    $value = $context->find('str');
                    $buffer .= $this->sectionB69d8ef0cf51964817757b531e66ea4a($context, $indent, $value);
                    $buffer .= '">
';
                    $buffer .= $indent . '                        <span aria-hidden="true">&raquo;</span>
';
                    $buffer .= $indent . '                    </a>
';
                    $buffer .= $indent . '                </li>
';
                    $buffer .= $indent . '            </ul>
';
                    $buffer .= $indent . '        </nav>';
                }
                $buffer .= '
';
                $buffer .= $indent . '    </div>
';
                $context->pop();
            }
        }
    
        return $buffer;
    }

}
