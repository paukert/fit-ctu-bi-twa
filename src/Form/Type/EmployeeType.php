<?php

namespace App\Form\Type;

use App\Entity\Employee;
use App\Entity\Role;
use Doctrine\ORM\EntityRepository;
use Symfony\Bridge\Doctrine\Form\Type\EntityType;
use Symfony\Component\Form\AbstractType;
use Symfony\Component\Form\Extension\Core\Type\EmailType;
use Symfony\Component\Form\Extension\Core\Type\FileType;
use Symfony\Component\Form\Extension\Core\Type\TelType;
use Symfony\Component\Form\Extension\Core\Type\TextType;
use Symfony\Component\Form\Extension\Core\Type\UrlType;
use Symfony\Component\Form\FormBuilderInterface;
use Symfony\Component\OptionsResolver\OptionsResolver;
use Symfony\Component\Validator\Constraints\File;

class EmployeeType extends AbstractType
{
	public function buildForm(FormBuilderInterface $builder, array $options)
	{
		return $builder
			->add('firstName', TextType::class, [
				'label' => 'First name'
			])
			->add('lastName', TextType::class, [
				'label' => 'Last name'
			])
			->add('roles', EntityType::class, [
				'label' => 'Roles',
				'class' => Role::class,
				'query_builder' => function (EntityRepository $er) {
					return $er->createQueryBuilder('role')
						->orderBy('role.description', 'ASC');
				},
				'choice_label' => 'description',
				'multiple' => true,
				'expanded' => true
			])
			->add('mail', EmailType::class, [
				'label' => 'E-mail address',
				'required' => false
			])
			->add('phone', TelType::class, [
				'label' => 'Phone number',
				'required' => false
			])
			->add('web', UrlType::class, [
				'label' => 'Website',
				'required' => false
			])
			->add('description', TextType::class, [
				'label' => 'Description',
				'required' => false
			])
			// Source: https://symfony.com/doc/5.4/controller/upload_file.html
			->add('image', FileType::class, [
				'label' => 'New profile image',
				'mapped' => false,
				'required' => false,
				'constraints' => [
					new File([
						'maxSize' => '1024k',
						'mimeTypes' => [
							'image/jpeg',
							'image/png',
						],
						'mimeTypesMessage' => 'Please upload a valid image (only JPEG and PNG images are supported)',
					])
				]
			]);
	}

	public function configureOptions(OptionsResolver $resolver)
	{
		$resolver->setDefaults([
			'data_class' => Employee::class,
		]);
	}
}
