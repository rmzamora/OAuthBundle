<?php
/**
 * This file is part of the JetBrains PhpStorm.
 *
 * (c) mell m. zamora <me@mellzamora.com>
 *
 * For the full copyright and license information, please view the LICENSE
 * file that was distributed with this source code.
 */

namespace Rmzamora\OAuthBundle\Form\DataTransformer;

use Symfony\Component\Form\DataTransformerInterface;
use Symfony\Component\Form\Exception\UnexpectedTypeException;

class ArrayToJSONStringTransformer implements DataTransformerInterface
{
    /**
     * {@inheritdoc}
     */
    public function transform($array)
    {
        if (null === $array || !is_array($array)) {
            return '';
        }

        return json_encode($array);
    }

    /**
     * {@inheritdoc}
     */
    public function reverseTransform($string)
    {
        if (is_array($string)) {
            return $string;
        }

        return json_decode($string);
    }
}