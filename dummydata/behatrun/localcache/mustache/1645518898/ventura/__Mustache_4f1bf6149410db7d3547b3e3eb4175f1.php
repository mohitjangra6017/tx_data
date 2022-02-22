<?php

class __Mustache_4f1bf6149410db7d3547b3e3eb4175f1 extends Mustache_Template
{
    private $lambdaHelper;

    public function renderInternal(Mustache_Context $context, $indent = '')
    {
        $this->lambdaHelper = new Mustache_LambdaHelper($this->mustache, $context);
        $buffer = '';

        $buffer .= $indent . '<nav class="totaraNav_prim" aria-label="';
        // 'str' section
        $value = $context->find('str');
        $buffer .= $this->section0bfc9ffc07d676feed395f07fc6d98b8($context, $indent, $value);
        $buffer .= '">
';
        $buffer .= $indent . '    <div class="container-fluid">
';
        $buffer .= $indent . '
';
        $buffer .= $indent . '        ';
        // 'masthead_toggle' section
        $value = $context->find('masthead_toggle');
        $buffer .= $this->sectionE0d7063346cc5f4383d22d0d188a2867($context, $indent, $value);
        $buffer .= '
';
        $buffer .= $indent . '
';
        $buffer .= $indent . '        ';
        // 'masthead_logo' section
        $value = $context->find('masthead_logo');
        $buffer .= $this->section5521d73b8967e90f62af1c63cb5bd8e0($context, $indent, $value);
        $buffer .= '
';
        $buffer .= $indent . '
';
        $buffer .= $indent . '        <ul class="totaraNav_prim--list totaraNav_prim--list_hideMobile" role="menubar" data-tw-totaraNav-list="">
';
        $buffer .= $indent . '            <li class="totaraNav_prim--list_item" role="presentation">
';
        $buffer .= $indent . '                <a href="#" class="totaraNav_prim--list_close" data-tw-totaranav-list-close="">
';
        $buffer .= $indent . '                    ';
        $value = $this->resolveValue($context->find('close_menu_icon'), $context);
        $buffer .= $value;
        $buffer .= '
';
        $buffer .= $indent . '                </a>
';
        $buffer .= $indent . '            </li>';
        // 'menuitems' section
        $value = $context->find('menuitems');
        $buffer .= $this->section1b62c225138df65b1d36200fad34bd87($context, $indent, $value);
        $buffer .= '
';
        $buffer .= $indent . '        </ul>
';
        $buffer .= $indent . '
';
        $buffer .= $indent . '        <div class="totaraNav_prim--side">
';
        $buffer .= $indent . '            ';
        // 'masthead_quickaccessmenu' section
        $value = $context->find('masthead_quickaccessmenu');
        $buffer .= $this->section3341a7e33a07961f7267a734029a3c6a($context, $indent, $value);
        $buffer .= '
';
        $buffer .= $indent . '
';
        $buffer .= $indent . '            <div class="totaraNav_prim--side__separator"></div>
';
        $buffer .= $indent . '
';
        $buffer .= $indent . '            ';
        // 'masthead_lang' section
        $value = $context->find('masthead_lang');
        $buffer .= $this->section6015f5c740837cd77b84dbefb6f80f4f($context, $indent, $value);
        $buffer .= '
';
        $buffer .= $indent . '
';
        $buffer .= $indent . '            ';
        // 'masthead_plugins' section
        $value = $context->find('masthead_plugins');
        $buffer .= $this->section7e47fd838360706bd54579957f9f374a($context, $indent, $value);
        $buffer .= '
';
        $buffer .= $indent . '
';
        $buffer .= $indent . '            ';
        // 'masthead_usermenu' section
        $value = $context->find('masthead_usermenu');
        $buffer .= $this->section7c3b9a3ffbb0b1dc0ee003181183ded7($context, $indent, $value);
        $buffer .= '
';
        $buffer .= $indent . '        </div>
';
        $buffer .= $indent . '    </div>
';
        $buffer .= $indent . '</nav>
';

        return $buffer;
    }

    private function section0bfc9ffc07d676feed395f07fc6d98b8(Mustache_Context $context, $indent, $value)
    {
        $buffer = '';
    
        if (!is_string($value) && is_callable($value)) {
            $source = 'primarynavigation, totara_core';
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
                
                $buffer .= 'primarynavigation, totara_core';
                $context->pop();
            }
        }
    
        return $buffer;
    }

    private function sectionE0d7063346cc5f4383d22d0d188a2867(Mustache_Context $context, $indent, $value)
    {
        $buffer = '';
    
        if (!is_string($value) && is_callable($value)) {
            $source = '{{> totara_core/nav_toggle }}';
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
                $partialstr = 'totara_core/nav_toggle';
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

    private function section5521d73b8967e90f62af1c63cb5bd8e0(Mustache_Context $context, $indent, $value)
    {
        $buffer = '';
    
        if (!is_string($value) && is_callable($value)) {
            $source = '{{> totara_core/masthead_logo }}';
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
                $partialstr = 'totara_core/masthead_logo';
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

    private function section4cc2c568df0471f29881cefa8bc40963(Mustache_Context $context, $indent, $value)
    {
        $buffer = '';
    
        if (!is_string($value) && is_callable($value)) {
            $source = ' totaraNav_prim--list_item_hasChildren';
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
                
                $buffer .= ' totaraNav_prim--list_item_hasChildren';
                $context->pop();
            }
        }
    
        return $buffer;
    }

    private function section7e66f183e6537421b345179e60c01917(Mustache_Context $context, $indent, $value)
    {
        $buffer = '';
    
        if (!is_string($value) && is_callable($value)) {
            $source = ' totaraNav_prim--list_item_externalLink';
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
                
                $buffer .= ' totaraNav_prim--list_item_externalLink';
                $context->pop();
            }
        }
    
        return $buffer;
    }

    private function section54710de7dd87ab62820cfafda71e2243(Mustache_Context $context, $indent, $value)
    {
        $buffer = '';
    
        if (!is_string($value) && is_callable($value)) {
            $source = ' totaraNav_prim--list_item_selected';
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
                
                $buffer .= ' totaraNav_prim--list_item_selected';
                $context->pop();
            }
        }
    
        return $buffer;
    }

    private function sectionC0749975951bb121b1d3ed8be25e3248(Mustache_Context $context, $indent, $value)
    {
        $buffer = '';
    
        if (!is_string($value) && is_callable($value)) {
            $source = ' data-tw-totaraNav-hasChildren=""';
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
                
                $buffer .= ' data-tw-totaraNav-hasChildren=""';
                $context->pop();
            }
        }
    
        return $buffer;
    }

    private function section436c147124ca51714262729e76ce7dda(Mustache_Context $context, $indent, $value)
    {
        $buffer = '';
    
        if (!is_string($value) && is_callable($value)) {
            $source = 'target="{{.}}" rel="noopener"';
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
                
                $buffer .= 'target="';
                $value = $this->resolveValue($context->last(), $context);
                $buffer .= call_user_func($this->mustache->getEscape(), $value);
                $buffer .= '" rel="noopener"';
                $context->pop();
            }
        }
    
        return $buffer;
    }

    private function section33eaa4f0240563b63872c03b95d5e9fd(Mustache_Context $context, $indent, $value)
    {
        $buffer = '';
    
        if (!is_string($value) && is_callable($value)) {
            $source = ' href="#" aria-haspopup="true" aria-expanded="false"';
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
                
                $buffer .= ' href="#" aria-haspopup="true" aria-expanded="false"';
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

    private function sectionE9b89e0dac1c2c3a333124a8fc0cc08d(Mustache_Context $context, $indent, $value)
    {
        $buffer = '';
    
        if (!is_string($value) && is_callable($value)) {
            $source = '
                    {{> totara_core/nav_expand }}
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
                $partialstr = 'totara_core/nav_expand';
                if (strpos($partialstr, '&&') === 0) {
                    $partialstr = $context->find(substr($partialstr, 2));
                }
                if ($partial = $this->mustache->loadPartial($partialstr)) {
                    $buffer .= $partial->renderInternal($context, $indent . '                    ');
                }
                $context->pop();
            }
        }
    
        return $buffer;
    }

    private function section1b62c225138df65b1d36200fad34bd87(Mustache_Context $context, $indent, $value)
    {
        $buffer = '';
    
        if (!is_string($value) && is_callable($value)) {
            $source = '{{!
          }}<li id="{{class_name}}"
                class="totaraNav_prim--list_item
                {{#haschildren}} totaraNav_prim--list_item_hasChildren{{/haschildren}}
                {{^haschildren}}{{#external_link}} totaraNav_prim--list_item_externalLink{{/external_link}}{{/haschildren}}
                {{#class_isselected}} totaraNav_prim--list_item_selected{{/class_isselected}}"
                 data-tw-totaraNav-item=""
                data-tw-totaraNav-topLevelItem=""
                {{#haschildren}} data-tw-totaraNav-hasChildren=""{{/haschildren}}
                role="presentation">
                <a class="totaraNav_prim--list_item_link" {{#target}}target="{{.}}" rel="noopener"{{/target}}
                    {{^haschildren}} href="{{url}}"{{/haschildren}}
                    {{#haschildren}} href="#" aria-haspopup="true" aria-expanded="false"{{/haschildren}}
                    role="menuitem">
                    <div class="totaraNav--expand_indent" {{#haschildren}}data-tw-totaraNav-chevron=""{{/haschildren}}></div>{{!
                  }}<div class="totaraNav_prim--list_item_label">
                        {{{linktext}}}
                        {{^haschildren}}{{#external_link}}
                            {{{external_link_icon}}}
                        {{/external_link}}{{/haschildren}}
                    </div>
                </a>
                {{#haschildren}}
                    {{> totara_core/nav_expand }}
                {{/haschildren}}
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
                
                $buffer .= '<li id="';
                $value = $this->resolveValue($context->find('class_name'), $context);
                $buffer .= call_user_func($this->mustache->getEscape(), $value);
                $buffer .= '"
';
                $buffer .= $indent . '                class="totaraNav_prim--list_item
';
                $buffer .= $indent . '                ';
                // 'haschildren' section
                $value = $context->find('haschildren');
                $buffer .= $this->section4cc2c568df0471f29881cefa8bc40963($context, $indent, $value);
                $buffer .= '
';
                $buffer .= $indent . '                ';
                // 'haschildren' inverted section
                $value = $context->find('haschildren');
                if (empty($value)) {
                    
                    // 'external_link' section
                    $value = $context->find('external_link');
                    $buffer .= $this->section7e66f183e6537421b345179e60c01917($context, $indent, $value);
                }
                $buffer .= '
';
                $buffer .= $indent . '                ';
                // 'class_isselected' section
                $value = $context->find('class_isselected');
                $buffer .= $this->section54710de7dd87ab62820cfafda71e2243($context, $indent, $value);
                $buffer .= '"
';
                $buffer .= $indent . '                 data-tw-totaraNav-item=""
';
                $buffer .= $indent . '                data-tw-totaraNav-topLevelItem=""
';
                $buffer .= $indent . '                ';
                // 'haschildren' section
                $value = $context->find('haschildren');
                $buffer .= $this->sectionC0749975951bb121b1d3ed8be25e3248($context, $indent, $value);
                $buffer .= '
';
                $buffer .= $indent . '                role="presentation">
';
                $buffer .= $indent . '                <a class="totaraNav_prim--list_item_link" ';
                // 'target' section
                $value = $context->find('target');
                $buffer .= $this->section436c147124ca51714262729e76ce7dda($context, $indent, $value);
                $buffer .= '
';
                $buffer .= $indent . '                    ';
                // 'haschildren' inverted section
                $value = $context->find('haschildren');
                if (empty($value)) {
                    
                    $buffer .= ' href="';
                    $value = $this->resolveValue($context->find('url'), $context);
                    $buffer .= call_user_func($this->mustache->getEscape(), $value);
                    $buffer .= '"';
                }
                $buffer .= '
';
                $buffer .= $indent . '                    ';
                // 'haschildren' section
                $value = $context->find('haschildren');
                $buffer .= $this->section33eaa4f0240563b63872c03b95d5e9fd($context, $indent, $value);
                $buffer .= '
';
                $buffer .= $indent . '                    role="menuitem">
';
                $buffer .= $indent . '                    <div class="totaraNav--expand_indent" ';
                // 'haschildren' section
                $value = $context->find('haschildren');
                $buffer .= $this->section0ffe285672783e897ead5fd5dfdd1159($context, $indent, $value);
                $buffer .= '></div>';
                $buffer .= '<div class="totaraNav_prim--list_item_label">
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
                // 'haschildren' section
                $value = $context->find('haschildren');
                $buffer .= $this->sectionE9b89e0dac1c2c3a333124a8fc0cc08d($context, $indent, $value);
                $buffer .= $indent . '            </li>';
                $context->pop();
            }
        }
    
        return $buffer;
    }

    private function section3341a7e33a07961f7267a734029a3c6a(Mustache_Context $context, $indent, $value)
    {
        $buffer = '';
    
        if (!is_string($value) && is_callable($value)) {
            $source = '{{> totara_core/quickaccessmenu }}';
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
                $partialstr = 'totara_core/quickaccessmenu';
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

    private function section6015f5c740837cd77b84dbefb6f80f4f(Mustache_Context $context, $indent, $value)
    {
        $buffer = '';
    
        if (!is_string($value) && is_callable($value)) {
            $source = '{{> totara_core/masthead_lang }}';
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
                $partialstr = 'totara_core/masthead_lang';
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

    private function section7e47fd838360706bd54579957f9f374a(Mustache_Context $context, $indent, $value)
    {
        $buffer = '';
    
        if (!is_string($value) && is_callable($value)) {
            $source = '{{> totara_core/masthead_plugins }}';
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
                $partialstr = 'totara_core/masthead_plugins';
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

    private function section7c3b9a3ffbb0b1dc0ee003181183ded7(Mustache_Context $context, $indent, $value)
    {
        $buffer = '';
    
        if (!is_string($value) && is_callable($value)) {
            $source = '{{> totara_core/masthead_usermenu }}';
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
                $partialstr = 'totara_core/masthead_usermenu';
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
