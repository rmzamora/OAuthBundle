<?php
/**
 * This file is part of the JetBrains PhpStorm.
 *
 * (c) mell m. zamora <me@mellzamora.com>
 *
 * For the full copyright and license information, please view the LICENSE
 * file that was distributed with this source code.
 */

namespace Rmzamora\OAuthBundle\Entity;

use Sonata\UserBundle\Entity\BaseUser as BaseClass;

/**
 * Represents a Base User Entity
 */
class User extends BaseClass
{

    /**
     * @var string
     */
    protected $linkedinUid;

    /**
     * @var string
     */
    protected $linkedinName;

    /**
     * @var string
     */
    protected $linkedinData;



    public function __construct()
    {
        parent::__construct();
    }


    /**
     * @return string
     */
    public function getLinkedinData()
    {
        return $this->linkedinData;
    }

    /**
     * @return string
     */
    public function setLinkedinData($linkedinData)
    {
        $this->linkedinData = $linkedinData;
    }

    /**
     * @param string $linkedinName
     */
    public function setLinkedinName($linkedinName)
    {
        $this->linkedinName = $linkedinName;
    }

    /**
     * @return string
     */
    public function getLinkedinName()
    {
        return $this->linkedinName;
    }

    /**
     * @param string $linkedinUid
     */
    public function setLinkedinUid($linkedinUid)
    {
        $this->linkedinUid = $linkedinUid;
    }

    /**
     * @return string
     */
    public function getLinkedinUid()
    {
        return $this->linkedinUid;
    }
}