<?php

namespace App\Http\Controllers;

use App\Models\Menu;
use App\Http\Requests\MenuRequest;

class MenuController extends Controller
{
    public function index(\Illuminate\Http\Request $request)
    {
        $menus = Menu::with(['author', 'items'])->latest()->get();
        $activeMenuId = $request->query('menu', $menus->first()->id ?? null);
        $activeMenu = $activeMenuId ? $menus->firstWhere('id', $activeMenuId) : null;

        return view('menus.index', compact('menus', 'activeMenu'));
    }

    public function store(MenuRequest $request)
    {
        $menu = Menu::create([
            'name'       => $request->name,
            'location'   => $request->location,
            'created_by' => auth()->id(),
        ]);

        try {
            $users = \App\Models\User::all();
            \Illuminate\Support\Facades\Notification::send($users, new \App\Notifications\ContentChangeNotification('Menu', 'created', $menu->name, auth()->user()->name ?? 'Unknown'));
        } catch (\Throwable $e) {
            \Illuminate\Support\Facades\Log::error('Failed to send ContentChangeNotification: ' . $e->getMessage());
        }

        return redirect()->route('menus.index', ['menu' => $menu->id])->with('success', 'Menu created successfully.');
    }

    public function update(MenuRequest $request, Menu $menu)
    {
        if (!auth()->user()->hasRole('admin') && auth()->id() !== $menu->created_by) {
            abort(403, 'Unauthorized action.');
        }

        $menu->update([
            'name'       => $request->name,
            'location'   => $request->location,
        ]);

        try {
            $users = \App\Models\User::all();
            \Illuminate\Support\Facades\Notification::send($users, new \App\Notifications\ContentChangeNotification('Menu', 'updated', $menu->name, auth()->user()->name ?? 'Unknown'));
        } catch (\Throwable $e) {
            \Illuminate\Support\Facades\Log::error('Failed to send ContentChangeNotification: ' . $e->getMessage());
        }

        return redirect()->route('menus.index', ['menu' => $menu->id])->with('success', 'Menu updated successfully.');
    }

    public function destroy(Menu $menu)
    {
        if (!auth()->user()->hasRole('admin') && auth()->id() !== $menu->created_by) {
            abort(403, 'Unauthorized action.');
        }

        $menu->delete();

        return redirect()->route('menus.index')->with('success', 'Menu deleted successfully.');
    }
}
