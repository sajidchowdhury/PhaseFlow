<?php

namespace App\Controllers;

use App\Core\Controller;
use App\Models\Client;
use App\Models\TenantUsage;

class ClientController extends Controller
{
    protected $currentTenantId;

    public function __construct()
    {
        parent::__construct();
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

        $data = [
            'clients' => $clients,
            'total_clients' => count($clients),
            'page_title' => 'Clients'
        ];

        $this->view('clients/index', $data);
    }

    /**
     * Legacy compatibility (if routing calls Clients())
     */
    public function Clients()
    {
        $this->index(); // Redirect to proper index method
    }

    /**
     * Show create form
     */
    public function create()
    {
        $data = ['page_title' => 'Add New Client'];
        $this->view('clients/create', $data);
    }

    /**
     * Store new client
     */
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

        $data = [
            'client' => $client,
            'full_profile' => $client->getFullProfile(),
            'page_title' => $client->name
        ];

        $this->view('clients/show', $data);
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

        $data = [
            'client' => $client,
            'page_title' => 'Edit Client'
        ];

        $this->view('clients/edit', $data);
    }

    /**
     * Update client
     */
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

        // Image update
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

    /**
     * Delete client
     */
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