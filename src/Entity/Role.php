<?php

namespace App\Entity;

use App\Repository\RoleRepository;
use Doctrine\Common\Collections\ArrayCollection;
use Doctrine\Common\Collections\Collection;
use Doctrine\ORM\Mapping as ORM;
use JMS\Serializer\Annotation as Serialize;
use Symfony\Component\Validator\Constraints as Assert;

/**
 * @ORM\Entity(repositoryClass=RoleRepository::class)
 *
 * @Serialize\ExclusionPolicy("none")
 */
class Role
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
	 *     min=5,
	 *     max=255,
	 *     minMessage = "Description must be at least {{ limit }} characters long",
	 *     maxMessage = "Description cannot be longer than {{ limit }} characters")
	 */
	private $description;

	/**
	 * @ORM\Column(type="string", length=100)
	 * @Assert\NotBlank
	 * @Assert\Length(
	 *     min=3,
	 *     max=100,
	 *     minMessage = "Title (abbreviation) must be at least {{ limit }} characters long",
	 *     maxMessage = "Title (abbreviation) cannot be longer than {{ limit }} characters")
	 */
	private $title;

	/**
	 * @ORM\ManyToMany(targetEntity=Employee::class, mappedBy="roles")
	 *
	 * @Serialize\Exclude
	 */
	private $employees;

	/**
	 * @ORM\Column(type="boolean")
	 * @Assert\Type(type="bool")
	 *
	 * @Serialize\Exclude
	 */
	private $isVisible;

	public function __construct()
	{
		$this->employees = new ArrayCollection();
	}

	public function getId(): ?int
	{
		return $this->id;
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

	public function getTitle(): ?string
	{
		return $this->title;
	}

	public function setTitle(string $title): self
	{
		$this->title = $title;

		return $this;
	}

	/**
	 * @return Collection|Employee[]
	 */
	public function getEmployees(): Collection
	{
		return $this->employees;
	}

	public function addEmployee(Employee $employee): self
	{
		if (!$this->employees->contains($employee)) {
			$this->employees[] = $employee;
			$employee->addRole($this);
		}

		return $this;
	}

	public function removeEmployee(Employee $employee): self
	{
		if ($this->employees->removeElement($employee)) {
			$employee->removeRole($this);
		}

		return $this;
	}

	public function getIsVisible(): ?bool
	{
		return $this->isVisible;
	}

	public function setIsVisible(bool $isVisible): self
	{
		$this->isVisible = $isVisible;

		return $this;
	}
}
