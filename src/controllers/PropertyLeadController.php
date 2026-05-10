<?php

declare(strict_types=1);

require_once __DIR__ . '/../models/LeadRequestModel.php';

class PropertyLeadController
{
    private LeadRequestModel $model;

    public function __construct(PDO $pdo)
    {
        $this->model = new LeadRequestModel($pdo);
    }

    public function submit(int $estateId): void
    {
        if (($_SESSION['role'] ?? null) !== 'user') {
            $_SESSION['flash_error'] = 'Connectez-vous avec un compte client pour envoyer une demande.';
            header('Location: /login');
            exit();
        }

        if (!lux_csrf_validate($_POST['csrf_token'] ?? null)) {
            $_SESSION['flash_error'] = 'Jeton de sécurité invalide. Réessayez.';
            header('Location: /properties/' . $estateId);
            exit();
        }

        $kind = $_POST['request_kind'] ?? 'information';
        if (!in_array($kind, ['information', 'visite'], true)) {
            $kind = 'information';
        }

        $message = trim((string) ($_POST['message'] ?? ''));
        $visitAt = trim((string) ($_POST['preferred_visit_at'] ?? ''));

        if ($message === '' && $kind === 'information') {
            $_SESSION['flash_error'] = 'Indiquez votre question ou précisez votre demande.';
            header('Location: /properties/' . $estateId);
            exit();
        }

        if ($kind === 'visite' && $visitAt === '') {
            $_SESSION['flash_error'] = 'Choisissez une date ou créneau souhaité pour la visite.';
            header('Location: /properties/' . $estateId);
            exit();
        }

        $userId = (int) $_SESSION['user_id'];
        $visitSql = $visitAt !== '' ? str_replace('T', ' ', $visitAt) : null;

        if ($this->model->create($userId, $estateId, $kind, $message, $visitSql)) {
            $_SESSION['flash_success'] = 'Votre demande a été transmise à l’agence.';
        } else {
            $_SESSION['flash_error'] = 'Envoi impossible pour le moment.';
        }

        header('Location: /properties/' . $estateId);
        exit();
    }
}
