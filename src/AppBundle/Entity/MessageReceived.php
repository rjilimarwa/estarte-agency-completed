<?php

namespace AppBundle\Entity;

use Doctrine\ORM\Mapping as ORM;
use Symfony\Component\Validator\Constraints as Assert;

/**
 * MessageReceived
 *
 * @ORM\Table(name="message_received")
 * @ORM\Entity(repositoryClass="AppBundle\Repository\MessageReceivedRepository")
 *
 * @ORM\HasLifecycleCallbacks
 */
class MessageReceived
{
    /**
     * @var integer
     *
     * @ORM\Column(name="id", type="integer")
     * @ORM\Id
     * @ORM\GeneratedValue(strategy="AUTO")
     */
    private $id;

    /**
     * @var string
     *
     * @ORM\Column(name="full_name", type="string", length=60)
	 * @Assert\NotBlank(message="Nom vide.")
     * @Assert\Length(
     *     min=3,
     *     max="50",
     *     minMessage="Nom est court",
     *     maxMessage="Nom est long."
	 *	)
     */
    private $fullName;

    /**
     * @var string
     *
     * @ORM\Column(name="email", type="string", length=100)
	 * @Assert\Email(message="Email non valide.")
	 * @Assert\NotBlank(message="Email est vide")
     */
    private $email;

    /**
     * @var string
     *
     * @ORM\Column(name="telephone", type="string", length=20, nullable=true)
     *
	 * @Assert\Regex(
	 *		"/^[0-9 ]+$/",
	 *		message="Téléphone: utiliser seulement des chiffres et espaces"
	 * )
     * @Assert\Length(
     *     min=8,
     *     max="20",
     *     minMessage="Téléphone doit être au moin 8 caractéres.",
     *     maxMessage="Téléphone est trop long (max 20 caractéres)."
	 *	)
     */
    private $phone;

    /**
     * @var string
     *
     * @ORM\Column(name="sujet", type="string", length=255)
     * @Assert\Length(
     *     min=3,
     *     max="255",
     *     minMessage="Sujet est court.",
     *     maxMessage="Sujet est long, max 255)."
	 *	)
	 * @Assert\NotBlank(message="Sujet est vide")
     */
    private $subject;

    /**
     * @var string
     *
     * @ORM\Column(name="message", type="text")
	 * @Assert\NotBlank(message="Message est vide")
     * @Assert\Length(
     *     min=10,
     *     minMessage="Message est court.",
     *	)
     */
    private $message;

    /**
     * @var \DateTime
     *
     * @ORM\Column(name="createdAt", type="datetime", nullable=true)
     *
     */
    private $createdAt;


	/**
	 * Construct 
	 */
	public function __construct()
	{
	}

    /**
     * @ORM\PrePersist()
     */
    public function setDate()
    {
        $this->createdAt = new \DateTime();
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
     * Set fullName
     *
     * @param string $fullName
     *
     * @return MessageReceived
     */
    public function setFullName($fullName)
    {
        $this->fullName = $fullName;

        return $this;
    }

    /**
     * Get fullName
     *
     * @return string
     */
    public function getFullName()
    {
        return $this->fullName;
    }

    /**
     * Set email
     *
     * @param string $email
     *
     * @return MessageReceived
     */
    public function setEmail($email)
    {
        $this->email = $email;

        return $this;
    }

    /**
     * Get email
     *
     * @return string
     */
    public function getEmail()
    {
        return $this->email;
    }

    /**
     * Set phone
     *
     * @param string $phone
     *
     * @return MessageReceived
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
     * Set subject
     *
     * @param string $subject
     *
     * @return MessageReceived
     */
    public function setSubject($subject)
    {
        $this->subject = $subject;

        return $this;
    }

    /**
     * Get subject
     *
     * @return string
     */
    public function getSubject()
    {
        return $this->subject;
    }

    /**
     * Set message
     *
     * @param string $message
     *
     * @return MessageReceived
     */
    public function setMessage($message)
    {
        $this->message = $message;

        return $this;
    }

    /**
     * Get message
     *
     * @return string
     */
    public function getMessage()
    {
        return $this->message;
    }

    /**
     * Set createdAt
     *
     * @param \DateTime $createdAt
     *
     * @return MessageReceived
     *
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
}
