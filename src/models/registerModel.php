<?php

class RegisterModel
{
    private PDO $pdo;

    public function __construct(PDO $pdo)
    {
        $this->pdo = $pdo;
    }

    public function mail_exists(string $mail): bool
    {
        $stmt = $this->pdo->prepare('SELECT user_id FROM user_ WHERE mail = ?');
        $stmt->execute([$mail]);
        return (bool) $stmt->fetchColumn();
    }

    public function create_user(array $data): bool
    {
        $stmt = $this->pdo->prepare(
            'INSERT INTO user_ (first_name, last_name, mail, phone_number, password) VALUES (?, ?, ?, ?, ?)'
        );
        return $stmt->execute([
            $data['first_name'],
            $data['last_name'],
            $data['mail'],
            $data['phone_number'],
            $data['password'],
        ]);
    }
}
