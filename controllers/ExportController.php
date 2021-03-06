<?php
/**
 * Created by PhpStorm.
 * User: phpNT - http://phpnt.com
 * Date: 05.07.2016
 * Time: 20:41
 */
/* @var $dataProvider yii\data\ActiveDataProvider */

namespace vrtc\exportFile\controllers;

use Yii;
use Dompdf\Dompdf;
use Dompdf\Options;
use yii\helpers\Json;
use yii\web\Controller;
use PhpOffice\PhpSpreadsheet\Spreadsheet;
use PhpOffice\PhpSpreadsheet\Writer\Xlsx;

class ExportController extends Controller
{
    public function actionExcel()
    {
        $data = $this->getData();
        $searchModel    = $data['searchModel'];
        $dataProvider   = $data['dataProvider'];
        $title          = $data['title'];
        $tableName      = $searchModel->getTableSchema()->fullName;
        $fields         = $this->getFieldsKeys($searchModel->exportFields());

        $spreadsheet = new Spreadsheet();
        $sheet = $spreadsheet->getActiveSheet();

        $sheet->setTitle($title ? $title : $tableName);

        $letter = 65;
        foreach ($fields as $one) {
            $sheet->setCellValue(chr($letter).'1', $searchModel->getAttributeLabel($one));
            $letter++;
        }

        $row = 2;
        $letter = 65;

        foreach ($dataProvider->getModels() as $model) {
            foreach ($searchModel->exportFields() as $one) {
                if (is_string($one)) {
                    $sheet->setCellValue(chr($letter).$row,$model[$one]);
                } else {
                    $sheet->setCellValue(chr($letter).$row,$one($model));
                }
                $letter++;
            }
            $letter = 65;
            $row++ ;
        }

        $letter = 65;
        foreach($fields as $columnID) {
            $sheet->getColumnDimension(chr($letter))
                ->setAutoSize(true);
            $letter++;
        }


        header('Content-Type: application/vnd.openxmlformats-officedocument.spreadsheetml.sheet');
        $filename = $tableName.".xlsx";
        header('Content-Disposition: attachment;filename='.$filename);
        header('Cache-Control: max-age=0');

        $writer = new Xlsx($spreadsheet);
        $writer->save('php://output');
        exit;
    }

    public function actionCsv()
    {
        $data = $this->getData();
        $searchModel    = $data['searchModel'];
        $dataProvider   = $data['dataProvider'];
        $tableName      = $searchModel->getTableSchema()->fullName;
        $fields         = $this->getFieldsKeys($searchModel->exportFields());
        $csvCharset     = \Yii::$app->request->post('csvCharset');

        header('Pragma: public');
        header('Expires: 0');
        header('Cache-Control: must-revalidate, post-check=0, pre-check=0');
        header('Content-Description: File Transfer');
        header('Content-Type: text/csv');
        $filename = $tableName.".csv";
        header('Content-Disposition: attachment;filename='.$filename);
        header('Content-Transfer-Encoding: binary');
        $fp = fopen('php://output', 'w');

        fputs($fp, $bom =( chr(0xEF) . chr(0xBB) . chr(0xBF) ));

        if ($fp)
        {
            $items = [];
            $i = 0;
            foreach ($fields as $one) {
                $items[$i] = $one;
                $i++;
            }
            fputs($fp, implode($items, ',')."\n");
            $items = [];
            $i = 0;
            foreach ($dataProvider->getModels() as $model) {
                foreach ($searchModel->exportFields() as $one) {
                    if (is_string($one)) {
                        $item = str_replace('"', '\"', $model[$one]);
                    } else {
                        $item = str_replace('"', '\"', $one($model));
                    }
                    if ($item) {
                        $items[$i] = '"'.$item.'"';
                    } else {
                        $items[$i] = $item;
                    }
                    $i++;
                }
                fputs($fp, implode($items, ',')."\n");
                $items = [];
                $i = 0;
            }
        }
        fclose($fp); exit;
    }

    public function actionTxt()
    {

        $data = $this->getData();
        $searchModel    = $data['searchModel'];
        $dataProvider   = $data['dataProvider'];
        $tableName      = $searchModel->getTableSchema()->fullName;
        $fields         = $this->getFieldsKeys($searchModel->exportFields());
        $csvCharset     = \Yii::$app->request->post('csvCharset');
        $txtDelimiter = \Yii::$app->request->post('txtDelimiter');
        $txtQuoteItem = \Yii::$app->request->post('txtQuoteItem');
        $includeFields = \Yii::$app->request->post('includeFields');
        header('Pragma: public');
        header('Expires: 0');
        header('Cache-Control: must-revalidate, post-check=0, pre-check=0');
        header('Content-Description: File Transfer');
        header('Content-Type: text/plain');
        $filename = $tableName.".txt";
        header('Content-Disposition: attachment;filename='.$filename);
        header('Content-Transfer-Encoding: binary');
        $fp = fopen('php://output', 'w');

        if($includeFields){
            $includeFields = explode(',', $includeFields);
        }

        fputs($fp, $bom =( chr(0xEF) . chr(0xBB) . chr(0xBF) ));

        if ($fp)
        {
            $items = [];
            $i = 0;
            foreach ($dataProvider->getModels() as $model) {
                foreach ($searchModel->exportFields() as $k => $one) {
                    if(!in_array((string)$k, $includeFields)){
                       continue;
                    }
                    if (is_string($one)) {
                        $item = str_replace('"', '\"', $model[$one]);
                    } else {
                        $item = str_replace('"', '\"', $one($model));
                    }
                    if ($item) {
                        $items[$i] = $txtQuoteItem . $item . $txtQuoteItem;
                    } else {
                        $items[$i] = $item;
                    }
                    $i++;
                }
                fputs($fp, implode($items, $txtDelimiter)."\n");
                $items = [];
                $i = 0;
            }
        }
        fclose($fp);exit;
    }

    public function actionWord()
    {
        $data = $this->getData();
        $searchModel    = $data['searchModel'];
        $dataProvider   = $data['dataProvider'];
        $title          = $data['title'];
        $tableName      = $searchModel->getTableSchema()->fullName;
        $fields         = $this->getFieldsKeys($searchModel->exportFields());

        $phpWord = new \PhpOffice\PhpWord\PhpWord();
        $section = $phpWord->addSection();
        $sectionStyle = $section->getSettings();
        $sectionStyle->setLandscape();
        $sectionStyle->setBorderTopColor('C0C0C0');
        $sectionStyle->setMarginTop(300);
        $sectionStyle->setMarginRight(300);
        $sectionStyle->setMarginBottom(300);
        $sectionStyle->setMarginLeft(300);
        $phpWord->addTitleStyle(1, ['name'=>'HelveticaNeueLT Std Med', 'size'=>16], ['align'=>'center']); //h
        $section->addTitle('<p style="font-size: 24px; text-align: center;">'.$title ? $title : $tableName.'</p>');

        $table = $section->addTable(
            [
                'name' => 'Tahoma',
                'align'=>'center',
                'cellMarginTop'     => 30,
                'cellMarginRight'   => 30,
                'cellMarginBottom'  => 30,
                'cellMarginLeft'    => 30,
            ]);
        $table->addRow(300, ['exactHeight' => false]);
        foreach ($fields as $one) {
            $table->addCell(1500,[
                'bgColor'           => 'eeeeee',
                'valign'            => 'center',
                'borderTopSize'     => 5,
                'borderRightSize'   => 5,
                'borderBottomSize'  => 5,
                'borderLeftSize'    => 5
            ])->addText($searchModel->getAttributeLabel($one),['bold'=>true, 'size' => 10], ['align'=>'center']);
        }
        foreach ($dataProvider->getModels() as $model) {
            $table->addRow(300, ['exactHeight' => false]);
            foreach ($searchModel->exportFields() as $one) {
                if (is_string($one)) {
                    $table->addCell(1500,[
                        'valign'            => 'center',
                        'borderTopSize'     => 1,
                        'borderRightSize'   => 1,
                        'borderBottomSize'  => 1,
                        'borderLeftSize'    => 1
                    ])->addText($model[$one],['bold'=>false, 'size' => 10], ['align'=>'left']);
                } else {
                    $table->addCell(1500,[
                        'valign'            => 'center',
                        'borderTopSize'     => 1,
                        'borderRightSize'   => 1,
                        'borderBottomSize'  => 1,
                        'borderLeftSize'    => 1
                    ])->addText($one($model),['bold'=>false, 'size' => 10], ['align'=>'left']);
                }
            }
        }

        $objWriter = \PhpOffice\PhpWord\IOFactory::createWriter($phpWord, 'Word2007');
        $filename = $tableName."-word-".date("Y-m-d-H-i-s").".docx";
        $objWriter->save($filename);

        header('Content-Description: File Transfer');
        header('Content-Type: application/vnd.ms-word');
        header('Content-Disposition: attachment;filename='.$filename .' ');
        header('Content-Transfer-Encoding: binary');
        header('Expires: 0');
        header('Cache-Control: must-revalidate, post-check=0, pre-check=0');
        header('Pragma: public');
        header('Content-Length: ' . filesize($filename));
        flush();
        readfile($filename);
        unlink($filename); // deletes the temporary file
        exit;
    }

    public function actionHtml()
    {
        $data = $this->getData();
        $searchModel    = $data['searchModel'];
        $dataProvider   = $data['dataProvider'];
        $title          = $data['title'];
        $tableName      = $data['tableName'];
        $fields         = $this->getFieldsKeys($searchModel->exportFields());

        $phpWord = new \PhpOffice\PhpWord\PhpWord();
        $section = $phpWord->addSection();
        $section->addTitle($title ? $title : $tableName);
        $table = $section->addTable(
            [
                'name' => 'Tahoma',
                'size' => 10,
                'align'=>'center',
            ]);
        $table->addRow(300, ['exactHeight' => true]);
        foreach ($fields as $one) {
            $table->addCell(1500,[
                'bgColor'           => 'eeeeee',
                'valign'            => 'center',
                'borderTopSize'     => 5,
                'borderRightSize'   => 5,
                'borderBottomSize'  => 5,
                'borderLeftSize'    => 5
            ])->addText($searchModel->getAttributeLabel($one),['bold'=>true, 'size' => 10], ['align'=>'center']);
        }
        foreach ($dataProvider->getModels() as $model) {
            $table->addRow(300, ['exactHeight' => true]);
            foreach ($searchModel->exportFields() as $one) {
                if (is_string($one)) {
                    $table->addCell(1500,[
                        'valign'            => 'center',
                        'borderTopSize'     => 1,
                        'borderRightSize'   => 1,
                        'borderBottomSize'  => 1,
                        'borderLeftSize'    => 1
                    ])->addText('<p style="margin-left: 10px;">'.$model[$one].'</p>',['bold'=>false, 'size' => 10], ['align' => 'right']);
                } else {
                    $table->addCell(1500,[
                        'valign'            => 'center',
                        'borderTopSize'     => 1,
                        'borderRightSize'   => 1,
                        'borderBottomSize'  => 1,
                        'borderLeftSize'    => 1
                    ])->addText('<p style="margin-left: 10px;">'.$one($model).'</p>',['bold'=>false, 'size' => 10], ['align' => 'right']);
                }
            }
        }

        header('Content-Type: application/html');
        $filename = $tableName.".html";
        header('Content-Disposition: attachment;filename='.$filename .' ');
        header('Cache-Control: max-age=0');
        $objWriter = \PhpOffice\PhpWord\IOFactory::createWriter($phpWord, 'HTML');
        $objWriter->save('php://output');
    }

    public function actionPdf()
    {
        $data = $this->getData();
        $searchModel    = $data['searchModel'];
        $dataProvider   = $data['dataProvider'];
        $title          = $data['title'];
        $tableName      = $searchModel->getTableSchema()->fullName;
        $fields         = $this->getFieldsKeys($searchModel->exportFields());
        ini_set("allow_url_fopen", true);
        $options = new Options();
        $options->set('defaultFont', 'dejavu sans');
        $options->set('isRemoteEnabled', true);
        $dompdf = new Dompdf($options);
        $html = '<html><head><style>body { font-family: DejaVu Sans }</style></head><body>';
        $html .= '<h1>'.$title ? $title : $tableName.'</h1>';
        $html .= '<table width="100%" cellspacing="0" cellpadding="0">';
        $html .= '<tr style="background-color: #ececec;">';
        foreach ($fields as $one) {
            $html .= '<td style="border: 2px solid #cccccc; text-align: center; font-weight: 500; ">'.$searchModel->getAttributeLabel($one).'</td>';
        }
        $html .= '</tr>';

        foreach ($dataProvider->getModels() as $model) {
            $html .= '<tr>';
            foreach ($searchModel->exportFields() as $one) {
                if (is_string($one)) {
                    $html .= '<td style="border: 1px solid #cccccc; text-align: left; font-weight: 300; padding-left: 10px;">'.$model[$one].'</td>';
                } else {
                    $html .= '<td style="border: 1px solid #cccccc; text-align: left; font-weight: 300; padding-left: 10px; ">'.$one($model).'</td>';
                }
            }
            $html .= '</tr>';
        }
        $html .= '</table>';
        $html .= '</body></html>';
        $dompdf->loadHtml($html);
        $dompdf->setPaper('A4', 'landscape');
        $dompdf->render();
      $dompdf->stream($tableName.'_'.time());
    }

    private function getData() {
        $queryParams = Json::decode(\Yii::$app->request->post('queryParams'));
        $searchModel = \Yii::$app->request->post('model');
        $searchModel = new $searchModel;
        $tableName = $searchModel->tableName();
        $dataProvider = $searchModel->search($queryParams);
        $title = \Yii::$app->request->post('title');
        $getAll = \Yii::$app->request->post('getAll');
        if ($getAll) {
            $dataProvider->pagination = false;
        }
        return [
            'dataProvider'  => $dataProvider,
            'searchModel'   => $searchModel,
            'title'         => $title,
            'tableName'     => $tableName
        ];
    }

    private function getFieldsKeys($fieldsSended) {
        $fields = [];
        $i = 0;
        foreach ($fieldsSended as $key => $value) {
            if (is_int($key)) {
                $fields[$i] = $value;
            } else {
                $fields[$i] = $key;
            }
            $i++;
        }
        return $fields;
    }
}
