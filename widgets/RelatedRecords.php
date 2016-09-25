<?php namespace Your\Plugin\Widgets;

use Backend\Classes\FormWidgetBase;
/**
 * Created by PhpStorm.
 * User: ng
 * Date: 16/9/23
 * Time: 下午10:09
 */
class RelatedRecords extends FormWidgetBase
{
    protected $relatedIds = [];
    protected $titleField = '';
    protected $imageField = '';
    protected $contentField = '';
    protected $modelClass = '';
    protected $whereClause = '';
    protected $recordsPerPage = 10;

    protected $widgetDir = '';

    public function init()
    {
        $this->widgetDir = '/plugins/' . str_replace('\\', '/', trim(strtolower(__NAMESPACE__), '\\')) .
            '/relatedrecords';

        $this->fillFromConfig([
            'titleField',
            'imageField',
            'contentField',
            'modelClass',
            'whereClause',
            'recordsPerPage',
        ]);

        parent::init();
    }

    public function widgetDetails()
    {
        return [
            'name'        => 'Related Records',
            'description' => 'Select related records'
        ];
    }

    public function prepareData()
    {
        $this->vars['value'] = $this->getLoadValue();
        $this->relatedIds = is_array($this->vars['value']) ?
            $this->vars['value'] :
            json_decode($this->vars['value'], true);

        $records = [];

        if ($this->modelClass && $this->relatedIds) {
            $model = new $this->modelClass();

            $records = $model::whereIn('id', $this->relatedIds);

            if ($this->whereClause) {
                $records = $records->whereRaw($this->whereClause);
            }

            $records = $records->get()->toArray();
            // 维持顺序
            $recordsSorted = [];

            foreach ($this->relatedIds as $id) {
                foreach ($records as $key => $record) {
                    if ($record['id'] == $id) {
                        $recordsSorted[] = $record;
                        unset($records[$key]);
                        break;
                    }
                }
            }

            $records = $recordsSorted;
        }

        foreach ($records as &$record) {
            if ($this->titleField && isset($record[$this->titleField])) {
                $record['rr_title'] = $record[$this->titleField] ? $record[$this->titleField] : '';
            } else {
                $record['rr_title'] = '';
            }

            if ($this->imageField && isset($record[$this->imageField])) {
                $record['rr_image'] = $record[$this->imageField] ?
                    \Cms\Classes\MediaLibrary::url($record[$this->imageField]) :
                    $this->widgetDir . '/assets/img/default.png';
            } else {
                $record['rr_image'] = $this->widgetDir . '/assets/img/default.png';
            }

            if ($this->contentField && isset($record[$this->contentField])) {
                $record['rr_content'] = $record[$this->contentField] ? $record[$this->contentField] : '';
            } else {
                $record['rr_content'] = '';
            }
        }

        $this->vars['name']           = $this->formField->getName();
        $this->vars['model']          = $this->model;
        $this->vars['titleField']     = $this->titleField;
        $this->vars['imageField']     = $this->imageField;
        $this->vars['contentField']   = $this->imageField;
        $this->vars['modelClass']     = $this->modelClass;
        $this->vars['recordsPerPage'] = $this->recordsPerPage;
        $this->vars['whereClause']    = $this->whereClause;
        $this->vars['widgetDir']      = $this->widgetDir;
        $this->vars['records']        = $records;
        $this->vars['recordId']       = isset($this->model->id) ? $this->model->id : 0;
    }

    public function render()
    {
        $this->addCss($this->widgetDir . '/assets/css/relatedrecords.css');
        $this->addJs($this->widgetDir . '/assets/js/relatedrecords.js');
        $this->prepareData();
        return $this->makePartial('field_relatedrecords');
    }
}