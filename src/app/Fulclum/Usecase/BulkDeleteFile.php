<?php
/**
 * Created by PhpStorm.
 * User: arimoto
 * Date: 2018/09/25
 * Time: 11:14
 */

namespace App\Fulclum\Usecase;

use Illuminate\Database\Eloquent\Model;

class BulkDeleteFile
{
    private $targetDir;

    /**
     * UploadFile constructor.
     * @param String $targetDir
     */
    public function __construct(String $targetDir)
    {
        $this->targetDir = $targetDir;
    }

    /**
     * @param array $columns
     * @param array $enums
     * @param Model $model
     * @return array
     */
    public function run(array $columns, array $enums, Model $model) : array
    {
        $data = [];
        foreach ($enums as $enum) {
            if (! array_key_exists($enum->value(), $columns)) continue;

            (new DeleteFile($this->getTargetDir()))->run($model->toArray()[$enum->value()]);
            $data[$enum->value()] = '';
        }
        return $data;
    }

    /**
     * @return String
     */
    public function getTargetDir()
    {
        return $this->targetDir;
    }
}