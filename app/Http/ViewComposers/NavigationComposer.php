<?php

namespace App\Http\ViewComposers;

use Illuminate\View\View;
use App\Models\Menu;

class NavigationComposer
{
    public function compose(View $view)
    {
        $user = auth()->user();
        $role = $user ? $user->roles->first() : null;

        // Ambil ID menu yang memiliki route dan diizinkan untuk role berdasarkan permission 'index'
        $allowedChildMenuIds = $role
            ? $role->menus()
            ->wherePivotIn('permission_id', function ($query) {
                $query->select('permissions.id')
                    ->from('permissions')
                    ->where('name', 'index');
            })
            ->pluck('menus.id')
            ->toArray()
            : [];

        // Ambil semua menu dengan urutan,
        // tetapi jika menu memiliki route, hanya ambil jika diizinkan
        $allowedMenus = Menu::orderBy('order')
            ->where(function ($query) use ($allowedChildMenuIds) {
                $query->whereNull('route')
                    ->orWhere('route', '')
                    ->orWhereIn('id', $allowedChildMenuIds);
            })
            ->get();

        $menuTree = $this->buildMenuTree($allowedMenus);

        // Kirim data ke view
        $view->with('menuTree', $menuTree);
    }

    protected function buildMenuTree($menus, $parentId = null)
    {
        $branch = collect();
        foreach ($menus as $menu) {
            if ($menu->parent_id == $parentId) {
                $children = $this->buildMenuTree($menus, $menu->id);
                // Masukkan menu jika memiliki route atau memiliki anak yang valid
                if (!empty($menu->route) || $children->isNotEmpty()) {
                    $menu->children = $children;
                    $branch->push($menu);
                }
            }
        }
        return $branch;
    }
}
