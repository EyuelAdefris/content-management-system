<?php

namespace App\Http\Controllers;

use App\Models\Menu;
use App\Models\MenuItem;
use Illuminate\Http\Request;

class MenuItemController extends Controller
{
    public function store(Request $request, Menu $menu)
    {
        if (!auth()->user()->hasRole('admin') && auth()->id() !== $menu->created_by) {
            abort(403, 'Unauthorized action.');
        }

        $request->validate([
            'label' => 'required|string|max:255',
            'url'   => 'nullable|string|max:255',
            'order' => 'integer',
        ]);

        $menu->items()->create([
            'label' => $request->label,
            'url'   => $request->url,
            'order' => $request->order ?? 0,
        ]);

        return back()->with('success', 'Link added successfully.');
    }

    public function update(Request $request, Menu $menu, MenuItem $item)
    {
        if (!auth()->user()->hasRole('admin') && auth()->id() !== $menu->created_by) {
            abort(403, 'Unauthorized action.');
        }

        if ($item->menu_id !== $menu->id) {
            abort(404);
        }

        $request->validate([
            'label' => 'required|string|max:255',
            'url'   => 'nullable|string|max:255',
            'order' => 'integer',
        ]);

        $item->update([
            'label' => $request->label,
            'url'   => $request->url,
            'order' => $request->order ?? 0,
        ]);

        return back()->with('success', 'Link updated successfully.');
    }

    public function destroy(Menu $menu, MenuItem $item)
    {
        if (!auth()->user()->hasRole('admin') && auth()->id() !== $menu->created_by) {
            abort(403, 'Unauthorized action.');
        }

        if ($item->menu_id !== $menu->id) {
            abort(404);
        }

        $item->delete();

        return back()->with('success', 'Link deleted successfully.');
    }
}
