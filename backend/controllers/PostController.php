<?php

namespace backend\controllers;

use afzalroq\cms\controllers\ItemsController;
use afzalroq\cms\entities\Entities;
use afzalroq\cms\entities\Items;
use Yii;
use yii\helpers\Json;

class PostController extends ItemsController
{
    public function getViewPath()
    {
        return Yii::getAlias('@vendor/afzalroq/yii2-cms/views/items');
    }

    public function actionCreate($slug)
    {
        $model = new Items($slug);

        if (Yii::$app->request->isAjax) {
            $model->load(Yii::$app->request->post());
            return Json::encode(\yii\widgets\ActiveForm::validate($model));
        }
        if ($model->load(Yii::$app->request->post())) {
            $operation = Yii::$app->request->post('save');
            if (in_array($operation, ['add-new', 'save-close', 'save'])) {

                if ($slug === 'posts') {
                    $text_finish = ""; $text_2_1 = $model->text_2_1;
                    if ($model->text_2_4 === ""){
                        while($text_2_1 != ""){
                        $teg_now =strpos($text_2_1,"<");
                        $teg_fin =strpos($text_2_1,">");
                        $teg = substr($text_2_1, $teg_now, $teg_fin-$teg_now+1);
                        $text_finish .= $teg;
                        $text_2_1 = substr($text_2_1, $teg_fin+1, strlen($text_2_1));
                        $text_fin =strpos($text_2_1,"<");
                        $text = substr($text_2_1, 0, $text_fin);
                        $text_finish .= $this->to_cyrillic($text);
                        $text_2_1 = str_replace($text, "",$text_2_1);
                        $tegs_now =strpos($text_2_1,"<");
                        $tegs_fin =strpos($text_2_1,">");
                        $tegs = substr($text_2_1, $tegs_now, $tegs_fin-$tegs_now+1);
                        $text_finish .= $tegs;
                        $text_2_1 = substr($text_2_1, $tegs_fin+1, strlen($text_2_1));
                        }
                        $model->text_2_4 = $text_finish;
                    }
                    if ($model->text_1_1 === ""){
                        $model->text_1_1 = $this->to_latin($model->text_1_4);
                    }
                    if ($model->text_2_1 === ""){
                        $model->text_2_1 = $this->to_latin($model->text_2_4);
                    }
                }

//                $model->save();
                if(!$model->save()){
                    dd($model->getErrors());
//                    throw new \Exception()
                }
                foreach ($model->files as $file) $model->addPhoto($file);
                Yii::$app->session->setFlash('success', Yii::t('cms', 'Saved'));
                if ($operation === 'add-new') return $this->redirect(['create', 'slug' => $slug]);
                if ($operation === 'save') return $this->redirect(['update', 'id' => $model->id, 'slug' => $slug]);
            }

            return $this->redirect(['index', 'slug' => $slug]);
        }
        return $this->render('create', [
            'model' => $model,
            'entity' => Entities::findOne(['slug' => $slug])
        ]);
    }
 
    public function actionUpdate($id, $slug)
    {
        $model = $this->findModel($id);

        if ($model->load(Yii::$app->request->post())) {
            $operation = Yii::$app->request->post('save');
            if (in_array($operation, ['add-new', 'save-close', 'save'])) {

                if ($slug === 'posts') {
//                    dd($model);
                    $text_finish = ""; $text_2_1 = $model->text_2_1;
                    if ($model->text_2_4 === ""){
                        while($text_2_1 != ""){
                        $teg_now =strpos($text_2_1,"<");
                        $teg_fin =strpos($text_2_1,">");
                        $teg = substr($text_2_1, $teg_now, $teg_fin-$teg_now+1);
                        $text_finish .= $teg;
                        $text_2_1 = substr($text_2_1, $teg_fin+1, strlen($text_2_1));
                        $text_fin =strpos($text_2_1,"<");
                        $text = substr($text_2_1, 0, $text_fin);
                        $text_finish .= $this->to_cyrillic($text);
                        $text_2_1 = str_replace($text, "",$text_2_1);
                        $tegs_now =strpos($text_2_1,"<");
                        $tegs_fin =strpos($text_2_1,">");
                        $tegs = substr($text_2_1, $tegs_now, $tegs_fin-$tegs_now+1);
                        $text_finish .= $tegs;
                        $text_2_1 = substr($text_2_1, $tegs_fin+1, strlen($text_2_1));
                        }
                        $model->text_2_4 = $text_finish;
                    }
                    if ($model->text_1_1 === ""){
                        $model->text_1_1 = $this->to_latin($model->text_1_4);
                    }
                    if ($model->text_1_4 === ""){
                        $model->text_1_4 = $this->to_cyrillic($model->text_1_1);
                    }
                    if ($model->text_3_4 === ""){
                        $model->text_3_4 = $this->to_cyrillic($model->text_3_1);
                    }
                    if ($model->text_2_1 === ""){
                        $model->text_2_1 = $this->to_latin($model->text_2_4);
                    }
                }


                $model->save();
                foreach ($model->files as $file) $model->addPhoto($file);
                Yii::$app->session->setFlash('success', Yii::t('cms', 'Saved'));
                if ($operation === 'add-new') return $this->redirect(['create', 'slug' => $slug]);
                if ($operation === 'save') return $this->redirect(['update', 'id' => $model->id, 'slug' => $slug]);
            }

            return $this->redirect(['index', 'slug' => $slug]);
        }

        return $this->render('update', [
            'model' => $model,
            'entity' => Entities::findOne(['slug' => $slug])
        ]);
    }

    function to_cyrillic($string):string
    {
        $gost = [
            "a" => "??", "b" => "??", "v" => "??", "g" => "??", "d" => "??", "e" => "??", "yo" => "??",
            "j" => "??", "z" => "??", "i" => "??", "y" => "??", "k" => "??",  "????" => "??", "q" => "??",
            "l" => "??", "m" => "??", "n" => "??", "o" => "??", "p" => "??", "r" => "??", "s" => "??", "t" => "??",
            "f" => "??", "h" => "??", "c" => "??", "o`" => "??",
            "ch" => "??", "sh" => "??", "sch" => "??", "ie" => "??", "u" => "??", "ya" => "??", "A" => "??", "B" => "??",
            "V" => "??", "G" => "??", "D" => "??", "E" => "??", "Yo" => "??", "J" => "??", "Z" => "??", "I" => "??", "Y" => "??",
            "K" => "??", "L" => "??", "M" => "??", 'Q' => '??',
            "N" => "??", "O" => "??", "P" => "??",
            "R" => "??", "S" => "??", "T" => "??", "Yu" => "??", "F" => "??", "H" => "??", "C" => "??", "Ch" => "??", "Sh" => "??",
            "Sch" => "??", "Ie" => "??", "U" => "??", "Ya" => "??", "'" => "??", "_'" => "??", "''" => "??", "_''" => "??",
             '&nbsp;' => '&nbsp;',
        ];
        return strtr($string, $gost);

    }

    function to_latin($string):string
    {
        $gost = [
            "??" => "a", "??" => "b", "??" => "v", "??" => "g", "??" => "g`", "??" => "d",
            "??" => "e", "????" => "oye", "????" => "iye","????" => "uye","????" => "aye" , "??" => "yo", "??" => "j", "??" => "z", "??" => "i",
            "??" => "y", "??" => "k", "??" => "q" , "??" => "l", "??" => "m", "??" => "n",
            "??" => "o", "??" => "p", "??" => "r", "??" => "s", "??" => "t",
            "??" => "u", "??" => "o`", "??" => "f", "??" => "x", "??" => "h" , "??" => "ts", "??" => "ch",
            "??" => "sh", "??" => "sch", "??" => "ie", "??" => "e", "??" => "yu",
            "??" => "ya",
            "??" => "A", "??" => "B", "??" => "V", "??" => "G", "??" => "G`" , "??" => "D",
            "??" => "Ye", "??" => "Yo", "??" => "J", "??" => "Z", "??" => "I",
            "??" => "Y", "??" => "K", "??" => "Q", "??" => "L", "??" => "M", "??" => "N",
            "??" => "O", "??" => "P", "??" => "R", "??" => "S", "??" => "T",
            "??" => "U", "??" => "F", "??" => "H","??" => "H", "??" => "Ts", "??" => "Ch",
            "??" => "Sh", "??" => "Sch", "??" => "Ie", "??" => "E", "??" => "Yu",
            "??" => "Ya", "??" => "O`",
            "??" => "'", "??" => "_'", "??" => "'", "??" => "_''"
        ];
        return strtr($string, $gost);
    }

}
