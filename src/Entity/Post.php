<?php

namespace App\Entity;

use App\Repository\PostRepository;
use Doctrine\Common\Collections\ArrayCollection;
use Doctrine\Common\Collections\Collection;
use Doctrine\DBAL\Types\Types;
use Doctrine\ORM\Mapping as ORM;

#[ORM\Entity(repositoryClass: PostRepository::class)]
class Post
{
    #[ORM\Id]
    #[ORM\GeneratedValue]
    #[ORM\Column]
    private ?int $id = null;

    #[ORM\Column(length: 255)]
    private ?string $title = null;

    #[ORM\Column(length: 255)]
    private ?string $workplace = null;

    #[ORM\Column(type: Types::TEXT)]
    private ?string $description = null;

    #[ORM\Column(length: 50)]
    private ?string $schedule = null;

    #[ORM\Column(length: 50)]
    private ?string $salary = null;

    #[ORM\Column(type: 'datetime')]
    private \DateTime $publishedAt;

    #[ORM\Column]
    private ?bool $isValidated = false;

    #[ORM\ManyToOne(targetEntity: "App\Entity\User", inversedBy: "posts")]
    #[ORM\JoinColumn(name: 'user_id', referencedColumnName: 'id', onDelete: 'CASCADE')]
    private $user;

    
    // SETTERS & GETTERS
    public function getId(): ?int
    {
        return $this->id;
    }

    public function getTitle(): ?string
    {
        return $this->title;
    }

    public function setTitle(string $title): self
    {
        $this->title = $title;

        return $this;
    }

    public function getWorkplace(): ?string
    {
        return $this->workplace;
    }

    public function setWorkplace(string $workplace): self
    {
        $this->workplace = $workplace;

        return $this;
    }

    public function getDescription(): ?string
    {
        return $this->description;
    }

    public function setDescription(string $description): self
    {
        $this->description = $description;

        return $this;
    }

    public function isIsValidated(): ?bool
    {
        return $this->isValidated;
    }

    public function setIsValidated(bool $isValidated): self
    {
        $this->isValidated = $isValidated;

        return $this;
    }

    /**
     * Get the value of schedule
     */
    public function getSchedule()
    {
        return $this->schedule;
    }

    /**
     * Set the value of schedule
     *
     * @return  self
     */
    public function setSchedule($schedule)
    {
        $this->schedule = $schedule;

        return $this;
    }

    /**
     * Get the value of salary
     */
    public function getSalary()
    {
        return $this->salary;
    }

    /**
     * Set the value of salary
     *
     * @return  self
     */
    public function setSalary($salary)
    {
        $this->salary = $salary;

        return $this;
    }

    /**
     * Get the value of publishedAt
     */
    public function getPublishedAt()
    {
        return $this->publishedAt;
    }

    /**
     * Set the value of publishedAt
     *
     * @return  self
     */
    public function setPublishedAt($publishedAt)
    {
        $this->publishedAt = $publishedAt;

        return $this;
    }

    /**
     * Get the value of user
     */
    public function getUser()
    {
        return $this->user;
    }

    /**
     * Set the value of user
     *
     * @return  self
     */
    public function setUser($user)
    {
        $this->user = $user;

        return $this;
    }
}
