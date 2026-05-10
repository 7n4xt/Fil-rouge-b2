<?php

declare(strict_types=1);

require_once __DIR__ . '/../models/LeadRequestModel.php';
require_once __DIR__ . '/../models/ClientDossierModel.php';

class ClientAccountController
{
    private LeadRequestModel $leads;
    private ClientDossierModel $dossiers;

    public function __construct(PDO $pdo)
    {
        $this->leads = new LeadRequestModel($pdo);
        $this->dossiers = new ClientDossierModel($pdo);
    }

    public function index(): void
    {
        $uid = (int) $_SESSION['user_id'];
        $data = [
            'requests' => $this->leads->listForUser($uid),
            'dossiers' => $this->dossiers->listForUser($uid),
            'steps' => ClientDossierModel::STEPS,
        ];
        require __DIR__ . '/../views/clientAccount.php';
    }
}
