<?php

namespace AppBundle\Entity;

use FOS\UserBundle\Model\User as BaseUser;
use Doctrine\ORM\Mapping as ORM;
use Symfony\Bridge\Doctrine\Validator\Constraints\UniqueEntity;
use Symfony\Component\Validator\Constraints as Assert;

/**
 * @ORM\Entity(repositoryClass="AppBundle\Repository\UserRepository")
 * @ORM\Table(name="fos_user")
 * @ORM\HasLifecycleCallbacks
 *
 */
class User extends BaseUser
{
    /**
     * @ORM\Id
     * @ORM\Column(type="integer")
     * @ORM\GeneratedValue(strategy="AUTO")
     */
    protected $id;

    /**
     * @var string
     *
     * @Assert\NotBlank(message="email est vide")
     * @Assert\Email(message="format email non valide")
     *
     */
    protected $email;

    /**
     * @var string
     *
     */
    protected $password;

    /**
     * @ORM\Column(name="created_at", type="datetime")
     *
     */
    protected $createdAt;

    /**
     * @ORM\OneToOne(targetEntity="AppBundle\Entity\Administrator", mappedBy="user", cascade={"persist"})
     * @ORM\JoinColumn(onDelete="CASCADE")
     * @Assert\Valid()
     */
    private $administrator;

    /**
     * @ORM\OneToOne(targetEntity="AppBundle\Entity\Particular", mappedBy="user", cascade={"persist"})
     * @ORM\JoinColumn(onDelete="CASCADE")
     * @Assert\Valid()
     */
    private $particular;

    /**
     * @ORM\OneToOne(targetEntity="AppBundle\Entity\Company", mappedBy="user", cascade={"all"})
     * @ORM\JoinColumn(onDelete="CASCADE")
     * @Assert\Valid()
     */
    private $company;

    /**
     * @ORM\ManyToOne(targetEntity="AppBundle\Entity\City")
     * @ORM\JoinColumn(nullable=true, onDelete="SET NULL")
     *
     */
    private $city;

    /**
     * @ORM\ManyToOne(targetEntity="AppBundle\Entity\Area")
     * @ORM\JoinColumn(nullable=true, onDelete="SET NULL")
     *
     */
    private $area;

    public function getUniqueName()
    {
        if ($this->hasRole('ROLE_COMPANY')) {
            return $this->getCompany()->getCorporateName();
        } elseif ($this->hasRole('ROLE_PARTICULAR')) {
            return $this->getParticular()->getUniqueName();
        }
        elseif ($this->hasRole('ROLE_SUPER_ADMIN')) {
            return $this->getAdministrator()->getUniqueName();
        }
        elseif ($this->hasRole('ROLE_ADMIN')) {
            return $this->getAdministrator()->getUniqueName();
        }
        return false;
    }

    public function getMobile()
    {
        if ($this->hasRole('ROLE_COMPANY')) {
            return $this->getCompany()->getMobile();
        } elseif ($this->hasRole('ROLE_PARTICULAR')) {
            return $this->getParticular()->getMobile();
        }
        return false;
    }

    public function getAddress()
    {
        if ($this->hasRole('ROLE_COMPANY')) {
            return $this->getCompany()->getAddress();
        } elseif ($this->hasRole('ROLE_PARTICULAR')) {
            return $this->getParticular()->getAddress();
        }
        return false;
    }

    public function getPostCode()
    {
        if ($this->hasRole('ROLE_COMPANY')) {
            return $this->getCompany()->getPostCode();
        } elseif ($this->hasRole('ROLE_PARTICULAR')) {
            return $this->getParticular()->getPostCode();
        }
        return false;
    }

    public function setEmail($email)
    {
        $this->setUsername($email);

        return parent::setEmail($email);
    }

    /**
     * Set the canonical email.
     *
     * @param string $emailCanonical
     * @return User
     */
    public function setEmailCanonical($emailCanonical)
    {
        $this->setUsernameCanonical($emailCanonical);

        return parent::setEmailCanonical($emailCanonical);
    }

    public function __construct()
    {
        parent::__construct();
        $this->createdAt = new \DateTime();
    }


    /**
     * Set createdAt
     *
     * @param \DateTime $createdAt
     *
     * @return User
     */
    public function setCreatedAt($createdAt)
    {
        $this->createdAt = $createdAt;

        return $this;
    }

    /**
     * Get createdAt
     *
     * @return \DateTime
     */
    public function getCreatedAt()
    {
        return $this->createdAt;
    }

    /**
     * Set administrator
     *
     * @param \AppBundle\Entity\Administrator $administrator
     *
     * @return User
     */
    public function setAdministrator(\AppBundle\Entity\Administrator $administrator = null)
    {
        $this->administrator = $administrator;

        return $this;
    }

    /**
     * Get administrator
     *
     * @return \AppBundle\Entity\Administrator
     */
    public function getAdministrator()
    {
        return $this->administrator;
    }

    /**
     * Set particular
     *
     * @param \AppBundle\Entity\Particular $particular
     *
     * @return User
     */
    public function setParticular(\AppBundle\Entity\Particular $particular = null)
    {
        $this->particular = $particular;
        $this->particular->setUser($this);
        return $this;
    }

    /**
     * Get particular
     *
     * @return \AppBundle\Entity\Particular
     */
    public function getParticular()
    {
        return $this->particular;
    }

    /**
     * Set company
     *
     * @param \AppBundle\Entity\Company $company
     *
     * @return User
     */
    public function setCompany(\AppBundle\Entity\Company $company = null)
    {
        $this->company = $company;
        $this->company->setUser($this);
        return $this;
    }

    /**
     * Get company
     *
     * @return \AppBundle\Entity\Company
     */
    public function getCompany()
    {
        return $this->company;
    }

    /**
     * Set area
     *
     * @param \AppBundle\Entity\Area $area
     *
     * @return User
     */
    public function setArea(\AppBundle\Entity\Area $area = null)
    {
        $this->area = $area;

        return $this;
    }

    /**
     * Get area
     *
     * @return \AppBundle\Entity\Area
     */
    public function getArea()
    {
        return $this->area;
    }

    /**
     * Set city
     *
     * @param \AppBundle\Entity\City $city
     *
     * @return User
     */
    public function setCity(\AppBundle\Entity\City $city = null)
    {
        $this->city = $city;

        return $this;
    }

    /**
     * Get city
     *
     * @return \AppBundle\Entity\City
     */
    public function getCity()
    {
        return $this->city;
    }
}
