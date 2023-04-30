<?php

namespace App\Entity;

use App\Repository\UserRepository;
use Doctrine\Common\Collections\Collection;
use Doctrine\DBAL\Types\Types;
use Doctrine\ORM\Mapping as ORM;
use Symfony\Component\Security\Core\User\PasswordAuthenticatedUserInterface;
use Symfony\Component\Security\Core\User\UserInterface;
use Symfony\Component\PasswordHasher\Hasher\UserPasswordHasherInterface;
use Doctrine\Common\Collections\ArrayCollection;
use Symfony\Bridge\Doctrine\Validator\Constraints\UniqueEntity;

#[ORM\Entity(repositoryClass: UserRepository::class)]
#[UniqueEntity("email", message: "L'adresse email saisie est déja utilisé")]
class User implements UserInterface, PasswordAuthenticatedUserInterface
{
    #[ORM\Id]
    #[ORM\GeneratedValue]
    #[ORM\Column]
    private ?int $id = null;

    private ?string $uuid;

    #[ORM\Column(length: 50)]
    private ?string $name = null;

    #[ORM\Column(length: 60)]
    private ?string $firstname = null;

    #[ORM\Column(length: 255, unique: true)]
    private ?string $email = null;

    #[ORM\Column]
    private array $roles = ["ROLE_USER"];

    /**
     * @var string The hashed password
     */
    #[ORM\Column]
    private ?string $password = null;

    // on ne lui donne pas d'attribut ORM car il n'est pas push dans la bdd
    private ?string $confirm = null;

    #[ORM\Column(nullable: true)]
    private ?string $type = null;

    #[ORM\Column(type: 'string', nullable: true)]
    private ?string $cv = null;

    #[ORM\Column(nullable: true)]
    private ?string $enterprise = null;

    #[ORM\Column(nullable: true)]
    private ?string $enterpriseAdress = null;

    #[ORM\Column(nullable: true)]
    private ?bool $validatedAccount = null;







    #[ORM\OneToMany(targetEntity: "App\Entity\Post", mappedBy: "user")]
    private $posts;



    // construct password hasher
    public function __construct(UserPasswordHasherInterface $passwordHasher)
    {
        $this->passwordHasher = $passwordHasher;
        $this->posts = new ArrayCollection();
    }


    // SETTERS & GETTERS
    public function getId(): ?int
    {
        return $this->id;
    }

    /**
     * A visual identifier that represents this user.
     *
     * @see UserInterface
     */
    public function getUserIdentifier(): string
    {
        return (string) $this->email;
    }

    /**
     * @see UserInterface
     */
    public function getRoles(): array
    {
        $roles = $this->roles;

        return array_unique($roles);
    }

    public function setRoles(array $roles): self
    {
        $this->roles = $roles;

        return $this;
    }

    /**
     * @see PasswordAuthenticatedUserInterface
     */
    public function getPassword(): string
    {
        return $this->password;
    }

    public function setPassword(string $password): self
    {
        $this->password = $this->passwordHasher->hashPassword($this, $password);
        return $this;
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
     * Get the value of posts
     */
    public function getPosts()
    {
        return $this->posts;
    }

    /**
     * Set the value of posts
     *
     * @return  self
     */
    public function setPosts($posts)
    {
        $this->posts = $posts;

        return $this;
    }

    /**
     * Get the value of confirm
     */
    public function getConfirm()
    {
        return $this->confirm;
    }

    /**
     * Set the value of confirm
     *
     * @return  self
     */
    public function setConfirm($confirm)
    {
        $this->confirm = $confirm;

        return $this;
    }

    /**
     * Get the value of uuid
     */
    public function getUuid()
    {
        return $this->uuid;
    }

    /**
     * Set the value of uuid
     *
     * @return  self
     */
    public function setUuid($uuid)
    {
        $this->uuid = $uuid;

        return $this;
    }

    /**
     * Get the value of name
     */
    public function getName()
    {
        return $this->name;
    }

    /**
     * Set the value of name
     *
     * @return  self
     */
    public function setName($name)
    {
        $this->name = $name;

        return $this;
    }

    /**
     * Get the value of email
     */
    public function getEmail()
    {
        return $this->email;
    }

    /**
     * Set the value of email
     *
     * @return  self
     */
    public function setEmail($email)
    {
        $this->email = $email;

        return $this;
    }

    /**
     * Get the value of firstname
     */
    public function getFirstname()
    {
        return $this->firstname;
    }

    /**
     * Set the value of firstname
     *
     * @return  self
     */
    public function setFirstname($firstname)
    {
        $this->firstname = $firstname;

        return $this;
    }

    /**
     * Get the value of type
     */
    public function getType()
    {
        return $this->type;
    }

    /**
     * Set the value of type
     *
     * @return  self
     */
    public function setType($type)
    {
        $this->type = $type;

        return $this;
    }

    /**
     * Get the value of cv
     */
    public function getCv()
    {
        return $this->cv;
    }

    /**
     * Set the value of cv
     *
     * @return  self
     */
    public function setCv($cv)
    {
        $this->cv = $cv;

        return $this;
    }

    /**
     * Get the value of enterprise
     */
    public function getEnterprise()
    {
        return $this->enterprise;
    }

    /**
     * Set the value of enterprise
     *
     * @return  self
     */
    public function setEnterprise($enterprise)
    {
        $this->enterprise = $enterprise;

        return $this;
    }

    /**
     * Get the value of enterpriseAdress
     */
    public function getEnterpriseAdress()
    {
        return $this->enterpriseAdress;
    }

    /**
     * Set the value of enterpriseAdress
     *
     * @return  self
     */
    public function setEnterpriseAdress($enterpriseAdress)
    {
        $this->enterpriseAdress = $enterpriseAdress;

        return $this;
    }

    /**
     * Get the value of validatedAccount
     */
    public function getValidatedAccount()
    {
        return $this->validatedAccount;
    }

    /**
     * Set the value of validatedAccount
     *
     * @return  self
     */
    public function setValidatedAccount($validatedAccount)
    {
        $this->validatedAccount = $validatedAccount;

        return $this;
    }
}
