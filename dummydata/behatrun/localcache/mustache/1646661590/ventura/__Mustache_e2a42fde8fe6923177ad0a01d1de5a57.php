<?php

class __Mustache_e2a42fde8fe6923177ad0a01d1de5a57 extends Mustache_Template
{
    private $lambdaHelper;

    public function renderInternal(Mustache_Context $context, $indent = '')
    {
        $this->lambdaHelper = new Mustache_LambdaHelper($this->mustache, $context);
        $buffer = '';

        $buffer .= $indent . '
';
        $buffer .= $indent . '<main class="totara_reportbuilder__report_create"
';
        $buffer .= $indent . '      data-core-autoinitialise="true"
';
        $buffer .= $indent . '      data-core-autoinitialise-amd="totara_reportbuilder/create">
';
        $buffer .= $indent . '
';
        $buffer .= $indent . '    <div class="totara_reportbuilder__report_create_content totara_reportbuilder__report_create_overlay';
        // 'panel_region_enabled' section
        $value = $context->find('panel_region_enabled');
        $buffer .= $this->sectionF3d56ebf14e8669f4b3838eb16a50a21($context, $indent, $value);
        $buffer .= '">
';
        $buffer .= $indent . '
';
        $buffer .= $indent . '        <aside class="totara_reportbuilder__report_create_aside">
';
        // Totara hack: if the partial starts with "." then try
        // to resolve it from the current context.
        $partialstr = 'totara_core/toggle_filter_panel';
        if (strpos($partialstr, '&&') === 0) {
            $partialstr = $context->find(substr($partialstr, 2));
        }
        if ($partial = $this->mustache->loadPartial($partialstr)) {
            $buffer .= $partial->renderInternal($context, $indent . '            ');
        }
        // 'filter_data' section
        $value = $context->find('filter_data');
        $buffer .= $this->sectionB551b05a13579f8118132d24593b916b($context, $indent, $value);
        // Totara hack: if the partial starts with "." then try
        // to resolve it from the current context.
        $partialstr = 'totara_core/select_region_panel';
        if (strpos($partialstr, '&&') === 0) {
            $partialstr = $context->find(substr($partialstr, 2));
        }
        if ($partial = $this->mustache->loadPartial($partialstr)) {
            $buffer .= $partial->renderInternal($context, $indent . '            ');
        }
        $buffer .= $indent . '        </aside>
';
        $buffer .= $indent . '
';
        $buffer .= $indent . '        <div class="totara_reportbuilder__report_create_results"
';
        $buffer .= $indent . '             aria-live="polite"
';
        $buffer .= $indent . '             data-core-autoinitialise="true"
';
        $buffer .= $indent . '             data-core-autoinitialise-amd="totara_core/list_toggle"
';
        $buffer .= $indent . '             data-tw-target=".totara_reportbuilder__createreport_list"
';
        $buffer .= $indent . '             data-tw-target-class="totara_reportbuilder__createreport_list">
';
        $buffer .= $indent . '            <span data-totara_reportbuilder-create_report-results_count>
';
        $buffer .= $indent . '            </span>
';
        $buffer .= $indent . '
';
        $buffer .= $indent . '            <div class="totara_reportbuilder__createreport_itemstyletoggle" data-tw-switcher>
';
        $buffer .= $indent . '                <a href="#" role="button" class="totara_reportbuilder__createreport_itemstyletoggle_btn totara_reportbuilder__createreport_itemstyletoggle_btn--narrow"
';
        $buffer .= $indent . '                   data-tw-trigger="grid">
';
        $buffer .= $indent . '                    ';
        // 'flex_icon' section
        $value = $context->find('flex_icon');
        $buffer .= $this->section9b4aa030938d1a9439a8a8263d9bef7b($context, $indent, $value);
        $buffer .= '
';
        $buffer .= $indent . '                </a>
';
        $buffer .= $indent . '                <a href="#" role="button" class="totara_reportbuilder__createreport_itemstyletoggle_btn totara_reportbuilder__createreport_itemstyletoggle_btn--wide"
';
        $buffer .= $indent . '                   data-tw-trigger="table">
';
        $buffer .= $indent . '                    ';
        // 'flex_icon' section
        $value = $context->find('flex_icon');
        $buffer .= $this->sectionB202891a4275a5a5976621830c09e62c($context, $indent, $value);
        $buffer .= '
';
        $buffer .= $indent . '                </a>
';
        $buffer .= $indent . '            </div>
';
        $buffer .= $indent . '
';
        $buffer .= $indent . '            <div class="totara_reportbuilder__createreport_list totara_reportbuilder__createreport_list--grid" data-tw-report-create-container>
';
        // Totara hack: if the partial starts with "." then try
        // to resolve it from the current context.
        $partialstr = 'totara_core/grid';
        if (strpos($partialstr, '&&') === 0) {
            $partialstr = $context->find(substr($partialstr, 2));
        }
        if ($partial = $this->mustache->loadPartial($partialstr)) {
            $buffer .= $partial->renderInternal($context, $indent . '                ');
        }
        $buffer .= $indent . '            </div>
';
        $buffer .= $indent . '
';
        $buffer .= $indent . '            <div class="totara_reportbuilder__createreport_load" data-tw-report-create-load>
';
        $buffer .= $indent . '                <button>';
        // 'str' section
        $value = $context->find('str');
        $buffer .= $this->section22fa933ad3212e239aed4a1c0414266e($context, $indent, $value);
        $buffer .= '</button>
';
        $buffer .= $indent . '            </div>
';
        $buffer .= $indent . '        </div>
';
        $buffer .= $indent . '    </div>
';
        $buffer .= $indent . '</main>
';

        return $buffer;
    }

    private function sectionF3d56ebf14e8669f4b3838eb16a50a21(Mustache_Context $context, $indent, $value)
    {
        $buffer = '';
    
        if (!is_string($value) && is_callable($value)) {
            $source = ' totara_reportbuilder__report_create__content--has_side_filter';
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
                
                $buffer .= ' totara_reportbuilder__report_create__content--has_side_filter';
                $context->pop();
            }
        }
    
        return $buffer;
    }

    private function sectionB551b05a13579f8118132d24593b916b(Mustache_Context $context, $indent, $value)
    {
        $buffer = '';
    
        if (!is_string($value) && is_callable($value)) {
            $source = '
                {{> &&filter_template }}
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
                $partialstr = '&&filter_template';
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

    private function section9b4aa030938d1a9439a8a8263d9bef7b(Mustache_Context $context, $indent, $value)
    {
        $buffer = '';
    
        if (!is_string($value) && is_callable($value)) {
            $source = 'view-grid, tile_view, totara_reportbuilder';
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
                
                $buffer .= 'view-grid, tile_view, totara_reportbuilder';
                $context->pop();
            }
        }
    
        return $buffer;
    }

    private function sectionB202891a4275a5a5976621830c09e62c(Mustache_Context $context, $indent, $value)
    {
        $buffer = '';
    
        if (!is_string($value) && is_callable($value)) {
            $source = 'view-list, list_view, totara_reportbuilder';
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
                
                $buffer .= 'view-list, list_view, totara_reportbuilder';
                $context->pop();
            }
        }
    
        return $buffer;
    }

    private function section22fa933ad3212e239aed4a1c0414266e(Mustache_Context $context, $indent, $value)
    {
        $buffer = '';
    
        if (!is_string($value) && is_callable($value)) {
            $source = 'loadmore, totara_reportbuilder';
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
                
                $buffer .= 'loadmore, totara_reportbuilder';
                $context->pop();
            }
        }
    
        return $buffer;
    }

}
