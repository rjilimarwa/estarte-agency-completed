<?php

namespace AppBundle\Entity;

use Doctrine\ORM\Mapping as ORM;
use Symfony\Component\Validator\Constraints as Assert;
use Symfony\Bridge\Doctrine\Validator\Constraints\UniqueEntity;

/**
 * Particular
 *
 * @ORM\Table(name="particular")
 * @ORM\Entity(repositoryClass="AppBundle\Repository\ParticularRepository")
 * @ORM\HasLifecycleCallbacks
 */
class Particular
{
    /**
     * @var int
     *
     * @ORM\Column(name="id", type="integer")
     * @ORM\Id
     * @ORM\GeneratedValue(strategy="AUTO")
     */
    private $id;

    /**
     * @var string
     *
     * @ORM\Column(name="first_name", type="string", length=60)
     *
     * @Assert\NotBlank(message="Nom est vide")
     *
     */
    protected $firstName;

    /**
     * @var string
     *
     * @ORM\Column(name="last_name", type="string", length=60)
     *
     * @Assert\NotBlank(message="Prénom est vide")
     *
     */
    protected $lastName;

    /**
     * @var string
     *
     * @ORM\Column(name="civility", type="string", length=3, nullable=true)
     *
     * @Assert\NotBlank(message="Civilité est obligatoire", groups={"profile_edit"})
     *
     */
    protected $civility;

    /**
     * @ORM\Column(name="mobile", type="string", length=20, nullable=true)
     *
     * @Assert\Regex(
     *        "/^[0-9 ]+$/",
     *        message="Mobile: utiliser seulement des chiffres et espaces",
     * )
     * @Assert\Length(
     *     min=8,
     *     max="20",
     *     minMessage="Mobile doit être au moin 8 caractéres.",
     *     maxMessage="Mobile est trop long (max 20 caractéres).",
     *    )
     * @Assert\NotBlank(message="Téléphone mobile est obligatoire.")
     */
    protected $mobile;

    /**
     * @ORM\Column(name="phone", type="string", length=20, nullable=true)
     *
     * @Assert\Regex(
     *        "/^[0-9 ]+$/",
     *        message="Téléphone: utiliser seulement des chiffres et espaces",
     *        groups={"profile_edit"}
     * )
     * @Assert\Length(
     *     min=8,
     *     max="20",
     *     minMessage="Téléphone doit être au moin 8 caractéres.",
     *     maxMessage="Téléphone est trop long (max 20 caractéres).",
     *     groups={"profile_edit"}
     *    )
     */
    protected $phone;

    /**
     * @ORM\Column(name="address", type="string", length=255, nullable=true)
     */
    protected $address;

    /**
     * @ORM\Column(name="post_code", type="string", length=10, nullable=true)
     *
     * @Assert\Regex(
     *        "/^[0-9]+$/",
     *        message="Code postal: utiliser seulement des chiffres", groups={"profile_edit"}
     * )
     */
    protected $postCode;

    /**
     * @ORM\ManyToOne(targetEntity="AppBundle\Entity\Area")
     * @ORM\JoinColumn(nullable=true, onDelete="SET NULL")
     */
    private $area;

    /**
     * @ORM\OneToOne(targetEntity="AppBundle\Entity\User", inversedBy="particular")
     * @ORM\JoinColumn(nullable= false)
     */
    private $user;

    public function getUniqueName()
    {
        return sprintf('%s  %s', $this->firstName, $this->lastName);
    }


    public function __construct()
    {
    }

    /**
     * Get id
     *
     * @return integer
     */
    public function getId()
    {
        return $this->id;
    }

    /**
     * Set firstName
     *
     * @param string $firstName
     *
     * @return Particular
     */
    public function setFirstName($firstName)
    {
        $this->firstName = $firstName;

        return $this;
    }

    /**
     * Get firstName
     *
     * @return string
     */
    public function getFirstName()
    {
        return $this->firstName;
    }

    /**
     * Set lastName
     *
     * @param string $lastName
     *
     * @return Particular
     */
    public function setLastName($lastName)
    {
        $this->lastName = $lastName;

        return $this;
    }

    /**
     * Get lastName
     *
     * @return string
     */
    public function getLastName()
    {
        return $this->lastName;
    }

    /**
     * Set civility
     *
     * @param string $civility
     *
     * @return Particular
     */
    public function setCivility($civility)
    {
        $this->civility = $civility;

        return $this;
    }

    /**
     * Get civility
     *
     * @return string
     */
    public function getCivility()
    {
        return $this->civility;
    }

    /**
     * Set mobile
     *
     * @param string $mobile
     *
     * @return Particular
     */
    public function setMobile($mobile)
    {
        $this->mobile = $mobile;

        return $this;
    }

    /**
     * Get mobile
     *
     * @return string
     */
    public function getMobile()
    {
        return $this->mobile;
    }

    /**
     * Set phone
     *
     * @param string $phone
     *
     * @return Particular
     */
    public function setPhone($phone)
    {
        $this->phone = $phone;

        return $this;
    }

    /**
     * Get phone
     *
     * @return string
     */
    public function getPhone()
    {
        return $this->phone;
    }

    /**
     * Set address
     *
     * @param string $address
     *
     * @return Particular
     */
    public function setAddress($address)
    {
        $this->address = $address;

        return $this;
    }

    /**
     * Get address
     *
     * @return string
     */
    public function getAddress()
    {
        return $this->address;
    }

    /**
     * Set postCode
     *
     * @param string $postCode
     *
     * @return Particular
     */
    public function setPostCode($postCode)
    {
        $this->postCode = $postCode;

        return $this;
    }

    /**
     * Get postCode
     *
     * @return string
     */
    public function getPostCode()
    {
        return $this->postCode;
    }

    /**
     * Set area
     *
     * @param \AppBundle\Entity\Area $area
     *
     * @return Particular
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
     * Set user
     *
     * @param \AppBundle\Entity\User $user
     *
     * @return Particular
     */
    public function setUser(\AppBundle\Entity\User $user)
    {
        $this->user = $user;
        $this->user->addRole('ROLE_PARTICULAR');
        return $this;
    }

    /**
     * Get user
     *
     * @return \AppBundle\Entity\User
     */
    public function getUser()
    {
        return $this->user;
    }
}
