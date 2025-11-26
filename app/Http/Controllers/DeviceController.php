<?php
namespace App\Http\Controllers;

use Illuminate\Http\Request;
use App\Models\Device;
use Illuminate\Support\Facades\DB;

class DeviceController extends Controller
{
    public function index()
    {
         $devices = Device::orderBy('household_name')->get();
        return view('index', compact('devices'));
    }

    public function create()
    {
        return view('create');
    }

    public function store(Request $request)
    {
        $request->validate([
            'device_id' => 'required|unique:devices',
            'barangay'  => 'required',
            'contact_number' => 'nullable|string|max:20',
        ]);

        Device::create($request->all());
        return redirect()->route('devices.index')->with('success', 'Device added successfully.');

        $device = Device::create($data);

        // write an initial status log so the dashboard sees it right away
        DB::table('status_logs')->insert([
            'device_id'  => $device->device_id,
            'status'     => $device->status ?? 'OFF',
            'created_at' => now(),
            'updated_at' => now(),
        ]);

    }

    public function edit(Device $device)
    {
        return view('edit', compact('device'));
    }

  public function update(Request $request, \App\Models\Device $device)
{
    $data = $request->validate([
        'household_name' => 'required|string|max:255',
        'device_id'      => 'required|string|max:50|unique:devices,device_id,' . $device->id,
        'barangay'       => 'required|string|max:255',
        'contact_number' => 'nullable|string|max:20',
    ]);

    $device->update($data);

    // Log the update action for audit purposes
    DB::table('status_logs')->insert([
        'device_id'  => $device->device_id,
        'status'     => $device->status ?? 'OFF', // Use existing status or default to OFF
        'created_at' => now(),
        'updated_at' => now(),
    ]);

    // ✅ back to the table and highlight this device
    return redirect()
        ->route('devices.index', ['highlight' => $device->id])
        ->with('success', 'Device updated successfully.');
}


public function destroy(Device $device)
{
    $device->delete();
    return redirect()->route('devices.index')->with('success', 'Device removed successfully.');
}
}

