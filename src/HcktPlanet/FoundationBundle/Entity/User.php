<?php
/**
 * Created By: Luciano Bargmann
 * Date: 25/12/14
 * Time: 04:10
 * © HCKTPlanet Informatica Ltda
 * All Rights Reserved. Unauthorized copies of this code file may subject you to civil and criminal liability.
 */

namespace HcktPlanet\FoundationBundle\Entity;

use FOS\UserBundle\Entity\User as BaseUser;
use Doctrine\ORM\Mapping as ORM;

/**
 * @ORM\Entity
 * @ORM\Table(name="fos_user")
 */
class User extends BaseUser
{
    /**
     * @ORM\Id
     * @ORM\Column(type="integer")
     * @ORM\GeneratedValue(strategy="AUTO")
     */
    protected $id;

    public function __construct()
    {
        parent::__construct();
        // your own logic
    }
}