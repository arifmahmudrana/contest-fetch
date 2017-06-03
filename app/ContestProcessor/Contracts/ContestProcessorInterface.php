<?php
/**
 * Created by PhpStorm.
 * User: rana
 * Date: 6/3/17
 * Time: 11:45 AM
 */

namespace App\ContestProcessor\Contracts;


interface ContestProcessorInterface
{
    public function process($response);
}