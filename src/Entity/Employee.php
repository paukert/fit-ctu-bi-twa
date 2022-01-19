<?php

namespace App\Entity;

use App\Repository\EmployeeRepository;
use Doctrine\Common\Collections\ArrayCollection;
use Doctrine\Common\Collections\Collection;
use Doctrine\ORM\Mapping as ORM;
use JMS\Serializer\Annotation\Exclude;
use JMS\Serializer\Annotation\ExclusionPolicy;
use Symfony\Component\Validator\Constraints as Assert;

/**
 * @ORM\Entity(repositoryClass=EmployeeRepository::class)
 *
 * @ExclusionPolicy("none")
 */
class Employee
{
	/**
	 * @ORM\Id
	 * @ORM\GeneratedValue
	 * @ORM\Column(type="integer")
	 */
	private $id;

	/**
	 * @ORM\Column(type="string", length=255)
	 * @Assert\NotBlank
	 * @Assert\Length(
	 *     min=2,
	 *     max=255,
	 *     minMessage = "First name must be at least {{ limit }} characters long",
	 *     maxMessage = "First name cannot be longer than {{ limit }} characters")
	 */
	private $firstName;

	/**
	 * @ORM\Column(type="string", length=255)
	 * @Assert\NotBlank
	 * @Assert\Length(
	 *     min=2,
	 *     max=255,
	 *     minMessage = "Last name must be at least {{ limit }} characters long",
	 *     maxMessage = "Last name cannot be longer than {{ limit }} characters")
	 */
	private $lastName;

	/**
	 * @ORM\Column(type="string", length=255, nullable=true)
	 * @Assert\Email(message = "The e-mail '{{ value }}' is not a valid e-mail.")
	 * @Assert\Length(
	 *     max=255,
	 *     maxMessage = "E-mail address cannot be longer than {{ limit }} characters")
	 */
	private $mail;

	/**
	 * @ORM\Column(type="string", length=20, nullable=true)
	 * @Assert\Length(
	 *     min=9,
	 *     max=20,
	 *     minMessage = "Phone number should be at least {{ limit }} characters long",
	 *     maxMessage = "Phone number should not be longer than {{ limit }} characters")
	 */
	private $phone;

	/**
	 * @ORM\Column(type="string", length=2000, nullable=true)
	 * @Assert\Length(
	 *     max=2000,
	 *     maxMessage = "Description cannot be longer than {{ limit }} characters")
	 */
	private $description;

	/**
	 * @ORM\OneToMany(targetEntity=Account::class, mappedBy="owner")
	 *
	 * @Exclude
	 */
	private $accounts;

	/**
	 * @ORM\ManyToMany(targetEntity=Role::class, inversedBy="employees")
	 * @ORM\JoinTable(name="employee_roles")
	 */
	private $roles;

	/**
	 * @ORM\Column(type="string", length=500, nullable=true)
	 * @Assert\Url
	 * @Assert\Length(
	 *     max=500,
	 *     maxMessage = "Website address cannot be longer than {{ limit }} characters")
	 */
	private $web;

	/**
	 * @ORM\Column(type="string", length=50, nullable=true)
	 *
	 * @Exclude
	 */
	private $imageFileName;


	public function __construct()
	{
		$this->accounts = new ArrayCollection();
		$this->roles = new ArrayCollection();
	}

	public function getId(): ?int
	{
		return $this->id;
	}

	public function getFirstName(): ?string
	{
		return $this->firstName;
	}

	public function setFirstName(string $firstName): self
	{
		$this->firstName = $firstName;

		return $this;
	}

	public function getLastName(): ?string
	{
		return $this->lastName;
	}

	public function setLastName(string $lastName): self
	{
		$this->lastName = $lastName;

		return $this;
	}

	public function getName(): string
	{
		return $this->getFirstName() . ' ' . $this->getLastName();
	}

	public function getMail(): ?string
	{
		return $this->mail;
	}

	public function setMail(?string $mail): self
	{
		$this->mail = $mail;

		return $this;
	}

	public function getPhone(): ?string
	{
		return $this->phone;
	}

	public function setPhone(?string $phone): self
	{
		$this->phone = $phone;

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

	/**
	 * @return Collection|Account[]
	 */
	public function getAccounts(): Collection
	{
		return $this->accounts;
	}

	public function addAccount(Account $account): self
	{
		if (!$this->accounts->contains($account)) {
			$this->accounts[] = $account;
			$account->setOwner($this);
		}

		return $this;
	}

	public function removeAccount(Account $account): self
	{
		if ($this->accounts->removeElement($account)) {
			// set the owning side to null (unless already changed)
			if ($account->getOwner() === $this) {
				$account->setOwner(null);
			}
		}

		return $this;
	}

	/**
	 * @return Collection|Role[]
	 */
	public function getRoles(): Collection
	{
		return $this->roles;
	}

	public function addRole(Role $role): self
	{
		if (!$this->roles->contains($role)) {
			$this->roles[] = $role;
		}

		return $this;
	}

	public function removeRole(Role $role): self
	{
		$this->roles->removeElement($role);

		return $this;
	}

	public function getWeb(): ?string
	{
		return $this->web;
	}

	public function setWeb(?string $web): self
	{
		$this->web = $web;

		return $this;
	}

	public function getImageFileName(): ?string
	{
		return $this->imageFileName;
	}

	public function setImageFileName(?string $imageFileName): self
	{
		$this->imageFileName = $imageFileName;

		return $this;
	}
}
