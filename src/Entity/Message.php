<?php

namespace App\Entity;

use App\Repository\MessageRepository;
use Doctrine\ORM\Mapping as ORM;

/**
 * @ORM\Entity(repositoryClass=MessageRepository::class)
 */
class Message
{
    /**
     * @ORM\Id
     * @ORM\GeneratedValue
     * @ORM\Column(type="integer")
     */
    private $id;

    /**
     * @ORM\Column(type="text")
     */
    private $content;

    /**
     * @ORM\Column(type="datetime")
     */
    private $createDate;

    /**
     * @ORM\Column(type="integer")
     */
    private $SenderId;

    /**
     * @ORM\Column(type="integer")
     */
    private $ReceiverId;

    /**
     * @ORM\ManyToOne(targetEntity=Conversation::class, inversedBy="messages")
     * @ORM\JoinColumn(nullable=false)
     */
    private $conversationId;

    public function getId(): ?int
    {
        return $this->id;
    }

    public function getContent(): ?string
    {
        return $this->content;
    }

    public function setContent(string $content): self
    {
        $this->content = $content;

        return $this;
    }

    public function getCreateDate(): ?\DateTimeInterface
    {
        return $this->createDate;
    }

    public function setCreateDate(\DateTimeInterface $createDate): self
    {
        $this->createDate = $createDate;

        return $this;
    }

    public function getSenderId(): ?int
    {
        return $this->SenderId;
    }

    public function setSenderId(int $SenderId): self
    {
        $this->SenderId = $SenderId;

        return $this;
    }

    public function getReceiverId(): ?int
    {
        return $this->ReceiverId;
    }

    public function setReceiverId(int $ReceiverId): self
    {
        $this->ReceiverId = $ReceiverId;

        return $this;
    }

    public function getConversationId(): ?Conversation
    {
        return $this->conversationId;
    }

    public function setConversationId(?Conversation $conversationId): self
    {
        $this->conversationId = $conversationId;

        return $this;
    }
}
