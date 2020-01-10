<?php

namespace App\Entity;

use Doctrine\Common\Collections\ArrayCollection;
use Doctrine\Common\Collections\Collection;
use Doctrine\ORM\Mapping as ORM;
use Symfony\Component\Security\Core\User\UserInterface;
use Symfony\Bridge\Doctrine\Validator\Constraints\UniqueEntity;


/**
 * @ORM\Entity(repositoryClass="App\Repository\UserRepository")
 * @UniqueEntity("login")
 */
class User implements UserInterface, \Serializable
{
    /**
     * @ORM\Id()
     * @ORM\GeneratedValue()
     * @ORM\Column(type="integer")
     */
    private $id;

    /**
     * @ORM\Column(type="string", length=180)
     */
    private $login;

    /**
     * @ORM\Column(type="json")
     */
    private $roles = [];

    /**
     * @var string The hashed password
     * @ORM\Column(type="string")
     */
    private $password;

    /**
     * @ORM\Column(type="boolean", options={"default" = false})
     */
    private $isActivate;

    /**
     * @ORM\OneToOne(targetEntity="App\Entity\Email", inversedBy="user",  cascade={"persist"})
     */
    private $email;


    /**
     * @ORM\Column(type="datetime", nullable=true)
     */
    private $lastUpdate;

    /**
     * @ORM\OneToOne(targetEntity="App\Entity\EmailLinkToken", inversedBy="user",cascade={"persist"})
     */
    private $emailLinkToken;


    public function __construct()
    {
        $this->isActivate = false;
        $this->roles = ['ROLE_USER'];
        $this->lastUpdate = new \DateTime();
    }


    public function getId(): ?int
    {
        return $this->id;
    }

    public function getLogin(): ?string
    {
        return $this->login;
    }

    public function setLogin(string $login): self
    {
        $this->login = $login;

        return $this;
    }

    /**
     * A visual identifier that represents this user.
     *
     * @see UserInterface
     */
    public function getUsername(): string
    {
        return (string) $this->login;
    }

    /**
     * @see UserInterface
     */
    public function getRoles(): ?array
    {
        $roles = $this->roles;
        // guarantee every user at least has ROLE_USER
        $roles[] = 'ROLE_USER';

        return array_unique($roles);
    }

    public function setRoles(array $roles): self
    {
        $this->roles = $roles;

        return $this;
    }

    /**
     * @see UserInterface
     */
    public function getPassword(): ?string
    {
        return (string) $this->password;
    }

    public function setPassword(string $password): self
    {
        $this->password = $password;

        return $this;
    }

    public function getIsActivate(): ?bool
    {
        return $this->isActivate;
    }

    public function setIsActivate(bool $isActivate): self
    {
        $this->isActivate = $isActivate;

        return $this;
    }

    /**
     * @see UserInterface
     */
    public function getSalt()
    {
        // not needed when using the "bcrypt" algorithm in security.yaml
    }

    /**
     * @see UserInterface
     */
    public function eraseCredentials()
    {
        // If you store any temporary, sensitive data on the user, clear it here
        // $this->plainPassword = null;
    }

    /**
     * @inheritDoc
     */
    public function serialize()
    {
        return serialize([
            $this->id,
            $this->login,
            $this->password,
            $this->roles,
            $this->isActivate
        ]);
    }

    /**
     * @inheritDoc
     */
    public function unserialize($serialized)
    {
        list(
            $this->id,
            $this->login,
            $this->password,
            $this->roles,
            $this->isActivate
        ) = unserialize($serialized, ['allowed_classes' => false]);
    }



    public function getEmail(): ?Email
    {
        return $this->email;
    }

    public function setEmail(Email $email): self
    {
        $this->email = $email;
     /*  if ($email->getUser() !== $this){
           $email->setUser($this->getId());
       }
       $email->setUser($this->getId());*/
        return $this;
    }

    public function removeEmail(): self
    {

            $this->removeElement($this->email);
            // set the owning side to null (unless already changed)
        /*    if ($email->getUser() === $this) {
                $email->setUser(null);
            }*/


        return $this;
    }


    public function getLastUpdate(): ?\DateTimeInterface
    {
        return $this->lastUpdate;
    }

    public function setLastUpdate(?\DateTimeInterface $lastUpdate): self
    {
        $this->lastUpdate = $lastUpdate;

        return $this;
    }

    public function getEmailLinkToken(): ?EmailLinkToken
    {
        return $this->emailLinkToken;
    }

    public function setEmailLinkToken(EmailLinkToken $emailLinkToken): self
    {
        $this->emailLinkToken = $emailLinkToken;
        // set the owning side of the relation if necessary

       /* if ($emailLinkToken->getUser() !== $this) {
            $emailLinkToken->setUser($this->getId());
        }*/

        //$emailLinkToken->setUser($this->getId());
        return $this;
    }



}
