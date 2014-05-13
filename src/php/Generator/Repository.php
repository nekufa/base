<?php

namespace Cti\Storage\Generator;

use Symfony\Component\Config\Definition\Exception\Exception;

class Repository
{
    /**
     * @inject
     * @var \Cti\Core\Module\Fenom
     */
    protected $fenom;

    /**
     * @var \Cti\Storage\Component\Model
     */
    protected $model;

    public function getCode()
    {
        $fields = array();
        foreach($this->model->getProperties() as $property) {
            $fields[] = $property->getName();
        }
        $code = $this->fenom->render('repository', array(
            'model' => $this->model,
            'fields' => $fields,
        ));
        return $code;
    }
}