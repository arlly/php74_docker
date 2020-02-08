<?php
/**
 * Created by PhpStorm.
 * User: arimoto
 * Date: 2018/10/25
 * Time: 10:41
 */

namespace App\Fulclum\Repository;


abstract class AbstractSupportRepository extends Repository
{
    /**
     * @param String $env
     * @return bool
     */
    abstract function supports(String $env): bool;
}