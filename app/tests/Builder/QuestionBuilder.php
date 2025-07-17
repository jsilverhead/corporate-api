<?php

declare(strict_types=1);

namespace App\Tests\Builder;

use App\Domain\Survey\Question;
use App\Domain\Survey\Service\CreateQuestionService;
use Doctrine\ORM\EntityManagerInterface;

class QuestionBuilder
{
    /** @psalm-var non-empty-string|null */
    private ?string $question = null;

    public function __construct(
        private readonly CreateQuestionService $createQuestionService,
        private readonly EntityManagerInterface $entityManager,
    ) {
    }

    public function build(): Question
    {
        $question = $this->createQuestionService->create(question: $this->question ?? 'Укажите ваше хобби?');

        $this->entityManager->flush();

        return $question;
    }
}
