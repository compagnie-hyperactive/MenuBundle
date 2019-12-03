<?php
/**
 * Created by PhpStorm.
 * User: afratani@idaia.group
 * Date: 15/11/19
 * Time: 09:23
 */

namespace Lch\MenuBundle\Service;


use Symfony\Component\Validator\Constraint;

/**
 * @Annotation
 */
class ValidMenu extends Constraint
{
    public $message = "Two or more menu have the same location in the same language!";

    public function getTargets()
    {
        return self::CLASS_CONSTRAINT;
    }
}