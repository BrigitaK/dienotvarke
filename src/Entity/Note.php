<?php

namespace App\Entity;

use App\Repository\NoteRepository;
use Doctrine\ORM\Mapping as ORM;
use Symfony\Component\Validator\Constraints as Assert;

/**
 * @ORM\Entity(repositoryClass=NoteRepository::class)
 */
class Note
{
    /**
     * @ORM\Id
     * @ORM\GeneratedValue
     * @ORM\Column(type="integer")
     */
    private $id;

    /**
     * @ORM\Column(type="string", length=255)
     * @Assert\NotBlank(message="Title field can not be empty!")
     * @Assert\Length(
     *      min = 2,
     *      max = 255,
     *      minMessage = "The title is too short. Minimum length is {{ limit }} characters",
     *      maxMessage = "The title cannot be longer than {{ limit }} characters"
     * )
     * 
     */
    private $title;

    /**
     * @ORM\Column(type="string", length=255)
     * @Assert\NotBlank(message="Priority should not be blank.")
     */
    private $priority;

    /**
     * @ORM\Column(type="text")
     * @Assert\NotBlank(message="Note should not be blank.")
     */
    private $note;

    /**
     * @ORM\Column(type="integer")
     */
    private $status_id;

    /**
     * @ORM\ManyToOne(targetEntity=Status::class, inversedBy="notes")
     */
    private $status;

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

    public function getPriority(): ?string
    {
        return $this->priority;
    }

    public function setPriority(string $priority): self
    {
        $this->priority = $priority;

        return $this;
    }

    public function getNote(): ?string
    {
        return $this->note;
    }

    public function setNote(string $note): self
    {
        $this->note = $note;

        return $this;
    }

    public function getStatusId(): ?int
    {
        return $this->status_id;
    }

    public function setStatusId(int $status_id): self
    {
        $this->status_id = $status_id;

        return $this;
    }

    public function getStatus(): ?Status
    {
        return $this->status;
    }

    public function setStatus(?Status $status): self
    {
        $this->status = $status;

        return $this;
    }
}
