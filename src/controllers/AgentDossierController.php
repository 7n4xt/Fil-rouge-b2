<?php

declare(strict_types=1);

require_once __DIR__ . '/../models/ClientDossierModel.php';

class AgentDossierController
{
    private ClientDossierModel $model;

    public function __construct(PDO $pdo)
    {
        $this->model = new ClientDossierModel($pdo);
    }

    public function index(): void
    {
        $agentId = (int) $_SESSION['user_id'];
        $data = [
            'dossiers' => $this->model->listForAgent($agentId),
            'steps' => ClientDossierModel::STEPS,
        ];
        require __DIR__ . '/../views/agentDossiers.php';
    }

    public function create(): void
    {
        if (!lux_csrf_validate($_POST['csrf_token'] ?? null)) {
            $_SESSION['flash_error'] = 'Jeton de sécurité invalide.';
            header('Location: /agent/dossiers');
            exit();
        }

        $agentId = (int) $_SESSION['user_id'];
        $mail = trim((string) ($_POST['client_mail'] ?? ''));
        $flow = $_POST['flow_type'] ?? 'achat';
        if (!in_array($flow, ['achat', 'vente'], true)) {
            $flow = 'achat';
        }
        $title = trim((string) ($_POST['title'] ?? ''));
        $notes = trim((string) ($_POST['notes_public'] ?? ''));
        $estateIdRaw = trim((string) ($_POST['estate_id'] ?? ''));
        $estateId = $estateIdRaw !== '' ? (int) $estateIdRaw : null;

        if (!filter_var($mail, FILTER_VALIDATE_EMAIL)) {
            $_SESSION['flash_error'] = 'Courriel client invalide.';
            header('Location: /agent/dossiers');
            exit();
        }

        $clientId = $this->model->findClientIdByMail($mail);
        if ($clientId === null) {
            $_SESSION['flash_error'] = 'Aucun compte client trouvé avec ce courriel (compte non agent / non admin).';
            header('Location: /agent/dossiers');
            exit();
        }

        if ($this->model->create($clientId, $agentId, $estateId, $flow, $title, $notes)) {
            $_SESSION['flash_success'] = 'Dossier créé.';
        } else {
            $_SESSION['flash_error'] = 'Création impossible.';
        }

        header('Location: /agent/dossiers');
        exit();
    }

    public function update(): void
    {
        if (!lux_csrf_validate($_POST['csrf_token'] ?? null)) {
            $_SESSION['flash_error'] = 'Jeton de sécurité invalide.';
            header('Location: /agent/dossiers');
            exit();
        }

        $agentId = (int) $_SESSION['user_id'];
        $id = (int) ($_POST['dossier_id'] ?? 0);
        $step = trim((string) ($_POST['step'] ?? ''));
        if (!in_array($step, ClientDossierModel::STEPS, true)) {
            $_SESSION['flash_error'] = 'Étape invalide.';
            header('Location: /agent/dossiers');
            exit();
        }

        $notesPublic = trim((string) ($_POST['notes_public'] ?? ''));
        $notesInternal = trim((string) ($_POST['notes_internal'] ?? ''));

        if ($id <= 0 || !$this->model->updateByAgent($id, $agentId, $step, $notesPublic, $notesInternal)) {
            $_SESSION['flash_error'] = 'Mise à jour impossible.';
        } else {
            $_SESSION['flash_success'] = 'Dossier mis à jour.';
        }

        header('Location: /agent/dossiers');
        exit();
    }
}
