<?php


namespace Artica\Controller;

use Artica\ApiView\ApiViewInterface;
use yii\base\Arrayable;
use yii\base\Model;
use yii\data\DataProviderInterface;
use yii\helpers\ArrayHelper;

/**
 * Class Serializer
 * Serialize controller responses.
 *
 * @package Artica\Controller
 */
class Serializer extends \yii\rest\Serializer
{
    /**
     * Serializes the given data into a format that can be easily turned into other formats.
     * This method mainly converts the objects of recognized types into array representation.
     * It will not do conversion for unknown object types or non-object data.
     * The default implementation will handle [[Model]] and [[DataProviderInterface]].
     * You may override this method to support more object types.
     * @param mixed $data the data to be serialized.
     * @return mixed the converted data.
     */
    public function serialize($data)
    {
        if ($data instanceof Model && $data->hasErrors()) {
            return $this->serializeModelErrors($data);
        } elseif ($data instanceof Arrayable) {
            return $this->serializeModel($data);
        } elseif ($data instanceof DataProviderInterface) {
            return $this->serializeDataProvider($data);
        } elseif ($data instanceof ApiViewInterface) {
            return $this->serializeApiView($data);
        }

        return $data;
    }

    /**
     * Serializes a set of models.
     * @param array $models
     * @return array the array representation of the models
     */
    protected function serializeModels(array $models)
    {
        list($fields, $expand) = $this->getRequestedFields();
        foreach ($models as $i => $model) {
            if ($model instanceof ApiViewInterface) {
                $models[$i] = $model->toArray();
            } elseif ($model instanceof Arrayable) {
                $models[$i] = $model->toArray($fields, $expand);
            } elseif (is_array($model)) {
                $models[$i] = ArrayHelper::toArray($model);
            }
        }

        return $models;
    }

    /**
     * Serializes a Api view.
     * @param ApiViewInterface $apiView
     * @return array the array representation of the ApiView.
     */
    protected function serializeApiView(ApiViewInterface $apiView): array
    {
        return $apiView->toArray();
    }
}