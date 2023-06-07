<?php

namespace App\Entity;

use App\Repository\UserRepository;
use Doctrine\Common\Collections\ArrayCollection;
use Doctrine\Common\Collections\Collection;
use Doctrine\DBAL\Types\Types;
use Doctrine\ORM\Mapping as ORM;
use Symfony\Bridge\Doctrine\Validator\Constraints\UniqueEntity;
use Symfony\Component\Security\Core\User\PasswordAuthenticatedUserInterface;
use Symfony\Component\Security\Core\User\UserInterface;

#[ORM\Entity(repositoryClass: UserRepository::class)]
#[UniqueEntity(fields: ['email'], message: 'There is already an account with this email')]
class User implements UserInterface, PasswordAuthenticatedUserInterface
{
    #[ORM\Id]
    #[ORM\GeneratedValue]
    #[ORM\Column]
    private ?int $id = null;

    #[ORM\Column(length: 255)]
    private ?string $username = null;

    #[ORM\Column(length: 255)]
    private ?string $email = null;

    /**
     * @var string The hashed password
     */
    #[ORM\Column(length: 255)]
    private ?string $password = null;

    #[ORM\Column(length: 255, nullable: true)]
    private ?string $userImage = null;

    #[ORM\OneToMany(mappedBy: 'user', targetEntity: Review::class, orphanRemoval: true)]
    private Collection $reviews;

    #[ORM\Column(length: 255, nullable: true)]
    private ?string $address = null;

    #[ORM\ManyToMany(targetEntity: self::class, inversedBy: 'friends')]
    private Collection $friends;

    #[ORM\Column(type: Types::DATE_MUTABLE)]
    private ?\DateTimeInterface $memberSince;

    #[ORM\Column(length: 255, nullable: true)]
    private ?string $description = null;

    #[ORM\Column(length: 255, nullable: true)]
    private ?string $thingsILove = null;

    #[ORM\Column(length: 255, nullable: true)]
    private ?string $myHometown = null;

    #[ORM\Column(length: 255, nullable: true)]
    private ?string $whenIAmNotWhelping = null;

    #[ORM\Column(length: 255, nullable: true)]
    private ?string $whyYouShouldRead = null;

    #[ORM\Column]
    private array $roles = [];

    #[ORM\OneToMany(mappedBy: 'user', targetEntity: Notification::class, orphanRemoval: true)]
    private Collection $notifications;

    #[ORM\OneToMany(mappedBy: 'sender', targetEntity: Message::class, orphanRemoval: true)]
    private Collection $messagesSent;

    #[ORM\OneToMany(mappedBy: 'receiver', targetEntity: Message::class, orphanRemoval: true)]
    private Collection $messagesReceived;

    public function __construct()
    {
        $this->reviews = new ArrayCollection();
        $this->friends = new ArrayCollection();
        $this->memberSince = new \DateTime();
        $this->notifications = new ArrayCollection();
        $this->messagesSent = new ArrayCollection();
        $this->messagesReceived = new ArrayCollection();
    }

    public function getId(): ?int
    {
        return $this->id;
    }

    public function getUsername(): ?string
    {
        return $this->username;
    }

    public function setUsername(string $username): self
    {
        $this->username = $username;

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

    /**
     * A visual identifier that represents this user.
     *
     * @see UserInterface
     */
    public function getUserIdentifier(): string
    {
        return (string) $this->username;
    }

    /**
     * @see UserInterface
     */
    public function getRoles(): array
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
     * @see PasswordAuthenticatedUserInterface
     */
    public function getPassword(): string
    {
        return $this->password;
    }

    public function setPassword(string $password): self
    {
        $this->password = $password;

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

    public function getUserImage(): ?string
    {
        return $this->userImage;
    }

    public function setUserImage(?string $userImage): self
    {
        $this->userImage = $userImage;

        return $this;
    }

    /**
     * @return Collection<int, Review>
     */
    public function getReviews(): Collection
    {
        return $this->reviews;
    }

    public function addReview(Review $review): self
    {
        if (!$this->reviews->contains($review)) {
            $this->reviews->add($review);
            $review->setUser($this);
        }

        return $this;
    }

    public function removeReview(Review $review): self
    {
        if ($this->reviews->removeElement($review)) {
            // set the owning side to null (unless already changed)
            if ($review->getUser() === $this) {
                $review->setUser(null);
            }
        }

        return $this;
    }

    public function getAddress(): ?string
    {
        return $this->address;
    }

    public function setAddress(?string $address): self
    {
        $this->address = $address;

        return $this;
    }

    /**
     * @return Collection<int, self>
     */
    public function getFriends(): Collection
    {
        return $this->friends;
    }

    public function addFriend(self $friend): self
    {
        if (!$this->friends->contains($friend)) {
            $this->friends->add($friend);
        }

        return $this;
    }

    public function removeFriend(self $friend): self
    {
        $this->friends->removeElement($friend);

        return $this;
    }

    public function getMemberSince(): ?\DateTimeInterface
    {
        return $this->memberSince;
    }

    public function setMemberSince(\DateTimeInterface $memberSince): self
    {
        $this->memberSince = $memberSince;

        return $this;
    }

    public function getDescription(): ?string
    {
        return $this->description;
    }

    public function setDescription(?string $description): self
    {
        $this->description = $description;

        return $this;
    }

    public function getThingsILove(): ?string
    {
        return $this->thingsILove;
    }

    public function setThingsILove(?string $thingsILove): self
    {
        $this->thingsILove = $thingsILove;

        return $this;
    }

    public function getMyHometown(): ?string
    {
        return $this->myHometown;
    }

    public function setMyHometown(?string $myHometown): self
    {
        $this->myHometown = $myHometown;

        return $this;
    }

    public function getWhenIAmNotWhelping(): ?string
    {
        return $this->whenIAmNotWhelping;
    }

    public function setWhenIAmNotWhelping(?string $whenIAmNotWhelping): self
    {
        $this->whenIAmNotWhelping = $whenIAmNotWhelping;

        return $this;
    }

    public function getWhyYouShouldRead(): ?string
    {
        return $this->whyYouShouldRead;
    }

    public function setWhyYouShouldRead(?string $whyYouShouldRead): self
    {
        $this->whyYouShouldRead = $whyYouShouldRead;

        return $this;
    }

    /**
     * @return Collection<int, Notification>
     */
    public function getNotifications(): Collection
    {
        return $this->notifications;
    }

    public function getUnreadNotifications(): Collection
    {
        return $this->notifications->filter(function(Notification $notification) {
            return $notification->isIsRead() === false;
        });
    }

    public function addNotification(Notification $notification): self
    {
        if (!$this->notifications->contains($notification)) {
            $this->notifications->add($notification);
            $notification->setUser($this);
        }

        return $this;
    }

    public function removeNotification(Notification $notification): self
    {
        if ($this->notifications->removeElement($notification)) {
            // set the owning side to null (unless already changed)
            if ($notification->getUser() === $this) {
                $notification->setUser(null);
            }
        }

        return $this;
    }

    /**
     * @return Collection<int, Message>
     */
    public function getMessagesSent(): Collection
    {
        return $this->messagesSent;
    }

    public function addMessagesSent(Message $messagesSent): self
    {
        if (!$this->messagesSent->contains($messagesSent)) {
            $this->messagesSent->add($messagesSent);
            $messagesSent->setSender($this);
        }

        return $this;
    }

    public function removeMessagesSent(Message $messagesSent): self
    {
        if ($this->messagesSent->removeElement($messagesSent)) {
            // set the owning side to null (unless already changed)
            if ($messagesSent->getSender() === $this) {
                $messagesSent->setSender(null);
            }
        }

        return $this;
    }

    /**
     * @return Collection<int, Message>
     */
    public function getMessagesReceived(): Collection
    {
        return $this->messagesReceived;
    }

    public function addMessagesReceived(Message $messagesReceived): self
    {
        if (!$this->messagesReceived->contains($messagesReceived)) {
            $this->messagesReceived->add($messagesReceived);
            $messagesReceived->setReceiver($this);
        }

        return $this;
    }

    public function removeMessagesReceived(Message $messagesReceived): self
    {
        if ($this->messagesReceived->removeElement($messagesReceived)) {
            // set the owning side to null (unless already changed)
            if ($messagesReceived->getReceiver() === $this) {
                $messagesReceived->setReceiver(null);
            }
        }

        return $this;
    }
}
