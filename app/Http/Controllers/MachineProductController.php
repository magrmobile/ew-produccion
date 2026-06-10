<?php

namespace App\Http\Controllers;

use App\Machine;
use App\Process;
use App\Product;
use App\Speed;
use Exception;
use Illuminate\Http\Request;

class MachineProductController extends Controller
{
    public function index(Request $request)
    {
        $query = Speed::with(['machine.process', 'product', 'user']);

        if ($request->filled('process_id')) {
            $query->whereHas('machine', function ($machineQuery) use ($request) {
                $machineQuery->where('process_id', $request->process_id);
            });
        }

        if ($request->filled('machine_id')) {
            $query->where('machine_id', $request->machine_id);
        }

        if ($request->filled('product_id')) {
            $query->where('product_id', $request->product_id);
        }

        $speeds = $query->orderBy('machine_id')
            ->orderBy('product_id')
            ->paginate(20)
            ->appends($request->only('process_id', 'machine_id', 'product_id'));

        $processes = Process::orderBy('description')->get();
        $machines = Machine::orderBy('machine_name')->get();
        $products = Product::orderBy('product_name')->get();

        return view('machine-products.index', compact('speeds', 'processes', 'machines', 'products'));
    }

    public function create()
    {
        $machines = Machine::orderBy('machine_name')->get();
        $products = Product::orderBy('product_name')->get();

        return view('machine-products.create', compact('machines', 'products'));
    }

    public function check(Request $request)
    {
        $request->validate([
            'machine_id' => 'required|exists:machines,id',
            'product_id' => 'required|exists:products,id',
        ]);

        $speed = Speed::where('machine_id', $request->machine_id)
            ->where('product_id', $request->product_id)
            ->first();

        if (!$speed) {
            return response()->json([
                'exists' => false,
            ]);
        }

        return response()->json([
            'exists' => true,
            'speed' => $speed->speed,
            'edit_url' => route('machine-products.edit', [
                'machine' => $speed->machine_id,
                'product' => $speed->product_id,
            ]),
        ]);
    }

    public function store(Request $request)
    {
        $this->validate($request, $this->rules());

        if ($this->exists($request->machine_id, $request->product_id)) {
            return redirect()->back()
                ->withInput()
                ->withErrors(['product_id' => 'Ya existe una velocidad para esta maquina y producto.']);
        }

        try {
            Speed::create([
                'machine_id' => $request->machine_id,
                'product_id' => $request->product_id,
                'speed' => $request->speed,
                'created_by' => auth()->id(),
            ]);

            $notification = 'La velocidad se ha registrado correctamente';
        } catch (Exception $e) {
            $notification = $this->errorMessage($e);
        }

        return redirect()->route('machine-products.index')->with(compact('notification'));
    }

    public function edit($machineId, $productId)
    {
        $speed = $this->findOrFail($machineId, $productId);
        $machines = Machine::orderBy('machine_name')->get();
        $products = Product::orderBy('product_name')->get();

        return view('machine-products.edit', compact('speed', 'machines', 'products'));
    }

    public function update(Request $request, $machineId, $productId)
    {
        $this->validate($request, $this->rules());

        $speed = $this->findOrFail($machineId, $productId);
        $changedKey = $request->machine_id != $machineId || $request->product_id != $productId;

        if ($changedKey && $this->exists($request->machine_id, $request->product_id)) {
            return redirect()->back()
                ->withInput()
                ->withErrors(['product_id' => 'Ya existe una velocidad para esta maquina y producto.']);
        }

        try {
            Speed::where('machine_id', $machineId)
                ->where('product_id', $productId)
                ->update([
                    'machine_id' => $request->machine_id,
                    'product_id' => $request->product_id,
                    'speed' => $request->speed,
                    'created_by' => $speed->created_by ?: auth()->id(),
                ]);

            $notification = 'La velocidad se ha actualizado correctamente';
        } catch (Exception $e) {
            $notification = $this->errorMessage($e);
        }

        return redirect()->route('machine-products.index')->with(compact('notification'));
    }

    public function destroy($machineId, $productId)
    {
        try {
            $this->findOrFail($machineId, $productId);

            Speed::where('machine_id', $machineId)
                ->where('product_id', $productId)
                ->delete();

            $notification = 'La velocidad se ha eliminado correctamente';
        } catch (Exception $e) {
            $notification = $this->errorMessage($e);
        }

        return redirect()->route('machine-products.index')->with(compact('notification'));
    }

    private function rules()
    {
        return [
            'machine_id' => 'required|exists:machines,id',
            'product_id' => 'required|exists:products,id',
            'speed' => 'required|numeric|min:0',
        ];
    }

    private function exists($machineId, $productId)
    {
        return Speed::where('machine_id', $machineId)
            ->where('product_id', $productId)
            ->exists();
    }

    private function findOrFail($machineId, $productId)
    {
        return Speed::with(['machine', 'product'])
            ->where('machine_id', $machineId)
            ->where('product_id', $productId)
            ->firstOrFail();
    }

    private function errorMessage(Exception $e)
    {
        return isset($e->errorInfo[2]) ? $e->errorInfo[2] : $e->getMessage();
    }
}
