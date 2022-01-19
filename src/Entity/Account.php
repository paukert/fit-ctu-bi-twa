<?php

namespace App\Entity;

use App\Repository\AccountRepository;
use Doctrine\ORM\Mapping as ORM;
use JMS\Serializer\Annotation\Exclude;
use JMS\Serializer\Annotation\ExclusionPolicy;
use Symfony\Component\Security\Core\User\PasswordAuthenticatedUserInterface;
use Symfony\Component\Security\Core\User\UserInterface;
use Symfony\Component\Validator\Constraints as Assert;

/**
 * @ORM\Entity(repositoryClass=AccountRepository::class)
 *
 * @ExclusionPolicy("none")
 */
class Account implements UserInterface, PasswordAuthenticatedUserInterface
{
	/**
	 * @ORM\Id
	 * @ORM\GeneratedValue
	 * @ORM\Column(type="integer")
	 */
	private $id;

	/**
	 * @ORM\Column(type="string", length=180, unique=true)
	 *
	 * @Assert\NotBlank
	 * @Assert\Length(
	 *     min=3,
	 *     max=255,
	 *     minMessage = "Username must be at least {{ limit }} characters long",
	 *     maxMessage = "Username cannot be longer than {{ limit }} characters")
	 */
	private $username;

	/**
	 * @ORM\Column(type="json")
	 *
	 * @Exclude
	 */
	private $roles = [];

	/**
	 * @var string The hashed password
	 * @ORM\Column(type="string")
	 *
	 * @Assert\NotBlank
	 *
	 * @Exclude
	 */
	private $password;

	/**
	 * @ORM\ManyToOne(targetEntity=Employee::class, inversedBy="accounts")
	 * @ORM\JoinColumn(nullable=false)
	 *
	 * @Exclude
	 */
	private $owner;

	/**
	 * @ORM\Column(type="datetime", nullable=true)
	 *
	 * @Assert\Type(type="\DateTimeInterface")
	 */
	private $validTo;

	public function getId(): ?int
	{
		return $this->id;
	}

	/**
	 * @deprecated since Symfony 5.3, use getUserIdentifier instead
	 */
	public function getUsername(): string
	{
		return (string)$this->username;
	}

	public function setUsername(string $username): self
	{
		$this->username = $username;

		return $this;
	}

	/**
	 * A visual identifier that represents this user.
	 *
	 * @see UserInterface
	 */
	public function getUserIdentifier(): string
	{
		return (string)$this->username;
	}

	/**
	 * @see UserInterface
	 */
	public function getRoles(): array
	{
		$roles = $this->roles;
		// guarantee every user at least has ROLE_USER
		$roles[] = 'ROLE_USER';

		if ($this->isPermanent())
			$roles[] = 'ROLE_ACCOUNT_PERMANENT';
		else
			$roles[] = 'ROLE_ACCOUNT_TEMPORARY';

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
	 * Returning a salt is only needed, if you are not using a modern
	 * hashing algorithm (e.g. bcrypt or sodium) in your security.yaml.
	 *
	 * @see UserInterface
	 */
	public function getSalt(): ?string
	{
		return null;
	}

	/**
	 * @see UserInterface
	 */
	public function eraseCredentials()
	{
		// If you store any temporary, sensitive data on the user, clear it here
		// $this->plainPassword = null;
	}

	public function getOwner(): ?Employee
	{
		return $this->owner;
	}

	public function setOwner(?Employee $owner): self
	{
		$this->owner = $owner;

		return $this;
	}

	public function getValidTo(): ?\DateTimeInterface
	{
		return $this->validTo;
	}

	public function setValidTo(?\DateTimeInterface $validTo): self
	{
		$this->validTo = $validTo;

		return $this;
	}

	public function isPermanent(): bool
	{
		return $this->getValidTo() === null;
	}
}
