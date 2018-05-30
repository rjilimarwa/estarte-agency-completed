<?php

namespace AppBundle\Entity;

use Doctrine\ORM\Mapping as ORM;
use Symfony\Component\Validator\Constraints as Assert;

/**
 * Administrator
 *
 * @ORM\Table(name="administrator")
 * @ORM\Entity(repositoryClass="AppBundle\Repository\AdministratorRepository")
 */
class Administrator
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
     * @ORM\Column(name="mobile", type="string", length=20, nullable=true)
     *
     * @Assert\Regex(
     *        "/^[0-9 ]+$/",
     *        message="Téléphone mobile: utiliser seulement des chiffres et espaces"
     * )
     * @Assert\Length(
     *     min=8,
     *     max="8",
     *     minMessage="Téléphone mobile doit être au moin 8 caractéres.",
     *     maxMessage="Téléphone mobile est trop long (max 20 caractéres)."
     *    )
     * @Assert\NotBlank(message="Téléphone mobile est obligatoire.")
     */
    protected $mobile;

    /**
     * @Assert\Valid()
     * @ORM\OneToOne(targetEntity="AppBundle\Entity\User", inversedBy="administrator", cascade={"all"} , fetch="EAGER")
     * @ORM\JoinColumn(nullable= false)
     */
    private $user;

    protected $uniqueName;

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
     * @return int
     */
    public function getId()
    {
        return $this->id;
    }

    /**
     * Set user
     *
     * @param \AppBundle\Entity\User $user
     *
     * @return Administrator
     */
    public function setUser(\AppBundle\Entity\User $user)
    {
        $this->user = $user;

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
     * Set firstName
     *
     * @param string $firstName
     *
     * @return Administrator
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
     * @return Administrator
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
     * Set mobile
     *
     * @param string $mobile
     *
     * @return Administrator
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
}
