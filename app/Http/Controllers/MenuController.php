<?php

namespace App\Http\Controllers;

use App\Models\Menu;
use Illuminate\Http\Request;
use Illuminate\Routing\Controllers\HasMiddleware;
use Illuminate\Routing\Controllers\Middleware;
use Spatie\Permission\Models\Role;

class MenuController extends Controller implements HasMiddleware
{
    public static function middleware(): array
    {
        return [
            new Middleware('permission:menu-list|menu-create|menu-edit|menu-delete', only: ['index']),
            new Middleware('permission:menu-create', only: ['create', 'store']),
            new Middleware('permission:menu-edit', only: ['edit', 'update']),
            new Middleware('permission:menu-delete', only: ['destroy']),
        ];
    }

    public function index()
    {
        $menus = Menu::with('parent')->orderBy('order')->get();

        return view('menus.index', compact('menus'));
    }

    public function create()
    {
        $parentMenus = Menu::parents()->ordered()->get();
        $roles = Role::all();

        return view('menus.create', compact('parentMenus', 'roles'));
    }

    public function store(Request $request)
    {
        $validated = $request->validate([
            'name' => 'required|max:255',
            'icon' => 'nullable|max:100',
            'route' => 'nullable|max:255',
            'url' => 'nullable|max:255',
            'parent_id' => 'nullable|exists:menus,id',
            'permission' => 'nullable|max:255',
            'order' => 'nullable|integer|min:0',
            'is_active' => 'boolean',
            'roles' => 'nullable|array',
            'roles.*' => 'exists:roles,id',
        ]);

        $menu = Menu::create([
            'name' => $validated['name'],
            'icon' => $validated['icon'] ?? null,
            'route' => $validated['route'] ?? null,
            'url' => $validated['url'] ?? null,
            'parent_id' => $validated['parent_id'] ?? null,
            'permission' => $validated['permission'] ?? null,
            'order' => $validated['order'] ?? 0,
            'is_active' => $request->boolean('is_active', true),
        ]);

        if ($request->has('roles')) {
            $menu->roles()->sync($request->roles);
        }

        return redirect()->route('admin.menus.index')->with('success', 'Menu created successfully.');
    }

    public function edit(Menu $menu)
    {
        $parentMenus = Menu::parents()->ordered()->where('id', '!=', $menu->id)->get();
        $roles = Role::all();
        $menuRoles = $menu->roles->pluck('id')->toArray();

        return view('menus.edit', compact('menu', 'parentMenus', 'roles', 'menuRoles'));
    }

    public function update(Request $request, Menu $menu)
    {
        $validated = $request->validate([
            'name' => 'required|max:255',
            'icon' => 'nullable|max:100',
            'route' => 'nullable|max:255',
            'url' => 'nullable|max:255',
            'parent_id' => 'nullable|exists:menus,id',
            'permission' => 'nullable|max:255',
            'order' => 'nullable|integer|min:0',
            'is_active' => 'boolean',
            'roles' => 'nullable|array',
            'roles.*' => 'exists:roles,id',
        ]);

        $menu->update([
            'name' => $validated['name'],
            'icon' => $validated['icon'] ?? null,
            'route' => $validated['route'] ?? null,
            'url' => $validated['url'] ?? null,
            'parent_id' => $validated['parent_id'] ?? null,
            'permission' => $validated['permission'] ?? null,
            'order' => $validated['order'] ?? 0,
            'is_active' => $request->boolean('is_active', true),
        ]);

        $menu->roles()->sync($request->roles ?? []);

        return redirect()->route('admin.menus.index')->with('success', 'Menu updated successfully.');
    }

    public function destroy(Menu $menu)
    {
        $menu->delete();

        return redirect()->route('admin.menus.index')->with('success', 'Menu deleted successfully.');
    }
}
