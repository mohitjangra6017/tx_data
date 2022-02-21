<?php

class __Mustache_d3432b06dde78107dc4371d8c6a27a44 extends Mustache_Template
{
    private $lambdaHelper;

    public function renderInternal(Mustache_Context $context, $indent = '')
    {
        $this->lambdaHelper = new Mustache_LambdaHelper($this->mustache, $context);
        $buffer = '';

        $buffer .= $indent . '
';
        $buffer .= $indent . '<div class="';
        $value = $this->resolveValue($context->find('classes'), $context);
        $buffer .= call_user_func($this->mustache->getEscape(), $value);
        $buffer .= ' d-inline-block">
';
        $buffer .= $indent . '    <form method="';
        $value = $this->resolveValue($context->find('method'), $context);
        $buffer .= call_user_func($this->mustache->getEscape(), $value);
        $buffer .= '" action="';
        $value = $this->resolveValue($context->find('action'), $context);
        $buffer .= call_user_func($this->mustache->getEscape(), $value);
        $buffer .= '" class="form-inline" id="';
        $value = $this->resolveValue($context->find('formid'), $context);
        $buffer .= call_user_func($this->mustache->getEscape(), $value);
        $buffer .= '">
';
        // 'params' section
        $value = $context->find('params');
        $buffer .= $this->section60a14ae5ab6931499cdaf64f3e4f6699($context, $indent, $value);
        // 'label' section
        $value = $context->find('label');
        $buffer .= $this->section03950c1041f3e0da4c44ae5b68977c66($context, $indent, $value);
        // 'helpicon' section
        $value = $context->find('helpicon');
        $buffer .= $this->sectionDb0f025ed3e747d086a6ad4bf2205a7a($context, $indent, $value);
        $buffer .= $indent . '        <select ';
        // 'attributes' section
        $value = $context->find('attributes');
        $buffer .= $this->section6805fd502f1e55bd3a63b02c625bf221($context, $indent, $value);
        // 'disabled' section
        $value = $context->find('disabled');
        $buffer .= $this->section05828eb925ccd1e324128320ddd16fea($context, $indent, $value);
        // 'title' section
        $value = $context->find('title');
        $buffer .= $this->section56fab61e65785f71232f41824ab11e2d($context, $indent, $value);
        $buffer .= ' id="';
        $value = $this->resolveValue($context->find('id'), $context);
        $buffer .= call_user_func($this->mustache->getEscape(), $value);
        $buffer .= '" class="custom-select ';
        $value = $this->resolveValue($context->find('classes'), $context);
        $buffer .= call_user_func($this->mustache->getEscape(), $value);
        $buffer .= '" name="';
        $value = $this->resolveValue($context->find('name'), $context);
        $buffer .= call_user_func($this->mustache->getEscape(), $value);
        $buffer .= '">
';
        // 'options' section
        $value = $context->find('options');
        $buffer .= $this->section253b2ed8acd5ca436a89ca25d6c940f7($context, $indent, $value);
        $buffer .= $indent . '        </select>
';
        $buffer .= $indent . '        <noscript>
';
        $buffer .= $indent . '            <input type="submit" role="button" class="btn btn-secondary" value="';
        // 'str' section
        $value = $context->find('str');
        $buffer .= $this->section9b3ccc5f65ed9245b5297e3d7e55569b($context, $indent, $value);
        $buffer .= '">
';
        $buffer .= $indent . '        </noscript>
';
        $buffer .= $indent . '    </form>
';
        $buffer .= $indent . '</div>
';
        // 'js' section
        $value = $context->find('js');
        $buffer .= $this->sectionA5205de8c0af67c57d78e3840e090d0a($context, $indent, $value);

        return $buffer;
    }

    private function section60a14ae5ab6931499cdaf64f3e4f6699(Mustache_Context $context, $indent, $value)
    {
        $buffer = '';
    
        if (!is_string($value) && is_callable($value)) {
            $source = '
            <input type="hidden" name="{{name}}" value="{{value}}">
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
                
                $buffer .= $indent . '            <input type="hidden" name="';
                $value = $this->resolveValue($context->find('name'), $context);
                $buffer .= call_user_func($this->mustache->getEscape(), $value);
                $buffer .= '" value="';
                $value = $this->resolveValue($context->find('value'), $context);
                $buffer .= call_user_func($this->mustache->getEscape(), $value);
                $buffer .= '">
';
                $context->pop();
            }
        }
    
        return $buffer;
    }

    private function section6805fd502f1e55bd3a63b02c625bf221(Mustache_Context $context, $indent, $value)
    {
        $buffer = '';
    
        if (!is_string($value) && is_callable($value)) {
            $source = '{{name}}="{{value}}" ';
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
                
                $value = $this->resolveValue($context->find('name'), $context);
                $buffer .= call_user_func($this->mustache->getEscape(), $value);
                $buffer .= '="';
                $value = $this->resolveValue($context->find('value'), $context);
                $buffer .= call_user_func($this->mustache->getEscape(), $value);
                $buffer .= '" ';
                $context->pop();
            }
        }
    
        return $buffer;
    }

    private function section03950c1041f3e0da4c44ae5b68977c66(Mustache_Context $context, $indent, $value)
    {
        $buffer = '';
    
        if (!is_string($value) && is_callable($value)) {
            $source = '
            <label for="{{id}}" {{#labelattributes}}{{name}}="{{value}}" {{/labelattributes}}>
                {{{label}}}
            </label>
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
                
                $buffer .= $indent . '            <label for="';
                $value = $this->resolveValue($context->find('id'), $context);
                $buffer .= call_user_func($this->mustache->getEscape(), $value);
                $buffer .= '" ';
                // 'labelattributes' section
                $value = $context->find('labelattributes');
                $buffer .= $this->section6805fd502f1e55bd3a63b02c625bf221($context, $indent, $value);
                $buffer .= '>
';
                $buffer .= $indent . '                ';
                $value = $this->resolveValue($context->find('label'), $context);
                $buffer .= $value;
                $buffer .= '
';
                $buffer .= $indent . '            </label>
';
                $context->pop();
            }
        }
    
        return $buffer;
    }

    private function sectionDb0f025ed3e747d086a6ad4bf2205a7a(Mustache_Context $context, $indent, $value)
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
                    $buffer .= $partial->renderInternal($context, $indent . '            ');
                }
                $context->pop();
            }
        }
    
        return $buffer;
    }

    private function section05828eb925ccd1e324128320ddd16fea(Mustache_Context $context, $indent, $value)
    {
        $buffer = '';
    
        if (!is_string($value) && is_callable($value)) {
            $source = ' disabled';
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
                
                $buffer .= ' disabled';
                $context->pop();
            }
        }
    
        return $buffer;
    }

    private function section56fab61e65785f71232f41824ab11e2d(Mustache_Context $context, $indent, $value)
    {
        $buffer = '';
    
        if (!is_string($value) && is_callable($value)) {
            $source = ' title="{{.}}"';
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
                
                $buffer .= ' title="';
                $value = $this->resolveValue($context->last(), $context);
                $buffer .= call_user_func($this->mustache->getEscape(), $value);
                $buffer .= '"';
                $context->pop();
            }
        }
    
        return $buffer;
    }

    private function section9e2875c627d2dbad7c957250bbb623f7(Mustache_Context $context, $indent, $value)
    {
        $buffer = '';
    
        if (!is_string($value) && is_callable($value)) {
            $source = 'selected';
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
                
                $buffer .= 'selected';
                $context->pop();
            }
        }
    
        return $buffer;
    }

    private function section4c4c73ea8dba5b55447082c641201ccd(Mustache_Context $context, $indent, $value)
    {
        $buffer = '';
    
        if (!is_string($value) && is_callable($value)) {
            $source = '
                    <option value="{{value}}" {{#selected}}selected{{/selected}}>{{{name}}}</option>
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
                
                $buffer .= $indent . '                    <option value="';
                $value = $this->resolveValue($context->find('value'), $context);
                $buffer .= call_user_func($this->mustache->getEscape(), $value);
                $buffer .= '" ';
                // 'selected' section
                $value = $context->find('selected');
                $buffer .= $this->section9e2875c627d2dbad7c957250bbb623f7($context, $indent, $value);
                $buffer .= '>';
                $value = $this->resolveValue($context->find('name'), $context);
                $buffer .= $value;
                $buffer .= '</option>
';
                $context->pop();
            }
        }
    
        return $buffer;
    }

    private function section23ae3a7694feb9fc66bd515258f38e59(Mustache_Context $context, $indent, $value)
    {
        $buffer = '';
    
        if (!is_string($value) && is_callable($value)) {
            $source = '
                    <optgroup label="{{name}}">
                    {{#options}}
                    <option value="{{value}}" {{#selected}}selected{{/selected}}>{{{name}}}</option>
                    {{/options}}
                    </optgroup>
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
                
                $buffer .= $indent . '                    <optgroup label="';
                $value = $this->resolveValue($context->find('name'), $context);
                $buffer .= call_user_func($this->mustache->getEscape(), $value);
                $buffer .= '">
';
                // 'options' section
                $value = $context->find('options');
                $buffer .= $this->section4c4c73ea8dba5b55447082c641201ccd($context, $indent, $value);
                $buffer .= $indent . '                    </optgroup>
';
                $context->pop();
            }
        }
    
        return $buffer;
    }

    private function section253b2ed8acd5ca436a89ca25d6c940f7(Mustache_Context $context, $indent, $value)
    {
        $buffer = '';
    
        if (!is_string($value) && is_callable($value)) {
            $source = '
                {{#optgroup}}
                    <optgroup label="{{name}}">
                    {{#options}}
                    <option value="{{value}}" {{#selected}}selected{{/selected}}>{{{name}}}</option>
                    {{/options}}
                    </optgroup>
                {{/optgroup}}
                {{^optgroup}}
                    <option {{ignore}} value="{{value}}" {{#selected}}selected{{/selected}}>{{{name}}}</option>
                {{/optgroup}}
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
                
                // 'optgroup' section
                $value = $context->find('optgroup');
                $buffer .= $this->section23ae3a7694feb9fc66bd515258f38e59($context, $indent, $value);
                // 'optgroup' inverted section
                $value = $context->find('optgroup');
                if (empty($value)) {
                    
                    $buffer .= $indent . '                    <option ';
                    $value = $this->resolveValue($context->find('ignore'), $context);
                    $buffer .= call_user_func($this->mustache->getEscape(), $value);
                    $buffer .= ' value="';
                    $value = $this->resolveValue($context->find('value'), $context);
                    $buffer .= call_user_func($this->mustache->getEscape(), $value);
                    $buffer .= '" ';
                    // 'selected' section
                    $value = $context->find('selected');
                    $buffer .= $this->section9e2875c627d2dbad7c957250bbb623f7($context, $indent, $value);
                    $buffer .= '>';
                    $value = $this->resolveValue($context->find('name'), $context);
                    $buffer .= $value;
                    $buffer .= '</option>
';
                }
                $context->pop();
            }
        }
    
        return $buffer;
    }

    private function section9b3ccc5f65ed9245b5297e3d7e55569b(Mustache_Context $context, $indent, $value)
    {
        $buffer = '';
    
        if (!is_string($value) && is_callable($value)) {
            $source = 'go, core';
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
                
                $buffer .= 'go, core';
                $context->pop();
            }
        }
    
        return $buffer;
    }

    private function sectionA5205de8c0af67c57d78e3840e090d0a(Mustache_Context $context, $indent, $value)
    {
        $buffer = '';
    
        if (!is_string($value) && is_callable($value)) {
            $source = '
require([\'jquery\'], function($) {
    $(\'#{{id}}\').change(function() {
        var ignore = $(this).find(\':selected\').attr(\'data-ignore\');
        if (typeof ignore === typeof undefined) {
            $(\'#{{formid}}\').submit();
        }
    });
});
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
                
                $buffer .= $indent . 'require([\'jquery\'], function($) {
';
                $buffer .= $indent . '    $(\'#';
                $value = $this->resolveValue($context->find('id'), $context);
                $buffer .= call_user_func($this->mustache->getEscape(), $value);
                $buffer .= '\').change(function() {
';
                $buffer .= $indent . '        var ignore = $(this).find(\':selected\').attr(\'data-ignore\');
';
                $buffer .= $indent . '        if (typeof ignore === typeof undefined) {
';
                $buffer .= $indent . '            $(\'#';
                $value = $this->resolveValue($context->find('formid'), $context);
                $buffer .= call_user_func($this->mustache->getEscape(), $value);
                $buffer .= '\').submit();
';
                $buffer .= $indent . '        }
';
                $buffer .= $indent . '    });
';
                $buffer .= $indent . '});
';
                $context->pop();
            }
        }
    
        return $buffer;
    }

}
