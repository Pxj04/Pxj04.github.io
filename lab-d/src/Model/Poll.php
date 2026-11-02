<?php

namespace App\Model;

use App\Service\Config;

class Poll
{
    private ?int $id = null;
    private string $question = '';
    private string $answerA = '';
    private string $answerB = '';

    private static function connection(): \PDO
    {
        return new \PDO(
            Config::get('db_dsn'),
            Config::get('db_user'),
            Config::get('db_pass'),
            [\PDO::ATTR_ERRMODE => \PDO::ERRMODE_EXCEPTION]
        );
    }

    public function getId(): ?int
    {
        return $this->id;
    }

    public function getQuestion(): string
    {
        return $this->question;
    }

    public function getAnswerA(): string
    {
        return $this->answerA;
    }

    public function getAnswerB(): string
    {
        return $this->answerB;
    }

    public function fill(array $data): self
    {
        $this->question = trim($data['question'] ?? '');
        $this->answerA = trim($data['answer_a'] ?? '');
        $this->answerB = trim($data['answer_b'] ?? '');

        return $this;
    }

    public static function fromArray(array $data): self
    {
        $poll = new self();
        $poll->fill($data);
        $poll->id = isset($data['id']) ? (int) $data['id'] : null;

        return $poll;
    }

    public static function findAll(): array
    {
        $statement = self::connection()->query(
            'SELECT * FROM poll ORDER BY id DESC'
        );

        $polls = [];
        foreach ($statement->fetchAll(\PDO::FETCH_ASSOC) as $row) {
            $polls[] = self::fromArray($row);
        }

        return $polls;
    }

    public static function find(int $id): ?self
    {
        $statement = self::connection()->prepare(
            'SELECT * FROM poll WHERE id = :id'
        );
        $statement->execute(['id' => $id]);
        $row = $statement->fetch(\PDO::FETCH_ASSOC);

        return $row ? self::fromArray($row) : null;
    }

    public function save(): void
    {
        $pdo = self::connection();
        $data = [
            'question' => $this->question,
            'answer_a' => $this->answerA,
            'answer_b' => $this->answerB,
        ];

        if ($this->id === null) {
            $statement = $pdo->prepare(
                'INSERT INTO poll (question, answer_a, answer_b)
                 VALUES (:question, :answer_a, :answer_b)'
            );
            $statement->execute($data);
            $this->id = (int) $pdo->lastInsertId();
        } else {
            $data['id'] = $this->id;
            $statement = $pdo->prepare(
                'UPDATE poll
                 SET question = :question,
                     answer_a = :answer_a,
                     answer_b = :answer_b
                 WHERE id = :id'
            );
            $statement->execute($data);
        }
    }

    public function delete(): void
    {
        $statement = self::connection()->prepare(
            'DELETE FROM poll WHERE id = :id'
        );
        $statement->execute(['id' => $this->id]);
        $this->id = null;
    }
}