<?php

namespace App\Controllers;

use App\Core\Controller;
use App\Models\Client;
use App\Models\TenantUsage;

class ClientsController extends Controller
{
    protected $currentTenantId;

    public function __construct()
    {
        // Removed parent::__construct() because base Controller has no constructor
        $this->currentTenantId = $_SESSION['tenant_id'] ?? null;
        
        if (!$this->currentTenantId) {
            $this->redirect('/login');
            exit;
        }
    }

      /**
     * Display all clients - Main entry point
     */
public function index()
    {
        $clients = Client::where('tenant_id', $this->currentTenantId)
                         ->orderBy('created_at', 'DESC')
                         ->get();

        $pageTitle = 'Clients';
        $total_clients = count($clients);

        // Capture content for layout
        ob_start();
        require __DIR__ . '/../../resources/View/clients/index.php';
        $content = ob_get_clean();

        // Render full layout
        require __DIR__ . '/../../resources/View/layouts/main.php';
    }

    public function Clients()
    {
        $this->index();
    }

    public function create()
    {
        $pageTitle = 'Add New Client';

        ob_start();
        require __DIR__ . '/../../resources/View/clients/create.php';
        $content = ob_get_clean();

        require __DIR__ . '/../../resources/View/layouts/main.php';
    }


    public function store()
    {
        if (!Client::canAddMoreClients($this->currentTenantId)) {
            $_SESSION['error'] = "আপনার প্ল্যানের সর্বোচ্চ ক্লায়েন্ট সীমা পূর্ণ হয়েছে। প্ল্যান আপগ্রেড করুন।";
            $this->redirect('/clients');
            return;
        }

        $data = $this->sanitizeInput($_POST ?? []);
        $data['tenant_id'] = $this->currentTenantId;
        $data['created_by'] = $_SESSION['user_id'] ?? null;
        $data['status'] = 'targeted';

        // Image upload
        if (isset($_FILES['image']) && $_FILES['image']['error'] === 0) {
            $uploadPath = $this->uploadImage($_FILES['image'], 'clients');
            if ($uploadPath) {
                $data['image_path'] = $uploadPath;
            }
        }

        $client = Client::create($data);

        if ($client) {
            $this->updateTenantClientCount($this->currentTenantId, +1);
            $_SESSION['success'] = "Client added successfully!";
            $this->redirect('/clients');
        } else {
            $_SESSION['error'] = "Failed to add client.";
            $this->redirect('/clients/create');
        }
    }

   /**
     * Show single client profile
     */
    public function show($id)
    {
        $client = Client::where('tenant_id', $this->currentTenantId)
                        ->where('id', $id)
                        ->first();

        if (!$client) {
            $_SESSION['error'] = "Client not found.";
            $this->redirect('/clients');
            return;
        }

        $pageTitle = $client->name ?? 'Client Profile';

        // Capture content
        ob_start();
        require __DIR__ . '/../../resources/View/clients/show.php';
        $content = ob_get_clean();

        // Render full layout
        require __DIR__ . '/../../resources/View/layouts/main.php';
    }

    /**
     * Show edit form
     */
    public function edit($id)
    {
        $client = Client::where('tenant_id', $this->currentTenantId)
                        ->where('id', $id)
                        ->first();

        if (!$client) {
            $_SESSION['error'] = "Client not found.";
            $this->redirect('/clients');
            return;
        }

        $pageTitle = 'Edit Client';

        // Capture content
        ob_start();
        require __DIR__ . '/../../resources/View/clients/edit.php';
        $content = ob_get_clean();

        // Render full layout
        require __DIR__ . '/../../resources/View/layouts/main.php';
    }

    public function update($id)
    {
        $client = Client::where('tenant_id', $this->currentTenantId)
                        ->where('id', $id)
                        ->first();

        if (!$client) {
            $_SESSION['error'] = "Client not found.";
            $this->redirect('/clients');
            return;
        }

        $data = $this->sanitizeInput($_POST);
        $data['updated_by'] = $_SESSION['user_id'] ?? null;

        if (isset($_FILES['image']) && $_FILES['image']['error'] === 0) {
            $uploadPath = $this->uploadImage($_FILES['image'], 'clients');
            if ($uploadPath) {
                $data['image_path'] = $uploadPath;
            }
        }

        if ($client->update($data)) {
            $_SESSION['success'] = "Client updated successfully!";
        } else {
            $_SESSION['error'] = "Failed to update client.";
        }

        $this->redirect("/clients/{$id}");
    }

    public function destroy($id)
    {
        $client = Client::where('tenant_id', $this->currentTenantId)
                        ->where('id', $id)
                        ->first();

        if ($client && $client->delete()) {
            $this->updateTenantClientCount($this->currentTenantId, -1);
            $_SESSION['success'] = "Client deleted successfully!";
        } else {
            $_SESSION['error'] = "Failed to delete client.";
        }

        $this->redirect('/clients');
    }

    private function updateTenantClientCount($tenantId, $increment)
    {
        $usage = TenantUsage::where('tenant_id', $tenantId)->first();
        if ($usage) {
            $usage->current_clients = max(0, $usage->current_clients + $increment);
            $usage->save();
        }
    }


}