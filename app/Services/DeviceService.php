<?php

namespace App\Services;

use App\Models\Device;
use Exception;

class DeviceService
{
    /**
     * Create a new device.
     *
     * @param array $data
     * @return Device
     * @throws Exception
     */
    public function createDevice(array $data): Device
    {
        try {
            // Create a new device
            return Device::create($data);
        } catch (Exception $e) {
            throw new Exception('Failed to create device: ' . $e->getMessage());
        }
    }
}
