<?php

declare(strict_types=1);

require_once __DIR__ . '/../models/AgentEstateModel.php';

class AgentEstateController
{
    private AgentEstateModel $model;

    public function __construct(PDO $pdo)
    {
        $this->model = new AgentEstateModel($pdo);
    }

    public function index(): void
    {
        $agentId = (int) $_SESSION['user_id'];
        $data = ['estates' => $this->model->listByAgent($agentId)];
        require __DIR__ . '/../views/agentEstateList.php';
    }

    public function newForm(): void
    {
        $data = [
            'estate' => null,
            'agences' => $this->model->listAgences(),
            'errors' => [],
            'old' => [],
        ];
        require __DIR__ . '/../views/agentEstateForm.php';
    }

    public function create(): void
    {
        if (!lux_csrf_validate($_POST['csrf_token'] ?? null)) {
            $_SESSION['flash_error'] = 'Jeton de sécurité invalide.';
            header('Location: /agent/biens/nouveau');
            exit();
        }

        $agentId = (int) $_SESSION['user_id'];
        [$row, $errors] = $this->normalizeEstateInput($_POST);

        if ($errors !== []) {
            $data = [
                'estate' => null,
                'agences' => $this->model->listAgences(),
                'errors' => $errors,
                'old' => $_POST,
            ];
            require __DIR__ . '/../views/agentEstateForm.php';

            return;
        }

        $newId = $this->model->insert($agentId, $row);
        $_SESSION['flash_success'] = 'Annonce créée.';
        header('Location: /properties/' . $newId);
        exit();
    }

    public function editForm(int $estateId): void
    {
        $agentId = (int) $_SESSION['user_id'];
        $estate = $this->model->findForAgent($estateId, $agentId);
        if (!$estate) {
            http_response_code(404);
            require __DIR__ . '/../views/notFound.php';

            return;
        }

        $data = [
            'estate' => $estate,
            'agences' => $this->model->listAgences(),
            'errors' => [],
            'old' => [],
        ];
        require __DIR__ . '/../views/agentEstateForm.php';
    }

    public function update(int $estateId): void
    {
        if (!lux_csrf_validate($_POST['csrf_token'] ?? null)) {
            $_SESSION['flash_error'] = 'Jeton de sécurité invalide.';
            header('Location: /agent/biens/editer/' . $estateId);
            exit();
        }

        $agentId = (int) $_SESSION['user_id'];
        [$row, $errors] = $this->normalizeEstateInput($_POST);

        if ($errors !== []) {
            $estate = $this->model->findForAgent($estateId, $agentId);
            if (!$estate) {
                http_response_code(404);
                require __DIR__ . '/../views/notFound.php';

                return;
            }
            $data = [
                'estate' => array_merge($estate, $row),
                'agences' => $this->model->listAgences(),
                'errors' => $errors,
                'old' => [],
            ];
            require __DIR__ . '/../views/agentEstateForm.php';

            return;
        }

        if ($this->model->update($estateId, $agentId, $row)) {
            $_SESSION['flash_success'] = 'Annonce mise à jour.';
            header('Location: /properties/' . $estateId);
        } else {
            $_SESSION['flash_error'] = 'Enregistrement impossible.';
            header('Location: /agent/biens/editer/' . $estateId);
        }
        exit();
    }

    /**
     * @param array<string, mixed> $post
     * @return array{0: array<string, mixed>, 1: list<string>}
     */
    private function normalizeEstateInput(array $post): array
    {
        $errors = [];

        $country = trim((string) ($post['estate_country'] ?? ''));
        $address = trim((string) ($post['estate_address'] ?? ''));
        $type = trim((string) ($post['estate_type'] ?? ''));
        $price = (float) str_replace(',', '.', (string) ($post['price'] ?? '0'));
        $surface = (int) ($post['surface'] ?? 0);
        $rooms = (int) ($post['rooms_count'] ?? 0);
        $bedrooms = (int) ($post['bedrooms_count'] ?? 0);
        $description = trim((string) ($post['description'] ?? ''));
        $status = trim((string) ($post['estate_status'] ?? 'available'));
        $energy = strtoupper(trim((string) ($post['energy_class'] ?? '')));
        $agenceId = (int) ($post['agence_id'] ?? 0);

        if ($country === '' || $address === '' || $type === '') {
            $errors[] = 'Pays, adresse et type sont obligatoires.';
        }
        if ($price <= 0) {
            $errors[] = 'Prix invalide.';
        }
        if ($surface <= 0 || $rooms <= 0) {
            $errors[] = 'Surface et nombre de pièces doivent être positifs.';
        }
        if ($bedrooms < 0) {
            $errors[] = 'Nombre de chambres invalide.';
        }
        if ($agenceId <= 0) {
            $errors[] = 'Choisissez une agence.';
        }
        $allowedStatus = ['available', 'reserved', 'sold', 'withdrawn'];
        if (!in_array($status, $allowedStatus, true)) {
            $status = 'available';
        }
        if (strlen($energy) > 3) {
            $energy = substr($energy, 0, 3);
        }

        $row = [
            'estate_country' => $country,
            'estate_address' => $address,
            'estate_type' => $type,
            'price' => $price,
            'surface' => $surface,
            'rooms_count' => $rooms,
            'bedrooms_count' => $bedrooms,
            'description' => $description !== '' ? $description : null,
            'estate_status' => $status,
            'energy_class' => $energy !== '' ? $energy : null,
            'agence_id' => $agenceId,
        ];

        return [$row, $errors];
    }
}
