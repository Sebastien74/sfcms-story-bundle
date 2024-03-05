<?php

namespace App\Entity\Module\Story;

use App\Entity\BaseEntity;
use App\Entity\Core\Website;
use App\Repository\Module\Story\StoryRepository;
use Doctrine\ORM\Mapping as ORM;

/**
 * Story.
 *
 * @author Sébastien FOURNIER <contact@sebastien-fournier.com>
 */
#[ORM\Table(name: 'module_story')]
#[ORM\Entity(repositoryClass: StoryRepository::class)]
#[ORM\HasLifecycleCallbacks]
class Story extends BaseEntity
{
    /**
     * Configurations.
     */
    protected static string $masterField = 'website';
    protected static array $interface = [
        'name' => 'story',
        'hideColumns' => ['position'],
    ];

    #[ORM\Column(type: 'string', length: 10, nullable: true)]
    private ?string $locale = null;

    #[ORM\Column(type: 'string', length: 180, nullable: true)]
    protected ?string $email = null;

    #[ORM\Column(type: 'string', length: 255, nullable: true)]
    protected ?string $lastName = null;

    #[ORM\Column(type: 'string', length: 255, nullable: true)]
    protected ?string $firstName = null;

    #[ORM\Column(type: 'datetime', nullable: true)]
    protected ?\DateTimeInterface $birthday = null;

    #[ORM\Column(type: 'text', nullable: true)]
    private ?string $message = null;

    #[ORM\Column(type: 'boolean')]
    private bool $active = false;

    #[ORM\Column(type: 'string', length: 500, nullable: true)]
    private ?string $token = null;

    #[ORM\ManyToOne(targetEntity: Website::class)]
    #[ORM\JoinColumn(nullable: false)]
    private ?Website $website = null;

    public function getLocale(): ?string
    {
        return $this->locale;
    }

    public function setLocale(?string $locale): self
    {
        $this->locale = $locale;

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

    public function getLastName(): ?string
    {
        return $this->lastName;
    }

    public function setLastName(?string $lastName): self
    {
        $this->lastName = $lastName;

        return $this;
    }

    public function getFirstName(): ?string
    {
        return $this->firstName;
    }

    public function setFirstName(?string $firstName): self
    {
        $this->firstName = $firstName;

        return $this;
    }

    public function setBirthday(?\DateTimeInterface $birthday)
    {
        $this->birthday = $birthday;

        return $this;
    }

    public function getBirthday(): ?\DateTimeInterface
    {
        return $this->birthday;
    }

    public function getMessage(): ?string
    {
        return $this->message;
    }

    public function setMessage(?string $message): self
    {
        $this->message = $message;

        return $this;
    }

    public function isActive(): ?bool
    {
        return $this->active;
    }

    public function setActive(bool $active): self
    {
        $this->active = $active;

        return $this;
    }

    public function getToken(): ?string
    {
        return $this->token;
    }

    public function setToken(?string $token): self
    {
        $this->token = $token;

        return $this;
    }

    public function getWebsite(): ?Website
    {
        return $this->website;
    }

    public function setWebsite(?Website $website): self
    {
        $this->website = $website;

        return $this;
    }
}
