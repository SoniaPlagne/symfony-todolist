<?php

namespace App\Entity;

use App\Repository\ProjectRepository;
use Doctrine\Common\Collections\ArrayCollection;
use Doctrine\Common\Collections\Collection;
use Doctrine\ORM\Mapping as ORM;
use Symfony\Component\Validator\Constraints as Assert;

/**
 * @ORM\Entity(repositoryClass=ProjectRepository::class)
 * @ORM\Table(name="projects")
 */
class Project
{
    /**
     * @ORM\Id
     * @ORM\GeneratedValue
     * @ORM\Column(type="integer")
     */
    private $id;

    /**
     * @ORM\Column(type="string", length=150)
     * @Assert\NotBlank
     */
    private $name;

    /**
     * @ORM\Column(type="text")
     * @Assert\NotBlank
     */
    private $description;

    /**
     * @ORM\Column(type="date")
     */
    private $start_date;

    /**
     * @var string
     * @Assert\NotBlank
     * @Assert\Date
     */
    private $start_date_str;

    /**
     * @ORM\Column(type="date")
     */
    private $end_date;

    /**
     * @var string
     * @Assert\NotBlank
     * @Assert\Date
     */
    private $end_date_str;

    /**
     * @ORM\OneToMany(targetEntity=Task::class, mappedBy="project", orphanRemoval=true)
     */
    private $tasks;

    /**
     * @ORM\ManyToMany(targetEntity=User::class, inversedBy="projects")
     */
    private $users;

    public function __construct()
    {
        $this->tasks = new ArrayCollection();
        $this->users = new ArrayCollection();
    }


    public function getId(): ?int
    {
        return $this->id;
    }


    public function getName(): ?string
    {
        return $this->name;
    }
    public function setName(string $name): self
    {
        $this->name = $name;
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

    public function getStartDate(): ?\DateTimeInterface
    {
        return $this->start_date;
    }
    public function setStartDate(\DateTimeInterface $start_date): self
    {
        $this->start_date = $start_date;
        return $this;
    }
    public function setStartDateStr(String $start_date_str)
    {
        $this->start_date_str = $start_date_str;

    }


    public function getEndDate(): ?\DateTimeInterface
    {
        return $this->end_date;

    }
    public function setEndDate(\DateTimeInterface $end_date): self
    {
        $this->end_date = $end_date;
        return $this;
    }
    public function setEndDateStr(String $end_date_str)
    {
        $this->end_date_str = $end_date_str;

    }


    /**
     * @return Collection|Task[]
     */
    public function getTasks(): Collection
    {
        return $this->tasks;
    }

    public function addTask(Task $task): self
    {
        if (!$this->tasks->contains($task)) {
            $this->tasks[] = $task;
            $task->setProject($this);
        }

        return $this;
    }

    public function removeTask(Task $task): self
    {
        if ($this->tasks->removeElement($task)) {
            // set the owning side to null (unless already changed)
            if ($task->getProject() === $this) {
                $task->setProject(null);
            }
        }

        return $this;
    }

    /**
     * @return Collection|User[]
     */
    public function getUsers(): Collection
    {
        return $this->users;
    }

    public function addUser(User $user): self
    {
        if (!$this->users->contains($user)) {
            $this->users[] = $user;
        }

        return $this;
    }

    public function removeUser(User $user): self
    {
        $this->users->removeElement($user);

        return $this;
    }
}
