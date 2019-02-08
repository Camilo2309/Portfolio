<?php

namespace App\Entity;

use Doctrine\Common\Collections\ArrayCollection;
use Doctrine\Common\Collections\Collection;
use Doctrine\ORM\Mapping as ORM;
use Symfony\Component\Security\Core\User\UserInterface;

/**
 * @ORM\Entity(repositoryClass="App\Repository\UserRepository")
 */
class User implements UserInterface
{
    public function __toString()
    {
        return $this->email;
    }

    /**
     * @ORM\Id()
     * @ORM\GeneratedValue()
     * @ORM\Column(type="integer")
     */
    private $id;

    /**
     * @ORM\Column(type="string", length=255)
     */
    private $email;

    /**
     * @ORM\Column(type="string", length=255)
     */
    private $password;

    /**
     * @ORM\Column(type="string", length=255)
     */
    private $picture;

    /**
     * @ORM\Column(type="string", length=255)
     */
    private $firstName;

    /**
     * @ORM\Column(type="string", length=255)
     */
    private $lastName;

    /**
     * @ORM\Column(type="string", length=255)
     */
    private $titlePresentation;

    /**
     * @ORM\Column(type="text")
     */
    private $presentation;

    /**
     * @ORM\OneToMany(targetEntity="App\Entity\Knowledge", mappedBy="user", cascade={"persist"})
     */
    private $knowledge;

    /**
     * @ORM\OneToMany(targetEntity="App\Entity\Experience", mappedBy="user")
     */
    private $experiences;


    public function __construct()
    {
        $this->knowledge = new ArrayCollection();
        $this->experiences = new ArrayCollection();
    }

    public function getId(): ?int
    {
        return $this->id;
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

    public function getPassword(): ?string
    {
        return $this->password;
    }

    public function setPassword(string $password): self
    {
        $this->password = $password;

        return $this;
    }

    public function getPicture(): ?string
    {
        return $this->picture;
    }

    public function setPicture(string $picture): self
    {
        $this->picture = $picture;

        return $this;
    }

    public function getFirstName(): ?string
    {
        return $this->firstName;
    }

    public function setFirstName(string $firstName): self
    {
        $this->firstName = $firstName;

        return $this;
    }

    public function getLastName(): ?string
    {
        return $this->lastName;
    }

    public function setLastName(string $lastName): self
    {
        $this->lastName = $lastName;

        return $this;
    }

    public function getTitlePresentation(): ?string
    {
        return $this->titlePresentation;
    }

    public function setTitlePresentation(string $titlePresentation): self
    {
        $this->titlePresentation = $titlePresentation;

        return $this;
    }

    public function getPresentation(): ?string
    {
        return $this->presentation;
    }

    public function setPresentation(string $presentation): self
    {
        $this->presentation = $presentation;

        return $this;
    }

    /**
     * @return Collection|Knowledge[]
     */
    public function getKnowledge(): Collection
    {
        return $this->knowledge;
    }

    public function addKnowledge(Knowledge $knowledge): self
    {
        if (!$this->knowledge->contains($knowledge)) {
            $this->knowledge[] = $knowledge;
            $knowledge->setUser($this);
        }

        return $this;
    }

    public function removeKnowledge(Knowledge $knowledge): self
    {
        if ($this->knowledge->contains($knowledge)) {
            $this->knowledge->removeElement($knowledge);
            // set the owning side to null (unless already changed)
            if ($knowledge->getUser() === $this) {
                $knowledge->setUser(null);
            }
        }

        return $this;
    }

    /**
     * @return Collection|Experience[]
     */
    public function getExperiences(): Collection
    {
        return $this->experiences;
    }

    public function addExperience(Experience $experience): self
    {
        if (!$this->experiences->contains($experience)) {
            $this->experiences[] = $experience;
            $experience->setUser($this);
        }

        return $this;
    }

    public function removeExperience(Experience $experience): self
    {
        if ($this->experiences->contains($experience)) {
            $this->experiences->removeElement($experience);
            // set the owning side to null (unless already changed)
            if ($experience->getUser() === $this) {
                $experience->setUser(null);
            }
        }

        return $this;
    }



    public function getRoles(){

        return ['ROLE_USER'];
    }


    public function getSalt(){}

    public function getUsername(){

        return $this->email;
    }

    public function eraseCredentials(){}

}
