<?php

namespace App\Http\Controllers\Api;

use App\Http\Controllers\Controller;
use App\Http\Requests\StoreDeviceRequest;
use App\Models\Device;
use App\Services\DeviceService;
use Exception;
use Illuminate\Http\JsonResponse;
use Illuminate\Support\Facades\Log;

class DeviceController extends Controller
{
    public function __construct(private DeviceService $deviceService)
    {
    }

    /**
     * Get all devices.
     *
     * @return JsonResponse
     */
    public function index(): JsonResponse
    {
        $devices = Device::all();
        return response()->json($devices);
    }

    /**
     * Store a new device.
     *
     * @param StoreDeviceRequest $request
     * @return JsonResponse
     */
    public function store(StoreDeviceRequest $request): JsonResponse
    {
        $data = $request->validated();

        try {
            $device = $this->deviceService->createDevice($data);

            return response()->json($device, 201);
        } catch (Exception $e) {
            Log::error('Failed to store device', ['data' => $data, 'error' => $e->getMessage()]);
            return response()->json(['message' => 'Failed to store device.', 'error' => $e->getMessage()], 500);
        }
    }

    /**
     * Delete a device.
     *
     * @param Device $device
     * @return JsonResponse
     */
    public function destroy(Device $device): JsonResponse
    {
        try {
            $device->delete();
            return response()->json(['message' => 'Device deleted'], 204);
        } catch (Exception $e) {
            Log::error('Failed to delete device', ['device_id' => $device->id, 'error' => $e->getMessage()]);
            return response()->json(['message' => 'Failed to delete device.', 'error' => $e->getMessage()], 500);
        }
    }
}
