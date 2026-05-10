<?php

class LoginModel
{
    private PDO $pdo;

    public function __construct(PDO $pdo)
    {
        $this->pdo = $pdo;
    }

    public function get_user_by_mail(string $mail): array|false
    {
        $stmt = $this->pdo->prepare(
            'SELECT user_id, first_name, password, is_admin, is_agent FROM user_ WHERE mail = ? LIMIT 1'
        );
        $stmt->execute([$mail]);
        return $stmt->fetch(PDO::FETCH_ASSOC);
    }
}
