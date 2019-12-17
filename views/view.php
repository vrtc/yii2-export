<?php
/**
 * Created by PhpStorm.
 * User: phpNT - http://phpnt.com
 * Date: 05.07.2016
 * Time: 21:24
 */
/* @var $widget vrtc\exportFile\ExportFile */
use yii\helpers\Html;
?>
<?php  if ($widget->xls && is_array($widget->xls)): ?>
  <?= Html::beginTag($widget->xls['tag'], $widget->xls['tagOption']) ?>
        <?php
        echo Html::beginForm(['/export/excel'], 'post', $widget->xls['formOption']);
        echo Html::hiddenInput('model', $widget->model);
        echo Html::hiddenInput('queryParams', $widget->queryParams);
        echo Html::hiddenInput('getAll', $widget->getAll);
        echo Html::hiddenInput('title', $widget->title);
        echo Html::submitButton($widget->xls['buttonName'], $widget->xls['buttonOption']);
        echo Html::endForm();
        ?>
    <?= Html::endTag($widget->xls['tag']); ?>
<?php endif; ?>
<?php  if ($widget->csv && is_array($widget->csv)): ?>
    <?= Html::beginTag($widget->csv['tag'], $widget->csv['tagOption']) ?>
    <?php
    echo Html::beginForm(['/export/csv'], 'post', $widget->csv['formOption']);
    echo Html::hiddenInput('model', $widget->model);
    echo Html::hiddenInput('queryParams', $widget->queryParams);
    echo Html::hiddenInput('getAll', $widget->getAll);
    echo Html::hiddenInput('title', $widget->title);
    echo Html::submitButton($widget->csv['buttonName'], $widget->csv['buttonOption']);
    echo Html::endForm();
    ?>
    <?= Html::endTag($widget->csv['tag']); ?>
<?php endif; ?>
<?php  if ($widget->txt && is_array($widget->txt)): ?>
    <?= Html::beginTag($widget->txt['tag'], $widget->txt['tagOption']) ?>
    <?php
    echo Html::beginForm(['/export/txt'], 'post', $widget->txt['formOption']);
    echo Html::hiddenInput('model', $widget->model);
    echo Html::hiddenInput('queryParams', $widget->queryParams);
    echo Html::hiddenInput('getAll', $widget->getAll);
    echo Html::hiddenInput('title', $widget->title);
    echo Html::hiddenInput('txtDelimiter', $widget->txt['txtDelimiter']);
    echo Html::hiddenInput('txtQuoteItem', $widget->txt['txtQuoteItem']);
    echo Html::submitButton($widget->txt['buttonName'], $widget->txt['buttonOption']);
    echo Html::endForm();
    ?>
    <?= Html::endTag($widget->txt['tag']); ?>
<?php endif; ?>

<?php  if ($widget->word && is_array($widget->word)): ?>
    <?= Html::beginTag($widget->word['tag'], $widget->word['tagOption']) ?>
    <?php
    echo Html::beginForm(['/export/word'], 'post', $widget->word['formOption']);
    echo Html::hiddenInput('model', $widget->model);
    echo Html::hiddenInput('queryParams', $widget->queryParams);
    echo Html::hiddenInput('getAll', $widget->getAll);
    echo Html::hiddenInput('title', $widget->title);
    echo Html::submitButton($widget->word['buttonName'], $widget->word['buttonOption']);
    echo Html::endForm();
    ?>
    <?= Html::endTag($widget->word['tag']); ?>
<?php endif; ?>

<?php  if ($widget->html && is_array($widget->html)): ?>
    <?= Html::beginTag($widget->html['tag'], $widget->html['tagOption']) ?>
    <?php
    echo Html::beginForm(['/export/html'], 'post', $widget->html['formOption']);
    echo Html::hiddenInput('model', $widget->model);
    echo Html::hiddenInput('queryParams', $widget->queryParams);
    echo Html::hiddenInput('getAll', $widget->getAll);
    echo Html::hiddenInput('title', $widget->title);
    echo Html::submitButton($widget->html['buttonName'], $widget->html['buttonOption']);
    echo Html::endForm();
    ?>
    <?= Html::endTag($widget->html['tag']); ?>
<?php endif; ?>

<?php  if ($widget->pdf && is_array($widget->pdf)): ?>
    <?= Html::beginTag($widget->pdf['tag'], $widget->pdf['tagOption']) ?>
    <?php
    echo Html::beginForm(['/export/pdf'], 'post', $widget->pdf['formOption']);
    echo Html::hiddenInput('model', $widget->model);
    echo Html::hiddenInput('queryParams', $widget->queryParams);
    echo Html::hiddenInput('getAll', $widget->getAll);
    echo Html::hiddenInput('title', $widget->title);
    echo Html::submitButton($widget->pdf['buttonName'], $widget->pdf['buttonOption']);
    echo Html::endForm();
    ?>
    <?= Html::endTag($widget->pdf['tag']); ?>
<?php endif; ?>
