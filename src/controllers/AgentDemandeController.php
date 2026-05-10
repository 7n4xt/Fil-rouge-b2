<?php

declare(strict_types=1);

require_once __DIR__ . '/../models/LeadRequestModel.php';

class AgentDemandeController
{
    private LeadRequestModel $model;

    public function __construct(PDO $pdo)
    {
        $this->model = new LeadRequestModel($pdo);
    }

    public function index(): void
    {
        try {
            $agentId = (int) $_SESSION['user_id'];
            $data = ['requests' => $this->model->listForAgent($agentId)];
            require __DIR__ . '/../views/agentDemandes.php';
        } catch (Throwable $e) {
            error_log('AgentDemandeController::index ' . $e->getMessage());
            render_http_error(
                503,
                'Base de données incomplète',
                'La table « lead_request » est probablement absente. Dans phpMyAdmin ou la CLI MySQL, exécutez le fichier database/schema_extensions.sql sur la base real_estate (ou réimportez real_estate.sql à jour). Détail technique : ' . $e->getMessage()
            );
        }
    }

    public function update(): void
    {
        if (!lux_csrf_validate($_POST['csrf_token'] ?? null)) {
            $_SESSION['flash_error'] = 'Jeton de sécurité invalide.';
            header('Location: /agent/demandes');
            exit();
        }

        try {
            $agentId = (int) $_SESSION['user_id'];
            $id = (int) ($_POST['lead_request_id'] ?? 0);
            $status = trim((string) ($_POST['status'] ?? ''));
            $note = trim((string) ($_POST['agent_note'] ?? ''));

            $allowed = ['nouveau', 'en_cours', 'traite', 'annule'];
            if ($id <= 0 || !in_array($status, $allowed, true)) {
                $_SESSION['flash_error'] = 'Données invalides.';
                header('Location: /agent/demandes');
                exit();
            }

            if ($this->model->updateByAgent($id, $agentId, $status, $note)) {
                $_SESSION['flash_success'] = 'Demande mise à jour.';
            } else {
                $_SESSION['flash_error'] = 'Mise à jour impossible.';
            }

            header('Location: /agent/demandes');
            exit();
        } catch (Throwable $e) {
            error_log('AgentDemandeController::update ' . $e->getMessage());
            render_http_error(
                503,
                'Base de données incomplète',
                'Importez database/schema_extensions.sql. Erreur : ' . $e->getMessage()
            );
        }
    }
}
