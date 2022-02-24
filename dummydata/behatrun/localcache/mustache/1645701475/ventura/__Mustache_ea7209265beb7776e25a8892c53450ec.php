<?php

class __Mustache_ea7209265beb7776e25a8892c53450ec extends Mustache_Template
{
    private $lambdaHelper;

    public function renderInternal(Mustache_Context $context, $indent = '')
    {
        $this->lambdaHelper = new Mustache_LambdaHelper($this->mustache, $context);
        $buffer = '';

        $buffer .= $indent . '<div class="totara-job-management-listing" data-enhance="job-management-listing" data-enhanced="false" data-userid="';
        $value = $this->resolveValue($context->find('userid'), $context);
        $buffer .= call_user_func($this->mustache->getEscape(), $value);
        $buffer .= '" data-jobcount="';
        $value = $this->resolveValue($context->find('jobcount'), $context);
        $buffer .= call_user_func($this->mustache->getEscape(), $value);
        $buffer .= '" data-canedit="';
        $value = $this->resolveValue($context->find('canedit'), $context);
        $buffer .= call_user_func($this->mustache->getEscape(), $value);
        $buffer .= '" data-allowmultiple="';
        $value = $this->resolveValue($context->find('allowmultiple'), $context);
        $buffer .= call_user_func($this->mustache->getEscape(), $value);
        $buffer .= '">
';
        // 'hasjobs' section
        $value = $context->find('hasjobs');
        $buffer .= $this->section3859c31381a170bbd832efc5c05012a9($context, $indent, $value);
        $buffer .= $indent . '    <div class="nojobassignments">';
        // 'str' section
        $value = $context->find('str');
        $buffer .= $this->section840184acc51cfc30a10ff29f5e04f79b($context, $indent, $value);
        $buffer .= '</div>
';
        $buffer .= $indent . '    ';
        // 'canadd' section
        $value = $context->find('canadd');
        $buffer .= $this->section36ce2984235fb94e4a597db566868383($context, $indent, $value);
        $buffer .= '
';
        $buffer .= $indent . '</div>
';
        // 'js' section
        $value = $context->find('js');
        $buffer .= $this->section14c108b8ba30d510a188344a7c0f2f23($context, $indent, $value);

        return $buffer;
    }

    private function section86d8d21838d24aef571781844a601fd8(Mustache_Context $context, $indent, $value)
    {
        $buffer = '';
    
        if (!is_string($value) && is_callable($value)) {
            $source = 'editjobassignment,totara_job';
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
                
                $buffer .= 'editjobassignment,totara_job';
                $context->pop();
            }
        }
    
        return $buffer;
    }

    private function section4735fa3a4188109f9137dfc920956a9c(Mustache_Context $context, $indent, $value)
    {
        $buffer = '';
    
        if (!is_string($value) && is_callable($value)) {
            $source = 'movedown,core';
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
                
                $buffer .= 'movedown,core';
                $context->pop();
            }
        }
    
        return $buffer;
    }

    private function section40d20d49ebc9007161741884d591f552(Mustache_Context $context, $indent, $value)
    {
        $buffer = '';
    
        if (!is_string($value) && is_callable($value)) {
            $source = '{{> &&template}}';
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
                    $buffer .= $partial->renderInternal($context);
                }
                $context->pop();
            }
        }
    
        return $buffer;
    }

    private function sectionA78390ddeae1ab407c9fefbce788a2a3(Mustache_Context $context, $indent, $value)
    {
        $buffer = '';
    
        if (!is_string($value) && is_callable($value)) {
            $source = '{{#context}}{{> &&template}}{{/context}}';
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
                
                // 'context' section
                $value = $context->find('context');
                $buffer .= $this->section40d20d49ebc9007161741884d591f552($context, $indent, $value);
                $context->pop();
            }
        }
    
        return $buffer;
    }

    private function section50e83a9ba1bd24044b6a7d03d3cbe8ab(Mustache_Context $context, $indent, $value)
    {
        $buffer = '';
    
        if (!is_string($value) && is_callable($value)) {
            $source = 'moveup,core';
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
                
                $buffer .= 'moveup,core';
                $context->pop();
            }
        }
    
        return $buffer;
    }

    private function section02d632055cc1bc3f83a364a0931769f0(Mustache_Context $context, $indent, $value)
    {
        $buffer = '';
    
        if (!is_string($value) && is_callable($value)) {
            $source = 'delete,core';
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
                
                $buffer .= 'delete,core';
                $context->pop();
            }
        }
    
        return $buffer;
    }

    private function sectionBe8bd86454c6349f417e6b73613d4c6d(Mustache_Context $context, $indent, $value)
    {
        $buffer = '';
    
        if (!is_string($value) && is_callable($value)) {
            $source = '<span class="pull-right job-actions"><a href="#" data-action="down" title="{{#str}}movedown,core{{/str}}">{{#icon_movedown}}{{#context}}{{> &&template}}{{/context}}{{/icon_movedown}}</a>
                <a href="#" data-action="up" data-function="move" title="{{#str}}moveup,core{{/str}}">{{#icon_moveup}}{{#context}}{{> &&template}}{{/context}}{{/icon_moveup}}</a>
                <a href="#" data-action="delete" data-function="move" title="{{#str}}delete,core{{/str}}">{{#icon_delete}}{{#context}}{{> &&template}}{{/context}}{{/icon_delete}}</a></span>';
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
                
                $buffer .= '<span class="pull-right job-actions"><a href="#" data-action="down" title="';
                // 'str' section
                $value = $context->find('str');
                $buffer .= $this->section4735fa3a4188109f9137dfc920956a9c($context, $indent, $value);
                $buffer .= '">';
                // 'icon_movedown' section
                $value = $context->find('icon_movedown');
                $buffer .= $this->sectionA78390ddeae1ab407c9fefbce788a2a3($context, $indent, $value);
                $buffer .= '</a>
';
                $buffer .= $indent . '                <a href="#" data-action="up" data-function="move" title="';
                // 'str' section
                $value = $context->find('str');
                $buffer .= $this->section50e83a9ba1bd24044b6a7d03d3cbe8ab($context, $indent, $value);
                $buffer .= '">';
                // 'icon_moveup' section
                $value = $context->find('icon_moveup');
                $buffer .= $this->sectionA78390ddeae1ab407c9fefbce788a2a3($context, $indent, $value);
                $buffer .= '</a>
';
                $buffer .= $indent . '                <a href="#" data-action="delete" data-function="move" title="';
                // 'str' section
                $value = $context->find('str');
                $buffer .= $this->section02d632055cc1bc3f83a364a0931769f0($context, $indent, $value);
                $buffer .= '">';
                // 'icon_delete' section
                $value = $context->find('icon_delete');
                $buffer .= $this->sectionA78390ddeae1ab407c9fefbce788a2a3($context, $indent, $value);
                $buffer .= '</a></span>';
                $context->pop();
            }
        }
    
        return $buffer;
    }

    private function sectionC46cd16631c55af67df115320e203e2a(Mustache_Context $context, $indent, $value)
    {
        $buffer = '';
    
        if (!is_string($value) && is_callable($value)) {
            $source = '
        <li><a class="editjoblink" href="{{editurl}}" title="{{#str}}editjobassignment,totara_job{{/str}}" data-id="{{id}}" data-sortorder="{{sortorder}}">{{{fullname}}}</a>
            {{#canedit}}<span class="pull-right job-actions"><a href="#" data-action="down" title="{{#str}}movedown,core{{/str}}">{{#icon_movedown}}{{#context}}{{> &&template}}{{/context}}{{/icon_movedown}}</a>
                <a href="#" data-action="up" data-function="move" title="{{#str}}moveup,core{{/str}}">{{#icon_moveup}}{{#context}}{{> &&template}}{{/context}}{{/icon_moveup}}</a>
                <a href="#" data-action="delete" data-function="move" title="{{#str}}delete,core{{/str}}">{{#icon_delete}}{{#context}}{{> &&template}}{{/context}}{{/icon_delete}}</a></span>{{/canedit}}</li>
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
                
                $buffer .= $indent . '        <li><a class="editjoblink" href="';
                $value = $this->resolveValue($context->find('editurl'), $context);
                $buffer .= call_user_func($this->mustache->getEscape(), $value);
                $buffer .= '" title="';
                // 'str' section
                $value = $context->find('str');
                $buffer .= $this->section86d8d21838d24aef571781844a601fd8($context, $indent, $value);
                $buffer .= '" data-id="';
                $value = $this->resolveValue($context->find('id'), $context);
                $buffer .= call_user_func($this->mustache->getEscape(), $value);
                $buffer .= '" data-sortorder="';
                $value = $this->resolveValue($context->find('sortorder'), $context);
                $buffer .= call_user_func($this->mustache->getEscape(), $value);
                $buffer .= '">';
                $value = $this->resolveValue($context->find('fullname'), $context);
                $buffer .= $value;
                $buffer .= '</a>
';
                $buffer .= $indent . '            ';
                // 'canedit' section
                $value = $context->find('canedit');
                $buffer .= $this->sectionBe8bd86454c6349f417e6b73613d4c6d($context, $indent, $value);
                $buffer .= '</li>
';
                $context->pop();
            }
        }
    
        return $buffer;
    }

    private function section3859c31381a170bbd832efc5c05012a9(Mustache_Context $context, $indent, $value)
    {
        $buffer = '';
    
        if (!is_string($value) && is_callable($value)) {
            $source = '
    <ul class="joblist unlist">
    {{#jobs}}
        <li><a class="editjoblink" href="{{editurl}}" title="{{#str}}editjobassignment,totara_job{{/str}}" data-id="{{id}}" data-sortorder="{{sortorder}}">{{{fullname}}}</a>
            {{#canedit}}<span class="pull-right job-actions"><a href="#" data-action="down" title="{{#str}}movedown,core{{/str}}">{{#icon_movedown}}{{#context}}{{> &&template}}{{/context}}{{/icon_movedown}}</a>
                <a href="#" data-action="up" data-function="move" title="{{#str}}moveup,core{{/str}}">{{#icon_moveup}}{{#context}}{{> &&template}}{{/context}}{{/icon_moveup}}</a>
                <a href="#" data-action="delete" data-function="move" title="{{#str}}delete,core{{/str}}">{{#icon_delete}}{{#context}}{{> &&template}}{{/context}}{{/icon_delete}}</a></span>{{/canedit}}</li>
    {{/jobs}}
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
                
                $buffer .= $indent . '    <ul class="joblist unlist">
';
                // 'jobs' section
                $value = $context->find('jobs');
                $buffer .= $this->sectionC46cd16631c55af67df115320e203e2a($context, $indent, $value);
                $buffer .= $indent . '    </ul>
';
                $context->pop();
            }
        }
    
        return $buffer;
    }

    private function section840184acc51cfc30a10ff29f5e04f79b(Mustache_Context $context, $indent, $value)
    {
        $buffer = '';
    
        if (!is_string($value) && is_callable($value)) {
            $source = 'nojobassignments,totara_job';
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
                
                $buffer .= 'nojobassignments,totara_job';
                $context->pop();
            }
        }
    
        return $buffer;
    }

    private function sectionB91509f96281b53adafaeca027ab495b(Mustache_Context $context, $indent, $value)
    {
        $buffer = '';
    
        if (!is_string($value) && is_callable($value)) {
            $source = 'jobassignmentadd,totara_job';
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
                
                $buffer .= 'jobassignmentadd,totara_job';
                $context->pop();
            }
        }
    
        return $buffer;
    }

    private function section36ce2984235fb94e4a597db566868383(Mustache_Context $context, $indent, $value)
    {
        $buffer = '';
    
        if (!is_string($value) && is_callable($value)) {
            $source = '<div class="addjobassignment"><a class="addjoblink" href="{{addurl}}" title="{{#str}}jobassignmentadd,totara_job{{/str}}">{{#str}}jobassignmentadd,totara_job{{/str}}</a></div>';
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
                
                $buffer .= '<div class="addjobassignment"><a class="addjoblink" href="';
                $value = $this->resolveValue($context->find('addurl'), $context);
                $buffer .= call_user_func($this->mustache->getEscape(), $value);
                $buffer .= '" title="';
                // 'str' section
                $value = $context->find('str');
                $buffer .= $this->sectionB91509f96281b53adafaeca027ab495b($context, $indent, $value);
                $buffer .= '">';
                // 'str' section
                $value = $context->find('str');
                $buffer .= $this->sectionB91509f96281b53adafaeca027ab495b($context, $indent, $value);
                $buffer .= '</a></div>';
                $context->pop();
            }
        }
    
        return $buffer;
    }

    private function section14c108b8ba30d510a188344a7c0f2f23(Mustache_Context $context, $indent, $value)
    {
        $buffer = '';
    
        if (!is_string($value) && is_callable($value)) {
            $source = '
require([\'totara_job/job_management_listing\'], function(Listing){
    Listing.init(\'{{userid}}\');
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
                
                $buffer .= $indent . 'require([\'totara_job/job_management_listing\'], function(Listing){
';
                $buffer .= $indent . '    Listing.init(\'';
                $value = $this->resolveValue($context->find('userid'), $context);
                $buffer .= call_user_func($this->mustache->getEscape(), $value);
                $buffer .= '\');
';
                $buffer .= $indent . '});
';
                $context->pop();
            }
        }
    
        return $buffer;
    }

}
