<?php

namespace AppBundle\Entity;

use Doctrine\ORM\Mapping as ORM;
use Symfony\Component\Validator\Constraints as Assert;
use Symfony\Bridge\Doctrine\Validator\Constraints\UniqueEntity;

/**
 * Company
 *
 * @ORM\Table(name="company")
 * @ORM\Entity(repositoryClass="AppBundle\Repository\CompanyRepository")
 * @ORM\HasLifecycleCallbacks
 */
class Company
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
     * @ORM\Column(name="corporate_name", type="string", length=60)
     *
     * @Assert\NotBlank(message="Nom est vide")
     *
     */
    protected $corporateName;

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
     * @ORM\Column(name="mobile", type="string", length=20)
     *
     * @Assert\Regex(
     *        "/^[0-9 ]+$/",
     *        message="Mobile: utiliser seulement des chiffres et espaces",
     *        groups={"profile_edit"}
     * )
     * @Assert\Length(
     *     min=8,
     *     max="20",
     *     minMessage="Mobile doit être au moin 8 caractéres.",
     *     maxMessage="Mobile est trop long (max 20 caractéres).",
     *     groups={"profile_edit"}
     *    )
     * @Assert\NotBlank(message="Téléphone mobile est obligatoire.")
     */
    protected $mobile;

    /**
     * @ORM\Column(name="fax", type="string", length=20, nullable=true)
     *
     * @Assert\Regex(
     *        "/^[0-9 ]+$/",
     *        message="Fax: utiliser seulement des chiffres et espaces", groups={"profile_edit"}
     * )
     * @Assert\Length(
     *     min=8,
     *     max="20",
     *     minMessage="Fax doit être au moin 8 caractéres.",
     *     maxMessage="Fax est trop long (max 20 caractéres).", groups={"profile_edit"}
     *    )
     */
    protected $fax;

    /**
     * @ORM\Column(name="address", type="string", length=255)
     *
     * @Assert\NotBlank(message="Adresse est vide")
     */
    protected $address;

    /**
     * @ORM\Column(name="post_code", type="string", length=10)
     *
     * @Assert\NotBlank(message="Code postal est vide")
     * @Assert\Regex(
     *        "/^[0-9]+$/",
     *        message="Code postal: utiliser seulement des chiffres", groups={"profile_edit"}
     * )
     */
    protected $postCode;

    /**
     * @ORM\Column(name="tax_registration_number", type="string", length=255, nullable=true)
     */
    private $taxRegistrationNumber;

    /**
     * @ORM\OneToOne(targetEntity="AppBundle\Entity\User", inversedBy="company")
     * @ORM\JoinColumn(nullable= false)
     */
    private $user;

    /**
     * @ORM\oneToOne(targetEntity="AppBundle\Entity\Logo", cascade={"all"})
     * @Assert\Valid()
     */
    private $logo;

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
     * Set corporateName
     *
     * @param string $corporateName
     *
     * @return Company
     */
    public function setCorporateName($corporateName)
    {
        $this->corporateName = $corporateName;

        return $this;
    }

    /**
     * Get corporateName
     *
     * @return string
     */
    public function getCorporateName()
    {
        return $this->corporateName;
    }

    /**
     * Set phone
     *
     * @param string $phone
     *
     * @return Company
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
     * Set mobile
     *
     * @param string $mobile
     *
     * @return Company
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
     * Set fax
     *
     * @param string $fax
     *
     * @return Company
     */
    public function setFax($fax)
    {
        $this->fax = $fax;

        return $this;
    }

    /**
     * Get fax
     *
     * @return string
     */
    public function getFax()
    {
        return $this->fax;
    }

    /**
     * Set address
     *
     * @param string $address
     *
     * @return Company
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
     * @return Company
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
     * Set taxRegistrationNumber
     *
     * @param string $taxRegistrationNumber
     *
     * @return Company
     */
    public function setTaxRegistrationNumber($taxRegistrationNumber)
    {
        $this->taxRegistrationNumber = $taxRegistrationNumber;

        return $this;
    }

    /**
     * Get taxRegistrationNumber
     *
     * @return string
     */
    public function getTaxRegistrationNumber()
    {
        return $this->taxRegistrationNumber;
    }

    /**
     * Set user
     *
     * @param \AppBundle\Entity\User $user
     *
     * @return Company
     */
    public function setUser(\AppBundle\Entity\User $user)
    {
        $this->user = $user;
        $this->user->addRole('ROLE_COMPANY');
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

    /**
     * Set logo
     *
     * @param \AppBundle\Entity\Logo $logo
     *
     * @return Company
     */
    public function setLogo(\AppBundle\Entity\Logo $logo = null)
    {
        $this->logo = $logo;

        return $this;
    }

    /**
     * Get logo
     *
     * @return \AppBundle\Entity\Logo
     */
    public function getLogo()
    {
        return $this->logo;
    }
}
