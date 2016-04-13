<?php

/**
 * Bootstrap Form Helper
 *
 *
 * PHP 5
 *
 *  Licensed under the Apache License, Version 2.0 (the "License");
 *  you may not use this file except in compliance with the License.
 *  You may obtain a copy of the License at
 *
 *      http://www.apache.org/licenses/LICENSE-2.0
 *
 *
 * @copyright Copyright (c) Mikaël Capelle (http://mikael-capelle.fr)
 * @link http://mikael-capelle.fr
 * @package app.View.Helper
 * @since Apache v2
 * @license http://www.apache.org/licenses/LICENSE-2.0
 */

namespace Bootstrap\View\Helper;

use Cake\View\Helper\FormHelper;

class BootstrapFormHelper extends FormHelper {

    use BootstrapTrait ;

    public $helpers = [
        'Html',
        'Url',
        'bHtml' => [
            'className' => 'Bootstrap.BootstrapHtml'
        ]
    ] ;

    /**
     * Default config for the helper.
     *
     * @var array
     */
    protected $_defaultConfig = [
        'errorClass' => 'has-error',
        'typeMap' => [
            'string' => 'text', 'datetime' => 'datetime', 'boolean' => 'checkbox',
            'timestamp' => 'datetime', 'text' => 'textarea', 'time' => 'time',
            'date' => 'date', 'float' => 'number', 'integer' => 'number',
            'decimal' => 'number', 'binary' => 'file', 'uuid' => 'string'
        ],
        'templates' => [
            'button' => '<button{{attrs}}>{{text}}</button>',
            'checkbox' => '<input type="checkbox" name="{{name}}" value="{{value}}"{{attrs}}>',
            'checkboxFormGroup' => '{{label}}',
            'checkboxWrapper' => '<div class="checkbox">{{label}}</div>',
            'checkboxContainer' => '{{h_checkboxContainer_start}}<div class="checkbox">{{content}}</div>{{h_checkboxContainer_end}}',
            'dateWidget' => '{{year}}{{month}}{{day}}{{hour}}{{minute}}{{second}}{{meridian}}',
            'error' => '<span class="help-block error-message{{h_errorClass}}">{{content}}</span>',
            'errorList' => '<ul>{{content}}</ul>',
            'errorItem' => '<li>{{text}}</li>',
            'file' => '<input type="file" name="{{name}}" {{attrs}}>',
            'fieldset' => '<fieldset{{attrs}}>{{content}}</fieldset>',
            'formStart' => '<form{{attrs}}>',
            'formEnd' => '</form>',
            'formGroup' => '{{label}}{{h_formGroup_start}}{{prepend}}{{input}}{{append}}{{h_formGroup_end}}',
            'hiddenBlock' => '<div style="display:none;">{{content}}</div>',
            'input' => '<input type="{{type}}" name="{{name}}" class="form-control{{attrs.class}}" {{attrs}} />',
            'inputSubmit' => '<input type="{{type}}"{{attrs}}>',
            'inputContainer' => '<div class="form-group {{type}}{{required}}">{{content}}</div>',
            'inputContainerError' => '<div class="form-group has-error {{type}}{{required}}">{{content}}{{error}}</div>',
            'label' => '<label class="{{s_labelClass}}{{h_labelClass}}{{attrs.class}}" {{attrs}}>{{text}}</label>',
            'nestingLabel' => '{{hidden}}<label{{attrs}}>{{input}}{{text}}</label>',
            'legend' => '<legend>{{text}}</legend>',
            'option' => '<option value="{{value}}"{{attrs}}>{{text}}</option>',
            'optgroup' => '<optgroup label="{{label}}"{{attrs}}>{{content}}</optgroup>',
            'select' => '<select name="{{name}}" class="form-control{{attrs.class}}" {{attrs}}>{{content}}</select>',
            'selectMultiple' => '<select name="{{name}}[]" multiple="multiple" class="form-control{{attrs.class}}" {{attrs}}>{{content}}</select>',
            'radio' => '<input type="radio" name="{{name}}" value="{{value}}"{{attrs}}>',
            'radioWrapper' => '<div class="radio">{{label}}</div>',
            'radioContainer' => '{{h_radioContainer_start}}<div class="form-group">{{content}}</div>{{h_radioContainer_end}}',
            'textarea' => '<textarea name="{{name}}" class="form-control{{attrs.class}}" {{attrs}}>{{value}}</textarea>',
            'submitContainer' => '<div class="form-group">{{h_submitContainer_start}}{{content}}{{h_submitContainer_end}}</div>',
        ],
        'templateClass' => 'Bootstrap\View\BootstrapStringTemplate',
        'buttons' => [
            'type' => 'default'
        ],
        'columns' => [
            'label' => 2,
            'input' => 10,
            'error' => 0
        ],
        'useCustomFileInput' => false
    ];

    /**
     * Default widgets
     *
     * @var array
     */
    protected $_defaultWidgets = [
        'button' => ['Cake\View\Widget\ButtonWidget'],
        'checkbox' => ['Cake\View\Widget\CheckboxWidget'],
        'file' => ['Cake\View\Widget\FileWidget'],
        'label' => ['Cake\View\Widget\LabelWidget'],
        'nestingLabel' => ['Cake\View\Widget\NestingLabelWidget'],
        'multicheckbox' => ['Cake\View\Widget\MultiCheckboxWidget', 'nestingLabel'],
        'radio' => ['Cake\View\Widget\RadioWidget', 'nestingLabel'],
        'select' => ['Cake\View\Widget\SelectBoxWidget'],
        'textarea' => ['Cake\View\Widget\TextareaWidget'],
        'datetime' => ['Cake\View\Widget\DateTimeWidget', 'select'],
        '_default' => ['Cake\View\Widget\BasicWidget'],
    ];

    public $horizontal = false ;
    public $inline = false ;

    private $buttonTypes = ['default', 'primary', 'info', 'success', 'warning', 'danger', 'link'] ;
    private $buttonSizes = ['xs', 'sm', 'lg'] ;

    /**
     *
     * Replace the templates with the ones specified by newTemplates, call the specified function
     * with the specified parameters, and then restore the old templates.
     *
     * @params $templates The new templates
     * @params $callback  The function to call
     * @params $params    The arguments for the $callback function
     *
     * @return The return value of $callback
     *
     **/
    protected function _wrapTemplates ($templates, $callback, $params) {
        $oldTemplates = array_map ([$this, 'templates'], array_combine(array_keys($templates), array_keys($templates))) ;
        $this->templates ($templates) ;
        $result = call_user_func_array ($callback, $params) ;
        $this->templates ($oldTemplates) ;
        return $result ;
    }

    /**
     *
     * Try to match the specified HTML code with a button or a input with submit type.
     *
     * @param $html The HTML code to check
     *
     * @return true if the HTML code contains a button
     *
     **/
    protected function _matchButton ($html) {
        return strpos($html, '<button') !== FALSE || strpos($html, 'type="submit"') !== FALSE ;
    }

    protected function _getDefaultTemplateVars (&$options) {
        $options += [
            'templateVars' => []
        ];
        $options['templateVars'] += [
            's_labelClass' => 'control-label'
        ];
        if ($this->horizontal) {
            $options['templateVars'] += [
                'h_formGroup_start' => '<div class="'.$this->_getColClass('input').'">',
                'h_formGroup_end'   => '</div>',
                'h_checkboxContainer_start' => '<div class="form-group"><div class="'.$this->_getColClass('label', true)
                .' '.$this->_getColClass('input').'">',
                'h_checkboxContainer_end' => '</div></div>',
                'h_radioContainer_start' => '<div class="form-group"><div class="'.$this->_getColClass('label', true)
                .' '.$this->_getColClass('input').'">',
                'h_radioContainer_end' => '</div></div>',
                'h_submitContainer_start' => '<div class="'.$this->_getColClass('label', true).' '.$this->_getColClass('input').'">',
                'h_submitContainer_end' => '</div>',
                'h_labelClass' => ' '.$this->_getColClass('label'),
                'h_errorClass' => ' '.$this->_getColClass('error')
            ];
        }
        if ($this->inline) {
            $options['templateVars']['s_labelClass'] = 'sr-only';
        }
        return $options;
    }

    public function formatTemplate($name, $data) {
        return $this->templater()->format($name, $this->_getDefaultTemplateVars($data));
    }

    public function widget($name, array $data = []) {
        return parent::widget($name, $this->_getDefaultTemplateVars($data));
    }

    protected function _inputContainerTemplate($options) {
        return parent::_inputContainerTemplate(array_merge($options, [
            'options' => $this->_getDefaultTemplateVars($options['options'])
        ]));
    }

    /**
     *
     * Create a Twitter Bootstrap like form.
     *
     * New options available:
     *     - horizontal: boolean, specify if the form is horizontal
     *     - inline: boolean, specify if the form is inline
     *     - search: boolean, specify if the form is a search form
     *
     * Unusable options:
     *     - inputDefaults
     *
     * @param $model The model corresponding to the form
     * @param $options Options to customize the form
     *
     * @return The HTML tags corresponding to the openning of the form
     *
     **/
    public function create($model = null, Array $options = array()) {
        $options += [
            'columns' => $this->config('columns'),
            'horizontal' => false,
            'inline' => false
        ];
        $this->colSize =  $options['columns'];
        $this->horizontal = $options['horizontal'];
        $this->inline = $options['inline'];
        unset($options['columns'], $options['horizontal'], $options['inline']) ;
        if ($this->horizontal) {
            $options = $this->addClass($options, 'form-horizontal') ;
        }
        else if ($this->inline) {
            $options = $this->addClass($options, 'form-inline') ;
        }
        $options['role'] = 'form' ;
        return parent::create($model, $options) ;
    }

    /**
     *
     * Return the col size class for the specified column (label, input or error).
     *
     **/
    protected function _getColClass ($what, $offset = false) {
        if ($what === 'error' && isset($this->colSize['error']) && $this->colSize['error'] == 0) {
            return $this->_getColClass('label', true).' '.$this->_getColClass('input');
        }
        if (isset($this->colSize[$what])) {
            return 'col-md-'.($offset ? 'offset-' : '').$this->colSize[$what] ;
        }
        $classes = [] ;
        foreach ($this->colSize as $cl => $arr) {
            if (isset($arr[$what])) {
                $classes[] = 'col-'.$cl.'-'.($offset ? 'offset-' : '').$arr[$what] ;
            }
        }
        return implode(' ', $classes) ;
    }

    protected function _wrapInputGroup ($addonOrButtons) {
        if ($addonOrButtons) {
            if (is_string($addonOrButtons)) {
                $addonOrButtons = $this->_makeIcon($addonOrButtons);
                $addonOrButtons = '<span class="input-group-'.
                                ($this->_matchButton($addonOrButtons) ? 'btn' : 'addon').'">'.$addonOrButtons.'</span>' ;
            }
            else if ($addonOrButtons !== false) {
                $addonOrButtons = '<span class="input-group-btn">'.implode('', $addonOrButtons).'</span>' ;
            }
        }
        return $addonOrButtons ;
    }

    public function prepend ($input, $prepend) {
        $prepend = $this->_wrapInputGroup ($prepend);
        if ($input === null) {
            return '<div class="input-group">'.$prepend ;
        }
        return $this->_wrap($input, $prepend, null);
    }

    public function append ($input, $append) {
        $append = $this->_wrapInputGroup($append);
        if ($input === null) {
            return $append.'</div>' ;
        }
        return $this->_wrap($input, null, $append);
    }

    public function wrap ($input, $prepend, $append) {
        return $this->prepend(null, $prepend).$input.$this->append(null, $append);
    }

    protected function _wrap ($input, $prepend, $append) {
        return '<div class="input-group">'.$prepend.$input.$append.'</div>' ;
    }

    /**
     *
     * Create & return an input block (Twitter Boostrap Like).
     *
     * New options:
     *      - prepend:
     *          -> string: Add <span class="add-on"> before the input
     *          -> array: Add elements in array before inputs
     *     - append: Same as prepend except it add elements after input
     *
     **/
    public function input($fieldName, array $options = array()) {

        $options += [
            'templateVars' => [],
            'prepend'      => false,
            'append'       => false,
            'help'         => false,
            'inline'       => false
        ];

        $options = $this->_parseOptions($fieldName, $options);

        $prepend = $options['prepend'];
        unset($options['prepend']);
        $append = $options['append'];
        unset($options['append']);
        if ($prepend || $append) {
            $prepend = $this->prepend(null, $prepend);
            $append  = $this->append(null, $append);
        }

        $help = $options['help'];
        unset($options['help']);
        if ($help) {
            $append .= '<p class="help-block">'.$help.'</p>' ;
        }

        $inline = $options['inline'];
        unset ($options['inline']) ;

        if ($options['type'] === 'radio') {
            $options['templates'] = [] ;
            if ($inline) {
                $options['templates'] = [
                    'label' => $this->templates('label'),
                    'radioWrapper' => '{{label}}',
                    'nestingLabel' => '{{hidden}}<label{{attrs}} class="radio-inline">{{input}}{{text}}</label>'
                ] ;
            }
            if ($this->horizontal) {
                $options['templates']['radioContainer'] = '<div class="form-group">{{content}}</div>';
            }
            if (empty($options['templates'])) {
                unset($options['templates']);
            }
        }

        $options['templateVars'] += [
            'prepend' => $prepend,
            'append' => $append
        ];

        return parent::input($fieldName, $options) ;
    }

    protected function _getDatetimeTemplate ($fields, $options) {
        $inputs = [] ;
        foreach ($fields as $field => $in) {
            $in = isset($options[$field]) ? $options[$field] : $in;
            if ($in) {
                if ($field === 'timeFormat')
                    $field = 'meridian' ; // Template uses "meridian" instead of timeFormat
                $inputs[$field] = '<div class="col-md-{{colsize}}">{{'.$field.'}}</div>';
            }
        }
        $tplt = $this->templates('dateWidget');
        $tplt = explode('}}{{', substr($tplt, 2, count($tplt) - 3));
        $html = '' ;
        foreach ($tplt as $v) {
            if (isset($inputs[$v])) {
                $html .= $inputs[$v] ;
            }
        }
        return str_replace('{{colsize}}', round(12 / count($inputs)),
                           '<div class="row">'.$html.'</div>') ;
    }

    /**
     * Creates file input widget.
     *
     * @param string $fieldName Name of a field, in the form "modelname.fieldname"
     * @param array $options Array of HTML attributes.
     * @return string A generated file input.
     * @link http://book.cakephp.org/3.0/en/views/helpers/form.html#creating-file-inputs
     */
    public function file($fieldName, array $options = []) {

        if (!$this->config('useCustomFileInput') || (isset($options['default']) && $options['default'])) {
            return parent::file($fieldName, $options);
        }

        $options += [
            '_input'  => [],
            '_button' => [],
            'id' => $fieldName,
            'secure' => true,
            'count-label' => __('files selected'),
            'button-label' => __('Choose File')
        ];

        $fakeInputCustomOptions = $options['_input'];
        $fakeButtonCustomOptions = $options['_button'];
        unset($options['_input'], $options['_button']);

        $options += ['secure' => true];
        $options = $this->_initInputField($fieldName, $options);
        unset($options['type']);
        $countLabel = $options['count-label'];
        unset($options['count-label']);
        $fileInput = $this->widget('file', array_merge($options, [
            'style' => 'display: none;',
            'onchange' => "document.getElementById('".$options['id']."-input').value = (this.files.length <= 1) ? this.files[0].name : this.files.length + ' ' + '" . $countLabel . "';"
        ]));

        $fakeInput = $this->text($fieldName, array_merge($options, $fakeInputCustomOptions, [
            'name' => $fieldName.'-text',
            'readonly' => 'readonly',
            'id' => $options['id'].'-input',
            'onclick' => "document.getElementById('".$options['id']."').click();"
        ]));
        $buttonLabel = $options['button-label'];
        unset($options['button-label']) ;

        $fakeButton = $this->button($buttonLabel, array_merge($fakeButtonCustomOptions, [
            'type' => 'button',
            'onclick' => "document.getElementById('".$options['id']."').click();"
        ]));
        return $fileInput.$this->Html->div('input-group', $this->Html->div('input-group-btn', $fakeButton).$fakeInput) ;
    }

    /**
     * Returns a set of SELECT elements for a full datetime setup: day, month and year, and then time.
     *
     * ### Date Options:
     *
     * - `empty` - If true, the empty select option is shown. If a string,
     *   that string is displayed as the empty element.
     * - `value` | `default` The default value to be used by the input. A value in `$this->data`
     *   matching the field name will override this value. If no default is provided `time()` will be used.
     * - `monthNames` If false, 2 digit numbers will be used instead of text.
     *   If an array, the given array will be used.
     * - `minYear` The lowest year to use in the year select
     * - `maxYear` The maximum year to use in the year select
     * - `orderYear` - Order of year values in select options.
     *   Possible values 'asc', 'desc'. Default 'desc'.
     *
     * ### Time options:
     *
     * - `empty` - If true, the empty select option is shown. If a string,
     * - `value` | `default` The default value to be used by the input. A value in `$this->data`
     *   matching the field name will override this value. If no default is provided `time()` will be used.
     * - `timeFormat` The time format to use, either 12 or 24.
     * - `interval` The interval for the minutes select. Defaults to 1
     * - `round` - Set to `up` or `down` if you want to force rounding in either direction. Defaults to null.
     * - `second` Set to true to enable seconds drop down.
     *
     * To control the order of inputs, and any elements/content between the inputs you
     * can override the `dateWidget` template. By default the `dateWidget` template is:
     *
     * `{{month}}{{day}}{{year}}{{hour}}{{minute}}{{second}}{{meridian}}`
     *
     * @param string $fieldName Prefix name for the SELECT element
     * @param array $options Array of Options
     * @return string Generated set of select boxes for the date and time formats chosen.
     * @link http://book.cakephp.org/3.0/en/views/helpers/form.html#creating-date-and-time-inputs
     */
    public function dateTime($fieldName, array $options = []) {
        $fields = ['year' => true, 'month' => true, 'day' => true, 'hour' => true, 'minute' => true, 'second' => false, 'timeFormat' => false];
        return $this->_wrapTemplates ([
            'dateWidget' => $this->_getDatetimeTemplate($fields, $options)
        ], 'parent::dateTime', [$fieldName, $options]);
    }

    /**
     * Generate time inputs.
     *
     * ### Options:
     *
     * See dateTime() for time options.
     *
     * @param string $fieldName Prefix name for the SELECT element
     * @param array $options Array of Options
     * @return string Generated set of select boxes for time formats chosen.
     * @see Cake\View\Helper\FormHelper::dateTime() for templating options.
     */
    public function time($fieldName, array $options = []) {
        $fields = ['hour' => true, 'minute' => true, 'second' => false, 'timeFormat' => false];
        return $this->_wrapTemplates ([
            'dateWidget' => $this->_getDatetimeTemplate($fields, $options)
        ], 'parent::time', [$fieldName, $options]);
    }

    /**
     * Generate date inputs.
     *
     * ### Options:
     *
     * See dateTime() for date options.
     *
     * @param string $fieldName Prefix name for the SELECT element
     * @param array $options Array of Options
     * @return string Generated set of select boxes for time formats chosen.
     * @see Cake\View\Helper\FormHelper::dateTime() for templating options.
     */
    public function date($fieldName, array $options = []) {
        $fields = ['year' => true, 'month' => true, 'day' => true];
        return $this->_wrapTemplates ([
            'dateWidget' => $this->_getDatetimeTemplate($fields, $options)
        ], 'parent::date', [$fieldName, $options]);
    }

    /**
     *
     * Create & return a Cakephp options array from the $options specified.
     *
     */
    protected function _createButtonOptions (array $options = []) {
        $options += [
            'bootstrap-block' => false
        ];
        $options = $this->_addButtonClasses($options);
        $block = $options['bootstrap-block'];
        unset($options['bootstrap-block']);
        if ($block) {
            $options = $this->addClass($options, 'btn-block') ;
        }
        return $options ;
    }

    /**
     *
     * Create & return a Twitter Like button.
     *
     * ### New options:
     *
     * - bootstrap-type: Twitter bootstrap button type (primary, danger, info, etc.)
     * - bootstrap-size: Twitter bootstrap button size (mini, small, large)
     *
     */
    public function button($title, array $options = []) {
        return $this->_easyIcon ('parent::button', $title, $this->_createButtonOptions($options));
    }

    /**
     *
     * Create & return a Twitter Like button group.
     *
     * @param $buttons The buttons in the group
     * @param $options Options for div method
     *
     * Extra options:
     *  - vertical true/false
     *
     **/
    public function buttonGroup ($buttons, array $options = []) {
        $options += [
            'vertical' => false
        ];
        $vertical = $options['vertical'];
        unset($options['vertical']) ;
        $options = $this->addClass($options, 'btn-group') ;
        if ($vertical) {
            $options = $this->addClass($options, 'btn-group-vertical') ;
        }
        return $this->Html->tag('div', implode('', $buttons), $options) ;
    }

    /**
     *
     * Create & return a Twitter Like button toolbar.
     *
     * @param $buttons The groups in the toolbar
     * @param $options Options for div method
     *
     **/
    public function buttonToolbar (array $buttonGroups, array $options = array()) {
        $options = $this->addClass($options, 'btn-toolbar') ;
        return $this->Html->tag('div', implode('', $buttonGroups), $options) ;
    }

    /**
     *
     * Create & return a twitter bootstrap dropdown button. This function is a shortcut for:
     *
     *   $this->Form->$buttonGroup([
     *     $this->Form->button($title, $options),
     *     $this->Html->dropdown($menu, [])
     *   ]);
     *
     * @param $title The text in the button
     * @param $menu HTML tags corresponding to menu options (which will be wrapped
     *          into <li> tag). To add separator, pass 'divider'.
     * @param $options Options for button
     *
     */
    public function dropdownButton ($title, array $menu = [], array $options = []) {

        $options['type'] = false ;
        $options['data-toggle'] = 'dropdown' ;
        $options = $this->addClass($options, "dropdown-toggle") ;

        return $this->buttonGroup([
            $this->button($title.' <span class="caret"></span>', $options),
            $this->bHtml->dropdown($menu)
        ]);

    }

    /**
     *
     * Create & return a Twitter Like submit input.
     *
     * New options:
     *     - bootstrap-type: Twitter bootstrap button type (primary, danger, info, etc.)
     *     - bootstrap-size: Twitter bootstrap button size (mini, small, large)
     *
     * Unusable options: div
     *
     **/
    public function submit($caption = null, array $options = array()) {
        return parent::submit($caption, $this->_createButtonOptions($options)) ;
    }

    /** SPECIAL FORM **/

    /**
     *
     * Create a basic bootstrap search form.
     *
     * @param $model The model of the form
     * @param $options The options that will be pass to the BootstrapForm::create method
     * @param $inpOpts The options that will be pass to the BootstrapForm::input method
     * @param $btnOpts The options that will be pass to the BootstrapForm::button method
     *
     * Extra options:
     *  - id          ID of the input (and fieldname)
     *  - label       The input label (default false)
     *  - placeholder The input placeholder (default "Search... ")
     *  - button      The search button text (default: "Search")
     *  - _input      Options for the input (overrided by $inpOpts)
     *  - _button     Options for the button (overrided by $btnOpts)
     *
     **/
    public function searchForm ($model = null, $options = [], $inpOpts = [], $btnOpts = []) {

        $options += [
            'id'          => 'search',
            'label'       => false,
            'placeholder' => 'Search... ',
            'button'      => 'Search',
            '_input'      => [],
            '_button'     => []
        ];

        $options = $this->addClass($options, 'form-search');

        $btnOpts += $options['_button'];
        unset($options['_button']);

        $inpOpts += $options['_input'];
        unset($options['_input']);

        $inpOpts += [
            'id'          => $options['id'],
            'placeholder' => $options['placeholder'],
            'label'       => $options['label']
        ];

        unset($options['id']) ;
        unset($options['label']) ;
        unset($options['placeholder']) ;

        $btnName = $options['button'];
        unset($options['button']) ;

        $inpOpts['append'] = $this->button($btnName, $btnOpts);

        $options['inline'] = (bool)$inpOpts['label'];

        $output = '' ;

        $output .= $this->create($model, $options) ;
        $output .= $this->input($inpOpts['id'], $inpOpts);
        $output .= $this->end() ;

        return $output ;
    }

}

?>
