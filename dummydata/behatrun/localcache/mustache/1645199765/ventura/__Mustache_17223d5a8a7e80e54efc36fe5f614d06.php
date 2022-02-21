<?php

class __Mustache_17223d5a8a7e80e54efc36fe5f614d06 extends Mustache_Template
{
    private $lambdaHelper;

    public function renderInternal(Mustache_Context $context, $indent = '')
    {
        $this->lambdaHelper = new Mustache_LambdaHelper($this->mustache, $context);
        $buffer = '';

        // 'table' section
        $value = $context->find('table');
        $buffer .= $this->section4cd30e56d2c4b834d8cd8906fb6e1efb($context, $indent, $value);

        return $buffer;
    }

    private function sectionAd20463c348991d5bbd2fb97358ea7c0(Mustache_Context $context, $indent, $value)
    {
        $buffer = '';
    
        if (!is_string($value) && is_callable($value)) {
            $source = ' {{name}}="{{value}}"';
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
                
                $buffer .= ' ';
                $value = $this->resolveValue($context->find('name'), $context);
                $buffer .= call_user_func($this->mustache->getEscape(), $value);
                $buffer .= '="';
                $value = $this->resolveValue($context->find('value'), $context);
                $buffer .= call_user_func($this->mustache->getEscape(), $value);
                $buffer .= '"';
                $context->pop();
            }
        }
    
        return $buffer;
    }

    private function section22ab3923ba2cfc3cff171c9ace86b5d6(Mustache_Context $context, $indent, $value)
    {
        $buffer = '';
    
        if (!is_string($value) && is_callable($value)) {
            $source = ' id="{{.}}"';
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
                
                $buffer .= ' id="';
                $value = $this->resolveValue($context->last(), $context);
                $buffer .= call_user_func($this->mustache->getEscape(), $value);
                $buffer .= '"';
                $context->pop();
            }
        }
    
        return $buffer;
    }

    private function section9a3e7930dd3649baf4286b9eb2a01a5d(Mustache_Context $context, $indent, $value)
    {
        $buffer = '';
    
        if (!is_string($value) && is_callable($value)) {
            $source = ' width="{{.}}"';
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
                
                $buffer .= ' width="';
                $value = $this->resolveValue($context->last(), $context);
                $buffer .= call_user_func($this->mustache->getEscape(), $value);
                $buffer .= '"';
                $context->pop();
            }
        }
    
        return $buffer;
    }

    private function section280628ddb7813afe7e0ce26665347350(Mustache_Context $context, $indent, $value)
    {
        $buffer = '';
    
        if (!is_string($value) && is_callable($value)) {
            $source = ' summary="{{.}}"';
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
                
                $buffer .= ' summary="';
                $value = $this->resolveValue($context->last(), $context);
                $buffer .= call_user_func($this->mustache->getEscape(), $value);
                $buffer .= '"';
                $context->pop();
            }
        }
    
        return $buffer;
    }

    private function sectionCadc3ae82e56711a52457783cf34194e(Mustache_Context $context, $indent, $value)
    {
        $buffer = '';
    
        if (!is_string($value) && is_callable($value)) {
            $source = ' cellpadding="{{.}}"';
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
                
                $buffer .= ' cellpadding="';
                $value = $this->resolveValue($context->last(), $context);
                $buffer .= call_user_func($this->mustache->getEscape(), $value);
                $buffer .= '"';
                $context->pop();
            }
        }
    
        return $buffer;
    }

    private function section758316d313916b40f789b3a164183f5a(Mustache_Context $context, $indent, $value)
    {
        $buffer = '';
    
        if (!is_string($value) && is_callable($value)) {
            $source = ' cellspacing="{{.}}"';
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
                
                $buffer .= ' cellspacing="';
                $value = $this->resolveValue($context->last(), $context);
                $buffer .= call_user_func($this->mustache->getEscape(), $value);
                $buffer .= '"';
                $context->pop();
            }
        }
    
        return $buffer;
    }

    private function sectionFd79f01855c9e64033de5132b245f263(Mustache_Context $context, $indent, $value)
    {
        $buffer = '';
    
        if (!is_string($value) && is_callable($value)) {
            $source = ' class="accesshide"';
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
                
                $buffer .= ' class="accesshide"';
                $context->pop();
            }
        }
    
        return $buffer;
    }

    private function sectionE0a35a071d1acb1c04428277eb25bc30(Mustache_Context $context, $indent, $value)
    {
        $buffer = '';
    
        if (!is_string($value) && is_callable($value)) {
            $source = '<caption{{#hidden}} class="accesshide"{{/hidden}}>{{{text}}}</caption>';
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
                
                $buffer .= '<caption';
                // 'hidden' section
                $value = $context->find('hidden');
                $buffer .= $this->sectionFd79f01855c9e64033de5132b245f263($context, $indent, $value);
                $buffer .= '>';
                $value = $this->resolveValue($context->find('text'), $context);
                $buffer .= $value;
                $buffer .= '</caption>';
                $context->pop();
            }
        }
    
        return $buffer;
    }

    private function section3bd2769186e9f69cc8dd0528e41a9fa6(Mustache_Context $context, $indent, $value)
    {
        $buffer = '';
    
        if (!is_string($value) && is_callable($value)) {
            $source = ' lastcol';
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
                
                $buffer .= ' lastcol';
                $context->pop();
            }
        }
    
        return $buffer;
    }

    private function sectionE89f12d27ca9e1be7a95b3e925ef31c0(Mustache_Context $context, $indent, $value)
    {
        $buffer = '';
    
        if (!is_string($value) && is_callable($value)) {
            $source = ' {{.}}';
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
                
                $buffer .= ' ';
                $value = $this->resolveValue($context->last(), $context);
                $buffer .= call_user_func($this->mustache->getEscape(), $value);
                $context->pop();
            }
        }
    
        return $buffer;
    }

    private function sectionFff96f817533e0f39f061349f5ea4b40(Mustache_Context $context, $indent, $value)
    {
        $buffer = '';
    
        if (!is_string($value) && is_callable($value)) {
            $source = ' colspan="{{.}}"';
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
                
                $buffer .= ' colspan="';
                $value = $this->resolveValue($context->last(), $context);
                $buffer .= call_user_func($this->mustache->getEscape(), $value);
                $buffer .= '"';
                $context->pop();
            }
        }
    
        return $buffer;
    }

    private function section47b12e09644034fc364ca6dc9b180a4c(Mustache_Context $context, $indent, $value)
    {
        $buffer = '';
    
        if (!is_string($value) && is_callable($value)) {
            $source = ' rowspan="{{.}}"';
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
                
                $buffer .= ' rowspan="';
                $value = $this->resolveValue($context->last(), $context);
                $buffer .= call_user_func($this->mustache->getEscape(), $value);
                $buffer .= '"';
                $context->pop();
            }
        }
    
        return $buffer;
    }

    private function sectionB62667187cbb96c603554e151f6837e7(Mustache_Context $context, $indent, $value)
    {
        $buffer = '';
    
        if (!is_string($value) && is_callable($value)) {
            $source = ' style="{{.}}"';
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
                
                $buffer .= ' style="';
                $value = $this->resolveValue($context->last(), $context);
                $buffer .= call_user_func($this->mustache->getEscape(), $value);
                $buffer .= '"';
                $context->pop();
            }
        }
    
        return $buffer;
    }

    private function section4e379994995cbedbebbd78954cb0dca2(Mustache_Context $context, $indent, $value)
    {
        $buffer = '';
    
        if (!is_string($value) && is_callable($value)) {
            $source = ' abbr="{{.}}"';
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
                
                $buffer .= ' abbr="';
                $value = $this->resolveValue($context->last(), $context);
                $buffer .= call_user_func($this->mustache->getEscape(), $value);
                $buffer .= '"';
                $context->pop();
            }
        }
    
        return $buffer;
    }

    private function sectionA72ac76839b11e73a4bb394f48fb93d0(Mustache_Context $context, $indent, $value)
    {
        $buffer = '';
    
        if (!is_string($value) && is_callable($value)) {
            $source = ' scope="{{.}}"';
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
                
                $buffer .= ' scope="';
                $value = $this->resolveValue($context->last(), $context);
                $buffer .= call_user_func($this->mustache->getEscape(), $value);
                $buffer .= '"';
                $context->pop();
            }
        }
    
        return $buffer;
    }

    private function section0804e384b561230ab419d660af8da992(Mustache_Context $context, $indent, $value)
    {
        $buffer = '';
    
        if (!is_string($value) && is_callable($value)) {
            $source = '
            <th class="header c{{column}}{{#lastcol}} lastcol{{/lastcol}}{{#cellclasses}} {{.}}{{/cellclasses}}"{{#colspan}} colspan="{{.}}"{{/colspan}}{{#rowspan}} rowspan="{{.}}"{{/rowspan}}{{#cellattributes}} {{name}}="{{value}}"{{/cellattributes}}{{#cellstyle}} style="{{.}}"{{/cellstyle}}{{#cellid}} id="{{.}}"{{/cellid}}{{#abbr}} abbr="{{.}}"{{/abbr}}{{#cellscope}} scope="{{.}}"{{/cellscope}}>{{{content}}}</th>
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
                
                $buffer .= $indent . '            <th class="header c';
                $value = $this->resolveValue($context->find('column'), $context);
                $buffer .= call_user_func($this->mustache->getEscape(), $value);
                // 'lastcol' section
                $value = $context->find('lastcol');
                $buffer .= $this->section3bd2769186e9f69cc8dd0528e41a9fa6($context, $indent, $value);
                // 'cellclasses' section
                $value = $context->find('cellclasses');
                $buffer .= $this->sectionE89f12d27ca9e1be7a95b3e925ef31c0($context, $indent, $value);
                $buffer .= '"';
                // 'colspan' section
                $value = $context->find('colspan');
                $buffer .= $this->sectionFff96f817533e0f39f061349f5ea4b40($context, $indent, $value);
                // 'rowspan' section
                $value = $context->find('rowspan');
                $buffer .= $this->section47b12e09644034fc364ca6dc9b180a4c($context, $indent, $value);
                // 'cellattributes' section
                $value = $context->find('cellattributes');
                $buffer .= $this->sectionAd20463c348991d5bbd2fb97358ea7c0($context, $indent, $value);
                // 'cellstyle' section
                $value = $context->find('cellstyle');
                $buffer .= $this->sectionB62667187cbb96c603554e151f6837e7($context, $indent, $value);
                // 'cellid' section
                $value = $context->find('cellid');
                $buffer .= $this->section22ab3923ba2cfc3cff171c9ace86b5d6($context, $indent, $value);
                // 'abbr' section
                $value = $context->find('abbr');
                $buffer .= $this->section4e379994995cbedbebbd78954cb0dca2($context, $indent, $value);
                // 'cellscope' section
                $value = $context->find('cellscope');
                $buffer .= $this->sectionA72ac76839b11e73a4bb394f48fb93d0($context, $indent, $value);
                $buffer .= '>';
                $value = $this->resolveValue($context->find('content'), $context);
                $buffer .= $value;
                $buffer .= '</th>
';
                $context->pop();
            }
        }
    
        return $buffer;
    }

    private function sectionA44e0be89ca3abdfd60bd072e88d6dde(Mustache_Context $context, $indent, $value)
    {
        $buffer = '';
    
        if (!is_string($value) && is_callable($value)) {
            $source = '
            {{^header}}
            <td class="header c{{column}}{{#lastcol}} lastcol{{/lastcol}}{{#cellclasses}} {{.}}{{/cellclasses}}"{{#colspan}} colspan="{{.}}"{{/colspan}}{{#rowspan}} rowspan="{{.}}"{{/rowspan}}{{#cellattributes}} {{name}}="{{value}}"{{/cellattributes}}{{#cellstyle}} style="{{.}}"{{/cellstyle}}{{#cellid}} id="{{.}}"{{/cellid}}{{#abbr}} abbr="{{.}}"{{/abbr}}{{#cellscope}} scope="{{.}}"{{/cellscope}}>{{{content}}}</td>
            {{/header}}
            {{#header}}
            <th class="header c{{column}}{{#lastcol}} lastcol{{/lastcol}}{{#cellclasses}} {{.}}{{/cellclasses}}"{{#colspan}} colspan="{{.}}"{{/colspan}}{{#rowspan}} rowspan="{{.}}"{{/rowspan}}{{#cellattributes}} {{name}}="{{value}}"{{/cellattributes}}{{#cellstyle}} style="{{.}}"{{/cellstyle}}{{#cellid}} id="{{.}}"{{/cellid}}{{#abbr}} abbr="{{.}}"{{/abbr}}{{#cellscope}} scope="{{.}}"{{/cellscope}}>{{{content}}}</th>
            {{/header}}
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
                
                // 'header' inverted section
                $value = $context->find('header');
                if (empty($value)) {
                    
                    $buffer .= $indent . '            <td class="header c';
                    $value = $this->resolveValue($context->find('column'), $context);
                    $buffer .= call_user_func($this->mustache->getEscape(), $value);
                    // 'lastcol' section
                    $value = $context->find('lastcol');
                    $buffer .= $this->section3bd2769186e9f69cc8dd0528e41a9fa6($context, $indent, $value);
                    // 'cellclasses' section
                    $value = $context->find('cellclasses');
                    $buffer .= $this->sectionE89f12d27ca9e1be7a95b3e925ef31c0($context, $indent, $value);
                    $buffer .= '"';
                    // 'colspan' section
                    $value = $context->find('colspan');
                    $buffer .= $this->sectionFff96f817533e0f39f061349f5ea4b40($context, $indent, $value);
                    // 'rowspan' section
                    $value = $context->find('rowspan');
                    $buffer .= $this->section47b12e09644034fc364ca6dc9b180a4c($context, $indent, $value);
                    // 'cellattributes' section
                    $value = $context->find('cellattributes');
                    $buffer .= $this->sectionAd20463c348991d5bbd2fb97358ea7c0($context, $indent, $value);
                    // 'cellstyle' section
                    $value = $context->find('cellstyle');
                    $buffer .= $this->sectionB62667187cbb96c603554e151f6837e7($context, $indent, $value);
                    // 'cellid' section
                    $value = $context->find('cellid');
                    $buffer .= $this->section22ab3923ba2cfc3cff171c9ace86b5d6($context, $indent, $value);
                    // 'abbr' section
                    $value = $context->find('abbr');
                    $buffer .= $this->section4e379994995cbedbebbd78954cb0dca2($context, $indent, $value);
                    // 'cellscope' section
                    $value = $context->find('cellscope');
                    $buffer .= $this->sectionA72ac76839b11e73a4bb394f48fb93d0($context, $indent, $value);
                    $buffer .= '>';
                    $value = $this->resolveValue($context->find('content'), $context);
                    $buffer .= $value;
                    $buffer .= '</td>
';
                }
                // 'header' section
                $value = $context->find('header');
                $buffer .= $this->section0804e384b561230ab419d660af8da992($context, $indent, $value);
                $context->pop();
            }
        }
    
        return $buffer;
    }

    private function section03321cd91f7b6c0c64ada442937502d1(Mustache_Context $context, $indent, $value)
    {
        $buffer = '';
    
        if (!is_string($value) && is_callable($value)) {
            $source = '
    <thead>
        <tr>
        {{#head}}
            {{^header}}
            <td class="header c{{column}}{{#lastcol}} lastcol{{/lastcol}}{{#cellclasses}} {{.}}{{/cellclasses}}"{{#colspan}} colspan="{{.}}"{{/colspan}}{{#rowspan}} rowspan="{{.}}"{{/rowspan}}{{#cellattributes}} {{name}}="{{value}}"{{/cellattributes}}{{#cellstyle}} style="{{.}}"{{/cellstyle}}{{#cellid}} id="{{.}}"{{/cellid}}{{#abbr}} abbr="{{.}}"{{/abbr}}{{#cellscope}} scope="{{.}}"{{/cellscope}}>{{{content}}}</td>
            {{/header}}
            {{#header}}
            <th class="header c{{column}}{{#lastcol}} lastcol{{/lastcol}}{{#cellclasses}} {{.}}{{/cellclasses}}"{{#colspan}} colspan="{{.}}"{{/colspan}}{{#rowspan}} rowspan="{{.}}"{{/rowspan}}{{#cellattributes}} {{name}}="{{value}}"{{/cellattributes}}{{#cellstyle}} style="{{.}}"{{/cellstyle}}{{#cellid}} id="{{.}}"{{/cellid}}{{#abbr}} abbr="{{.}}"{{/abbr}}{{#cellscope}} scope="{{.}}"{{/cellscope}}>{{{content}}}</th>
            {{/header}}
        {{/head}}
        </tr>
    </thead>
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
                
                $buffer .= $indent . '    <thead>
';
                $buffer .= $indent . '        <tr>
';
                // 'head' section
                $value = $context->find('head');
                $buffer .= $this->sectionA44e0be89ca3abdfd60bd072e88d6dde($context, $indent, $value);
                $buffer .= $indent . '        </tr>
';
                $buffer .= $indent . '    </thead>
';
                $context->pop();
            }
        }
    
        return $buffer;
    }

    private function section497aee2f22a12194a04839fcc03310e6(Mustache_Context $context, $indent, $value)
    {
        $buffer = '';
    
        if (!is_string($value) && is_callable($value)) {
            $source = '
        </tbody><tbody>
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
                
                $buffer .= $indent . '        </tbody><tbody>
';
                $context->pop();
            }
        }
    
        return $buffer;
    }

    private function section5e96ec75439305fc88c78e77946e47bb(Mustache_Context $context, $indent, $value)
    {
        $buffer = '';
    
        if (!is_string($value) && is_callable($value)) {
            $source = '{{.}} ';
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
                
                $value = $this->resolveValue($context->last(), $context);
                $buffer .= call_user_func($this->mustache->getEscape(), $value);
                $buffer .= ' ';
                $context->pop();
            }
        }
    
        return $buffer;
    }

    private function sectionBa05800bf0c36bf123cddd7438906a13(Mustache_Context $context, $indent, $value)
    {
        $buffer = '';
    
        if (!is_string($value) && is_callable($value)) {
            $source = 'lastrow';
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
                
                $buffer .= 'lastrow';
                $context->pop();
            }
        }
    
        return $buffer;
    }

    private function section918dfeba5292e5d22a65337bd97355a1(Mustache_Context $context, $indent, $value)
    {
        $buffer = '';
    
        if (!is_string($value) && is_callable($value)) {
            $source = '
            <th class="{{#cellclasses}}{{.}} {{/cellclasses}}cell c{{column}}{{#lastcol}} lastcol{{/lastcol}}"{{#colspan}} colspan="{{.}}"{{/colspan}}{{#rowspan}} rowspan="{{.}}"{{/rowspan}}{{#cellattributes}} {{name}}="{{value}}"{{/cellattributes}}{{#cellstyle}} style="{{.}}"{{/cellstyle}}{{#cellid}} id="{{.}}"{{/cellid}}{{#abbr}} abbr="{{.}}"{{/abbr}}{{#cellscope}} scope="{{.}}"{{/cellscope}}>{{{content}}}</th>
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
                
                $buffer .= $indent . '            <th class="';
                // 'cellclasses' section
                $value = $context->find('cellclasses');
                $buffer .= $this->section5e96ec75439305fc88c78e77946e47bb($context, $indent, $value);
                $buffer .= 'cell c';
                $value = $this->resolveValue($context->find('column'), $context);
                $buffer .= call_user_func($this->mustache->getEscape(), $value);
                // 'lastcol' section
                $value = $context->find('lastcol');
                $buffer .= $this->section3bd2769186e9f69cc8dd0528e41a9fa6($context, $indent, $value);
                $buffer .= '"';
                // 'colspan' section
                $value = $context->find('colspan');
                $buffer .= $this->sectionFff96f817533e0f39f061349f5ea4b40($context, $indent, $value);
                // 'rowspan' section
                $value = $context->find('rowspan');
                $buffer .= $this->section47b12e09644034fc364ca6dc9b180a4c($context, $indent, $value);
                // 'cellattributes' section
                $value = $context->find('cellattributes');
                $buffer .= $this->sectionAd20463c348991d5bbd2fb97358ea7c0($context, $indent, $value);
                // 'cellstyle' section
                $value = $context->find('cellstyle');
                $buffer .= $this->sectionB62667187cbb96c603554e151f6837e7($context, $indent, $value);
                // 'cellid' section
                $value = $context->find('cellid');
                $buffer .= $this->section22ab3923ba2cfc3cff171c9ace86b5d6($context, $indent, $value);
                // 'abbr' section
                $value = $context->find('abbr');
                $buffer .= $this->section4e379994995cbedbebbd78954cb0dca2($context, $indent, $value);
                // 'cellscope' section
                $value = $context->find('cellscope');
                $buffer .= $this->sectionA72ac76839b11e73a4bb394f48fb93d0($context, $indent, $value);
                $buffer .= '>';
                $value = $this->resolveValue($context->find('content'), $context);
                $buffer .= $value;
                $buffer .= '</th>
';
                $context->pop();
            }
        }
    
        return $buffer;
    }

    private function section3b3f685240e923951a45659c2f77f0e7(Mustache_Context $context, $indent, $value)
    {
        $buffer = '';
    
        if (!is_string($value) && is_callable($value)) {
            $source = '
            {{^header}}
            <td class="{{#cellclasses}}{{.}} {{/cellclasses}}cell c{{column}}{{#lastcol}} lastcol{{/lastcol}}"{{#colspan}} colspan="{{.}}"{{/colspan}}{{#rowspan}} rowspan="{{.}}"{{/rowspan}}{{#cellattributes}} {{name}}="{{value}}"{{/cellattributes}}{{#cellstyle}} style="{{.}}"{{/cellstyle}}{{#cellid}} id="{{.}}"{{/cellid}}{{#abbr}} abbr="{{.}}"{{/abbr}}{{#cellscope}} scope="{{.}}"{{/cellscope}}>{{{content}}}</td>
            {{/header}}
            {{#header}}
            <th class="{{#cellclasses}}{{.}} {{/cellclasses}}cell c{{column}}{{#lastcol}} lastcol{{/lastcol}}"{{#colspan}} colspan="{{.}}"{{/colspan}}{{#rowspan}} rowspan="{{.}}"{{/rowspan}}{{#cellattributes}} {{name}}="{{value}}"{{/cellattributes}}{{#cellstyle}} style="{{.}}"{{/cellstyle}}{{#cellid}} id="{{.}}"{{/cellid}}{{#abbr}} abbr="{{.}}"{{/abbr}}{{#cellscope}} scope="{{.}}"{{/cellscope}}>{{{content}}}</th>
            {{/header}}
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
                
                // 'header' inverted section
                $value = $context->find('header');
                if (empty($value)) {
                    
                    $buffer .= $indent . '            <td class="';
                    // 'cellclasses' section
                    $value = $context->find('cellclasses');
                    $buffer .= $this->section5e96ec75439305fc88c78e77946e47bb($context, $indent, $value);
                    $buffer .= 'cell c';
                    $value = $this->resolveValue($context->find('column'), $context);
                    $buffer .= call_user_func($this->mustache->getEscape(), $value);
                    // 'lastcol' section
                    $value = $context->find('lastcol');
                    $buffer .= $this->section3bd2769186e9f69cc8dd0528e41a9fa6($context, $indent, $value);
                    $buffer .= '"';
                    // 'colspan' section
                    $value = $context->find('colspan');
                    $buffer .= $this->sectionFff96f817533e0f39f061349f5ea4b40($context, $indent, $value);
                    // 'rowspan' section
                    $value = $context->find('rowspan');
                    $buffer .= $this->section47b12e09644034fc364ca6dc9b180a4c($context, $indent, $value);
                    // 'cellattributes' section
                    $value = $context->find('cellattributes');
                    $buffer .= $this->sectionAd20463c348991d5bbd2fb97358ea7c0($context, $indent, $value);
                    // 'cellstyle' section
                    $value = $context->find('cellstyle');
                    $buffer .= $this->sectionB62667187cbb96c603554e151f6837e7($context, $indent, $value);
                    // 'cellid' section
                    $value = $context->find('cellid');
                    $buffer .= $this->section22ab3923ba2cfc3cff171c9ace86b5d6($context, $indent, $value);
                    // 'abbr' section
                    $value = $context->find('abbr');
                    $buffer .= $this->section4e379994995cbedbebbd78954cb0dca2($context, $indent, $value);
                    // 'cellscope' section
                    $value = $context->find('cellscope');
                    $buffer .= $this->sectionA72ac76839b11e73a4bb394f48fb93d0($context, $indent, $value);
                    $buffer .= '>';
                    $value = $this->resolveValue($context->find('content'), $context);
                    $buffer .= $value;
                    $buffer .= '</td>
';
                }
                // 'header' section
                $value = $context->find('header');
                $buffer .= $this->section918dfeba5292e5d22a65337bd97355a1($context, $indent, $value);
                $context->pop();
            }
        }
    
        return $buffer;
    }

    private function sectionD4e1cdddfbb284e4322f05094bcaceee(Mustache_Context $context, $indent, $value)
    {
        $buffer = '';
    
        if (!is_string($value) && is_callable($value)) {
            $source = '
        {{#divider}}
        </tbody><tbody>
        {{/divider}}
        <tr class="{{#rowclasses}}{{.}} {{/rowclasses}}{{#lastrow}}lastrow{{/lastrow}}"{{#rowattributes}} {{name}}="{{value}}"{{/rowattributes}}{{#rowid}} id="{{.}}"{{/rowid}}{{#rowstyle}} style="{{.}}"{{/rowstyle}}>
        {{#cells}}
            {{^header}}
            <td class="{{#cellclasses}}{{.}} {{/cellclasses}}cell c{{column}}{{#lastcol}} lastcol{{/lastcol}}"{{#colspan}} colspan="{{.}}"{{/colspan}}{{#rowspan}} rowspan="{{.}}"{{/rowspan}}{{#cellattributes}} {{name}}="{{value}}"{{/cellattributes}}{{#cellstyle}} style="{{.}}"{{/cellstyle}}{{#cellid}} id="{{.}}"{{/cellid}}{{#abbr}} abbr="{{.}}"{{/abbr}}{{#cellscope}} scope="{{.}}"{{/cellscope}}>{{{content}}}</td>
            {{/header}}
            {{#header}}
            <th class="{{#cellclasses}}{{.}} {{/cellclasses}}cell c{{column}}{{#lastcol}} lastcol{{/lastcol}}"{{#colspan}} colspan="{{.}}"{{/colspan}}{{#rowspan}} rowspan="{{.}}"{{/rowspan}}{{#cellattributes}} {{name}}="{{value}}"{{/cellattributes}}{{#cellstyle}} style="{{.}}"{{/cellstyle}}{{#cellid}} id="{{.}}"{{/cellid}}{{#abbr}} abbr="{{.}}"{{/abbr}}{{#cellscope}} scope="{{.}}"{{/cellscope}}>{{{content}}}</th>
            {{/header}}
        {{/cells}}
        </tr>
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
                
                // 'divider' section
                $value = $context->find('divider');
                $buffer .= $this->section497aee2f22a12194a04839fcc03310e6($context, $indent, $value);
                $buffer .= $indent . '        <tr class="';
                // 'rowclasses' section
                $value = $context->find('rowclasses');
                $buffer .= $this->section5e96ec75439305fc88c78e77946e47bb($context, $indent, $value);
                // 'lastrow' section
                $value = $context->find('lastrow');
                $buffer .= $this->sectionBa05800bf0c36bf123cddd7438906a13($context, $indent, $value);
                $buffer .= '"';
                // 'rowattributes' section
                $value = $context->find('rowattributes');
                $buffer .= $this->sectionAd20463c348991d5bbd2fb97358ea7c0($context, $indent, $value);
                // 'rowid' section
                $value = $context->find('rowid');
                $buffer .= $this->section22ab3923ba2cfc3cff171c9ace86b5d6($context, $indent, $value);
                // 'rowstyle' section
                $value = $context->find('rowstyle');
                $buffer .= $this->sectionB62667187cbb96c603554e151f6837e7($context, $indent, $value);
                $buffer .= '>
';
                // 'cells' section
                $value = $context->find('cells');
                $buffer .= $this->section3b3f685240e923951a45659c2f77f0e7($context, $indent, $value);
                $buffer .= $indent . '        </tr>
';
                $context->pop();
            }
        }
    
        return $buffer;
    }

    private function section4cd30e56d2c4b834d8cd8906fb6e1efb(Mustache_Context $context, $indent, $value)
    {
        $buffer = '';
    
        if (!is_string($value) && is_callable($value)) {
            $source = '
<table class="{{tableclasses}}{{^tableclasses}}generaltable{{/tableclasses}}"{{#tableattributes}} {{name}}="{{value}}"{{/tableattributes}}{{#id}} id="{{.}}"{{/id}}{{#width}} width="{{.}}"{{/width}}{{#summary}} summary="{{.}}"{{/summary}}{{#cellpadding}} cellpadding="{{.}}"{{/cellpadding}}{{#cellspacing}} cellspacing="{{.}}"{{/cellspacing}} data-origin="html_table">
    {{#caption}}<caption{{#hidden}} class="accesshide"{{/hidden}}>{{{text}}}</caption>{{/caption}}
    {{#has_head}}
    <thead>
        <tr>
        {{#head}}
            {{^header}}
            <td class="header c{{column}}{{#lastcol}} lastcol{{/lastcol}}{{#cellclasses}} {{.}}{{/cellclasses}}"{{#colspan}} colspan="{{.}}"{{/colspan}}{{#rowspan}} rowspan="{{.}}"{{/rowspan}}{{#cellattributes}} {{name}}="{{value}}"{{/cellattributes}}{{#cellstyle}} style="{{.}}"{{/cellstyle}}{{#cellid}} id="{{.}}"{{/cellid}}{{#abbr}} abbr="{{.}}"{{/abbr}}{{#cellscope}} scope="{{.}}"{{/cellscope}}>{{{content}}}</td>
            {{/header}}
            {{#header}}
            <th class="header c{{column}}{{#lastcol}} lastcol{{/lastcol}}{{#cellclasses}} {{.}}{{/cellclasses}}"{{#colspan}} colspan="{{.}}"{{/colspan}}{{#rowspan}} rowspan="{{.}}"{{/rowspan}}{{#cellattributes}} {{name}}="{{value}}"{{/cellattributes}}{{#cellstyle}} style="{{.}}"{{/cellstyle}}{{#cellid}} id="{{.}}"{{/cellid}}{{#abbr}} abbr="{{.}}"{{/abbr}}{{#cellscope}} scope="{{.}}"{{/cellscope}}>{{{content}}}</th>
            {{/header}}
        {{/head}}
        </tr>
    </thead>
    {{/has_head}}
    <tbody{{^body}} class="empty"{{/body}}>
    {{#body}}
        {{#divider}}
        </tbody><tbody>
        {{/divider}}
        <tr class="{{#rowclasses}}{{.}} {{/rowclasses}}{{#lastrow}}lastrow{{/lastrow}}"{{#rowattributes}} {{name}}="{{value}}"{{/rowattributes}}{{#rowid}} id="{{.}}"{{/rowid}}{{#rowstyle}} style="{{.}}"{{/rowstyle}}>
        {{#cells}}
            {{^header}}
            <td class="{{#cellclasses}}{{.}} {{/cellclasses}}cell c{{column}}{{#lastcol}} lastcol{{/lastcol}}"{{#colspan}} colspan="{{.}}"{{/colspan}}{{#rowspan}} rowspan="{{.}}"{{/rowspan}}{{#cellattributes}} {{name}}="{{value}}"{{/cellattributes}}{{#cellstyle}} style="{{.}}"{{/cellstyle}}{{#cellid}} id="{{.}}"{{/cellid}}{{#abbr}} abbr="{{.}}"{{/abbr}}{{#cellscope}} scope="{{.}}"{{/cellscope}}>{{{content}}}</td>
            {{/header}}
            {{#header}}
            <th class="{{#cellclasses}}{{.}} {{/cellclasses}}cell c{{column}}{{#lastcol}} lastcol{{/lastcol}}"{{#colspan}} colspan="{{.}}"{{/colspan}}{{#rowspan}} rowspan="{{.}}"{{/rowspan}}{{#cellattributes}} {{name}}="{{value}}"{{/cellattributes}}{{#cellstyle}} style="{{.}}"{{/cellstyle}}{{#cellid}} id="{{.}}"{{/cellid}}{{#abbr}} abbr="{{.}}"{{/abbr}}{{#cellscope}} scope="{{.}}"{{/cellscope}}>{{{content}}}</th>
            {{/header}}
        {{/cells}}
        </tr>
    {{/body}}
    {{^body}}
        <td colspan="{{columns}}"></td>
    {{/body}}
    </tbody>
</table>
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
                
                $buffer .= $indent . '<table class="';
                $value = $this->resolveValue($context->find('tableclasses'), $context);
                $buffer .= call_user_func($this->mustache->getEscape(), $value);
                // 'tableclasses' inverted section
                $value = $context->find('tableclasses');
                if (empty($value)) {
                    
                    $buffer .= 'generaltable';
                }
                $buffer .= '"';
                // 'tableattributes' section
                $value = $context->find('tableattributes');
                $buffer .= $this->sectionAd20463c348991d5bbd2fb97358ea7c0($context, $indent, $value);
                // 'id' section
                $value = $context->find('id');
                $buffer .= $this->section22ab3923ba2cfc3cff171c9ace86b5d6($context, $indent, $value);
                // 'width' section
                $value = $context->find('width');
                $buffer .= $this->section9a3e7930dd3649baf4286b9eb2a01a5d($context, $indent, $value);
                // 'summary' section
                $value = $context->find('summary');
                $buffer .= $this->section280628ddb7813afe7e0ce26665347350($context, $indent, $value);
                // 'cellpadding' section
                $value = $context->find('cellpadding');
                $buffer .= $this->sectionCadc3ae82e56711a52457783cf34194e($context, $indent, $value);
                // 'cellspacing' section
                $value = $context->find('cellspacing');
                $buffer .= $this->section758316d313916b40f789b3a164183f5a($context, $indent, $value);
                $buffer .= ' data-origin="html_table">
';
                $buffer .= $indent . '    ';
                // 'caption' section
                $value = $context->find('caption');
                $buffer .= $this->sectionE0a35a071d1acb1c04428277eb25bc30($context, $indent, $value);
                $buffer .= '
';
                // 'has_head' section
                $value = $context->find('has_head');
                $buffer .= $this->section03321cd91f7b6c0c64ada442937502d1($context, $indent, $value);
                $buffer .= $indent . '    <tbody';
                // 'body' inverted section
                $value = $context->find('body');
                if (empty($value)) {
                    
                    $buffer .= ' class="empty"';
                }
                $buffer .= '>
';
                // 'body' section
                $value = $context->find('body');
                $buffer .= $this->sectionD4e1cdddfbb284e4322f05094bcaceee($context, $indent, $value);
                // 'body' inverted section
                $value = $context->find('body');
                if (empty($value)) {
                    
                    $buffer .= $indent . '        <td colspan="';
                    $value = $this->resolveValue($context->find('columns'), $context);
                    $buffer .= call_user_func($this->mustache->getEscape(), $value);
                    $buffer .= '"></td>
';
                }
                $buffer .= $indent . '    </tbody>
';
                $buffer .= $indent . '</table>
';
                $context->pop();
            }
        }
    
        return $buffer;
    }

}
