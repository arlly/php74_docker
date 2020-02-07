<?php
/**
 * Created by PhpStorm.
 * User: arimoto
 * Date: 2018/09/25
 * Time: 10:12
 */

namespace App\Fulclum\Usecase;


use Illuminate\Http\UploadedFile;

class BulkUploadFile
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
     * @param array $files
     * @param array $keys
     * @return array
     */
    public function run(array $files, array $enums) : array
    {
        $data = [];
        foreach ($enums as $enum) {
            if (! array_key_exists($enum->value(), $files)) continue;

            $fileName = (new UploadFile($this->getTargetDir()))->run($files[$enum->value()]);
            $data[$enum->value()] = $fileName;
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