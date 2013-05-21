<?php
/**
 * This file is part of the JetBrains PhpStorm.
 *
 * (c) mell m. zamora <me@mellzamora.com>
 *
 * For the full copyright and license information, please view the LICENSE
 * file that was distributed with this source code.
 */

namespace Rmzamora\OAuthBundle\OAuth\Response;

use Symfony\Component\Security\Core\Exception\AuthenticationException;
use HWI\Bundle\OAuthBundle\OAuth\Response\PathUserResponse as BaseClass;

class PathUserResponse extends BaseClass
{
    /**
     * Extracts a value from the response for a given path.
     *
     * @param string  $path           Name of the path to get the value for
     * @param boolean $catchException Whether to throw an exception or return null
     *
     * @return null|string
     *
     * @throws AuthenticationException
     */
    protected function getValueForPath($path, $throwException = true)
    {
        try {
            // check if multiple fields
            if(substr_count($this->getPath($path), ',') > 0) {
                $steps = explode(',', $this->getPath($path));
                return $this->getMultipleValueForPath($steps, $throwException);
            } else {
                $steps = explode('.', $this->getPath($path));
                return $this->getSingleValueForPath($steps, $throwException);
            }
        } catch (AuthenticationException $e) {
            if (!$throwException) {
                return null;
            }
            throw $e;
        }
    }

    protected function getMultipleValueForPath($paths, $throwException = true) {
        $data = array();
        foreach ($paths as $path) {
            try {
                $steps = explode('.', $path);
                //$data[$path] = $this->getDataValue($path, $throwException);
                $data[$path] = $this->getSingleValueForPath($steps, $throwException);
            } catch (AuthenticationException $e) {
                if (!$throwException) {
                    continue;
                }
                throw $e;
            }
        }

        return $data;
    }

    protected function getSingleValueForPath($steps, $throwException = true) {
        $value = $this->response;
        foreach ($steps as $step) {
            if (!array_key_exists($step, $value)) {
                if (!$throwException) {
                    return null;
                }

                throw new AuthenticationException(sprintf('Could not follow path "%s" in OAuth provider response: %s', $step, var_export($this->response, true)));
            }
            $value = $value[$step];
        }
        return $value;
    }

    public function getDataValue($key, $throwException = true) {

        $value = $this->response;
        if (!array_key_exists($key, $value)) {
            if (!$throwException) {
                return null;
            }
            throw new AuthenticationException(sprintf('Could not follow path "%s" in OAuth provider response: %s',$key, var_export($this->response, true)));
        }
        $value = $value[$key];
        return $value;
    }
}
