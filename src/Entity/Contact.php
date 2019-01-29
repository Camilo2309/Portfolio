<?php

namespace App\Entity;

use Symfony\Component\Validator\Constraints as Assert;

/**
 * Class Contact
 */
class Contact
{

    /**
     * @var string
     * @Assert\NotBlank()
     * @Assert\Length(min=2, max=100 , minMessage="Votre nom et prénom doivent contenir au minimum 2 caractères.")
     */
    private $fullName;

    /**
     * @var string
     * @Assert\NotBlank()
     * @Assert\Email(message="Votre email n'est pas valide.")
     */
    private $email;

    /**
     * @var string
     * @Assert\NotBlank()
     * @Assert\Length(min=10, minMessage="Votre message doit contenir au minimum 10 caractères.")
     */
    private $message;

    public function getFullName(): ?string
    {
        return $this->fullName;
    }

    public function setFullName(string $fullName): self
    {
        $this->fullName = $fullName;

        return $this;
    }

    public function getEmail(): ?string
    {
        return $this->email;
    }

    public function setEmail(string $email): self
    {
        $this->email = $email;

        return $this;
    }

    public function getMessage(): ?string
    {
        return $this->message;
    }

    public function setMessage(string $message): self
    {
        $this->message = $message;

        return $this;
    }
}
