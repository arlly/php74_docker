<?php
/**
 * Created by PhpStorm.
 * User: arlly
 * Date: 2019/01/23
 * Time: 14:36
 */

namespace App\Fulclum\Usecase;
use Symfony\Component\Filesystem\Exception\FileNotFoundException;

class CopyFile
{
    protected $targetDir;

    /**
     * CopyFile constructor.
     * @param String $targetDir
     */
    public function __construct(String $targetDir)
    {
        $this->targetDir = $targetDir;
    }

    /**
     * @param String $filename
     * @return string
     */
    public function run(String $filename)
    {
        if (! \File::exists($this->targetDir. $filename)) throw new FileNotFoundException();

        $extension = \File::extension($filename);
        $newFilename = md5(uniqid()) . '.' . $extension;

        \File::copy($this->targetDir. $filename, $this->targetDir. $newFilename);

        return $newFilename;
    }

    /**
     * @return String
     */
    public function getTargetDir()
    {
        return $this->targetDir;
    }
}
