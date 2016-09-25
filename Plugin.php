<?php
namespace Your\Plugin;

class Plugin extends PluginBase
{

    // other required code

    public function registerFormWidgets()
    {
        // register the widget
        // relatedrecords in fields.yaml will represent this form widget
        return [
            'Your\Plugin\Widgets\RelatedRecords' => [
                'label' => 'Related Records',
                'code'  => 'relatedrecords'
            ]
        ];
    }

}
