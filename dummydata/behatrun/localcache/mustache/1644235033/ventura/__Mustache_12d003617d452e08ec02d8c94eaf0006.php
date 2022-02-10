<?php

class __Mustache_12d003617d452e08ec02d8c94eaf0006 extends Mustache_Template
{
    private $lambdaHelper;

    public function renderInternal(Mustache_Context $context, $indent = '')
    {
        $this->lambdaHelper = new Mustache_LambdaHelper($this->mustache, $context);
        $buffer = '';

        $buffer .= $indent . '<form action="';
        $value = $this->resolveValue($context->find('actionurl'), $context);
        $buffer .= call_user_func($this->mustache->getEscape(), $value);
        $buffer .= '" method="post" id="adminsettings" autocomplete="off">
';
        $buffer .= $indent . '    <div>
';
        $buffer .= $indent . '        <input type="hidden" name="sesskey" value="';
        $value = $this->resolveValue($context->find('sesskey'), $context);
        $buffer .= call_user_func($this->mustache->getEscape(), $value);
        $buffer .= '">
';
        $buffer .= $indent . '        <input type="hidden" name="action" value="save-settings">
';
        $buffer .= $indent . '    </div>
';
        $buffer .= $indent . '    <fieldset>
';
        $buffer .= $indent . '        <div class="clearer"></div>
';
        // 'hasresults' section
        $value = $context->find('hasresults');
        $buffer .= $this->section603e49b714872ff763be5b29b91cdc81($context, $indent, $value);
        // 'hasresults' inverted section
        $value = $context->find('hasresults');
        if (empty($value)) {
            
            $buffer .= $indent . '            ';
            // 'str' section
            $value = $context->find('str');
            $buffer .= $this->section36817e26d3b370aa587ec3ea3b300e7a($context, $indent, $value);
            $buffer .= '
';
        }
        $buffer .= $indent . '    </fieldset>
';
        $buffer .= $indent . '</form>
';

        return $buffer;
    }

    private function section814f7ab309abbd3b4cf598dd0e446d9a(Mustache_Context $context, $indent, $value)
    {
        $buffer = '';
    
        if (!is_string($value) && is_callable($value)) {
            $source = 'searchresults, admin';
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
                
                $buffer .= 'searchresults, admin';
                $context->pop();
            }
        }
    
        return $buffer;
    }

    private function sectionE0591e3c7143a8ce114dde7973b9e593(Mustache_Context $context, $indent, $value)
    {
        $buffer = '';
    
        if (!is_string($value) && is_callable($value)) {
            $source = '
                        <div class="clearer"></div>
                        {{{.}}}
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
                
                $buffer .= $indent . '                        <div class="clearer"></div>
';
                $buffer .= $indent . '                        ';
                $value = $this->resolveValue($context->last(), $context);
                $buffer .= $value;
                $buffer .= '
';
                $context->pop();
            }
        }
    
        return $buffer;
    }

    private function section99c7b75fdc3a25a7cfa32a09b4239ccc(Mustache_Context $context, $indent, $value)
    {
        $buffer = '';
    
        if (!is_string($value) && is_callable($value)) {
            $source = '
                <h2 class="main">{{#str}}searchresults, admin{{/str}} - <a href="{{url}}">{{{title}}}</a></h2>
                <fieldset class="adminsettings">
                    {{#settings}}
                        <div class="clearer"></div>
                        {{{.}}}
                    {{/settings}}
                </fieldset>
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
                
                $buffer .= $indent . '                <h2 class="main">';
                // 'str' section
                $value = $context->find('str');
                $buffer .= $this->section814f7ab309abbd3b4cf598dd0e446d9a($context, $indent, $value);
                $buffer .= ' - <a href="';
                $value = $this->resolveValue($context->find('url'), $context);
                $buffer .= call_user_func($this->mustache->getEscape(), $value);
                $buffer .= '">';
                $value = $this->resolveValue($context->find('title'), $context);
                $buffer .= $value;
                $buffer .= '</a></h2>
';
                $buffer .= $indent . '                <fieldset class="adminsettings">
';
                // 'settings' section
                $value = $context->find('settings');
                $buffer .= $this->sectionE0591e3c7143a8ce114dde7973b9e593($context, $indent, $value);
                $buffer .= $indent . '                </fieldset>
';
                $context->pop();
            }
        }
    
        return $buffer;
    }

    private function sectionE5479b5825bee73d37f8a0a91fe85548(Mustache_Context $context, $indent, $value)
    {
        $buffer = '';
    
        if (!is_string($value) && is_callable($value)) {
            $source = 'savechanges, admin';
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
                
                $buffer .= 'savechanges, admin';
                $context->pop();
            }
        }
    
        return $buffer;
    }

    private function section344dbb6473e9ccfd73a71a2ae81570be(Mustache_Context $context, $indent, $value)
    {
        $buffer = '';
    
        if (!is_string($value) && is_callable($value)) {
            $source = '{{#str}}savechanges, admin{{/str}}';
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
                $buffer .= $this->sectionE5479b5825bee73d37f8a0a91fe85548($context, $indent, $value);
                $context->pop();
            }
        }
    
        return $buffer;
    }

    private function sectionCe08e94baf8f1a62a3cadd257666c032(Mustache_Context $context, $indent, $value)
    {
        $buffer = '';
    
        if (!is_string($value) && is_callable($value)) {
            $source = '
                <div class="form-buttons">
                    <input type="submit" class="form-submit" value={{#quote}}{{#str}}savechanges, admin{{/str}}{{/quote}}>
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
                
                $buffer .= $indent . '                <div class="form-buttons">
';
                $buffer .= $indent . '                    <input type="submit" class="form-submit" value=';
                // 'quote' section
                $value = $context->find('quote');
                $buffer .= $this->section344dbb6473e9ccfd73a71a2ae81570be($context, $indent, $value);
                $buffer .= '>
';
                $buffer .= $indent . '                </div>
';
                $context->pop();
            }
        }
    
        return $buffer;
    }

    private function section603e49b714872ff763be5b29b91cdc81(Mustache_Context $context, $indent, $value)
    {
        $buffer = '';
    
        if (!is_string($value) && is_callable($value)) {
            $source = '
            {{#results}}
                <h2 class="main">{{#str}}searchresults, admin{{/str}} - <a href="{{url}}">{{{title}}}</a></h2>
                <fieldset class="adminsettings">
                    {{#settings}}
                        <div class="clearer"></div>
                        {{{.}}}
                    {{/settings}}
                </fieldset>
            {{/results}}
            {{#showsave}}
                <div class="form-buttons">
                    <input type="submit" class="form-submit" value={{#quote}}{{#str}}savechanges, admin{{/str}}{{/quote}}>
                </div>
            {{/showsave}}
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
                
                // 'results' section
                $value = $context->find('results');
                $buffer .= $this->section99c7b75fdc3a25a7cfa32a09b4239ccc($context, $indent, $value);
                // 'showsave' section
                $value = $context->find('showsave');
                $buffer .= $this->sectionCe08e94baf8f1a62a3cadd257666c032($context, $indent, $value);
                $context->pop();
            }
        }
    
        return $buffer;
    }

    private function section36817e26d3b370aa587ec3ea3b300e7a(Mustache_Context $context, $indent, $value)
    {
        $buffer = '';
    
        if (!is_string($value) && is_callable($value)) {
            $source = 'noresults, admin';
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
                
                $buffer .= 'noresults, admin';
                $context->pop();
            }
        }
    
        return $buffer;
    }

}
