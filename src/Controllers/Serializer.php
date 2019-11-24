<?php


namespace Artica\Controllers;

use Artica\ApiViews\ApiViewInterface;
use yii\base\Arrayable;
use yii\base\Model;
use yii\data\DataProviderInterface;

/**
 * Class Serializer
 * Serialize controller responses.
 *
 * @package Artica\Controllers
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
     * Serializes a Api view.
     * @param ApiViewInterface $apiView
     * @return array the array representation of the ApiView.
     */
    protected function serializeApiView(ApiViewInterface $apiView): array
    {
        return $apiView->toArray();
    }
}