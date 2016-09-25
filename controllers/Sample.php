<?php namespace Your\Plugin\Controllers;

/**
 * Class Sample
 * Sample of controller your may handle related record query
 * Your controller must has one method named onLoadRelatedRecords
 *
 * @package Your\Plugin\Controllers
 */

class Sample extends Controller
{
    public function onLoadRelatedRecords()
    {
        $page           = Request::input('page');
        $recordsPerPage = Request::input('recordsPerPage');
        $excludeIds     = Request::input('excludeIds');
        $modelClass     = Request::input('modelClass');
        $whereClause    = Request::input('whereClause');

        if (!class_exists($modelClass)) {
            echo "{$modelClass} not exists";
        }

        /**
         * var $model | Model;
         */
        $model = new $modelClass();

        if (!is_array($excludeIds)) {
            $excludeIds = json_decode($excludeIds);
        }

        $records = $model->whereNotIn('id', $excludeIds);

        if ($whereClause) {
            $records = $records->whereRaw($whereClause);
        }

        $records = $records->orderBy('id', 'desc')->paginate($recordsPerPage, $page);

        return [
            'result' => $this->makePartial('list_related_records', [
                'records' => $records,
                'modelClass' => $modelClass
            ])
        ];
    }
}