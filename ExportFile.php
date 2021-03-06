<?php
/**
 * Created by PhpStorm.
 * User: phpNT - http://phpnt.com
 * Date: 05.07.2016
 * Time: 21:24
 */

namespace vrtc\exportFile;

use yii\base\Widget;
use yii\helpers\Json;

class ExportFile extends Widget
{
    public $model;
    public $queryParams = [];
    public $getAll = false;
    public $title = false;
    public $csvCharset = 'UTF-8';
    public $view = false; //'@app/newpath/default/index'

    public $xls = [
        'formOption' => [],
        'buttonName' => 'MS Excel',
        'buttonOption' => [
            'class' => 'btn btn-primary'
        ],
        'tag' => 'div',
        'tagOption' => [
            'class' => 'pull-left',
            'style' => 'padding: 5px;'
        ]
    ];
    public $csv = [
        'formOption' => [],
        'buttonName' => 'CSV',
        'buttonOption' => [
            'class' => 'btn btn-primary'
        ],
        'tag' => 'div',
        'tagOption' => [
            'class' => 'pull-left',
            'style' => 'padding: 5px;'
        ]
    ];

    public $txt = [
        'formOption' => [],
        'buttonName' => 'TXT',
        'txtDelimiter' => ',',
        'txtQuoteItem' => '"',
        'buttonOption' => [
            'class' => 'btn btn-primary'
        ],
        'includeFields' => false,
        'tag' => 'div',
        'tagOption' => [
            'class' => 'pull-left',
            'style' => 'padding: 5px;'
        ]
    ];
    public $word = [
        'formOption' => [],
        'buttonName' => 'MS Word',
        'buttonOption' => [
            'class' => 'btn btn-primary'
        ],
        'tag' => 'div',
        'tagOption' => [
            'class' => 'pull-left',
            'style' => 'padding: 5px;'
        ]
    ];

    public $html = [
        'formOption' => [],
        'buttonName' => 'HTML',
        'buttonOption' => [
            'class' => 'btn btn-primary'
        ],
        'tag' => 'div',
        'tagOption' => [
            'class' => 'pull-left',
            'style' => 'padding: 5px;'
        ]
    ];
    public $pdf = [
        'formOption' => [],
        'buttonName' => 'PDF',
        'buttonOption' => [
            'class' => 'btn btn-primary'
        ],
        'tag' => 'div',
        'tagOption' => [
            'class' => 'pull-left',
            'style' => 'padding: 5px;'
        ]
    ];
    private $oldProperties;

    public function __construct($config = [])
    {

        $this->oldProperties = get_class_vars(self::class);
        parent::__construct($config);
    }

    public function beforeRun()
    {

        return parent::beforeRun(); // TODO: Change the autogenerated stub
    }

    /**
     *
     */
    public function init()
    {
        $reflection = new \ReflectionObject($this);
        $properties = $reflection->getProperties(\ReflectionProperty::IS_PUBLIC);

        $oldProperties = get_class_vars(self::class);
        foreach ($properties as $property) {
            $name    = $property->getName();
            $value   = $property->getValue($this);

            if(isset($this->{$name}) && is_array($this->{$name}) ) {

                $this->{$name} = array_merge($oldProperties[$name], $this->{$name});

            }
        }
        $this->view = !$this->view ? 'view' : $this->view;
        $this->queryParams = Json::encode($this->queryParams);
        parent::init();
    }

    /**
     * @return string
     */
    public function run()
    {
        return $this->render(
            $this->view,
            [
                'widget' => $this
            ]
        );
    }
}
