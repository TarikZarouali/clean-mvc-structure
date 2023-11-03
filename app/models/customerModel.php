<?php

class customerModel
{
    // Properties, fields
    private $db;

    public function __construct()
    {
        $this->db = new Database();
    }


    public function getActiveCustomers()
    {
        try {
            $getCustomersQuery = "SELECT `customerId`, `customerType`, `customerFirstName`, `customerLastName`, `customerEmail`, `customerPhone`, `customerAddress`, `customerZipCode`, `customerCreateDate`, `customerIsActive` FROM `customers` WHERE customerIsActive = 1";

            $this->db->query($getCustomersQuery);

            $result = $this->db->resultSet();

            return $result ?? [];
        } catch (PDOException $ex) {
            error_log("Error: Failed to get active customers from the database in class storeModel.");
            die('Error: Failed to get active customers');
        }
    }

    public function getCustomerById($customerId)
    {
        try {
            $getCustomerQuery = "SELECT `customerId`, `customerType`, `customerFirstName`, `customerLastName`, `customerEmail`, `customerPhone`, `customerAddress`, `customerZipCode`, `customerCreateDate`, `customerIsActive` FROM `customers` WHERE `customerId` = :customerId";

            $this->db->query($getCustomerQuery);

            $this->db->bind(':customerId', $customerId);

            $result = $this->db->single();

            return $result ?? null;
        } catch (PDOException $ex) {
            error_log("Error: Failed to get customer by ID from the database");
            die('Error: Failed to get customer by ID');
        }
    }

    public function createCustomer($newCustomer)
    {
        global $var;

        try {
            $createCustomerQuery = "INSERT INTO `customers` (`customerId`, `customerType`, `customerFirstName`, `customerLastName`, `customerEmail`, `customerPhone`, `customerAddress`, `customerZipCode`, `customerCreateDate`, `customerIsActive`) 
                            VALUES (:customerId, :customerType, :customerFirstName, :customerLastName, :customerEmail, :customerPhone, :customerAddress, :customerZipCode, :customerCreateDate, 1)";

            $this->db->query($createCustomerQuery);
            $this->db->bind(':customerId', $var['rand']);
            $this->db->bind(':customerType', $newCustomer['customerType']);
            $this->db->bind(':customerFirstName', $newCustomer['customerFirstName']);
            $this->db->bind(':customerLastName', $newCustomer['customerLastName']);
            $this->db->bind(':customerEmail', $newCustomer['customerEmail']);
            $this->db->bind(':customerPhone', $newCustomer['customerPhone']);
            $this->db->bind(':customerAddress', $newCustomer['customerAddress']);
            $this->db->bind(':customerZipCode', $newCustomer['customerZipCode']);
            $this->db->bind(':customerCreateDate', $var['timestamp']);

            return $this->db->execute();
        } catch (PDOException $ex) {
            error_log("ERROR: Failed to create Customer");
            die("ERROR: Failed to create Customer");
        }
    }

    public function updateCustomer($customerId, $updatedCustomer)
    {
        try {
            $updateCustomerQuery = "UPDATE `customers` 
                               SET `customerType` = :customerType,
                                   `customerFirstName` = :customerFirstName,
                                   `customerLastName` = :customerLastName,
                                   `customerEmail` = :customerEmail,
                                   `customerPhone` = :customerPhone,
                                   `customerAddress` = :customerAddress,
                                   `customerZipCode` = :customerZipCode
                                WHERE `customerId` = :customerId";

            $this->db->query($updateCustomerQuery);
            $this->db->bind(':customerId', $customerId);
            $this->db->bind(':customerType', $updatedCustomer['customerType']);
            $this->db->bind(':customerFirstName', $updatedCustomer['customerFirstName']);
            $this->db->bind(':customerLastName', $updatedCustomer['customerLastName']);
            $this->db->bind(':customerEmail', $updatedCustomer['customerEmail']);
            $this->db->bind(':customerPhone', $updatedCustomer['customerPhone']);
            $this->db->bind(':customerAddress', $updatedCustomer['customerAddress']);
            $this->db->bind(':customerZipCode', $updatedCustomer['customerZipCode']);

            return $this->db->execute();
        } catch (PDOException $ex) {
            error_log("Error: Failed to update customer");
            die('Error: Failed to update customer');
        }
    }

    public function deleteCustomer($customerId)
    {
        try {
            $deleteCustomerQuery = "UPDATE `customers` 
                                SET `customerIsActive` = '0' 
                                WHERE `customers`.`customerId` = :customerId";
            $this->db->query($deleteCustomerQuery);
            $this->db->bind(':customerId', $customerId);

            // Execute the query
            if ($this->db->execute()) {
                error_log("INFO: Customer has been marked as inactive");
                return true;
            } else {
                error_log("ERROR: Customer could not be marked as inactive");
                return false;
            }
        } catch (PDOException $ex) {
            error_log("ERROR: Exception occurred while marking the customer as inactive: " . $ex->getMessage());
            return false;
        }
    }
}