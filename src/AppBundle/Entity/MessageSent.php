<?php

namespace AppBundle\Entity;

use Doctrine\ORM\Mapping as ORM;
use Symfony\Component\Validator\Constraints as Assert;

/**
 * MessageSent
 *
 * @ORM\Table(name="message_sent")
 * @ORM\Entity(repositoryClass="AppBundle\Repository\MessageSentRepository")
 *
 * @ORM\HasLifecycleCallbacks
 *
 */
class MessageSent
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
     * @ORM\Column(name="email", type="string", length=100)
     * @Assert\Email(message="Email non valide.")
     * @Assert\NotBlank(message="Email est vide")
     */
    private $email;

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
     * Set email
     *
     * @param string $email
     *
     * @return MessageSent
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
     * Set subject
     *
     * @param string $subject
     *
     * @return MessageSent
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
     * @return MessageSent
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
     * @return MessageSent
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
