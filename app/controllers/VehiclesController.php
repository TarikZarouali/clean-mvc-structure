<?php

class vehiclesController extends Controller
{
    private $vehicleModel;
    private $storeModel;
    private int $delay = 2;


    public function __construct()
    {
        $this->vehicleModel = $this->model('vehicleModel');
        $this->storeModel = $this->model('storeModel');
    }

    public function overview()
    {
        if ($_SERVER['REQUEST_METHOD'] == 'GET') {
            $getVehicles = $this->vehicleModel->getActiveVehicles();
            $getStores = $this->storeModel->getActiveStores(); // Retrieve active stores

            $data = [
                'Vehicles' => $getVehicles,
            ];

            $this->view('vehicles/overview', $data);
        }
    }


    public function create()
    {
        if ($_SERVER['REQUEST_METHOD'] == 'POST') {
            $post = filter_input_array(INPUT_POST, FILTER_SANITIZE_FULL_SPECIAL_CHARS);

            // Validate and process the form data, and insert a new vehicle into the database
            $createVehicle = $this->vehicleModel->createVehicle($post);

            if ($createVehicle) {
                header('Location: ' . URLROOT . '/vehiclescontroller/overview');
                exit();
            } else {
                // If vehicle creation failed, you can handle it here
                header('Location: ' . URLROOT . '/vehiclescontroller/overview');
                exit();
            }
        } else {
            // Display the form for creating a new vehicle with the list of active stores
            $stores = $this->storeModel->getActiveStores(); // Retrieve active stores
            $data = [
                'Stores' => $stores,
            ];

            $this->view('vehicles/create', $data);
        }
    }

    public function update($vehicleId)
    {
        $selectedVehicle = $this->vehicleModel->getVehicleById($vehicleId);
        $stores = $this->storeModel->getActiveStores();

        if (!$selectedVehicle) {
            die('Vehicle not found');
        }

        if ($_SERVER['REQUEST_METHOD'] == 'POST') {
            // Filter and validate your POST data properly
            $post = filter_input_array(INPUT_POST, FILTER_SANITIZE_FULL_SPECIAL_CHARS);

            // Update the vehicle using the retrieved POST data
            $updated = $this->vehicleModel->updateVehicle($vehicleId, $post);

            if ($updated) {
                // Vehicle updated successfully, redirect to the vehicle overview page
                header('Location: ' . URLROOT . 'vehiclescontroller/overview');
                exit;
            } else {
                die('Vehicle update failed');
            }
        }

        // Load the update view with the selected vehicle data
        $data = [
            'vehicle' => $selectedVehicle,
            'Stores' => $stores
        ];
        $this->view('vehicles/update', $data);
    }

    public function delete($vehicleId)
    {
        if ($this->vehicleModel->deleteVehicle($vehicleId)) {
            header('Location:' . URLROOT . 'vehiclescontroller/overview');
        } else {
            echo 'An error occurred'; // Corrected capitalization
        }
    }
}