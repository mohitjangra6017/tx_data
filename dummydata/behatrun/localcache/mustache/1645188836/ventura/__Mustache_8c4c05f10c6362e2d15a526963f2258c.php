<?php

class __Mustache_8c4c05f10c6362e2d15a526963f2258c extends Mustache_Template
{
    private $lambdaHelper;

    public function renderInternal(Mustache_Context $context, $indent = '')
    {
        $this->lambdaHelper = new Mustache_LambdaHelper($this->mustache, $context);
        $buffer = '';

        $buffer .= $indent . '
';
        $buffer .= $indent . '<nav class="totaraNav_sub" aria-label="';
        // 'str' section
        $value = $context->find('str');
        $buffer .= $this->section212bff1a464048bbde13c2c589a9ec80($context, $indent, $value);
        $buffer .= '">
';
        $buffer .= $indent . '    <div class="container-fluid">
';
        $buffer .= $indent . '        <ul class="totaraNav_sub--list" role="menubar">
';
        $buffer .= $indent . '            ';
        // 'subnav' section
        $value = $context->find('subnav');
        $buffer .= $this->section4d36d8b86492c89cc592a11bbfaae109($context, $indent, $value);
        $buffer .= '
';
        $buffer .= $indent . '        </ul>
';
        $buffer .= $indent . '    </div>
';
        $buffer .= $indent . '</nav>
';

        return $buffer;
    }

    private function section212bff1a464048bbde13c2c589a9ec80(Mustache_Context $context, $indent, $value)
    {
        $buffer = '';
    
        if (!is_string($value) && is_callable($value)) {
            $source = 'subnavigation, totara_core';
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
                
                $buffer .= 'subnavigation, totara_core';
                $context->pop();
            }
        }
    
        return $buffer;
    }

    private function sectionA9feff5fca6d0562711e4eb60f49a197(Mustache_Context $context, $indent, $value)
    {
        $buffer = '';
    
        if (!is_string($value) && is_callable($value)) {
            $source = ' totaraNav_sub--list_item_hasChildren';
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
                
                $buffer .= ' totaraNav_sub--list_item_hasChildren';
                $context->pop();
            }
        }
    
        return $buffer;
    }

    private function sectionAb28d2a2383432a436eeee4a684cb897(Mustache_Context $context, $indent, $value)
    {
        $buffer = '';
    
        if (!is_string($value) && is_callable($value)) {
            $source = ' totaraNav_sub--list_item_externalLink';
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
                
                $buffer .= ' totaraNav_sub--list_item_externalLink';
                $context->pop();
            }
        }
    
        return $buffer;
    }

    private function sectionB9eec1c359fcb902aa440988bbd9c93d(Mustache_Context $context, $indent, $value)
    {
        $buffer = '';
    
        if (!is_string($value) && is_callable($value)) {
            $source = ' totaraNav_sub--list_item_selected';
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
                
                $buffer .= ' totaraNav_sub--list_item_selected';
                $context->pop();
            }
        }
    
        return $buffer;
    }

    private function section8bf01cb3447a1ff6797bcb21156f628e(Mustache_Context $context, $indent, $value)
    {
        $buffer = '';
    
        if (!is_string($value) && is_callable($value)) {
            $source = ' totaraNav_sub--list_item_childSelected';
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
                
                $buffer .= ' totaraNav_sub--list_item_childSelected';
                $context->pop();
            }
        }
    
        return $buffer;
    }

    private function sectionD3ba4bfbe009b36dd41fb39266786c11(Mustache_Context $context, $indent, $value)
    {
        $buffer = '';
    
        if (!is_string($value) && is_callable($value)) {
            $source = ' data-tw-totaraNav-hasChildren="true"';
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
                
                $buffer .= ' data-tw-totaraNav-hasChildren="true"';
                $context->pop();
            }
        }
    
        return $buffer;
    }

    private function section0e55b27ad044ba057d09ff050183ebe0(Mustache_Context $context, $indent, $value)
    {
        $buffer = '';
    
        if (!is_string($value) && is_callable($value)) {
            $source = ' aria-haspopup="true" aria-expanded="false"';
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
                
                $buffer .= ' aria-haspopup="true" aria-expanded="false"';
                $context->pop();
            }
        }
    
        return $buffer;
    }

    private function section0ffe285672783e897ead5fd5dfdd1159(Mustache_Context $context, $indent, $value)
    {
        $buffer = '';
    
        if (!is_string($value) && is_callable($value)) {
            $source = 'data-tw-totaraNav-chevron=""';
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
                
                $buffer .= 'data-tw-totaraNav-chevron=""';
                $context->pop();
            }
        }
    
        return $buffer;
    }

    private function section1260b1f01412386d617ae6a87b4cec59(Mustache_Context $context, $indent, $value)
    {
        $buffer = '';
    
        if (!is_string($value) && is_callable($value)) {
            $source = '
                            {{{external_link_icon}}}
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
                
                $buffer .= '
';
                $buffer .= $indent . '                            ';
                $value = $this->resolveValue($context->find('external_link_icon'), $context);
                $buffer .= $value;
                $buffer .= '
';
                $buffer .= $indent . '                        ';
                $context->pop();
            }
        }
    
        return $buffer;
    }

    private function section5b57b6aa1df7a2533379f1ee7c32a8cd(Mustache_Context $context, $indent, $value)
    {
        $buffer = '';
    
        if (!is_string($value) && is_callable($value)) {
            $source = '{{> totara_core/nav_expand }}';
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
                $partialstr = 'totara_core/nav_expand';
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

    private function section4d36d8b86492c89cc592a11bbfaae109(Mustache_Context $context, $indent, $value)
    {
        $buffer = '';
    
        if (!is_string($value) && is_callable($value)) {
            $source = '{{!
          }}<li id="sub{{class_name}}"
                class="totaraNav_sub--list_item
                {{#haschildren}} totaraNav_sub--list_item_hasChildren{{/haschildren}}
                {{^haschildren}}{{#external_link}} totaraNav_sub--list_item_externalLink{{/external_link}}{{/haschildren}}
                {{#class_isselected}} totaraNav_sub--list_item_selected{{/class_isselected}}
                {{#class_child_isselected}} totaraNav_sub--list_item_childSelected{{/class_child_isselected}}"
                 data-tw-totaraNav-item="true"
                {{#haschildren}} data-tw-totaraNav-hasChildren="true"{{/haschildren}}
                role="presentation">
                <a href="{{url}}" class="totaraNav_sub--list_item_link" role="menuitem" tabindex="0" target="{{target}}"
                    {{#haschildren}} aria-haspopup="true" aria-expanded="false"{{/haschildren}}>
                    <div class="totaraNav--expand_indent" {{#haschildren}}data-tw-totaraNav-chevron=""{{/haschildren}}></div>{{!
                  }}<div class="totaraNav_sub--list_item_label">
                        {{{linktext}}}
                        {{^haschildren}}{{#external_link}}
                            {{{external_link_icon}}}
                        {{/external_link}}{{/haschildren}}
                    </div>
                </a>
                {{#haschildren}}{{> totara_core/nav_expand }}{{/haschildren}}
            </li>{{!
          }}';
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
                
                $buffer .= '<li id="sub';
                $value = $this->resolveValue($context->find('class_name'), $context);
                $buffer .= call_user_func($this->mustache->getEscape(), $value);
                $buffer .= '"
';
                $buffer .= $indent . '                class="totaraNav_sub--list_item
';
                $buffer .= $indent . '                ';
                // 'haschildren' section
                $value = $context->find('haschildren');
                $buffer .= $this->sectionA9feff5fca6d0562711e4eb60f49a197($context, $indent, $value);
                $buffer .= '
';
                $buffer .= $indent . '                ';
                // 'haschildren' inverted section
                $value = $context->find('haschildren');
                if (empty($value)) {
                    
                    // 'external_link' section
                    $value = $context->find('external_link');
                    $buffer .= $this->sectionAb28d2a2383432a436eeee4a684cb897($context, $indent, $value);
                }
                $buffer .= '
';
                $buffer .= $indent . '                ';
                // 'class_isselected' section
                $value = $context->find('class_isselected');
                $buffer .= $this->sectionB9eec1c359fcb902aa440988bbd9c93d($context, $indent, $value);
                $buffer .= '
';
                $buffer .= $indent . '                ';
                // 'class_child_isselected' section
                $value = $context->find('class_child_isselected');
                $buffer .= $this->section8bf01cb3447a1ff6797bcb21156f628e($context, $indent, $value);
                $buffer .= '"
';
                $buffer .= $indent . '                 data-tw-totaraNav-item="true"
';
                $buffer .= $indent . '                ';
                // 'haschildren' section
                $value = $context->find('haschildren');
                $buffer .= $this->sectionD3ba4bfbe009b36dd41fb39266786c11($context, $indent, $value);
                $buffer .= '
';
                $buffer .= $indent . '                role="presentation">
';
                $buffer .= $indent . '                <a href="';
                $value = $this->resolveValue($context->find('url'), $context);
                $buffer .= call_user_func($this->mustache->getEscape(), $value);
                $buffer .= '" class="totaraNav_sub--list_item_link" role="menuitem" tabindex="0" target="';
                $value = $this->resolveValue($context->find('target'), $context);
                $buffer .= call_user_func($this->mustache->getEscape(), $value);
                $buffer .= '"
';
                $buffer .= $indent . '                    ';
                // 'haschildren' section
                $value = $context->find('haschildren');
                $buffer .= $this->section0e55b27ad044ba057d09ff050183ebe0($context, $indent, $value);
                $buffer .= '>
';
                $buffer .= $indent . '                    <div class="totaraNav--expand_indent" ';
                // 'haschildren' section
                $value = $context->find('haschildren');
                $buffer .= $this->section0ffe285672783e897ead5fd5dfdd1159($context, $indent, $value);
                $buffer .= '></div>';
                $buffer .= '<div class="totaraNav_sub--list_item_label">
';
                $buffer .= $indent . '                        ';
                $value = $this->resolveValue($context->find('linktext'), $context);
                $buffer .= $value;
                $buffer .= '
';
                $buffer .= $indent . '                        ';
                // 'haschildren' inverted section
                $value = $context->find('haschildren');
                if (empty($value)) {
                    
                    // 'external_link' section
                    $value = $context->find('external_link');
                    $buffer .= $this->section1260b1f01412386d617ae6a87b4cec59($context, $indent, $value);
                }
                $buffer .= '
';
                $buffer .= $indent . '                    </div>
';
                $buffer .= $indent . '                </a>
';
                $buffer .= $indent . '                ';
                // 'haschildren' section
                $value = $context->find('haschildren');
                $buffer .= $this->section5b57b6aa1df7a2533379f1ee7c32a8cd($context, $indent, $value);
                $buffer .= '
';
                $buffer .= $indent . '            </li>';
                $context->pop();
            }
        }
    
        return $buffer;
    }

}
