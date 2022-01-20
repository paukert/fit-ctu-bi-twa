<?php

/*
 * Source: https://symfony.com/doc/5.4/controller/upload_file.html
 */

namespace App\Service;

use Symfony\Component\HttpFoundation\File\Exception\FileException;
use Symfony\Component\HttpFoundation\File\UploadedFile;

class FileManager
{
	private string $targetDirectory;

	public function __construct(string $targetDirectory)
	{
		$this->targetDirectory = $targetDirectory;
	}

	public function upload(UploadedFile $file): ?string
	{
		$fileName = date('Y-m-d-H-i-s') . '-' . uniqid() . '.' . $file->guessExtension();

		try {
			$file->move($this->targetDirectory, $fileName);
		} catch (FileException) {
			$fileName = null;
			// TODO ... handle exception if something happens during file upload <-- what else can I do?
		}

		return $fileName;
	}

	public function delete(?string $file): void
	{
		if (!empty($file))
			unlink($this->targetDirectory . '/' . $file);
	}
}
