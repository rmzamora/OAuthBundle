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

use Rmzamora\OAuthBundle\OAuth\Response\PathUserResponse as BaseClass;

class TwitterUserResponse extends BaseClass
{

    /**
     * {@inheritdoc}
     */
    public function getUsername()
    {
        return $this->getValueForPath('username');
    }

    /**
     * {@inheritdoc}
     */
    public function getEmail()
    {
        return $this->getValueForPath('email');
    }

    /**
     * {@inheritdoc}
     */
    public function getBiography()
    {
        return $this->getValueForPath('biography');
    }

    /**
     * {@inheritdoc}
     */
    public function getDateOfBirth()
    {
        return new \DateTime($this->getValueForPath('dateOfBirth'));
    }

    /**
     * {@inheritdoc}
     */
    public function getFirstname()
    {
        return $this->getValueForPath('firstname');
    }

    /**
     * {@inheritdoc}
     */
    public function getGender()
    {
        return $this->getValueForPath('gender');
    }

    /**
     * {@inheritdoc}
     */
    public function getLastname()
    {
        return $this->getValueForPath('lastname');
    }

    /**
     * {@inheritdoc}
     */
    public function getLocale()
    {
        return $this->getValueForPath('locale');
    }

    /**
     * {@inheritdoc}
     */
    public function getPhone()
    {
        return $this->getValueForPath('phone');
    }

    /**
     * {@inheritdoc}
     */
    public function getTimezone()
    {
        return $this->getValueForPath('timezone');
    }

    /**
     * {@inheritdoc}
     */
    public function getTwitterData()
    {
        $data = $this->getValueForPath('twitterData');
        if (!is_array($data)) {
            $idx = $this->getPath('twitterData');
            $data = array($idx=>$data);
        }
        return array_merge(array('accessToken'=>$this->getAccessToken()), $data);
    }

    /**
     * {@inheritdoc}
     */
    public function getTwitterName()
    {
        return $this->getValueForPath('twitterName');
    }

    /**
     * {@inheritdoc}
     */
    public function getTwitterUid()
    {
        return $this->getValueForPath('twitterUid');
    }


    /**
     * {@inheritdoc}
     */
    public function getWebsite()
    {
        return $this->getValueForPath('website');
    }
}