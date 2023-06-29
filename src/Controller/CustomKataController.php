<?php

namespace App\Controller;

use App\Entity\Kata;
use DateTimeImmutable;
use Doctrine\ORM\EntityManagerInterface;
use Symfony\Component\HttpFoundation\Response;
use Symfony\Component\Routing\Annotation\Route;
use Symfony\Bundle\FrameworkBundle\Controller\AbstractController;

class CustomKataController extends AbstractController
{
	#[Route('/custom-kata/new')]
	public function new(EntityManagerInterface $entityManager): Response
	{
		$customKata = new Kata();
		$customKata->setName('Write a function that returns the string "hello world".');
		$customKata->setType('easy');
		$customKata->setQuestion('php');
		$customKata->setCreatedAt(new DateTimeImmutable());

		$entityManager->persist($customKata);
		$entityManager->flush();

		return new Response('Saved new question with id ' . $customKata->getId());
	}
}
