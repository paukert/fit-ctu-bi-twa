<?php

namespace App\Form\Type;

use App\Entity\Employee;
use Doctrine\ORM\EntityRepository;
use Symfony\Bridge\Doctrine\Form\Type\EntityType;
use Symfony\Component\Form\AbstractType;
use Symfony\Component\Form\Extension\Core\Type\ChoiceType;
use Symfony\Component\Form\Extension\Core\Type\DateType;
use Symfony\Component\Form\Extension\Core\Type\PasswordType;
use Symfony\Component\Form\Extension\Core\Type\RepeatedType;
use Symfony\Component\Form\Extension\Core\Type\TextType;
use Symfony\Component\Form\FormBuilderInterface;

class AccountType extends AbstractType
{
	public function buildForm(FormBuilderInterface $builder, array $options)
	{
		return $builder
			->add('username', TextType::class, [
				'label' => 'Username'
			])
			->add('password', RepeatedType::class, [
				'type' => PasswordType::class,
				'invalid_message' => 'The password fields must match.',
				'required' => true,
				'first_options' => ['label' => 'Password'],
				'second_options' => ['label' => 'Repeat Password']
			])
			->add('owner', EntityType::class, [
				'label' => 'Owner',
				'class' => Employee::class,
				'query_builder' => function (EntityRepository $er) {
					return $er->createQueryBuilder('employee')
						->addOrderBy('employee.lastName', 'ASC')
						->addOrderBy('employee.firstName', 'ASC');
				},
				'choice_label' => function (Employee $employee) {
					return $employee->getName();
				}
			])
			->add('validTo', DateType::class, [
				'label' => 'Valid to',
				'required' => false,
				'widget' => 'single_text'
			])
			->add('roles', ChoiceType::class, [
				'label' => 'Roles',
				'multiple' => true,
				'expanded' => true,
				'choices' => [
					'Admin' => 'ROLE_ADMIN',
				],
			]);
	}
}
