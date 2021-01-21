<?php

namespace App\Entity;

use App\Repository\ConversationRepository;
use Doctrine\Common\Collections\ArrayCollection;
use Doctrine\Common\Collections\Collection;
use Doctrine\ORM\Mapping as ORM;

/**
 * @ORM\Entity(repositoryClass=ConversationRepository::class)
 */
class Conversation
{
    /**
     * @ORM\Id
     * @ORM\GeneratedValue
     * @ORM\Column(type="integer")
     */
    private $id;

    /**
     * @ORM\Column(type="boolean")
     */
    private $isInitiated;

    /**
     * @ORM\ManyToOne(targetEntity=ProjectOwner::class, inversedBy="conversations")
     * @ORM\JoinColumn(nullable=false)
     */
    private $ownerId;

    /**
     * @ORM\ManyToOne(targetEntity=ProjectContributor::class, inversedBy="conversations")
     * @ORM\JoinColumn(nullable=false)
     */
    private $contributorId;

    /**
     * @ORM\OneToMany(targetEntity=Message::class, mappedBy="conversationId", orphanRemoval=true)
     */
    private $messages;

    public function __construct()
    {
        $this->messages = new ArrayCollection();
    }

    public function getId(): ?int
    {
        return $this->id;
    }

    public function getIsInitiated(): ?bool
    {
        return $this->isInitiated;
    }

    public function setIsInitiated(bool $isInitiated): self
    {
        $this->isInitiated = $isInitiated;

        return $this;
    }

    public function getOwnerId(): ?ProjectOwner
    {
        return $this->ownerId;
    }

    public function setOwnerId(?ProjectOwner $ownerId): self
    {
        $this->ownerId = $ownerId;

        return $this;
    }

    public function getContributorId(): ?ProjectContributor
    {
        return $this->contributorId;
    }

    public function setContributorId(?ProjectContributor $contributorId): self
    {
        $this->contributorId = $contributorId;

        return $this;
    }

    /**
     * @return Collection|Message[]
     */
    public function getMessages(): Collection
    {
        return $this->messages;
    }

    public function addMessage(Message $message): self
    {
        if (!$this->messages->contains($message)) {
            $this->messages[] = $message;
            $message->setConversationId($this);
        }

        return $this;
    }

    public function removeMessage(Message $message): self
    {
        if ($this->messages->removeElement($message)) {
            // set the owning side to null (unless already changed)
            if ($message->getConversationId() === $this) {
                $message->setConversationId(null);
            }
        }

        return $this;
    }
}
